<?php
/**
 * Created by PhpStorm.
 * User: zogxray
 * Date: 29.08.18
 * Time: 19:02
 */

namespace Micseres\ServiceHub\Protocol;

use Micseres\ServiceHub\Protocol\MicroServers\MicroServer;

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
     * @param string $name
     * @return bool
     */
    public function isRouteExists(string $name) :bool
    {
        return isset($this->routes[$name]);
    }

    /**
     * @param string $name
     * @return array
     */
    public function getRouteSever(string $name) :array
    {
        usort($this->routes[$name], function ($a, $b) {
            return $a['load'] <=> $b['load'];
        });

        return $this->routes[$name][0];
    }

    /**
     * @param string $route
     * @param array $server
     */
    public function addRouteServer(string $route, array  $server): void
    {
        $this->routes[$route][] = $server;
    }

    /**
     * @param string $route
     * @param int $index
     */
    public function removeRouteServer(string $route, int $index)
    {
        unset($this->routes[$route][$index]);
    }

    /**
     * @param string $name
     * @return array
     */
    public function getRouteSevers(string $name) :array
    {
        return $this->routes[$name];
    }

    /**
     * @return array
     */
    public function getRoutes(): array
    {
        return $this->routes;
    }
}

