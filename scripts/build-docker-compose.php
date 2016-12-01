<?php

use Symfony\Component\Yaml\Yaml;

return function ($pwd, $home, $projects) {
    $file = "$pwd/docker-compose.yml";
    $compose = Yaml::parse(file_get_contents($file));
    $env = &$compose['services']['web']['environment'];
    foreach ($env as $i => $line) {
        if (strpos($line, '/web/GO1/')) {
            unset($env[$i]);
        }
    }

    $env = array_values($env);
    foreach (array_keys($projects['php']) as $service) {
        $SERVICE = strtoupper($service);
        $env[] = '_DOCKER_' . $SERVICE . '_URL=___' . $service . '/';
    }

    $compose = Yaml::dump($compose, 4);
    $compose = str_replace('___', 'http://web/GO1/', $compose);
    file_put_contents($file, $compose);
};
