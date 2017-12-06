<?php

namespace go1\monolith;

use Doctrine\DBAL\Connection;
use go1\util\edge\EdgeTypes;
use go1\util\portal\PortalHelper;
use go1\util\user\Roles;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;
use Pimple\Container;
use Ramsey\Uuid\Uuid;
use Silex\Provider\DoctrineServiceProvider;

require_once __DIR__ . '/../php/vendor/go1.autoload.php';
require_once __DIR__ . '/../php/user/domain/password.php';

/** @var Connection $con */
/** @var Connection $db */
$accountsName = 'accounts-dev.gocatalyze.com';
$client = new Client;
$pwd = dirname(__DIR__);
$custom = $pwd . '/build.json';
$custom = is_file($custom) ? json_decode(file_get_contents($custom), true) : [];
$domain = $custom['features']['domain'] ?? 'default.go1.local';
$mail = $custom['features']['admin']['mail'] ?? 'staff@go1.co';

# ---------------------
# If the table is not yet available => create it.
# ---------------------
$c = (new Container)->register(new DoctrineServiceProvider, ['dbs.options' => [
    'install' => $base = [
        'host'          => '127.0.0.1',
        'user'          => 'root',
        'password'      => 'root',
        'port'          => '3306',
        'driver'        => 'pdo_mysql',
        'driverOptions' => [1002 => 'SET NAMES utf8'],
    ],
    'core'    => $base + ['dbname' => 'go1_dev'],
]]);

$con = $c['dbs']['install'];
// wait for MySQL, sometime mysql can not ready immediately after docker-compose task
$retries = 0;
$MAX_TRY = 5;
while ($retries < 5) {
    try {
        $con->ping();
        break;
    }
    catch (\Exception $e) {
        $retries += 1;
        if ($retries > $MAX_TRY) {
            echo "MySQL > hang up.\n";
            exit(1);
        }
        $t = pow(2, $retries);
        echo " - MySQL > waiting {$t}s . {$e->getMessage()}\n";
        sleep($t);
    }
}
$databases = $con->getSchemaManager()->listDatabases();
!in_array('go1_dev', $databases) && $con->getSchemaManager()->createDatabase('go1_dev');
!in_array('quiz_dev', $databases) && $con->getSchemaManager()->createDatabase('quiz_dev');

# ---------------------
# POST $service/install
# ---------------------
function serviceInstall(Client $client, $name)
{
    $retries = 0;
    $MAX_TRY = 5;

    while (1) {
        try {
            $client->get("http://localhost/GO1/{$name}");
            $client->get($url = "http://localhost/GO1/{$name}/install", ['http_errors' => false]);
            $client->post($url, ['http_errors' => false]);

            return;
        }
        catch (\Exception $e) {
            if ($e instanceof ConnectException || false !== strpos($e->getMessage(), 'Connection reset')) {
                if ($retries > $MAX_TRY) {
                    echo "[$name] give up.";

                    return;
                }

                $retries += 1;
                $t = pow(2, $retries);

                echo " + Waiting {$t}s\n";
                sleep($t);
            }
            else {
                echo " + [{$name}] Unexpected exception: " . $e->getMessage() . ". skipped\n";

                return;
            }
        }
    }
}

$projects = require __DIR__ . '/_projects.php'; # Make sure we have database for all services
$installExcluded = ['console', 'lti-consumer'];
foreach (array_keys($projects['php']) as $name) {
    if (in_array($name, $installExcluded)) {
        continue;
    }

    echo "[install] GET|POST http://localhost/GO1/{$name}/install\n";
    serviceInstall($client, $name);
}

echo "[install] POST http://localhost/GO1/staff/api/install\n";
$client->post('http://localhost/GO1/staff/api/install', ['http_errors' => false]);

# ---------------------
# Create portals
# ---------------------
$db = $c['dbs']['core'];
create_portal($db, $domain);
create_portal($db, $accountsName);

# ---------------------
# Create user for #staff.
#
# TODO: Publish message to rabbitMQ.
# ---------------------
if (!$db->fetchColumn("SELECT 1 FROM gc_user WHERE mail = ?", [$mail])) {
    $password = $custom['features']['admin']['password'] ?? 'root';
    $userRow = [
        'uuid'         => Uuid::uuid4()->toString(),
        'name'         => $mail,
        'mail'         => $mail,
        'password'     => _password_crypt('sha512', $password, _password_generate_salt(10)),
        'first_name'   => $custom['features']['admin']['first_name'] ?? 'Staff',
        'last_name'    => $custom['features']['admin']['last_name'] ?? 'Local',
        'profile_id'   => 1,
        'instance'     => $accountsName,
        'allow_public' => 0,
        'status'       => 1,
        'created'      => $now = time(),
        'access'       => $now,
        'login'        => $now,
        'timestamp'    => $now,
        'data'         => json_encode(['roles' => [Roles::ROOT]]),
    ];

    $accountRow = ['instance' => $domain, 'uuid' => Uuid::uuid4()->toString(), 'data' => json_encode(['roles' => [Roles::ADMIN]])] + $userRow;
    $db->insert('gc_user', $userRow);
    $userId = $db->lastInsertId('gc_user');
    $db->insert('gc_user', $accountRow);
    $accountId = $db->lastInsertId('gc_user');

    # EdgeHelper::link($db, EdgeTypes::HAS_ACCOUNT);
    $db->insert('gc_ro', [
        'type'      => EdgeTypes::HAS_ACCOUNT,
        'source_id' => $userId,
        'target_id' => $accountId,
        'weight'    => 0,
        'data'      => json_encode(['source' => 'monolith']),
    ]);
}

passthru('docker exec -it monolith_web_1 /app/quiz/bin/console migrations:migrate --no-interaction -e monolith');

function create_portal(Connection $db, string $name)
{
    if (!$db->fetchColumn('SELECT 1 FROM gc_instance WHERE title = ?', [$name])) {
        $db->insert('gc_instance', [
            'title'      => $name,
            'status'     => 1,
            'is_primary' => 1,
            'version'    => PortalHelper::STABLE_VERSION,
            'timestamp'  => $now = time(),
            'created'    => $now,
            'data'       => json_encode([
                'author'        => 'admin@' . $name,
                'configuration' => [
                    'is_virtual'                             => 1,
                    'user_invite'                            => 1,
                    PortalHelper::FEATURE_SEND_WELCOME_EMAIL => 1,
                ],
                'features'      => [
                    'marketplace' => true,
                    'user_invite' => true,
                    'auth0'       => false,
                ],
                'user_plan'     => [
                    'license' => 10,
                    'price'   => 3620,
                    'product' => 'marketplace',
                ],
            ]),
        ]);
    }
}
