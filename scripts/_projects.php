<?php

namespace go1\monolith\scripts;

$pwd = dirname(__DIR__);
$custom = file_exists($pwd . '/build.json') ? true : false;

$gh = function ($path) use ($custom) {
    # if custom, change
    #   from git@github.com:go1com/util.git
    #   to   https://github.com/go1com/util.git

    return !$custom ? $path : str_replace('git@github.com:', 'https://github.com/', $path);
};

return [
    'php'            => [
        'activity'       => 'git@code.go1.com.au:microservices/activity.git',
        'algolia'        => 'git@code.go1.com.au:microservices/algolia.git',
        'api'            => 'git@code.go1.com.au:microservices/api.git',
        'assessor'       => 'git@code.go1.com.au:microservices/assessor.git',
        'assignment'     => 'git@code.go1.com.au:microservices/assignment.git',
        'award'          => 'git@code.go1.com.au:microservices/award.git',
        'cloudinary'     => 'git@code.go1.com.au:microservices/cloudinary.git',
        'console'        => 'git@code.go1.com.au:go1/console.git',
        'contract'       => 'git@code.go1.com.au:microservices/contract.git',
        'coupon'         => 'git@code.go1.com.au:microservices/coupon.git',
        'credit'         => 'git@code.go1.com.au:microservices/credit.git',
        'currency'       => 'git@code.go1.com.au:microservices/currency.git',
        'eck'            => 'git@code.go1.com.au:microservices/eck.git',
        'enrolment'      => 'git@code.go1.com.au:microservices/enrolment.git',
        'entity'         => 'git@code.go1.com.au:microservices/entity.git',
        'exim'           => 'git@code.go1.com.au:microservices/exim.git',
        'explore'        => 'git@code.go1.com.au:microservices/explore.git',
        'finder'         => 'git@code.go1.com.au:microservices/finder.git',
        'oembed'         => 'git@code.go1.com.au:microservices/oembed.git',
        'graphin'        => 'git@code.go1.com.au:microservices/graphin.git',
        'history'        => 'git@code.go1.com.au:microservices/history.git',
        'index'          => 'git@code.go1.com.au:microservices/index.git',
        'lo'             => 'git@code.go1.com.au:microservices/lo.git',
        'lob'            => 'git@code.go1.com.au:microservices/lob.git',
        'low'            => 'git@code.go1.com.au:microservices/low.git',
        'lti-consumer'   => 'git@code.go1.com.au:microservices/lti-consumer.git',
        'mail'           => 'git@code.go1.com.au:microservices/mail.git',
        'migration'      => 'git@code.go1.com.au:microservices/migration.git',
        'note'           => 'git@code.go1.com.au:microservices/note.git',
        'notify'         => 'git@code.go1.com.au:microservices/notify.git',
        'onboard'        => 'git@code.go1.com.au:microservices/onboard.git',
        'payment'        => 'git@code.go1.com.au:microservices/payment.git',
        'portal'         => 'git@code.go1.com.au:microservices/portal.git',
        'quiz'           => 'git@code.go1.com.au:microservices/quiz.git',
        'quiz-rpc'       => 'git@code.go1.com.au:microservices/quiz-rpc.git',
        'report-data'    => 'git@code.go1.com.au:microservices/report-data.git',
        'report'         => 'git@code.go1.com.au:microservices/report.git',
        'rules'          => 'git@code.go1.com.au:microservices/rules.git',
        's3'             => 'git@code.go1.com.au:microservices/s3.git',
        'scorm'          => 'git@code.go1.com.au:microservices/scorm.git',
        'scraping'       => 'git@code.go1.com.au:microservices/scraping.git',
        'support'        => 'git@code.go1.com.au:microservices/support.git',
        'sms'            => 'git@code.go1.com.au:microservices/sms.git',
        'social'         => 'git@code.go1.com.au:microservices/social.git',
        'sso'            => 'git@code.go1.com.au:microservices/sso.git',
        'staff'          => 'git@code.go1.com.au:microservices/staff.git',
        'subscription'   => 'git@code.go1.com.au:microservices/subscription.git',
        'user'           => 'git@code.go1.com.au:microservices/user.git',
        'video'          => 'git@code.go1.com.au:microservices/video.git',
        'vote'           => 'git@code.go1.com.au:microservices/vote.git',
        'interactive-li' => 'git@code.go1.com.au:microservices/interactive-li.git',
        'mbosi-export'   => 'git@code.go1.com.au:microservices/mbosi-export.git',
    ],
    'php/libraries'  => [
        # 'clients'        => 'git@code.go1.com.au:go1/clients.git',
        # 'schema'         => 'git@code.go1.com.au:go1/schema.git',
        # 'graph_mock'     => 'git@code.go1.com.au:go1/graph-mock.git',
        # 'stash'          => 'git@code.go1.com.au:microservices/stash.git',
        'app'            => $gh('git@github.com:go1com/app.git'),
        'edge'           => $gh('git@github.com:go1com/edge.git'),
        'flood'          => $gh('git@github.com:go1com/flood.git'),
        'jwt_middleware' => $gh('git@github.com:go1com/JwtMiddleware.git'),
        'kv'             => $gh('git@github.com:go1com/kv.git'),
        'middleware'     => 'git@code.go1.com.au:go1/middlewares.git',
        'util'           => $gh('git@github.com:go1com/util.git'),
        'report_helpers' => $gh('git@github.com:go1com/report_helpers.git'),
    ],
    'drupal'         => [
        'accounts' => 'git@code.go1.com.au:go1/accounts.git',
        'gc'       => 'git@code.go1.com.au:gc/gocatalyze.git',
    ],
    'golang'         => [
        # Please update build-go.php
        # The projects are managed by glide.
    ],
    'web'            => [
        'ui'               => 'git@code.go1.com.au:apiom/apiom-ui.git',
        'website'          => 'git@code.go1.com.au:web/go1web.git',
        'report-component' => 'git@code.go1.com.au:angularjs/report-component.git',
        'report-monitor'   => 'git@code.go1.com.au:web/report-monitor.git',
    ],
    'infrastructure' => [
        'ci'            => 'git@code.go1.com.au:go1/ci.git',
        'cron'          => 'git@code.go1.com.au:microservices/cron.git',
        'deploy_helper' => $gh('git@github.com:go1com/deploy_helper.git'),
        'ecs'           => 'git@code.go1.com.au:go1/launch-configuration.git',
        'goweb'         => 'git@code.go1.com.au:microservices/goweb.git',
        'haproxy'       => 'git@code.go1.com.au:go1/haproxy.git',
        #'memcached'     => 'git@code.go1.com.au:server/memcached.git',
        'docker-php'    => 'https://github.com/go1com/docker-php.git',
        'wait-for-it'   => 'https://github.com/vishnubob/wait-for-it.git',
    ],
    'nodejs'         => [
        #'report' => 'git@code.go1.com.au:microservices/report-index.git',
        # @TODO: realtime
    ],
    'resources'      => [
        'documentation' => 'git@code.go1.com.au:go1/documentation.git',
    ],
];

# ---------------------
# Disabled
# ---------------------
# PHP
# ---
#   'batch'    => 'git@code.go1.com.au:microservices/batch.git',
#   'endpoint' => 'git@code.go1.com.au:microservices/endpoint.git',
#   'lib'      => 'git@code.go1.com.au:microservices/lib.git',
#   'natero'   => 'git@code.go1.com.au:microservices/natero.git',
#   'queue'    => 'git@code.go1.com.au:microservices/queue.git',
#   'status'   => 'git@code.go1.com.au:microservices/status.git',
#   'uptime'   => 'git@code.go1.com.au:microservices/uptime.git',
#   'workshop' => 'git@code.go1.com.au:microservices/workshop.git',
