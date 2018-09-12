<?php
/**
 * Created by PhpStorm.
 * User: zogxray
 * Date: 06.09.18
 * Time: 14:31
 */

namespace Micseres\ServiceHub\Protocol\MicroServers;

use Micseres\ServiceHub\Server\Exchange\RequestQuery;

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
     * @var MicroServer[]
     */
    private $servers = [];
    /**
     * @var RequestQuery
     */
    private $clientRequestQuery;
    /**
     * @var RequestQuery
     */
    private $serviceResponseQuery;

    /**
     * MicroServerRoute constructor.
     * @param $route
     * @param RequestQuery $clientRequestQuery
     * @param RequestQuery $serviceResponseQuery
     */
    public function __construct($route, RequestQuery $clientRequestQuery, RequestQuery $serviceResponseQuery)
    {
        $this->route = $route;
        $this->clientRequestQuery = $clientRequestQuery;
        $this->serviceResponseQuery = $serviceResponseQuery;
    }

    /**
     * @return string
     */
    public function getRoute(): string
    {
        return $this->route;
    }

    /**
     * @return MicroServer[]
     */
    public function getServers(): array
    {
        return $this->servers;
    }

    /**
     * @return MicroServer
     */
    public function getServer(): ?MicroServer
    {
        $count = count($this->servers);

        return $this->servers[rand(0, $count-1)];
    }

    /**
     * @param MicroServer $server
     */
    public function addServer(MicroServer $server): void
    {
        $this->servers[] = $server;
    }
    /**
     * @param MicroServer $server
     */
    public function addOrRefreshServer(MicroServer $server): void
    {
        $updateServer = null;

        /** @var MicroServer $existentServer */
        foreach ($this->servers as $existentServer) {

            if (
                ($existentServer->getIp() === $server->getIp())
                && ($existentServer->getPort() === $server->getPort())
                && ($existentServer->getFd() === $server->getFd())
                && ($existentServer->getReactorId() === $server->getReactorId())
            ) {
                $updateServer = $existentServer;
            }
        }

        if (null !== $updateServer) {
            $updateServer->setTime(new \DateTime('now'));
        } else {
            $this->servers[] = $server;
        }
    }

    /**
     * @param int $index
     */
    public function removeServer(int $index)
    {
        unset($this->servers[$index]);
    }

    /**
     * @return RequestQuery
     */
    public function getClientRequestQuery(): RequestQuery
    {
        return $this->clientRequestQuery;
    }

    /**
     * @return RequestQuery
     */
    public function getServiceResponseQuery(): RequestQuery
    {
        return $this->serviceResponseQuery;
    }
}