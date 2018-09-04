<?php
/**
 * Created by PhpStorm.
 * User: zogxray
 * Date: 04.09.18
 * Time: 11:07
 */

namespace Micseres\ServiceHub\Server;
use \Swoole\Server as SServer;

/**
 * Class Pool
 * @package Micseres\ServiceHub\Server
 */
class Pool
{
    private $pool;
    /**
     * Pool constructor.
     * @param ServerInterface $server
     * @param string $ip
     * @param int $port
     * @param int $mode
     */
    public function __construct(ServerInterface $server, string $ip, int $port, int $mode)
    {
        $this->pool = $server->getSwoole()->addListener($ip, $port, $mode);
    }

    /**
     * @return bool|\swoole_server_port
     */
    public function getPool()
    {
        return $this->pool;
    }

    /**
     * @param SServer $server
     * @param int $fd
     * @param int $reactorId
     */
    public function onConnect(SServer $server, int $fd, int $reactorId)
    {

    }

    /**
     * @param SServer $server
     * @param int $fd
     * @param int $reactorId
     * @param string $data
     */
    public function onReceive(SServer $server, int $fd, int $reactorId, string $data)
    {

    }
}
