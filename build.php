<?php

namespace at\labs;

$pwd = __DIR__;

$cmd = implode(' ', $argv);
$pull = false !== strpos($cmd, '--pull');
$skipComposer = false !== strpos($cmd, '--skip-php');
$skipWeb = false !== strpos($cmd, '--skip-web');

# @TODO: hostmaster, accounts, apiom, realtime
$projects = [
    'php' => [
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
    'web' => [
        'ui' => 'git@code.go1.com.au:apiom/apiom-ui.git',
    ],
];

$files = [
    'https://www.adminer.org/static/download/4.2.5/adminer-4.2.5-en.php' => "$pwd/php/adminer/public/index.php",
];

foreach ($files as $url => $file) {
    $dir = dirname($file);
    !is_dir($dir) && passthru("mkdir -p $dir");
    !is_file($file) && passthru("wget $url -O $file");
}

foreach ($projects as $lang => $services) {
    foreach ($services as $name => $path) {
        if (!is_dir("$pwd/$lang/$name")) {
            print_r("git clone -q --single-branch --branch=master $path $pwd/$lang/$name\n");
            passthru("git clone -q --single-branch --branch=master $path $pwd/$lang/$name");
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
if (!$skipComposer) {
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

// Build #ui
// ---------------------
if (!$skipWeb) {
    $node = "docker run -it --rm -w='/data' -v $pwd/web/ui:/data go1com/ci-nodejs";
    passthru("$node npm install");
    passthru("$node install --allow-root");
    passthru("$node grunt install");
    passthru("$node grunt build --force");
    passthru("$node grunt set-env:compose");
}
