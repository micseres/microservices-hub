<?php
/**
 * Created by PhpStorm.
 * User: zogxray
 * Date: 10.09.18
 * Time: 11:43
 */

namespace Micseres\ServiceHub\Server\Ports;

use Micseres\ServiceHub\App;
use Micseres\ServiceHub\Protocol\Middleware\RequestHandler;
use \Swoole\Server as SServer;

/**
 * Class TCPClientsPortListener
 * @package Micseres\ServiceHub\Server\Ports
 */
class TCPClientsPortListener extends ClientsPortListener implements TCPPortListenerInterface
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
     * @param int $fd
     * @param int $reactorId
     */
    public function onConnect(SServer $server, int $fd, int $reactorId)
    {
        $this->app->getLogger()->info("CLIENT SOCKET connect {$fd} to {$reactorId}");
    }

    /**
     * @param SServer $server
     * @param int $fd
     * @param int $reactorId
     */
    public function onClose(SServer $server, int $fd, int $reactorId)
    {
        $this->app->getLogger()->info("CLIENT SOCKET close {$fd} connect to {$reactorId}");
    }
}