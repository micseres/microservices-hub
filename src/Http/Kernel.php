<?php

namespace GepurIt\ServiceDispatcher\Http;


use GepurIt\ServiceDispatcher\src\Http\Middleware\CorsMiddleware;

/**
 * Class Kernel
 * @package GepurIt\ServiceDispatcher\Http
 */
class Kernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array
     */
    protected $middleware = [
        CorsMiddleware::class
    ];
}
