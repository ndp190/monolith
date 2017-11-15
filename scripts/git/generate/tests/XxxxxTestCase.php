<?php

namespace go1\xxxxx\tests;

use Doctrine\DBAL\DriverManager;
use go1\clients\MqClient;
use go1\util\schema\InstallTrait;
use go1\xxxxx\App;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

abstract class XxxxxTestCase extends TestCase
{
    use InstallTrait;

    protected $timestamp;
    protected $queueMessages = [];

    protected function getApp(): App
    {
        $app = require __DIR__ . '/../public/index.php';

        $app['dbs'] = $app->extend('dbs', function () {
            return ['default' => DriverManager::getConnection(['url' => 'sqlite://sqlite::memory:'])];
        });

        $this->appInstall($app);

        $app->extend('go1.client.mq', function () {
            $mqClient = $this
                ->getMockBuilder(MqClient::class)
                ->disableOriginalConstructor()
                ->setMethods(['publish'])
                ->getMock();

            $mqClient
                ->expects($this->any())
                ->method('publish')
                ->willReturnCallback(function ($body, string $routingKey) {
                    $this->queueMessages[$routingKey][] = $body;
                });

            return $mqClient;
        });

        return $app;
    }

    protected function appInstall(App $app)
    {
        $this->timestamp = time();

        $app->handle(Request::create('/install', 'POST));
    }
}
