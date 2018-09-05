<?php
/**
 * Created by PhpStorm.
 * User: zogxray
 * Date: 03.09.18
 * Time: 18:49
 */

namespace Micseres\ServiceHub\Server;

use Micseres\ServiceHub\App;

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
     * @var App
     */
    private $app;

    /**
     * Server constructor.
     * @param App $app
     */
    public function __construct(App $app)
    {
        $this->app = $app;
    }

    /**
     * @param string $ip
     * @param int $port
     * @param int $mode
     * @param int $type TCP/UDP
     */
    public function create(string $ip, int $port, int $mode, int $type)
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
     * @param string $ip
     * @param int $port
     * @param int $mode
     */
    public function addPool(string $ip, int $port, int $mode)
    {
        $pool = new Pool($this->app);
        $this->pools[] = $pool->create($this, $ip, $port, $mode);
    }
}