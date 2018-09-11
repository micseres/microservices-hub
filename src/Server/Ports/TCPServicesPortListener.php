<?php
/**
 * Created by PhpStorm.
 * User: zogxray
 * Date: 10.09.18
 * Time: 11:51
 */

namespace Micseres\ServiceHub\Server\Ports;

use Micseres\ServiceHub\App;
use Micseres\ServiceHub\Protocol\Middleware\RequestHandler;
use \Swoole\Server as SServer;

/**
 * Class TCPServicesPortListener
 * @package Micseres\ServiceHub\Server\Ports
 */
class TCPServicesPortListener extends ServicesPortListener implements TCPPortListenerInterface
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
        $this->app->getLogger()->info("SERVICE SOCKET connect {$fd} to {$reactorId}");
    }


    /**
     * @param SServer $server
     * @param int $fd
     * @param int $reactorId
     */
    public function onClose(SServer $server, int $fd, int $reactorId)
    {
        $this->app->getLogger()->info("SERVICE SOCKET close {$fd} connect to {$reactorId}");
    }
}