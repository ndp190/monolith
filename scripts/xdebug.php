<?php

namespace go1\monolith;

use go1\app\App;
use RuntimeException;
use Symfony\Component\HttpFoundation\Request;

require '/autoload/autoload.php';

$cmd = implode(' ', $argv);

if (false === strpos($cmd, 'curl')) {
    $curl = file_get_contents(__DIR__ . '/curl.bash');
    $curl = str_replace('#!/usr/bin/env bash', '', $curl);
    $curl = trim($curl);

    return passthru('php ' . __FILE__ . ' ' . $curl);
}

unset($argv[0], $argv[1]);

$headers = [];
foreach ($argv as $i => $arg) {
    $arg = trim($arg);
    if (!$arg) {
        continue;
    }

    if (0 === strpos($arg, 'http://')) {
        $matches = [];
        preg_match('`^http://localhost/GO1/([^/]+)(/.*)$`', $arg, $matches);
        if (empty($matches[1])) {
            throw new RuntimeException("only support: http://localhost/SERVICE_NAME");
        }

        $service = str_replace(['-service', '-'], ['', '_'], $matches[1]);
        $uri = $matches[2];
    }
    elseif ('-X' === $arg) {
        $method = $argv[$i + 1];
        unset($argv[$i], $argv[$i + 1]);
    }
    elseif ('-H' === $arg) {
        $arg = $argv[$i + 1];
        list($k, $v) = explode(': ', $arg, 2);
        $headers[$k][] = $v;
        unset($argv[$i], $argv[$i + 1]);
    }
    elseif ('--data-binary' === $arg) {
        $method = isset($method) ? $method : 'POST';
        $content = $argv[$i + 1];
        unset($argv[$i], $argv[$i + 1]);
    }
    elseif (isset($argv[$i])) {
        if ('--compressed' !== $arg) {
            dump("[$arg]");
            exit;
        }
    }
}

if (isset($uri) && isset($service)) {
    /** @var App $app */
    $app = "\\go1\\{$service}\\App";
    $app = new $app(require "/app/{$service}/config.default.php");

    $req = Request::create(
        $uri,
        isset($method) ? $method : 'GET',
        $params = [],
        $cookies = [],
        $files = [],
        $server = [],
        isset($content) ? $content : null
    );

    $req->headers->replace($headers);

    echo "[{$service}.handle] {$req->getMethod()} {$req->getRequestUri()}\n";
    $res = $app->handle($req);
    $app->terminate($req, $res);

    dump(
        [
            'code'    => $res->getStatusCode(),
            'content' => $res->getContent(),
        ]
    );
}
