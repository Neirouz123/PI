<?php

// Custom bootstrap to handle PHP 8+ E_STRICT deprecation warnings
// This file is included before vendor/autoload_runtime.php

// Set error reporting to exclude deprecation warnings entirely
error_reporting(E_ALL & ~E_DEPRECATED & ~E_USER_DEPRECATED);

// Set a custom error handler that completely suppresses E_STRICT warnings
set_error_handler(function ($severity, $message, $file, $line) {
    // Completely suppress any E_STRICT related warnings
    if (
        strpos($message, 'E_STRICT') !== false ||
        strpos($message, 'Constant E_STRICT is deprecated') !== false
    ) {
        return true; // Suppress completely
    }

    // Suppress all deprecation warnings from vendor/symfony
    if ($severity === E_DEPRECATED && strpos($file, 'vendor/symfony') !== false) {
        return true;
    }

    // Let other errors be handled normally
    return false;
});

// Also set ini to suppress deprecation warnings
ini_set('error_reporting', E_ALL & ~E_DEPRECATED & ~E_USER_DEPRECATED);

// Disable all deprecation warnings via environment
putenv('SYMFONY_DEPRECATIONS_HELPER=disabled');
