<?php

namespace go1\xxxxx\domain;

use go1\xxxxx\controller\InstallController;
use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Silex\Api\BootableProviderInterface;
use Silex\Application;

class XxxxxServiceProvider implements ServiceProviderInterface, BootableProviderInterface
{
    public function register(Container $c)
    {
        $c['ctrl.install'] = function (Container $c) {
            return new InstallController($c['dbs']['default']);
        };
    }

    public function boot(Application $app)
    {
        $app->get('/install', 'ctrl.install:get');
    }
}
