<?php

namespace go1\monolith;

use GuzzleHttp\Client;

$pwd = dirname(__DIR__);
$custom = $pwd . '/build.json';
$custom = is_file($custom) ? json_decode(file_get_contents($custom), true) : [];
$domain = isset($custom['features']['domain']) ? $custom['features']['domain'] : null;
$hooks = isset($custom['webhooks']) ? $custom['webhooks'] : [];

# ---------------------
# hook.start
# ---------------------
foreach ($hooks as $hook) {
    echo "[hook.start] $hook\n";
    passthru('curl -q -X POST ' . "'{$hook}'" . ' -H \'content-type: application/json\' -d {"event": "start"}');
}

require __DIR__ . '/build.php';
require_once $pwd . '/php/vendor/autoload.php';

$client = new Client;

# ---------------------
# Fix hard code in .data/resources/docker/fix-*.php
# ---------------------
if ($domain) {
    $fix[] = $pwd . '/.data/resources/docker/fix-apiom-ui.php';
    $fix[] = $pwd . '/.data/resources/docker/fix-website.php';
    foreach ($fix as $file) {
        $source = file_get_contents($file);
        $source = str_replace('http://localhost/GO1', 'http://' . $domain . '/GO1', $source);
        file_put_contents($file, $source);
    }
}

# ---------------------
# Start docker compose
# ---------------------
require __DIR__ . '/start.php';

# ---------------------
# Install database & setup default data.
# ---------------------
require __DIR__ . '/install.php';

# ---------------------
# hook.completed
# ---------------------
foreach ($hooks as $hook) {
    echo "[hook.complete] $hook\n";
    $client->post($hook, ['event' => 'completed']);
}
