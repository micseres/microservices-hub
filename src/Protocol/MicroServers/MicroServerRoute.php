<?php
/**
 * Created by PhpStorm.
 * User: zogxray
 * Date: 06.09.18
 * Time: 14:31
 */

namespace Micseres\ServiceHub\Protocol\MicroServers;

/**
 * Class MicroServerRoute
 * @package Micseres\ServiceHub\Protocol\MicroServers
 */
class MicroServerRoute implements MicroServerRouteInterface
{
    /**
     * @var string
     */
    private $route;

    /**
     * @var MicroServer
     */
    private $servers = [];

    /**
     * MicroServerRoute constructor.
     * @param $route
     */
    public function __construct($route)
    {
        $this->route = $route;
    }

    /**
     * @return string
     */
    public function getRoute(): string
    {
        return $this->route;
    }

    /**
     * @return MicroServer
     */
    public function getServers(): MicroServer
    {
        return $this->servers;
    }

    /**
     * @param MicroServer $server
     */
    public function addServer(MicroServer $server): void
    {
        $this->servers[] = $server;
    }
}