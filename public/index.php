<?php

use App\Kernel;

require_once dirname(__DIR__).'/vendor/autoload_runtime.php';

return function (array $context) {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    return new Kernel($context['APP_ENV'], (bool) $context['APP_DEBUG']);
};
