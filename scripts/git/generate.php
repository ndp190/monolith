<?php

require_once __DIR__ . '/../../php/vendor/go1.autoload.php';

return call_user_func(function () {
    // Change this when you run the script
    $name = 'foo';
    $camel = ucfirst($name);
    $targetRoot = __DIR__ . '/foo';

    // Should not change code below
    $templateRoot = __DIR__ . '/generate';
    $copy = function ($file) use ($templateRoot, $targetRoot, $name, $camel) {
        $path = str_replace([$templateRoot, 'Xxxxx', 'xxxxx'], ['', $camel, $name], $file);
        $path = trim($path, '/');
        $path = $targetRoot . '/' . $path;
        $content = file_get_contents($file);
        $content = str_replace([$templateRoot, 'Xxxxx', 'xxxxx'], ['', $camel, $name], $content);
        $dir = dirname($path);
        !is_dir($dir) && passthru("mkdir -p $dir");
        file_put_contents($path, $content);
    };

    $scan = function ($dir) use ($templateRoot, &$scan, &$copy) {
        foreach (glob("$dir/*") as $file) {
            if (is_dir($file)) {
                $scan($file);
            }
            else {
                $copy($file);
            }
        }
    };

    $scan($templateRoot);
});
