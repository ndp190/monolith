<?php

use go1\util\DB;
use go1\util\Service;

return call_user_func(function () {
    $env = getenv('ENV') ?: 'dev';

    return [
            'debug'         => 'production' !== $env,
            'accounts_name' => Service::accountsName($env),
            'logOptions'    => ['name' => 'xxxxx'],
            'clientOptions' => [],
            'dbOptions'     => ['default' => DB::connectionOptions('xxxxx')],
            'cacheOptions'  =>
                (getenv('CACHE_BACKEND') && 'memcached' === getenv('CACHE_BACKEND'))
                    ? ['backend' => 'memcached', 'host' => getenv('CACHE_HOST'), 'port' => getenv('CACHE_PORT')]
                    : ['backend' => 'filesystem', 'directory' => __DIR__ . '/cache'],
            'queueOptions'  => [
                'host' => getenv('QUEUE_HOST') ?: '172.31.11.129',
                'port' => getenv('QUEUE_PORT') ?: '5672',
                'user' => getenv('QUEUE_USER') ?: 'go1',
                'pass' => getenv('QUEUE_PASSWORD') ?: 'go1',
            ],
        ] + Service::urls(['rules', 'graphin'], $env, getenv('SERVICE_URL_PATTERN'));
});
