<?php

namespace go1\xxxxx;

use go1\app\App as GO1;
use go1\util\Service;
use go1\util\UtilServiceProvider;
use go1\xxxxx\domain\XxxxxServiceProvider;
use Symfony\Component\HttpFoundation\JsonResponse;

class App extends GO1
{
    const NAME    = 'xxxxx';
    const VERSION = Service::VERSION;

    public function __construct(array $values = [])
    {
        parent::__construct($values);

        $this
            ->register(new UtilServiceProvider)
            ->register(new XxxxxServiceProvider)
            ->get('/', function () {
                return new JsonResponse(['service' => static::NAME, 'version' => static::VERSION, 'time' => time()]);
            });
    }
}
