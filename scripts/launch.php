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
    passthru("curl -s -q -X POST '{$hook}' -H 'content-type: application/json' -d '{\"event\": \"start\"}' >/dev/null");
}

# ---------------------
# Domain is still empty at the moment, request to get a domain.
# ---------------------
require_once $pwd . '/php/vendor/autoload.php';

$client = new Client;

if (empty($domain) && isset($custom['get_public_dns_url'][0])) {
    $resp = $client->get($custom['get_public_dns_url'], ['verify' => false, 'http_errors' => false]);
    if ($resp->getStatusCode() == 200) {
        $domain = $resp->getBody()->getContents();

        // write back build.json
        $custom['features']['domain'] = $domain;
        file_put_contents($pwd . '/build.json', json_encode($custom));
    }
}

# ---------------------
# Default domain is localhost, we need to change it when we deploy to cloud.
# ---------------------
if ($domain) {
    echo "[x] Setup domain: $domain\n";
    call_user_func(require __DIR__ . '/fix-web.php', $pwd, $domain);
}

# ---------------------
# Make sure all images are updated (some images are cached).
# ---------------------
passthru('php ' . __DIR__ . '/pull.php');

# ---------------------
# Build every thing (php, web).
# ---------------------
passthru('php ' . __DIR__ . '/build.php');

# ---------------------
# Start docker compose
# ---------------------
passthru('php ' . __DIR__ . '/start.php --with-scorm');

# ---------------------
# Install database & setup default data.
# ---------------------
passthru('php ' . __DIR__ . '/install.php --with-scorm');

# ---------------------
# hook.completed
# ---------------------
foreach ($hooks as $hook) {
    echo "[hook.complete] $hook\n";
    $client->post($hook, ['http_errors' => false, 'json' => ['event' => 'completed']]);
}
