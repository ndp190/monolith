<?php

namespace go1\monolith;

use GuzzleHttp\Client;

$pwd = dirname(__DIR__);
require __DIR__ . '/build.php';
require_once $pwd . '/php/vendor/autoload.php';

$client = new Client;
$custom = $pwd . '/build.json';
$custom = is_file($custom) ? json_decode(file_get_contents($custom), true) : [];

# Start docker compose
require __DIR__ . '/start.php';

# Install database & setup default data.
require __DIR__ . '/install.php';

# Notify #launcher that the installation is completed.
if (!empty($custom['webhooks'])) {
    foreach ($custom['webhooks'] as $url) {
        echo "POST $url\n";

        $client->post($url, ['event' => 'completed']);
    }
}
