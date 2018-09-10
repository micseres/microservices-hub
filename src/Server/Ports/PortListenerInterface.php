<?php
/**
 * Created by PhpStorm.
 * User: zogxray
 * Date: 07.09.18
 * Time: 10:11
 */

namespace Micseres\ServiceHub\Server\Ports;

use \Swoole\Server as SServer;
use \Swoole\Server\Port;

/**
 * Interface PortListenerInterface
 * @package Micseres\ServiceHub\BaseServer
 */
interface PortListenerInterface
{
    const UDP_EVENTS = [
        'receive'
       // 'packet'
    ];

    const TCP_EVENTS = [
        'connect',
        'receive',
        'close'
    ];

    /**
     * @param SServer $server
     * @param int $fd
     * @param int $reactorId
     * @param string $data
     * @return mixed
     */
    public function onReceive(SServer $server, int $fd, int $reactorId, string $data);
}