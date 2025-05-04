<?php

use App\Kernel;

require_once dirname(__DIR__).'/vendor/autoload_runtime.php';

/*
 * @param  array<mixed>  $context
 * @return Kernel
 */
return function (array $context): Kernel {
    return new Kernel($context['APP_ENV'] ?? 'dev', (bool) $context['APP_DEBUG']);
};
