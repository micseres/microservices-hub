<?php
/**
 * Created by PhpStorm.
 * User: zogxray
 * Date: 11.09.18
 * Time: 11:09
 */

namespace Micseres\ServiceHub\Protocol\Middleware\Handlers;

use Micseres\ServiceHub\Protocol\Middleware\MiddlewareInterface;
use Micseres\ServiceHub\Protocol\Requests\RequestInterface;
use Micseres\ServiceHub\Protocol\Responses\ResponseInterface;

/**
 * Class AuthMiddleware
 * @package Micseres\ServiceHub\Protocol\Middleware\Handlers
 */
class AuthMiddleware implements MiddlewareInterface
{
    /**
     * @param RequestInterface $request
     * @param \Closure $next
     *
     * @return ResponseInterface|null
     */
    public function handle(RequestInterface $request, ?\Closure $next): ?ResponseInterface
    {
        return $next ? $next($request) : null;
    }
}