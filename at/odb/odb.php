<?php

namespace andytruong\odb;

use Symfony\Component\Console\Application;

/** @var App $app */
$app = require __DIR__ . '/public/index.php';

# $res = $app->handle(Request::create('/install'));
# dump($res->getStatusCode());
# dump($res->getContent());
# exit;

$console = new Application($app::NAME, $app::VERSION);
$console->add($app['cmd.import']);
$console->run();
