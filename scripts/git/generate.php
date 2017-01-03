<?php

namespace at\labs\git\generate;

use FilesystemIterator;
use RegexIterator;
use SplFileInfo;

require_once __DIR__ . '/../../php/vendor/go1.autoload.php';

// Change this constant when you run the script
const SERVICE_NAME = 'sms';
const SERVICE_DIR = __DIR__ . '/../../php/' . SERVICE_NAME;

function glob($dir)
{
    $iterator = new FilesystemIterator($dir, FilesystemIterator::SKIP_DOTS);
    $iterator = new RegexIterator($iterator, "/.+/i");

    /** @var SplFileInfo $file */
    foreach ($iterator as $file) {
        $files[] = $file->getPathname();
    }

    return !empty($files) ? $files : [];
}

return call_user_func(function () {
    $upper = strtoupper(SERVICE_NAME);
    $camel = ucfirst(SERVICE_NAME);
    $templateRoot = __DIR__ . '/generate';
    $copy = function ($file) use ($templateRoot, $upper, $camel) {
        $path = str_replace([$templateRoot, 'XXXXX', 'Xxxxx', 'xxxxx'], ['', $upper, $camel, SERVICE_NAME], $file);
        $path = trim($path, '/');
        $path = SERVICE_DIR . '/' . $path;
        $content = file_get_contents($file);
        $content = str_replace([$templateRoot, 'XXXXX', 'Xxxxx', 'xxxxx'], ['', $upper, $camel, SERVICE_NAME], $content);
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
