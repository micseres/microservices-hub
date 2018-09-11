<?php
/**
 * Created by PhpStorm.
 * User: zogxray
 * Date: 11.09.18
 * Time: 11:10
 */

namespace Micseres\ServiceHub\Protocol\Middleware;

use Micseres\ServiceHub\Protocol\Middleware\Handlers\AuthMiddleware;
use Micseres\ServiceHub\Protocol\Middleware\Handlers\ValidateMiddleware;
use Micseres\ServiceHub\Protocol\Requests\RequestInterface;
use Micseres\ServiceHub\Protocol\Responses\ResponseInterface;

/**
 * Class RequestHandler
 * @package Micseres\ServiceHub\Protocol\Middleware
 */
class RequestHandler implements RequestHandlerInterface
{
    /** @var array */
    protected $registred = [
        AuthMiddleware::class,
        ValidateMiddleware::class
    ];

    /** @var array RequestHandlerInterface[] */
    protected $middlewares = [];

    /** @var ClosureBuilder  */
    private $closureBuilder;

    /** @var \Closure */
    private $process;

    /**
     * RequestHandler constructor.
     * @param ClosureBuilder $closureBuilder
     */
    public function __construct(ClosureBuilder $closureBuilder)
    {
        $this->closureBuilder = $closureBuilder;

        foreach ($this->registred as $item) {
            $this->middlewares[] = new $item();
        }
    }

    /**
     * @param RequestInterface $request
     *
     * @return ResponseInterface|null
     */
    public function handle(RequestInterface $request): ?ResponseInterface
    {
        if ($this->process === null) {
            $this->process = $this->closureBuilder->build($this->middlewares);
        }

        $method = $this->process;

        return $method($request);
    }
}