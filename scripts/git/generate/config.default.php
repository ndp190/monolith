<?php

use go1\util\DB;

return call_user_func(function () {
    return [
        'debug'           => getenv('APP_DEBUG') ?: false,
        'accounts_name'   => getenv('ACCOUNTS_NAME') ?: 'accounts-dev.gocatalyze.com',
        'api_url'         => getenv('API_URL') ?: 'http://api-dev.mygo1.com',
        'entity_url'      => getenv('ENTITY_URL') ?: 'http://entity.dev.go1.service',
        'mail_url'        => getenv('MAIL_URL') ?: 'http://mail.dev.go1.service',
        'queue_url'       => getenv('QUEUE_URL') ?: 'http://queue.dev.go1.service',
        'outcome_url'     => getenv('OUTCOME_URL') ?: 'http://outcome.dev.go1.service',
        'payment_url'     => getenv('PAYMENT_URL') ?: 'http://payment.dev.go1.service',
        'portal_url'      => getenv('PORTAL_URL') ?: 'http://portal.dev.go1.service',
        'graph_url'       => getenv('GRAPH_URL') ?: 'http://neo4j:neo4j@neo4j:7474',
        'payment.options' => [
            'api_key'    => getenv('PAYMENT_API_KEY') ?: 'd9914468-3040-4b47-98d7-3fc3799f2d40',
            'secret_key' => getenv('PAYMENT_SECRET_KEY') ?: 'dd635358-3b0a-458a-bc06-421c627e2622',
        ],
        'logOptions'      => ['name' => 'enrolment'],
        'clientOptions'   => [],
        'dbOptions'       => [
            'default'   => DB::connectionOptions('go1'),
            'enrolment' => DB::connectionOptions('enrolment'),
        ],
        'cacheOptions'    =>
            (getenv('CACHE_BACKEND') && 'memcached' === getenv('CACHE_BACKEND'))
                ? ['backend' => 'memcached', 'host' => getenv('CACHE_HOST'), 'port' => getenv('CACHE_PORT')]
                : ['backend' => 'filesystem', 'directory' => __DIR__ . '/cache'],
        'queueOptions'    => [
            'host' => getenv('QUEUE_HOST') ?: '172.31.11.129',
            'port' => getenv('QUEUE_PORT') ?: '5672',
            'user' => getenv('QUEUE_USER') ?: 'go1',
            'pass' => getenv('QUEUE_PASSWORD') ?: 'go1',
        ],
        'stash'           => [
            'DB_HOST'        => 'ENROLMENT_DB_HOST',
            'DB_NAME'        => 'ENROLMENT_DB_NAME',
            'DB_USER'        => 'ENROLMENT_DB_USER',
            'DB_PASSWORD'    => 'ENROLMENT_DB_PASSWORD',
            'DB_PORT'        => 'ENROLMENT_DB_PORT',
            'RDS_HOSTNAME'   => 'GO1_DB_HOSTNAME',
            'RDS_DB_NAME'    => 'GO1_DB_NAME',
            'RDS_PASSWORD'   => 'GO1_DB_PASSWORD',
            'RDS_PORT'       => 'GO1_DB_PORT',
            'QUEUE_HOST'     => 'QUEUE_HOST',
            'QUEUE_PORT'     => 'QUEUE_PORT',
            'QUEUE_USER'     => 'QUEUE_USER',
            'QUEUE_PASSWORD' => 'QUEUE_PASSWORD',
        ],
    ];
});
