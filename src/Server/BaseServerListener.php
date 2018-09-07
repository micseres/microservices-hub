<?php
/**
 * Created by PhpStorm.
 * User: zogxray
 * Date: 07.09.18
 * Time: 10:25
 */

namespace Micseres\ServiceHub\Server;

use Micseres\ServiceHub\App;
use Micseres\ServiceHub\Protocol\MicroServers\MicroServerRoute;
use \Swoole\Server as SServer;

/**
 * Class CliPortListener
 * @package Micseres\ServiceHub\Server
 */
class BaseServerListener
{
    /**
     * @var App
     */
    private $app;

    /**
     * ServicesPortListenerListener constructor.
     * @param App $app
     */
    public function __construct(App $app){
        $this->app = $app;
    }

    /**
     * @param int $interval
     * @param SServer $server
     */
    public function onTimer(int $interval, SServer $server)
    {
        $time =  (new \DateTime('now'))->format('Y-m-d H:i:s.u');
        $this->app->getLogger()->info("CLI TIMER TICK on {$time}", ['interval' => $interval]);

        $router =  $this->app->getRouter();
        /** @var MicroServerRoute $route */
        foreach ($router->getRoutes() as $route) {
            foreach ($route->getServers() as $index => $microServer) {
                $server->send($microServer->getFd(), json_encode(['test' => $interval]));
                $this->app->getLogger()->info("PING micro server {$microServer->getIp()}:{$microServer->getPort()} from {$route->getRoute()}");

                $now = new \DateTime('now');
                $diff = $now->getTimestamp() - $microServer->getTime()->getTimestamp();
                if ($diff > (int)$this->app->getConfiguration()->getParameter('SERVER_LIVE_INTERVAL')) {
                    $this->app->getLogger()->info("REMOVE EXPIRED  micro server {$microServer->getIp()} from {$route->getRoute()}");
                    $route->removeServer($index);

                }
            }
        }
    }

    /**
     * @param SServer $server
     */
    public function onWorkerStart(SServer $server, int $interval)
    {
        $server->tick(10000,  [$this, 'onTimer'], $server);

        $this->app->getLogger()->info("CLI WORKER START");
    }


    /**
     * @param SServer $server
     * @param int $task_id
     * @param array $data
     */
    public function onFinish(SServer $server, int  $task_id, array $data)
    {
        $this->app->getLogger()->info("CLI FINISH task {$task_id}");
    }

    /**
     * @param SServer $server
     * @param int $task_id
     * @param int $from_id
     * @param array $data
     */
    public function onTask(SServer $server, int  $task_id, int $from_id, array $data)
    {
        $this->app->getLogger()->info("CLI START task {$task_id}");
    }

    /**
     * @param SServer $server
     */
    public function onStart(SServer $server)
    {
        $this->app->getLogger()->info("CLI SERVER IS STARTED");
    }

    /**
     * @param SServer $server
     * @param int $fd
     * @param int $reactorId
     */
    public function onConnect(SServer $server, int $fd, int $reactorId)
    {
        $this->app->getLogger()->info("CLI connect {$fd} to {$reactorId}");
    }

    /**
     * @param SServer $server
     * @param int $fd
     * @param int $reactorId
     * @param string $data
     */
    public function onReceive(SServer $server, int $fd, int $reactorId, string $data)
    {
        $this->app->getLogger()->info("CLI SOCKET receive {$fd} connect to {$reactorId}");
    }

    /**
     * @param SServer $server
     * @param int $fd
     * @param int $reactorId
     */
    public function onClose(SServer $server, int $fd, int $reactorId)
    {
        $this->app->getLogger()->info("CLI SOCKET close {$fd} connect to {$reactorId}");
    }
}