<?php
/**
 * Created by PhpStorm.
 * User: zogxray
 * Date: 03.09.18
 * Time: 18:49
 */

namespace Micseres\ServiceHub\Server;

use Micseres\ServiceHub\App;
use Micseres\ServiceHub\Server\Ports\PortListenerInterface;
use \Swoole\Server as SServer;

/**
 * Class MicroServer
 * @package Micseres\ServiceHub\MicroServer
 */
final class Server
{
    const SERVER_EVENTS = [
        'start',
        'connect',
        'receive',
        'close',
        'task',
        'finish',
        'workerStart'
    ];

    /**
     * @var SServer $swoole
     */
    private $swoole;

    /**
     * @var string
     */
    private $cliSocket = 'var/cli.sock';

    /**
     * @var App
     */
    private $app;

    /**
     * MicroServer constructor.
     * @param App $app
     */
    public function __construct(App $app)
    {
        $this->app = $app;
    }

    /**
     * @param array $events
     * @param array $setting
     */
    public function createBaseServer(array $events, array $setting = [])
    {
        $this->swoole = new \Swoole\Server($this->cliSocket, 0, SWOOLE_BASE, SWOOLE_UNIX_STREAM);

        $this->swoole->set($setting);

        $listener = new BaseServerListener($this->app);

        foreach ($events as $event) {
            $this->swoole->on($event, [$listener, 'on'.ucfirst($event)]);
        }
    }

    /**
     * @param PortListenerInterface $listener
     * @param array $events
     * @param string $ip
     * @param int $port
     * @param int $type
     * @param array $setting
     */
    public function addListener(PortListenerInterface $listener, array $events, string $ip, int $port, int $type, array $setting = [])
    {
        $port = $this->swoole->addListener($ip, $port, $type);

        $port->set($setting);

        foreach ($events as $event) {
            $port->on($event, [$listener, 'on'.ucfirst($event)]);
        }

    }

    /**
     * Start server
     */
    public function start(): void
    {
        $this->swoole->start();
    }

    /**
     * @return SServer
     */
    public function getSwoole(): \Swoole\Server
    {
        return $this->swoole;
    }
}