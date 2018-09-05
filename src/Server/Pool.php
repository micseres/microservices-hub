<?php
/**
 * Created by PhpStorm.
 * User: zogxray
 * Date: 04.09.18
 * Time: 11:07
 */

namespace Micseres\ServiceHub\Server;
use Monolog\Logger;
use \Swoole\Server as SServer;
use \Swoole\Server\Port;

/**
 * Class Pool
 * @package Micseres\ServiceHub\Server
 */
class Pool
{
    private $pool;

    /**
     * @var Logger
     */
    private $logger;

    /**
     * Server constructor.
     * @param Logger $logger
     */
    public function __construct(Logger $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param ServerInterface $server
     * @param string $ip
     * @param int $port
     * @param int $mode
     */
    public final function create(ServerInterface $server, string $ip, int $port, int $mode)
    {
        $this->pool = $server->getSwoole()->addListener($ip, $port, $mode);

        $server->getSwoole()->on('connect', [$this, 'onConnect']);
        $server->getSwoole()->on('receive', [$this, 'onReceive']);
        $server->getSwoole()->on('close', [$this, 'onReceive']);
    }

    /**
     * @return bool|\swoole_server_port|bool
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
        $this->logger->info("SOCKET connect {$fd} to {$reactorId}", (array)$server);
    }

    /**
     * @param SServer $server
     * @param int $fd
     * @param int $reactorId
     * @param string $data
     */
    public function onReceive(SServer $server, int $fd, int $reactorId, string $data)
    {
        $this->logger->info("SOCKET receive {$fd} connect to {$reactorId}", (array)$server);
    }

    /**
     * @param SServer $server
     * @param int $fd
     * @param int $reactorId
     */
    public function onClose(SServer $server, int $fd, int $reactorId)
    {
        $this->logger->info("SOCKET close {$fd} connect to {$reactorId}", (array)$server);
    }
}
