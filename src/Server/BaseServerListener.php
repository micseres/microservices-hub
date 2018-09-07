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
 * Class BASEPortListener
 * @package Micseres\ServiceHub\BaseServer
 */
class BaseServerListener implements BaseServerListenerInterface
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
    public function checkExpiredRouter(int $interval, SServer $server)
    {
        $router = $this->app->getRouter();
        /** @var MicroServerRoute $route */
        foreach ($router->getRoutes() as $route) {
            foreach ($route->getServers() as $index => $microServer) {
                $server->send($microServer->getFd(), json_encode(['test' => 'test']), $microServer->getReactorId());

                $now = new \DateTime('now');
                $diff = $now->getTimestamp() - $microServer->getTime()->getTimestamp();
                if ($diff > (int)$this->app->getConfiguration()->getParameter('SERVER_LIVE_INTERVAL')) {
                    $this->app->getLogger()->info("BASE REMOVE EXPIRED micro server {$microServer->getIp()} from {$route->getRoute()}");
                    $route->removeServer($index);

                }
            }
        }
    }

    public function workWithClientRequestQuery(int $interval, SServer $server)
    {
        $task = $this->app->getClientRequestQuery()->get();

        if (null !== $task) {
            $server->send($task->getServer()->getFd(), json_encode((array)$task->getRequest()), $task->getServer()->getReactorId());
            $this->app->getLogger()->info("BASE SEND REQUEST TO SERVICE", (array)$task->getRequest());
        }
    }

    /**
     * @param SServer $server
     * @param int $worker_id
     */
    public function onWorkerStart(SServer $server, int $worker_id)
    {
        if ($worker_id === 0) {
            $time = 1000;
            $server->tick($time,  [$this, 'checkExpiredRouter'], $server);
            $this->app->getLogger()->info("BASE WORKER {$worker_id} TIMER {$time} FOR ROUTES START");
        }

        $time = 1000;
        $server->tick($time,  [$this, 'workWithClientRequestQuery'], $server);
        $this->app->getLogger()->info("BASE WORKER {$worker_id} TIMER {$time} FOR ROUTES START");

        $this->app->getLogger()->info("BASE WORKER {$worker_id} START");
    }

    function onWorkerStop(SServer $server, int $worker_id)
    {
        $this->app->getLogger()->info("BASE WORKER {$worker_id} STOP");
    }

    /**
     * @param SServer $server
     * @param int $task_id
     * @param array $data
     */
    public function onFinish(SServer $server, int  $task_id, $data)
    {
        $this->app->getLogger()->info("BASE FINISH task {$task_id}");
    }

    /**
     * @param SServer $server
     * @param int $task_id
     * @param int $from_id
     * @param array $data
     */
    public function onTask(SServer $server, int  $task_id, int $from_id, $data)
    {
        $this->app->getLogger()->info("BASE START task {$task_id}");
    }

    /**
     * @param SServer $server
     */
    public function onStart(SServer $server)
    {
        $this->app->getLogger()->info("BASE SERVER IS STARTED");
    }

    /**
     * @param SServer $server
     * @return mixed|void
     */
    public function onShutdown(SServer $server)
    {
        $this->app->getLogger()->info("BASE SERVER IS SHUTDOWN");
    }

    /**
     * @param SServer $server
     * @param int $fd
     * @param int $reactorId
     */
    public function onConnect(SServer $server, int $fd, int $reactorId)
    {
        $this->app->getLogger()->info("BASE connect {$fd} to {$reactorId}");
    }

    /**
     * @param SServer $server
     * @param int $fd
     * @param int $reactorId
     * @param string $data
     */
    public function onReceive(SServer $server, int $fd, int $reactorId, string $data)
    {
        $this->app->getLogger()->info("BASE SOCKET receive {$fd} connect to {$reactorId}");
    }

    /**
     * @param SServer $server
     * @param int $fd
     * @param int $reactorId
     */
    public function onClose(SServer $server, int $fd, int $reactorId)
    {
        $this->app->getLogger()->info("BASE SOCKET close {$fd} connect to {$reactorId}");
    }
}