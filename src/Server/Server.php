<?php
/**
 * Created by PhpStorm.
 * User: zogxray
 * Date: 03.09.18
 * Time: 18:49
 */

namespace Micseres\ServiceHub\Server;

/**
 * Class Server
 * @package Micseres\ServiceHub\Server
 */
class Server implements ServerInterface
{
    /**
     * @var \Swoole\Server $swoole
     */
    private $swoole;

    private $pools = [];

    /**
     * @param string $ip
     * @param int $port
     * @param int $mode
     * @param int $type TCP/UDP
     * @return \Swoole\Server
     */
    public function create(string $ip, int $port, int $mode, int $type): \Swoole\Server
    {
        $this->swoole = new \Swoole\Server($ip, $port, $mode, $type);
    }

    /**
     * Start server
     */
    public function start(): void
    {
        $this->swoole->start();
    }

    /**
     * @return \Swoole\Server
     */
    public function getSwoole(): \Swoole\Server
    {
        return $this->swoole;
    }

    /**
     * @return array
     */
    public function getPools(): array
    {
        return $this->pools;
    }

    /**
     * @param Pool $pool
     */
    public function addPool(Pool $pool): void
    {
        $this->pools[] = $pool;
    }
}