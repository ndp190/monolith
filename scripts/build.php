<?php

namespace go1\monolith;

$cmd = implode(' ', $argv);
$pwd = dirname(__DIR__);
$home = getenv('HOME');

if (is_file("$home/.composer/vendor/autoload.php")) {
    require_once "$home/.composer/vendor/autoload.php";
}

# @TODO: hostmaster, accounts, realtime
$projects = [
    'php'            => [
        'assignment'   => 'git@code.go1.com.au:microservices/assignment.git',
        'batch'        => 'git@code.go1.com.au:microservices/batch.git',
        'cloudinary'   => 'git@code.go1.com.au:microservices/cloudinary.git',
        'credit'       => 'git@code.go1.com.au:microservices/credit.git',
        'currency'     => 'git@code.go1.com.au:microservices/currency.git',
        'eck'          => 'git@code.go1.com.au:microservices/eck.git',
        'endpoint'     => 'git@code.go1.com.au:microservices/endpoint.git',
        'enrolment'    => 'git@code.go1.com.au:microservices/enrolment.git',
        'entity'       => 'git@code.go1.com.au:microservices/entity.git',
        'finder'       => 'git@code.go1.com.au:microservices/finder.git',
        'queue'        => 'git@code.go1.com.au:microservices/queue.git',
        'history'      => 'git@code.go1.com.au:microservices/history.git',
        'graphin'      => 'git@code.go1.com.au:microservices/graphin.git',
        'lib'          => 'git@code.go1.com.au:microservices/lib.git',
        'lo'           => 'git@code.go1.com.au:microservices/lo.git',
        'lob'          => 'git@code.go1.com.au:microservices/lob.git',
        'mail'         => 'git@code.go1.com.au:microservices/mail.git',
        'note'         => 'git@code.go1.com.au:microservices/note.git',
        'payment'      => 'git@code.go1.com.au:microservices/payment.git',
        'portal'       => 'git@code.go1.com.au:microservices/portal.git',
        'quiz'         => 'git@code.go1.com.au:microservices/quiz.git',
        'uptime'       => 'git@code.go1.com.au:microservices/uptime.git',
        'user'         => 'git@code.go1.com.au:microservices/user.git',
        'rules'        => 'git@code.go1.com.au:microservices/rules.git',
        'social'       => 'git@code.go1.com.au:microservices/social.git',
        'status'       => 'git@code.go1.com.au:microservices/status.git',
        'subscription' => 'git@code.go1.com.au:microservices/subscription.git',
        'console'      => 'git@code.go1.com.au:go1/console.git',
        'boss'         => 'git@code.go1.com.au:go1/worker_manager.git',
        's3'           => 'git@code.go1.com.au:microservices/s3.git',
    ],
    'php/libraries'  => [
        'app'            => 'git@github.com:go1com/app.git',
        'clients'        => 'git@code.go1.com.au:go1/clients.git',
        'edge'           => 'git@github.com:go1com/edge.git',
        'flood'          => 'git@github.com:go1com/flood.git',
        'jwt_middleware' => 'git@github.com:go1com/JwtMiddleware.git',
        'kv'             => 'git@github.com:go1com/kv.git',
        'middleware'     => 'git@code.go1.com.au:go1/middlewares.git',
        'schema'         => 'git@code.go1.com.au:go1/schema.git',
        'util'           => 'git@code.go1.com.au:go1/util.git',
        'graph_mock'     => 'git@code.go1.com.au:go1/graph-mock.git',
    ],
    'drupal'         => [
        'accounts' => 'git@code.go1.com.au:go1/accounts.git',
        'gc'       => 'git@code.go1.com.au:gc/gocatalyze.git',
    ],
    'golang'         => [
        'api'      => 'git@code.go1.com.au:go1/api.v3.git',
        'batch'    => 'git@code.go1.com.au:microservices/batch-go.git',
        'consumer' => 'git@code.go1.com.au:microservices/consumer.git',
    ],
    'web'            => [
        'ui'      => 'git@code.go1.com.au:apiom/apiom-ui.git',
        'website' => 'git@code.go1.com.au:web/go1web.git',
    ],
    'infrastructure' => [
        'haproxy'       => 'git@code.go1.com.au:go1/haproxy.git',
        'ecs'           => 'git@code.go1.com.au:go1/launch-configuration.git',
        'deploy_helper' => 'git@github.com:go1com/deploy_helper.git',
        'goweb'         => 'git@code.go1.com.au:microservices/goweb.git',
        'cron'          => 'git@code.go1.com.au:microservices/cron.git',
    ],
];

$pull = strpos($cmd, '--pull') ? true : false;
call_user_func(require $pwd . '/scripts/build-git-pull.php', $pwd, $pull, $projects);

!strpos($cmd, '--skip-php') && call_user_func(require $pwd . '/scripts/build-php.php', $pwd, $home, $projects);
!strpos($cmd, '--skip-drupal') && call_user_func(require $pwd . '/scripts/build-drupal.php', $pwd, $home);
!strpos($cmd, '--skip-web') && call_user_func(require $pwd . '/scripts/build-web.php', $pwd, $home);
!strpos($cmd, '--skip-tools') && call_user_func(require $pwd . '/scripts/build-tools.php', $pwd);
!strpos($cmd, '--skip-go') && call_user_func(require $pwd . '/scripts/build-go.php', $pwd, $home, $projects);
!strpos($cmd, '--skip-docker-compose') && call_user_func(require $pwd . '/scripts/build-docker-compose.php', $pwd, $home, $projects);
