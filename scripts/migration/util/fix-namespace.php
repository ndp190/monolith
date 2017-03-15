<?php

namespace go1\monolith\migration\util;

use go1\util\edge\EdgeHelper;
use go1\util\edge\EdgeTypes;
use go1\util\enrolment\EnrolmentHelper;
use go1\util\enrolment\EnrolmentStatuses;
use go1\util\lo\LiTypes;
use go1\util\lo\LoChecker;
use go1\util\lo\LoHelper;
use go1\util\lo\LoStatuses;
use go1\util\lo\LoTypes;
use go1\util\portal\PortalChecker;
use go1\util\portal\PortalHelper;
use go1\util\portal\PortalStatuses;
use go1\util\user\Roles;
use go1\util\user\UserHelper;
use go1\util\vote\VoteHelper;
use go1\util\vote\VoteTypes;

$base = realpath(__DIR__ . '/../../../php');
$map = [
    'go1\util\EdgeTypes'                    => EdgeTypes::class,
    'go1\util\EnrolmentStatuses'            => EnrolmentStatuses::class,
    'go1\util\EnrolmentHelper'              => EnrolmentHelper::class,
    'go1\util\LiTypes'                      => LiTypes::class,
    'go1\util\LoChecker'                    => LoChecker::class,
    'go1\util\LoHelper'                     => LoHelper::class,
    'go1\util\LoStatuses'                   => LoStatuses::class,
    'go1\util\LoTypes'                      => LoTypes::class,
    'go1\util\PortalChecker'                => PortalChecker::class,
    'go1\util\PortalHelper'                 => PortalHelper::class,
    'go1\util\PortalStatuses'               => PortalStatuses::class,
    'go1\util\EdgeHelper'                   => EdgeHelper::class,
    'go1\util\Roles'                        => Roles::class,
    'go1\util\UserHelper'                   => UserHelper::class,
    'go1\LtiConsumer\controller\UserHelper' => UserHelper::class,
    'go1\util\VoteHelper'                   => VoteHelper::class,
    'go1\util\VoteTypes'                    => VoteTypes::class,
];

$scan = function ($base) use (&$scan, &$map) {
    $tree = scandir($base);

    foreach ($tree as $node) {
        if (strpos($node, '.php')) {
            echo "  \\- {$node}\n";

            $original = file_get_contents("$base/$node");
            $copy = $original;

            foreach ($map as $from => $to) {
                if (strpos($copy, $from)) {
                    echo "        \\- {$from} -> {$to}\n";
                    $copy = str_replace($from, $to, $copy);
                }
            }

            if ($copy !== $original) {
                echo " \\-> Patch {$base}/{$node}\n";

                file_put_contents("$base/$node", $copy);
            }
        }
        else {
            if (is_dir("$base/$node")) {
                if (!in_array($node, ['.', '..', '.git', 'vendor'])) {
                    $scan("{$base}/{$node}");
                }
            }
        }
    }
};

$scan($base);
