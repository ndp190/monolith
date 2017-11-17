<?php

namespace go1\monolith;

use Doctrine\DBAL\Connection;
use go1\util\edge\EdgeTypes;
use go1\util\portal\PortalHelper;
use go1\util\user\Roles;
use GuzzleHttp\Client;
use Pimple\Container;
use Ramsey\Uuid\Uuid;
use Silex\Provider\DoctrineServiceProvider;

require_once __DIR__ . '/../php/vendor/go1.autoload.php';
require_once __DIR__ . '/../php/user/domain/password.php';

/** @var Connection $con */
/** @var Connection $db */
$accountsName = 'accounts-dev.gocatalyze.com';
$domain = 'default.go1.local';
$client = new Client;
$pwd = dirname(__DIR__);
$custom = $pwd . '/build.json';
$custom = is_file($custom) ? json_decode(file_get_contents($custom), true) : [];
$mail = isset($custom['admin']['mail']) ? $custom['admin']['mail'] : 'staff@local';

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
$databases = $con->getSchemaManager()->listDatabases();
!in_array('go1_dev', $databases) && $con->getSchemaManager()->createDatabase('go1_dev');
!in_array('quiz_dev', $databases) && $con->getSchemaManager()->createDatabase('quiz_dev');

# ---------------------
# POST $service/install
# ---------------------
$projects = require __DIR__ . '/_projects.php'; # Make sure we have database for all services
foreach (array_keys($projects['php']) as $name) {
    echo "[install] GET|POST http://localhost/GO1/{$name}/install\n";
    $client->get($url = "http://localhost/GO1/{$name}/install", ['http_errors' => false]);
    $client->post($url, ['http_errors' => false]);
}

echo "[install] POST http://staff.local/api/install\n";
$client->post('http://staff.local/api/install', ['http_errors' => false]);

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
if (!$db->fetchColumn("SELECT 1 FROM gc_user WHERE mail = ?", ['staff@local'])) {
    $userRow = [
        'uuid'         => Uuid::uuid4()->toString(),
        'name'         => $mail,
        'mail'         => $mail,
        'password'     => _password_crypt('sha512', isset($custom['admin']['password']) ? $custom['admin']['password'] : 'root', _password_generate_salt(10)),
        'first_name'   => isset($custom['admin']['first_name']) ? $custom['admin']['first_name'] : 'Staff',
        'last_name'    => isset($custom['admin']['last_name']) ? $custom['admin']['last_name'] : 'Local',
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

    $accountRow = ['instance' => $domain] + $userRow;
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

passthru('docker exec -it monolith_web_1 /app/quiz/bin/console migrations:migrate --no-interaction -e=monolith');

function create_portal(Connection $db, string $name)
{
    if ($db->fetchColumn('SELECT 1 FROM gc_instance WHERE title = ?', [$name])) {
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
