<?php
/**
 * Created by PhpStorm.
 * User: zogxray
 * Date: 03.09.18
 * Time: 18:49
 */

namespace Micseres\ServiceHub\Server;

use Micseres\ServiceHub\App;
use Micseres\ServiceHub\Protocol\Requests\PingRequest;
use Micseres\ServiceHub\Protocol\Responses\Response;
use \Swoole\Server as SServer;
use \Swoole\Server\Port;

/**
 * Class MicroServer
 * @package Micseres\ServiceHub\MicroServer
 */
class Server implements ServerInterface
{
    /**
     * @var SServer $swoole
     */
    private $swoole;

    private $pools = [];

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
     * @param string $ip
     * @param int $port
     * @param int $mode
     * @param int $type TCP/UDP
     */
    public function create(string $ip, int $port, int $mode, int $type)

    {
        $this->swoole = new \Swoole\Server($ip, $port, $mode, $type);

        $this->swoole->set([
            'worker_num' => 1,
            'task_worker_num' => 1,
            //'daemonize' => true,
            'max_request' => 10000,
            'dispatch_mode' => 2,
            'debug_mode'=> 1,
            'log_file' => './logs/swoole_http_server.log'
        ]);

        $this->swoole->on('start', [$this, 'onStart']);
        $this->swoole->on('connect', [$this, 'onConnect']);
        $this->swoole->on('receive', [$this, 'onReceive']);
        $this->swoole->on('close', [$this, 'onClose']);
        $this->swoole->on('task', [$this, 'onTask']);
        $this->swoole->on('finish', [$this, 'onFinish']);
        $this->swoole->on('workerStart', [$this, 'onWorkerStart']);
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

    /**
     * @param int $interval
     * @param SServer $server
     *
     * @throws \Micseres\ServiceHub\Exceptions\ConfigurationException
     */
    public function onTimer(int $interval, SServer $server)
    {
        $time =  (new \DateTime('now'))->format('Y-m-d H:i:s.u');
//        $this->app->getLogger()->info("TIMER TICK on {$time}", ['interval' => $interval]);

        $router =  $this->app->getRouter();

        foreach ($router->getRoutes() as $route => $servers) {
            foreach ($servers as $index => $server) {
                $serverLastLive = new \DateTime($server['time']);
                $now = new \DateTime('now');
                $diff = $now->getTimestamp() - $serverLastLive->getTimestamp();
                if ($diff > (int)$this->app->getConfiguration()->getParameter('SERVER_LIVE_INTERVAL')) {
                    $this->app->getLogger()->info("REMOVE EXPIRED server {$server['ip']} from {$route}", $server);
                    $router->removeRouteServer($route, $index);
                }
            }
        }
    }

    /**
     * @param SServer $server
     */
    public function onWorkerStart(SServer $server, int $interval)
    {
        $this->swoole->tick(2000,  [$this, 'onTimer'], $server);
        $this->app->getLogger()->info("WORKER START", (array)$server);
    }


    /**
     * @param SServer $server
     * @param int $task_id
     * @param array $data
     */
    public function onFinish(SServer $server, int  $task_id, array $data)
    {
        $this->app->getLogger()->info("FINISH task {$task_id}", (array)$server);
    }

    /**
     * @param SServer $server
     * @param int $task_id
     * @param int $from_id
     * @param array $data
     */
    public function onTask(SServer $server, int  $task_id, int $from_id, array $data)
    {
        $this->app->getLogger()->info("START task {$task_id}", (array)$server);
    }

    /**
     * @param SServer $server
     */
    public function onStart(SServer $server)
    {
        $this->app->getLogger()->info("SERVER IS STARTED", (array)$server);
    }

    /**
     * @param SServer $server
     * @param int $fd
     * @param int $reactorId
     */
    public function onConnect(SServer $server, int $fd, int $reactorId)
    {
        $this->app->getLogger()->info("SOCKET connect {$fd} to {$reactorId}", (array)$server);
    }

    /**
     * @param SServer $server
     * @param int $fd
     * @param int $reactorId
     * @param string $data
     */
    public function onReceive(SServer $server, int $fd, int $reactorId, string $data)
    {
        $this->app->getLogger()->info("SOCKET receive {$fd} connect to {$reactorId}", (array)$server);
        $request = new PingRequest();

        $request->deserialize($data);

        $constraints = $request->validate();

        if (null !== $constraints) {
            $errorResponse = new Response();
            $errorResponse->setProtocol("1.0");
            $errorResponse->setAction("error");
            $errorResponse->setMessage("Service not registered. Invalid request");
            $errorResponse->setPayload([
                'constraints' => $constraints,
                'time' => (new \DateTime('now'))->format('Y-m-d H:i:s.u')
            ]);

            $server->send($fd, json_encode($errorResponse->serialize()));

            $this->app->getLogger()->info("SOCKET send {$fd} connect to {$reactorId}", (array)$errorResponse);
        } else {

            $router = $this->app->getRouter();

            $remoteIp = $server->connection_info($fd)["remote_ip"];
            $remotePort = $server->connection_info($fd)["remote_port"];


            $microServiceServer = [
                'ip' => $remoteIp,
                'port' => $remotePort,
                'load' => $request->getPayload()['load'],
                'time' => $request->getPayload()['time']
            ];

            $router->addRouteServer($request->getAction(), $microServiceServer);
            $this->app->getLogger()->info("ADD server {$microServiceServer['ip']} from {$request->getAction()}", $microServiceServer);

            $response = new Response();
            $response->setProtocol("1.0");
            $response->setAction("registered");
            $response->setMessage("Service registered for work");
            $response->setPayload([
                'time' => (new \DateTime('now'))->format('Y-m-d H:i:s.u')
            ]);

            $server->send($fd, json_encode($response->serialize()));

            $this->app->getLogger()->info("SOCKET send {$fd} connect to {$reactorId}", (array)$response);
        }
    }

    /**
     * @param SServer $server
     * @param int $fd
     * @param int $reactorId
     */
    public function onClose(SServer $server, int $fd, int $reactorId)
    {
        $this->app->getLogger()->info("SOCKET close {$fd} connect to {$reactorId}", (array)$server);
    }
}