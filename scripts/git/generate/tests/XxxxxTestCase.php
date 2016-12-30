<?php

namespace go1\xxxxx\tests;

use Doctrine\DBAL\DriverManager;
use go1\schema\InstallTrait;
use go1\xxxxx\App;
use PHPUnit_Framework_TestCase;
use Symfony\Component\HttpFoundation\Request;

abstract class XxxxxTestCase extends PHPUnit_Framework_TestCase
{
    use InstallTrait;

    protected $timestamp;

    protected function getApp(): App
    {
        /** @var App $app */
        $app = require __DIR__ . '/../public/index.php';

        $app['dbs'] = $app->extend('dbs', function () {
            $master = DriverManager::getConnection(['url' => 'sqlite://sqlite::memory:']);
            $slave = &$master;

            return ['default' => $master];
        });

        $this->appInstall($app);

        return $app;
    }

    protected function appInstall(App $app)
    {
        $this->timestamp = time();

        $app->handle(Request::create('/install'));
    }
}
