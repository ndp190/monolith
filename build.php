<?php

namespace at;

$pwd = __DIR__;

$projects = [
    'api'        => 'git@code.go1.com.au:go1/api.v3.git',
    'cloudinary' => 'git@code.go1.com.au:microservices/cloudinary.git',
    'queue'      => 'git@code.go1.com.au:microservices/queue.git',
    'history'    => 'git@code.go1.com.au:microservices/history.git',
    'mail'       => 'git@code.go1.com.au:microservices/mail.git',
    'portal'     => 'git@code.go1.com.au:microservices/portal.git',
    'uptime'     => 'git@code.go1.com.au:microservices/uptime.git',
    'user'       => 'git@code.go1.com.au:microservices/user.git',
    'status'     => 'git@code.go1.com.au:microservices/status.git',
];

if (!is_dir(__DIR__ . '/php/go1/adminer')) {
    passthru("mkdir -p $pwd/php/go1/adminer/public");
    passthru("wget https://www.adminer.org/static/download/4.2.5/adminer-4.2.5-en.php -O " . __DIR__ . "/php/go1/adminer/public/index.php");
}

foreach ($projects as $name => $path) {
    if (!is_dir("$pwd/php/go1/$name")) {
        passthru("git clone --single-branch --branch=master $path $pwd/php/go1/$name -vvv");
    }

    passthru("mkdir -p $pwd/php/go1/$name/vendor");

    file_put_contents(
        __DIR__ . "/php/go1/$name/vendor/autoload.php",
        '<?php return require_once "/app/vendor/autoload.php";'
    );
}
