<?php

namespace at\labs\git\generate;

use FilesystemIterator;
use RegexIterator;
use SplFileInfo;

require_once __DIR__ . '/../../php/vendor/go1.autoload.php';

function glob($dir)
{
    $iter = new FilesystemIterator($dir, FilesystemIterator::SKIP_DOTS);
    $iter = new RegexIterator($iter, "/.+/i");

    /** @var SplFileInfo $file */
    foreach ($iter as $file) {
        $files[] = $file->getPathname();
    }

    return !empty($files) ? $files : [];
}

return call_user_func(function () {
    // Change this when you run the script
    $name = 'onboard';
    $targetRoot = __DIR__ . '/../../php/onboard';

    // Should not change code below
    $upper = strtoupper($name);
    $camel = ucfirst($name);
    $templateRoot = __DIR__ . '/generate';
    $copy = function ($file) use ($templateRoot, $targetRoot, $name, $upper, $camel) {
        $path = str_replace([$templateRoot, 'XXXXX', 'Xxxxx', 'xxxxx'], ['', $upper, $camel, $name], $file);
        $path = trim($path, '/');
        $path = $targetRoot . '/' . $path;
        $content = file_get_contents($file);
        $content = str_replace([$templateRoot, 'XXXXX', 'Xxxxx', 'xxxxx'], ['', $upper, $camel, $name], $content);
        $dir = dirname($path);
        !is_dir($dir) && passthru("mkdir -p $dir");
        file_put_contents($path, $content);
    };

    $scan = function ($dir) use ($templateRoot, &$scan, &$copy) {
        foreach (glob($dir) as $file) {
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
