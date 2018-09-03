<?php
/**
 * Created by PhpStorm.
 * User: zogxray
 * Date: 28.08.18
 * Time: 18:29
 */

namespace GepurIt\ServiceDispatcher\src\Http\Middleware;


/**
 * Class CorsMiddleware
 * @package GepurIt\ServiceDispatcher\src\Http\Middleware
 */
class CorsMiddleware
{
    /**
     * Execute as PSR-15 middleware.
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {

    }
}