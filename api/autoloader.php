<?php

spl_autoload_register(function ($class) {
    $baseNamespace = 'GabineteDigital\\';
    $baseDir = __DIR__ . '/src/';
    $vendorDir = __DIR__ . '/vendor/php-jwt/src/';
    if (strpos($class, $baseNamespace) === 0) {
        $relativeClass = substr($class, strlen($baseNamespace));
        $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';
        if (file_exists($file)) {
            require $file;
        }
    } elseif (strpos($class, 'Firebase\\JWT') === 0) {
        $relativeClass = substr($class, strlen('Firebase\\JWT\\'));
        $file = $vendorDir . str_replace('\\', '/', $relativeClass) . '.php';
        if (file_exists($file)) {
            require $file;
        }
    }
});
