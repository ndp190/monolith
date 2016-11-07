<?php

use Composer\Autoload\ClassLoader;

if (is_file("/autoload/autoload.php")) return require_once "/autoload/autoload.php";

if (is_file(__DIR__ . "/autoload.php")) {
    /** @var ClassLoader $loader */
    $loader = require_once __DIR__ . "/autoload.php";

    foreach ($loader->getPrefixesPsr4() as $ns => $paths) {
        if (0 === strpos($ns, 'go1\\')) {
            $path = explode('../../php/', $paths[0])[1];
            $loader->addPsr4($ns, __DIR__ . '/../' . $path);
        }
    }

    return $loader;
}
