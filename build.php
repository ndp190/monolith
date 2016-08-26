<?php

namespace at\labs;

$pwd = __DIR__;

# @TODO: hostmaster, accounts, apiom, realtime
$projects = [
    'php' => [
        'api'        => 'git@code.go1.com.au:go1/api.v3.git',
        'cloudinary' => 'git@code.go1.com.au:microservices/cloudinary.git',
        'enrolment'  => 'git@code.go1.com.au:microservices/enrolment.git',
        'queue'      => 'git@code.go1.com.au:microservices/queue.git',
        'history'    => 'git@code.go1.com.au:microservices/history.git',
        'lo'         => 'git@code.go1.com.au:microservices/lo.git',
        'mail'       => 'git@code.go1.com.au:microservices/mail.git',
        'outcome'    => 'git@code.go1.com.au:microservices/outcome.git',
        'payment'    => 'git@code.go1.com.au:microservices/payment.git',
        'portal'     => 'git@code.go1.com.au:microservices/portal.git',
        'quiz'       => 'git@code.go1.com.au:microservices/quiz.git',
        'uptime'     => 'git@code.go1.com.au:microservices/uptime.git',
        'user'       => 'git@code.go1.com.au:microservices/user.git',
        'status'     => 'git@code.go1.com.au:microservices/status.git',
    ],
];

$files = [
    'https://www.adminer.org/static/download/4.2.5/adminer-4.2.5-en.php' => "$pwd/php/go1/adminer/public/index.php",
];

foreach ($files as $url => $file) {
    $dir = dirname($file);
    !is_dir($dir) && passthru("mkdir -p $dir");
    !is_file($file) && passthru("wget $url -O $file");
}

foreach ($projects as $lang => $services) {
    foreach ($services as $name => $path) {
        if (!is_dir("$pwd/$lang/go1/$name")) {
            passthru("git clone --single-branch --branch=master $path $pwd/$lang/go1/$name");
        }

        if ('php' === $lang) {
            passthru("mkdir -p $pwd/$lang/go1/$name/vendor");
            file_put_contents(
                "$pwd/$lang/go1/$name/vendor/autoload.php",
                '<?php return require_once "/autoload/autoload.php";'
            );
        }
    }
}

passthru("cd $pwd/php/go1 && composer install -vvv && cd $pwd");
passthru("docker run --rm -v $pwd/php/go1/:/app/ go1com/php:php7 sh /app/install.sh");
