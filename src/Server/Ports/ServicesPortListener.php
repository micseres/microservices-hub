<?php
/**
 * Created by PhpStorm.
 * User: zogxray
 * Date: 10.09.18
 * Time: 11:48
 */

namespace Micseres\ServiceHub\Server\Ports;

use Micseres\ServiceHub\Protocol\MicroServers\MicroServer;
use Micseres\ServiceHub\Protocol\Middleware\RequestHandler;
use Micseres\ServiceHub\Protocol\Requests\ServiceRequest;
use Micseres\ServiceHub\Protocol\Responses\Response;
use \Swoole\Server as SServer;
use Micseres\ServiceHub\App;

/**
 * Class ServicesPortListener
 * @package Micseres\ServiceHub\Server\Ports
 */
abstract class ServicesPortListener
{
    /**
     * @var App
     */
    protected $app;


    /**
     * @var RequestHandler
     */
    protected $requestHandler;

    /**
     * @param SServer $server
     * @param int $fd
     * @param int $reactorId
     * @param string $data
     * @throws \ReflectionException
     */
    public function onReceive(SServer $server, int $fd, int $reactorId, string $data)
    {
        $this->app->getLogger()->info("SERVICE SOCKET from {$fd} receive data to {$reactorId}");

        $request = new ServiceRequest($data);

        $response = $this->requestHandler->handle($request);

        if (null !== $response) {
            $server->send($fd, json_encode($response->serialize()));
            $this->app->getLogger()->error("SERVICE SOCKET send ERROR RESPONSE to {$fd} from {$reactorId}", (array)$response);
        } else {
            /**@todo PUT SOME REGISTRY HERE **/
            if ($request->getRoute() === 'system' && $request->getAction() === 'register') {
                $router = $this->app->getRouter();
                $remoteIp = $server->connection_info($fd)["remote_ip"];
                $remotePort = $server->connection_info($fd)["remote_port"];

                $route = $router->getRoute($request->getPayload()['route']);
                $microServiceServer = new MicroServer($fd, $reactorId, $remoteIp, $remotePort, $request->getPayload()['load'], $registeredAt = new \DateTime('now'));
                $route->addOrRefreshServer($microServiceServer);

                $this->app->getLogger()->info("ADD micro server {$microServiceServer->getIp()} to {$request->getPayload()['route']}", (array)$microServiceServer);

                $response = new Response();
                $response->setAction("registered");
                $response->setRoute($request->getRoute());
                $response->setMessage("Service registered for work");

                $server->send($fd, json_encode($response->serialize()));
                $this->app->getLogger()->info("SERVICE SOCKET REGISTERED to {$fd} from {$reactorId}", (array)$response);
            } else {
                $router = $this->app->getRouter();
                $route = $router->getRoute($request->getRoute());

                $queryItem = $route->getServiceResponseQuery()->pick($request->getPayload()['task_id']);

                if (null !== $queryItem) {
                    $request2 = [
                        'protocol' => '1.0',
                        'action' => $request->getAction(),
                        'route' => $request->getRoute(),
                        'message' => $request->getMessage(),
                        'payload' => [
                            'fibonacci' => $request->getPayload()['fibonacci']
                        ]
                    ];

                    $server->send($queryItem->getClient()->getFd(), json_encode($request2), $queryItem->getClient()->getReactorId());
                } else {
                    $this->app->getLogger()->info("QUERY ITEM NOT FOUND", (array)$queryItem);
                }
            }
        }
    }
}