<?php
/**
 * Created by PhpStorm.
 * User: zogxray
 * Date: 04.09.18
 * Time: 11:07
 */

namespace Micseres\ServiceHub\Server;
use Micseres\ServiceHub\App;
use \Swoole\Server as SServer;
use \Swoole\Server\Port;

/**
 * Class Pool
 * @package Micseres\ServiceHub\Server
 */
class Pool
{
    /**
     * @var App
     */
    private $app;

    /**
     * Server constructor.
     * @param App $app
     */
    public function __construct(App $app)
    {
        $this->app = $app;
    }

    /**
     * @param ServerInterface $server
     * @param string $ip
     * @param int $port
     * @param int $mode
     *
     * @return Pool
     */
    public final function create(ServerInterface $server, string $ip, int $port, int $mode)
    {
        $server->getSwoole()->addListener($ip, $port, $mode);
        $server->getSwoole()->on('connect', [$this, 'onConnect']);
        $server->getSwoole()->on('receive', [$this, 'onReceive']);
        $server->getSwoole()->on('close', [$this, 'onClose']);

        return $this;
    }

    /**
     * @param SServer $server
     * @param int $fd
     * @param int $reactorId
     */
    public function onConnect(SServer $server, int $fd, int $reactorId)
    {
        $this->app->getLogger()->info("SOCKET connect {$fd} to {$reactorId}", (array)$server);
    }

    /**
     * @param SServer $server
     * @param int $fd
     * @param int $reactorId
     * @param string $data
     */
    public function onReceive(SServer $server, int $fd, int $reactorId, string $data)
    {
        $this->app->getLogger()->info("SOCKET receive {$fd} connect to {$reactorId}", (array)$server);
    }

    /**
     * @param SServer $server
     * @param int $fd
     * @param int $reactorId
     */
    public function onClose(SServer $server, int $fd, int $reactorId)
    {
        $this->app->getLogger()->info("SOCKET close {$fd} connect to {$reactorId}", (array)$server);
    }
}
