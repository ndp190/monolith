<?php

namespace go1\xxxxx\controller;

use go1\util\AccessChecker;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @see https://code.go1.com.au/microservices/consumer
 * @see https://code.go1.com.au/go1/util/blob/master/Queue.php
 */
class ConsumeController
{
    private $logger;
    private $accessChecker;

    public function __construct(LoggerInterface $logger, AccessChecker $accessChecker)
    {
        $this->logger = $logger;
        $this->accessChecker = $accessChecker;
    }

    public function post(Request $req)
    {
        if (!$this->accessChecker->isAccountsAdmin($req)) {
            return new JsonResponse(['message' => 'Internal resource'], 403);
        }

        $routingKey = $req->get('routingKey');
        $body = $req->get('body');
        $body = is_scalar($body) ? json_decode($body, true) : json_decode(json_encode($body), true);

        switch ($routingKey) {
            default:
                $this->logger->debug("Consuming {$routingKey}");
        }

        return new JsonResponse(null, 204);
    }
}
