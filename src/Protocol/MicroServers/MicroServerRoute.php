<?php
/**
 * Created by PhpStorm.
 * User: zogxray
 * Date: 06.09.18
 * Time: 14:31
 */

namespace Micseres\ServiceHub\Protocol\MicroServers;

use Micseres\ServiceHub\Server\Exchange\ClientRequestQuery;
use Micseres\ServiceHub\Server\Exchange\ServiceResponseQuery;

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
     * @var ClientRequestQuery
     */
    private $clientRequestQuery;
    /**
     * @var ServiceResponseQuery
     */
    private $serviceResponseQuery;

    /**
     * MicroServerRoute constructor.
     * @param $route
     * @param ClientRequestQuery $clientRequestQuery
     * @param ServiceResponseQuery $serviceResponseQuery
     */
    public function __construct($route, ClientRequestQuery $clientRequestQuery, ServiceResponseQuery $serviceResponseQuery)
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
        /** @var @todo WRITE SOME FINE $count */
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
            var_dump('update');
            $updateServer->setTime(new \DateTime('now'));
        } else {
            var_dump('add');
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
     * @return ClientRequestQuery
     */
    public function getClientRequestQuery(): ClientRequestQuery
    {
        return $this->clientRequestQuery;
    }

    /**
     * @return ServiceResponseQuery
     */
    public function getServiceResponseQuery(): ServiceResponseQuery
    {
        return $this->serviceResponseQuery;
    }
}