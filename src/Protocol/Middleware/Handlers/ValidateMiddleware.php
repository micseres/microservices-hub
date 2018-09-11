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
use Micseres\ServiceHub\Protocol\Responses\Response;
use Micseres\ServiceHub\Protocol\Responses\ResponseInterface;

/**
 * Class ValidateMiddleware
 * @package Micseres\ServiceHub\Protocol\Middleware\Handlers
 */
class ValidateMiddleware implements MiddlewareInterface
{
    /**
     * @param RequestInterface $request
     * @param \Closure $next
     *
     * @return ResponseInterface|null
     */
    public function handle(RequestInterface $request, ?\Closure $next): ?ResponseInterface
    {
        $constraints = $request->validate();

        if (null !== $constraints) {
            $errorResponse = new Response();
            $errorResponse->setProtocol("1.0");
            $errorResponse->setAction("error");
            $errorResponse->setRoute("system");
            $errorResponse->setMessage("Invalid request");
            $errorResponse->setPayload([
                'constraints' => $constraints,
                'time' => (new \DateTime('now'))->format('Y-m-d H:i:s.u')
            ]);

            return $errorResponse;
        }

        return $next ? $next($request) : null;
    }
}