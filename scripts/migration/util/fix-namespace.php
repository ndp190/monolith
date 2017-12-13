<?php

namespace go1\monolith\migration\util;

use go1\util\edge\EdgeHelper;
use go1\util\edge\EdgeTypes;
use go1\util\enrolment\EnrolmentHelper;
use go1\util\enrolment\EnrolmentStatuses;
use go1\util\Error;
use go1\util\graph\mock\GraphEnrolmentMockTrait;
use go1\util\graph\mock\GraphLoMockTrait;
use go1\util\graph\mock\GraphUserMockTrait;
use go1\util\group\GroupHelper;
use go1\util\group\GroupItemStatus;
use go1\util\group\GroupItemTypes;
use go1\util\group\GroupStatus;
use go1\util\lo\LiTypes;
use go1\util\lo\LoChecker;
use go1\util\lo\LoHelper;
use go1\util\lo\LoStatuses;
use go1\util\lo\LoTypes;
use go1\util\portal\PortalChecker;
use go1\util\portal\PortalHelper;
use go1\util\portal\PortalPrices;
use go1\util\portal\PortalStatuses;
use go1\util\schema\InstallTrait;
use go1\util\schema\mock\EnrolmentMockTrait;
use go1\util\schema\mock\InstanceMockTrait;
use go1\util\schema\mock\LoMockTrait;
use go1\util\schema\mock\UserMockTrait;
use go1\util\user\Roles;
use go1\util\user\UserHelper;
use go1\util\vote\VoteHelper;
use go1\util\vote\VoteTypes;

$base = realpath(__DIR__ . '/../../../php');
$map = [
    'go1\graph_mock\GraphEnrolmentMockTrait' => GraphEnrolmentMockTrait::class,
    'go1\graph_mock\GraphLoMockTrait'        => GraphLoMockTrait::class,
    'go1\graph_mock\GraphUserMockTrait'      => GraphUserMockTrait::class,
    'go1\LtiConsumer\controller\UserHelper'  => UserHelper::class,
    'go1\schema\InstallTrait'                => InstallTrait::class,
    'go1\schema\mock\EnrolmentMockTrait'     => EnrolmentMockTrait::class,
    'go1\schema\mock\InstanceMockTrait'      => InstanceMockTrait::class,
    'go1\schema\mock\LoMockTrait'            => LoMockTrait::class,
    'go1\schema\mock\UserMockTrait'          => UserMockTrait::class,
    'go1\util\EdgeHelper'                    => EdgeHelper::class,
    'go1\util\EdgeTypes'                     => EdgeTypes::class,
    'go1\util\EnrolmentHelper'               => EnrolmentHelper::class,
    'go1\util\EnrolmentStatuses'             => EnrolmentStatuses::class,
    'go1\util\ErrorCodes'                    => Error::class,
    'go1\util\GroupHelper'                   => GroupHelper::class,
    'go1\util\GroupItemStatus'               => GroupItemStatus::class,
    'go1\util\GroupItemTypes'                => GroupItemTypes::class,
    'go1\util\GroupStatus'                   => GroupStatus::class,
    'go1\util\LiTypes'                       => LiTypes::class,
    'go1\util\LoChecker'                     => LoChecker::class,
    'go1\util\LoHelper'                      => LoHelper::class,
    'go1\util\LoStatuses'                    => LoStatuses::class,
    'go1\util\LoTypes'                       => LoTypes::class,
    'go1\util\PortalChecker'                 => PortalChecker::class,
    'go1\util\PortalHelper'                  => PortalHelper::class,
    'go1\util\PortalPrices'                  => PortalPrices::class,
    'go1\util\PortalStatuses'                => PortalStatuses::class,
    'go1\util\Roles'                         => Roles::class,
    'go1\util\UserHelper'                    => UserHelper::class,
    'go1\util\VoteHelper'                    => VoteHelper::class,
    'go1\util\VoteTypes'                     => VoteTypes::class,
];

$scan = function ($base) use (&$scan, &$map) {
    $tree = scandir($base);

    foreach ($tree as $node) {
        if (false !== strpos($node, '.php')) {
            echo "  \\- {$node}\n";

            $original = file_get_contents("$base/$node");
            $copy = $original;

            foreach ($map as $from => $to) {
                if (false !== strpos($copy, $from)) {
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
