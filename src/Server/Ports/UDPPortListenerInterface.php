<?php
/**
 * Created by PhpStorm.
 * User: zogxray
 * Date: 10.09.18
 * Time: 11:26
 */

namespace Micseres\ServiceHub\Server\Ports;

use \Swoole\Server as SServer;
use \Swoole\Server\Port;

/**
 * Interface UDPPortListenerInterface
 * @package Micseres\ServiceHub\Server\Ports
 */
interface UDPPortListenerInterface extends PortListenerInterface
{
    /**
     * @param SServer $server
     * @param string $data
     * @param array $addr
     * @return mixed
     */
    public function onPacket(SServer $server, string $data, array $addr);
}