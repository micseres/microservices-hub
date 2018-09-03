<?php
/**
 * Created by PhpStorm.
 * User: zogxray
 * Date: 03.09.18
 * Time: 17:42
 */

namespace Micseres\ServiceHub\Server;
use swoole_server;

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
     * @return \Swoole\Server
     */
    public function create(string $ip, int $port, int $mode, int $type): \Swoole\Server;

    /**
     * Start server
     */
    public function start(): void;
}