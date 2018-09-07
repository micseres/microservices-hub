<?php
/**
 * Created by PhpStorm.
 * User: zogxray
 * Date: 07.09.18
 * Time: 10:11
 */

namespace Micseres\ServiceHub\Server\Ports;

use Micseres\ServiceHub\App;
use Micseres\ServiceHub\Protocol\MicroServers\MicroServer;
use Micseres\ServiceHub\Protocol\Requests\PingRequest;
use Micseres\ServiceHub\Protocol\Responses\Response;
use \Swoole\Server as SServer;

/**
 * Class ServicesPortListenerListener
 * @package Micseres\ServiceHub\BaseServer
 */
class ServicesPortListener implements PortListenerInterface
{
    /**
     * @var App
     */
    private $app;

    /**
     * ServicesPortListenerListener constructor.
     * @param App $app
     */
    public function __construct(App $app)
    {
        $this->app = $app;
    }

    /**
     * @param SServer $server
     * @param int $fd
     * @param int $reactorId
     */
    public function onConnect(SServer $server, int $fd, int $reactorId)
    {
        $this->app->getLogger()->info("SERVICE connect {$fd} to {$reactorId}");

        swoole_timer_tick(2000, function () use ($server, $fd) {
            $server->send($fd, json_encode(['test' => 'test']));
            $this->app->getLogger()->info("PING micro server");
        });
    }

    /**
     * @param SServer $server
     * @param int $fd
     * @param int $reactorId
     * @param string $data
     */
    public function onReceive(SServer $server, int $fd, int $reactorId, string $data)
    {
        $this->app->getLogger()->info("SERVICE SOCKET receive {$fd} connect to {$reactorId}");
        $request = new PingRequest();

        $request->deserialize($data);

        $constraints = $request->validate();

        if (null !== $constraints) {
            $errorResponse = new Response();
            $errorResponse->setProtocol("1.0");
            $errorResponse->setAction("error");
            $errorResponse->setRoute($request->getRoute());
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

            $route = $router->getRoute($request->getRoute());
            $microServiceServer = new MicroServer($fd, $reactorId, $remoteIp, $remotePort, $request->getPayload()['load'], $registeredAt = new \DateTime('now'));
            $route->addOrRefreshServer($microServiceServer);

            $this->app->getLogger()->info("ADD micro server {$microServiceServer->getIp()} to {$request->getRoute()}", (array)$microServiceServer);

            $response = new Response();
            $response->setProtocol("1.0");
            $response->setAction("registered");
            $response->setRoute($request->getRoute());
            $response->setMessage("Service registered for work");
            $response->setPayload([
                'time' => $registeredAt->format('Y-m-d H:i:s.u')
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
        $this->app->getLogger()->info("SOCKET close {$fd} connect to {$reactorId}");
    }
}