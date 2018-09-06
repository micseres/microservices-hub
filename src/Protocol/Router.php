<?php
/**
 * Created by PhpStorm.
 * User: zogxray
 * Date: 29.08.18
 * Time: 19:02
 */

namespace Micseres\ServiceHub\Protocol;

use Micseres\ServiceHub\Protocol\MicroServers\MicroServer;
use Micseres\ServiceHub\Protocol\MicroServers\MicroServerRoute;

/**
 * Class Router
 * @package Micseres\ServiceHub\Socket
 */
final class Router
{
    /**
     * @var array
     */
    private $routes = [];

    /**
     * @param MicroServerRoute $route
     */
    public function addRoute(MicroServerRoute $route): void
    {
        $this->routes[$route->getRoute()] = $route;
    }

    /**
     * @param string $route
     * @return MicroServerRoute
     */
    public function getRoute(string $route): MicroServerRoute
    {
        return $this->routes[$route];
    }

    /**
     * @return array
     */
    public function getRoutes(): array
    {
        return $this->routes;
    }
}

