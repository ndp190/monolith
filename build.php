<?php

namespace at\labs;

$cmd = implode(' ', $argv);
$pwd = __DIR__;
$home = getenv('HOME');

# @TODO: hostmaster, accounts, realtime
$projects = [
    'php'            => [
        'api'        => 'git@code.go1.com.au:go1/api.v3.git',
        'assignment' => 'git@code.go1.com.au:microservices/assignment.git',
        'cloudinary' => 'git@code.go1.com.au:microservices/cloudinary.git',
        'currency'   => 'git@code.go1.com.au:microservices/currency.git',
        'eck'        => 'git@code.go1.com.au:microservices/eck.git',
        'endpoint'   => 'git@code.go1.com.au:microservices/endpoint.git',
        'enrolment'  => 'git@code.go1.com.au:microservices/enrolment.git',
        'entity'     => 'git@code.go1.com.au:microservices/entity.git',
        'finder'     => 'git@code.go1.com.au:microservices/finder.git',
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
        'console'    => 'git@code.go1.com.au:go1/console.git',
        'boss'       => 'git@code.go1.com.au:go1/worker_manager.git',
        's3'         => 'git@code.go1.com.au:go1/s3.git',
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

$pull = strpos($cmd, '--pull') ? true : false;
call_user_func(require $pwd . '/.data/build-git-pull.php', $pwd, $pull, $projects);

!strpos($cmd, '--skip-php') && call_user_func(require $pwd . '/.data/build-php.php', $pwd, $home, $projects);
!strpos($cmd, '--skip-drupal') && call_user_func(require $pwd . '/.data/build-drupal.php', $pwd, $home);
!strpos($cmd, '--skip-web') && call_user_func(require $pwd . '/.data/build-web.php', $pwd, $home);
!strpos($cmd, '--skip-tools') && call_user_func(require $pwd . '/.data/build-tools.php', $pwd);
