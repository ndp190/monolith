<?php

use Composer\Autoload\ClassLoader;
use PHPUnit\Framework\TestCase;

if (is_file("/autoload/autoload.php")) return require_once "/autoload/autoload.php";

if (is_file(__DIR__ . "/autoload.php")) {
    /** @var ClassLoader $loader */
    $loader = require_once __DIR__ . "/autoload.php";

    if (!class_exists('PHPUnit_Framework_TestCase') && class_exists(TestCase::class)) {
        class PHPUnit_Framework_TestCase extends TestCase
        {
        }
    }

    return $loader;
}
