<?php

namespace at\labs;

return function ($pwd) {
    $files = [
        'https://www.adminer.org/static/download/4.2.5/adminer-4.2.5-en.php' => "$pwd/php/adminer/public/index.php",
    ];

    foreach ($files as $url => $file) {
        $dir = dirname($file);
        !is_dir($dir) && passthru("mkdir -p $dir");
        !is_file($file) && passthru("wget $url -O $file");
    }
};
