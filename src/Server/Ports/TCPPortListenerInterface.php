<?php
/**
 * Created by PhpStorm.
 * User: zogxray
 * Date: 10.09.18
 * Time: 11:28
 */

namespace Micseres\ServiceHub\Server\Ports;

use \Swoole\Server as SServer;
use \Swoole\Server\Port;


/**
 * Class TCPPortListenerInterface
 * @package Micseres\ServiceHub\Server\Ports
 */
interface TCPPortListenerInterface extends PortListenerInterface
{
    /**
     * @param SServer $server
     * @param int $fd
     * @param int $reactorId
     * @return mixed
     */
    public function onConnect(SServer $server, int $fd, int $reactorId);

    /**
     * @param SServer $server
     * @param int $fd
     * @param int $reactorId
     * @return mixed
     */
    public function onClose(SServer $server, int $fd, int $reactorId);
}