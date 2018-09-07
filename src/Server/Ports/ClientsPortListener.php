<?php
/**
 * Created by PhpStorm.
 * User: zogxray
 * Date: 07.09.18
 * Time: 10:13
 */

namespace Micseres\ServiceHub\Server\Ports;

use Micseres\ServiceHub\App;
use \Swoole\Server as SServer;

/**
 * Class ClientsPortListener
 * @package Micseres\ServiceHub\BaseServer\Ports
 */
class ClientsPortListener implements PortListenerInterface
{
    /**
     * @var App
     */
    private $app;

    /**
     * ServicesPortListenerListener constructor.
     * @param App $app
     */
    public function __construct(App $app)

    {
        $this->app = $app;
    }

    /**
     * @param SServer $server
     * @param int $fd
     * @param int $reactorId
     */
    public function onConnect(SServer $server, int $fd, int $reactorId)
    {
        $this->app->getLogger()->info("CLIENT connect {$fd} to {$reactorId}");
    }

    /**
     * @param SServer $server
     * @param int $fd
     * @param int $reactorId
     * @param string $data
     */
    public function onReceive(SServer $server, int $fd, int $reactorId, string $data)
    {
        $this->app->getLogger()->info("CLIENT SOCKET receive {$fd} connect to {$reactorId}");
        $server->send($fd, $data);
        $this->app->getLogger()->info("CLIENT SOCKET send ping back");
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