<?php

namespace go1\xxxxx\tests;

use Doctrine\DBAL\DriverManager;
use go1\util\schema\InstallTrait;
use go1\xxxxx\App;
use PHPUnit_Framework_TestCase;
use Symfony\Component\HttpFoundation\Request;

abstract class XxxxxTestCase extends PHPUnit_Framework_TestCase
{
    use InstallTrait;

    protected $timestamp;

    protected function getApp(): App
    {
        $app = require __DIR__ . '/../public/index.php';

        $app['dbs'] = $app->extend('dbs', function () {
            return ['default' => DriverManager::getConnection(['url' => 'sqlite://sqlite::memory:'])];
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
