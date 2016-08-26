<?php

namespace at\labs;

$pwd = __DIR__;
$home = getenv('HOME');

$cmd = implode(' ', $argv);
$pull = false !== strpos($cmd, '--pull');
$skipPHP = false !== strpos($cmd, '--skip-php');
$skipDrupal = false !== strpos($cmd, '--skip-drupal');
$skipWeb = false !== strpos($cmd, '--skip-web');

# @TODO: hostmaster, accounts, realtime
$projects = [
    'php'            => [
        'api'        => 'git@code.go1.com.au:go1/api.v3.git',
        'cloudinary' => 'git@code.go1.com.au:microservices/cloudinary.git',
        'enrolment'  => 'git@code.go1.com.au:microservices/enrolment.git',
        'queue'      => 'git@code.go1.com.au:microservices/queue.git',
        'history'    => 'git@code.go1.com.au:microservices/history.git',
        'graphin'    => 'git@code.go1.com.au:microservices/graphin.git',
        'lo'         => 'git@code.go1.com.au:microservices/lo.git',
        'mail'       => 'git@code.go1.com.au:microservices/mail.git',
        'outcome'    => 'git@code.go1.com.au:microservices/outcome.git',
        'payment'    => 'git@code.go1.com.au:microservices/payment.git',
        'portal'     => 'git@code.go1.com.au:microservices/portal.git',
        'quiz'       => 'git@code.go1.com.au:microservices/quiz.git',
        'uptime'     => 'git@code.go1.com.au:microservices/uptime.git',
        'user'       => 'git@code.go1.com.au:microservices/user.git',
        'rules'      => 'git@code.go1.com.au:microservices/rules.git',
        'status'     => 'git@code.go1.com.au:microservices/status.git',
    ],
    'drupal'         => [
        'accounts' => 'git@code.go1.com.au:go1/accounts.git',
        'gc'       => 'git@code.go1.com.au:gc/gocatalyze.git',
    ],
    'web'            => [
        'ui'      => 'git@code.go1.com.au:apiom/apiom-ui.git',
        'website' => 'git@code.go1.com.au:web/go1web.git',
    ],
    'infrastructure' => [
        'haproxy' => 'git@code.go1.com.au:go1/haproxy.git',
        'ecs'     => 'git@code.go1.com.au:go1/launch-configuration.git',
    ],
];

// Clone the code base
// ---------------------
foreach ($projects as $lang => $services) {
    foreach ($services as $name => $path) {
        if (!is_dir("$pwd/$lang/$name")) {
            print_r("git clone -q --branch=master $path $pwd/$lang/$name\n");
            passthru("git clone -q --branch=master $path $pwd/$lang/$name");
        }
        elseif ($pull) {
            print_r("git pull -q origin master\n");
            passthru("cd $pwd/$lang/$name && git pull -q origin master && cd $pwd");
        }

        if ('php' === $lang) {
            passthru("mkdir -p $pwd/$lang/$name/vendor");
            file_put_contents(
                "$pwd/$lang/$name/vendor/autoload.php",
                '<?php return require_once "/autoload/autoload.php";'
            );
        }
    }
}

// Autoload PHP projects
// ---------------------
if (!$skipPHP) {
    $composer = json_decode(file_get_contents("$pwd/php/composer.json"), true);
    foreach (array_keys($projects['php']) as $service) {
        $composer['autoload']['psr-4']["go1\\$service\\"] = "/app/$service/";
        if (file_exists("$pwd/php/$service/composer.json")) {
            $sub = json_decode(file_get_contents("$pwd/php/{$service}/composer.json"), true);
            if (!empty($sub['require'])) {
                foreach ($sub['require'] as $lib => $version) {
                    $composer['require'][$lib] = $version;
                }
            }
        }
    }

    ksort($composer['autoload']['psr-4']);
    ksort($composer['require']);
    $composer = json_encode($composer, JSON_PRETTY_PRINT);
    $composer = str_replace('\/', '/', $composer);
    file_put_contents("$pwd/php/composer.json", $composer);

    passthru("cd $pwd/php && composer install -vvv && cd $pwd");
    passthru("docker run --rm -v $pwd/php/:/app/ go1com/php:php7 sh /app/install.sh");
}

// Build Gocatalyze code base
if (!$skipDrupal) {
    if (!file_exists("$pwd/.data/cli/drush")) {
        passthru("mkdir $pwd/.data/cli");
        passthru("wget https://s3.amazonaws.com/files.drush.org/drush.phar -O $pwd/.data/cli/drush");
    }

    if (!file_exists("$pwd/.data/drupal")) {
        passthru("mkdir $pwd/.data/drupal");
    }

    $php = "docker run --rm";
    $php .= " -v $pwd/php/:/app/";
    $php .= " -v $pwd/.data/cli/:/cli/";
    $php .= " -v $pwd/.data/drupal/:/drupal/";
    $php .= " go1com/php:php7 php /cli/drush";
    passthru("$php sh /app/install.sh");
    exit;

    # rm -rf builds/drupal
    # drush make apps/gc/build/build.make builds/drupal
    # cd builds/drupal/profiles && ln -s ../../../apps/gc gocatalyze && cd -
    # cd apps/gc/vendor && composer install --ignore-platform-reqs && cd -
    # mv sites/all/vendor builds/drupal/sites/all/vendor
}

// Build #ui
// ---------------------
if (!$skipWeb) {
    $node = "docker run -it --rm -w='/data' -v $pwd/web/ui:/data -v '$home/.ssh/id_rsa:/private-key' go1com/ci-nodejs";
    passthru("$node npm install");
    passthru("$node bash -c 'eval $(ssh-agent -s) && ssh-add /private-key && mkdir -p ~/.ssh && echo -e \"Host *\\n\\tStrictHostKeyChecking no\\n\\n\" > ~/.ssh/config && bower install --allow-root'");
    passthru("$node grunt install");
    passthru("$node grunt build --force");
    passthru("$node grunt set-env:compose");
}

// Extra tools
// ---------------------
$files = ['https://www.adminer.org/static/download/4.2.5/adminer-4.2.5-en.php' => "$pwd/php/adminer/public/index.php"];
foreach ($files as $url => $file) {
    $dir = dirname($file);
    !is_dir($dir) && passthru("mkdir -p $dir");
    !is_file($file) && passthru("wget $url -O $file");
}
