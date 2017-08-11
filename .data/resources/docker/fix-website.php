<?php

$html = file_get_contents('/website/index.html');

$matches = [];
preg_match('`<script src="scripts/scripts.([0-9a-z]+).js">`i', $html, $matches);

$configFile = "/website/scripts/scripts.{$matches[1]}.js";
$config = file_get_contents($configFile);
if (!strpos($config, 'localhost/GO1/') !== false) {
    $config = str_replace('https://api-dev.mygo1.com/v3', 'http://localhost/GO1', $config);
    $config = str_replace('https://api-dev.mygo1.com/v2', 'http://localhost/GO1', $config);
    $config = str_replace('https://api-dev.mygo1.com', 'http://localhost/GO1', $config);
    $config = str_replace('env:"dev"', 'env:"monolith"', $config);
    $config = str_replace('domain:".mygo1.com"', 'domain:".go1.local"', $config);
    file_put_contents($configFile, $config);
}
