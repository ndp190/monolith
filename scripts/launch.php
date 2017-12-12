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

require __DIR__ . '/build.php';
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
# Fix hard code in .data/resources/docker/fix-*.php
# ---------------------
if ($domain) {
    echo "[x] Setup domain: $domain\n";
    $fix[] = $pwd . '/.data/resources/docker/web/fix-apiom-ui.php';
    $fix[] = $pwd . '/.data/resources/docker/web/fix-website.php';
    foreach ($fix as $file) {
        $source = file_get_contents($file);
        $source = str_replace('localhost/GO1', $domain . '/GO1', $source);
        file_put_contents($file, $source);
    }
}

# replace apiom image tag with customized
$tag = $custom['features']['apiom_tag'] ?? 'master';
if ('master' !== $tag && !empty($custom['features']['s3_key']) && !empty($custom['features']['s3_secret'])) {
    // fetch custom apiom local
    $accessKey = $custom['features']['s3_key'];
    $secretKey = $custom['features']['s3_secret'];

    foreach ([strtolower($tag), strtoupper($tag)] as $_tag) {
        $cmd = "AWS_ACCESS_KEY_ID=$accessKey AWS_SECRET_ACCESS_KEY=$secretKey aws s3 sync s3://apiomtest/$_tag-prod/ $pwd/.data/resources/docker/web/apiom";
        passthru($cmd);
    }

    $dockerfilePath = $pwd . '/.data/resources/docker/web/Dockerfile';
    $dockerfile = file_get_contents($dockerfilePath);
    $dockerfile = str_replace(
        'COPY --from=ui /apiomui /apiomui',
        "ADD ./apiom /apiomui",
        $dockerfile);
    file_put_contents($dockerfilePath, $dockerfile);
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
    $client->post($hook, ['http_errors' => false, 'json' => ['event' => 'completed']]);
}
