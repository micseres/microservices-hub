<?php
/**
 * Created by PhpStorm.
 * User: zogxray
 * Date: 07.09.18
 * Time: 10:13
 */

namespace Micseres\ServiceHub\Server\Ports;

use Micseres\ServiceHub\App;
use Micseres\ServiceHub\Protocol\Middleware\RequestHandler;
use \Swoole\Server as SServer;

/**
 * Class UDPClientsPortListener
 * @package Micseres\ServiceHub\BaseServer\Ports
 */
class UDPClientsPortListener extends ClientsPortListener implements UDPPortListenerInterface
{
    /**
     * ServicesPortListenerListener constructor.
     * @param App $app
     * @param RequestHandler $requestHandler
     */
    public function __construct(App $app, RequestHandler $requestHandler)

    {
        $this->app = $app;
        $this->requestHandler = $requestHandler;
    }

    /**
     * @param SServer $server
     * @param string $data
     * @param array $addr
     */
    public function onPacket(SServer $server, string $data, array $addr)
    {
        $addr = json_encode($addr);
        $this->app->getLogger()->info("CLIENT SOCKET receive packet from {$addr}", (array)$data);
    }
}