<?php

namespace andytruong\odb;

use andytruong\odb\command\ImportCommand;
use andytruong\odb\controller\InstallController;
use andytruong\odb\domain\BreadRepository;
use go1\app\App as GO1;
use Goutte\Client;
use Pimple\Container;

class App extends GO1
{
    const NAME    = 'odb';
    const VERSION = 'v1.0';

    const HAS_IMAGE     = 1;
    const HAS_AUDIO     = 2;
    const HAS_SCRIPTURE = 3;
    const HAS_PLAN      = 4;
    const HAS_POEM      = 5;
    const HAS_THOUGHT   = 6;
    const HAS_TAG       = 7;
    const HAS_AUTHOR    = 8;

    public function __construct(array $values)
    {
        parent::__construct($values);

        $this['client'] = function () {
            return new Client();
        };

        $this['ctrl.install'] = function (Container $c) {
            return new InstallController($c['dbs']['default']);
        };

        $this['cmd.import'] = function (Container $c) {
            return new ImportCommand($c['client'], $c['repository']);
        };

        $this['repository'] = function (Container $c) {
            return new BreadRepository($c['dbs']['default']);
        };

        $this->get('/install', 'ctrl.install:get');
    }
}
