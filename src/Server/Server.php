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
abstract class Server
{
    /**
     * @param string $ip
     * @param int $port
     * @param int $mode
     * @param int $type TCP/UDP
     * @return \Swoole\Server
     */
    public function create(string $ip, int $port, int $mode, int $type): \Swoole\Server
    {
        // TODO: Implement create() method.
    }

    /**
     * Start server
     */
    public function start(): void
    {
        // TODO: Implement start() method.
    }
}