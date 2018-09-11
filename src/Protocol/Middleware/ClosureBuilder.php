<?php
/**
 * @author: Andrii yakovlev <yawa20@gmail.com>
 * @since : 10.09.18
 */

namespace Micseres\ServiceHub\Protocol\Middleware;

/**
 * Class ClosureBuilder
 * @package Micseres\ServiceHub\Protocol\Middleware
 */
class ClosureBuilder
{
    /**
     * @param MiddlewareInterface[] $middlewares
     * @return mixed
     */
    public function build(array $middlewares)
    {
        $stack = array_reverse($middlewares);

        $closure = array_reduce(
            $stack,
            function (?\Closure $next, MiddlewareInterface $current) {
                return function ($request) use ($current, $next) {
                    return $current->handle($request, $next);
                };
            },
            null
        );

        return $closure;
    }
}
