<?php
/**
 * Created by PhpStorm.
 * User: zogxray
 * Date: 03.09.18
 * Time: 17:26
 */

namespace Micseres\ServiceHub\Protocol\Middleware;



use Micseres\ServiceHub\Protocol\Requests\RequestInterface;
use Micseres\ServiceHub\Protocol\Responses\ResponseInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Interface MiddlewareInterface
 * @package Micseres\ServiceHub\Protocol\Middleware
 */
interface MiddlewareInterface
{
    /**
     * @param RequestInterface $request
     * @param RequestHandlerInterface $handler
     * @return ResponseInterface
     */
    public function process(RequestInterface $request, RequestHandlerInterface $handler): ResponseInterface;
}