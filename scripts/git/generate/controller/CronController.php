<?php

namespace go1\xxxxx\controller;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class CronController
{
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function post(Request $req)
    {
        $this->logger->debug('Cron start.');

        return new JsonResponse(null, 204);
    }
}
