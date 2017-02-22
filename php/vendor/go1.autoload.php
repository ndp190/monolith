<?php

use Composer\Autoload\ClassLoader;
use PHPUnit\Framework\TestCase;

if (is_file("/autoload/autoload.php")) return require_once "/autoload/autoload.php";

if (is_file(__DIR__ . "/autoload.php")) {
    /** @var ClassLoader $loader */
    $loader = require_once __DIR__ . "/autoload.php";

    $loader->addPsr4('go1\\clients\\', __DIR__ . '/../libraries/util/clients');

    foreach ($loader->getPrefixesPsr4() as $ns => $paths) {
        if (0 === strpos($ns, 'go1\\')) {
            $path = explode('../../php/', $paths[0])[1];
            $loader->addPsr4($ns, __DIR__ . '/../' . $path);
        }
    }

    if (!class_exists('PHPUnit_Framework_TestCase') && class_exists(TestCase::class)) {
        class PHPUnit_Framework_TestCase extends TestCase
        {
        }
    }

    return $loader;
}
