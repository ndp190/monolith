<?php

/** @var ClassLoader $loader */
use Composer\Autoload\ClassLoader;

$loader = require __DIR__ . '/autoload.php';

$psr4 = $loader->getPrefixesPsr4();

foreach (array_keys($psr4) as $ns) {
    if (0 === strpos($ns, 'go1\\')) {
        $project = explode('\\', $ns)[1];
        if (is_dir(__DIR__ . '/../' . $project)) {
            $loader->addPsr4($ns, __DIR__ . '/../' . $project);
        }
    }
}
