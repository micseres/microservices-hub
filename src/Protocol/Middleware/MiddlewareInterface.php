<?php
/**
 * @author: Andrii yakovlev <yawa20@gmail.com>
 * @since : 05.09.18
 */

namespace Micseres\ServiceHub\Protocol\Middleware;

use Micseres\ServiceHub\Protocol\Requests\RequestInterface;
use Micseres\ServiceHub\Protocol\Responses\ResponseInterface;

/**
 * Interface MiddlewareInterface
 * @package Micseres\ServiceHub\Protocol\Middleware
 */
interface MiddlewareInterface
{
    /**
     * @param mixed $request
     * @param \Closure $next
     *
     * @return ResponseInterface|null
     */
    public function handle(RequestInterface $request, \Closure $next): ?ResponseInterface;
}
