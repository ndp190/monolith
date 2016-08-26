<?php

if (0 === strpos($_SERVER['REQUEST_URI'], '/GO1/')) {
    $_SERVER['REQUEST_URI'] = preg_replace('`^/GO1/[a-z0-9]+/(.*)$`', '/$1', $_SERVER['REQUEST_URI']);
}

if (0 === strpos($_SERVER['REQUEST_URI'], '/v3/')) {
    $_SERVER['REQUEST_URI'] = substr($_SERVER['REQUEST_URI'], 3);
}

if (0 === strpos($_SERVER['REQUEST_URI'], '//')) {
    $_SERVER['REQUEST_URI'] = substr($_SERVER['REQUEST_URI'], 1);
}

if (!empty($_GET['debug_server'])) {
    print_r($_SERVER);
    exit;
}

return require_once "/app/vendor/autoload.php";
