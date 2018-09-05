<?php
/**
 * Created by PhpStorm.
 * User: zogxray
 * Date: 03.09.18
 * Time: 17:42
 */

namespace Micseres\ServiceHub\Server;
use \Swoole\Server as SServer;

/**
 * Class ServerInterface
 * @package Micseres\ServiceHub\Server
 */
interface ServerInterface
{
    /**
     * @param string $ip
     * @param int $port
     * @param int $mode
     * @param int $type TCP/UDP
     */
    public function create(string $ip, int $port, int $mode, int $type);

    /**
     * Start server
     */
    public function start(): void;

    /**
     * @return SServer
     */
    public function getSwoole(): SServer;

    /**
     * @return array
     */
    public function getPools(): array;

    /**
     * @param Pool $pool
     */
    public function addPool(Pool $pool): void;
}