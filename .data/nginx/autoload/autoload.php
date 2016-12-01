<?php

use Composer\Autoload\ClassLoader;

if (0 === strpos($_SERVER['REQUEST_URI'], '/GO1/')) {
    $_SERVER['REQUEST_URI'] = preg_replace('`^/GO1/[a-z0-9]+/(.*)$`', '/$1', $_SERVER['REQUEST_URI']);
}

if (0 === strpos($_SERVER['REQUEST_URI'], '/v3/')) {
    $_SERVER['REQUEST_URI'] = substr($_SERVER['REQUEST_URI'], 3);
}

if (0 === strpos($_SERVER['REQUEST_URI'], '//')) {
    $_SERVER['REQUEST_URI'] = substr($_SERVER['REQUEST_URI'], 1);
}

/** @var ClassLoader $loader */
$loader = require_once "/app/vendor/autoload.php";
foreach ($loader->getPrefixesPsr4() as $ns => $paths) {
    if (0 === strpos($ns, 'go1\\')) {
        $project = explode('\\', $ns)[1];
        if (is_dir("/app/{$project}")) {
            $loader->setPsr4($ns, "/app/{$project}");
        }
        elseif (is_dir("/app/libraries/{$project}")) {
            $loader->setPsr4($ns, "/app/libraries/{$project}");
        }
    }
}

return $loader;
