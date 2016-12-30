<?php

namespace go1\xxxxx;

use go1\app\App as GO1;
use go1\clients\ClientServiceProvider;
use go1\util\UtilServiceProvider;
use go1\xxxxx\domain\XxxxxxServiceProvider;
use Symfony\Component\HttpFoundation\JsonResponse;

class App extends GO1
{
    const NAME    = 'xxxxx';
    const VERSION = 'v17.1.1';

    public function __construct(array $values = [])
    {
        parent::__construct($values);

        $this
            ->register(new ClientServiceProvider)
            ->register(new UtilServiceProvider)
            ->register(new XxxxxxServiceProvider)
            ->get('/', function () {
                return new JsonResponse(['service' => static::NAME, 'version' => static::VERSION, 'time' => time()]);
            });
    }
}
