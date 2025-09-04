<?php

// Suppress all deprecation warnings FIRST, before any includes
error_reporting(E_ALL & ~E_DEPRECATED & ~E_USER_DEPRECATED);
ini_set('error_reporting', E_ALL & ~E_DEPRECATED & ~E_USER_DEPRECATED);

// Set environment variable to disable Symfony deprecations
putenv('SYMFONY_DEPRECATIONS_HELPER=disabled');
$_ENV['SYMFONY_DEPRECATIONS_HELPER'] = 'disabled';

use App\Kernel;

// Include custom bootstrap to handle E_STRICT deprecation
require_once dirname(__DIR__) . '/config/bootstrap.php';

require_once dirname(__DIR__) . '/vendor/autoload_runtime.php';

return function (array $context) {
    return new Kernel($context['APP_ENV'], (bool) $context['APP_DEBUG']);
};
