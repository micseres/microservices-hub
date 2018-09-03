<?php
/**
 * Created by PhpStorm.
 * User: zogxray
 * Date: 29.08.18
 * Time: 19:02
 */

namespace Micseres\ServiceHub\Protocol;

/**
 * Class Router
 * @package Micseres\ServiceHub\Socket
 */
final class Router
{
    /**
     * @var array
     */
    private $routes = [
        'sleep' => [
            ['ip' => '127.0.0.1', 'port' => 7777, 'load' => 10],
            ['ip' => '127.0.0.1', 'port' => 7778, 'load' => 90]
        ],
        'report' => [
            ['ip' => '127.0.0.1', 'port' => 8888, 'load' => 10]
        ]
    ];

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
     * @param string $name
     * @return array
     */
    public function getRouteSevers(string $name) :array
    {
        return $this->routes[$name];
    }
}

