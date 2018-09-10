<?php
/**
 * Created by PhpStorm.
 * User: zogxray
 * Date: 10.09.18
 * Time: 11:48
 */

namespace Micseres\ServiceHub\Server\Ports;

use Micseres\ServiceHub\Protocol\MicroServers\MicroServer;
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
     * @param SServer $server
     * @param int $fd
     * @param int $reactorId
     * @param string $data
     * @throws \ReflectionException
     */
    public function onReceive(SServer $server, int $fd, int $reactorId, string $data)
    {
        $this->app->getLogger()->info("SERVICE SOCKET from {$fd} receive data to {$reactorId}");
        $request = new ServiceRequest();

        $request->deserialize($data);

        $constraints = $request->validate();

        if (null !== $constraints) {
            $errorResponse = new Response();
            $errorResponse->setProtocol("1.0");
            $errorResponse->setAction("error");
            $errorResponse->setRoute($request->getRoute());
            $errorResponse->setMessage("Invalid request");
            $errorResponse->setPayload([
                'constraints' => $constraints,
                'time' => (new \DateTime('now'))->format('Y-m-d H:i:s.u')
            ]);

            $server->send($fd, json_encode($errorResponse->serialize()));

            $this->app->getLogger()->info("SERVICE SOCKET send ERROR RESPONSE to {$fd} from {$reactorId}", (array)$errorResponse);
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
                $response->setProtocol("1.0");
                $response->setAction("registered");
                $response->setRoute($request->getRoute());
                $response->setMessage("Service registered for work");
                $response->setPayload([
                    'time' => $registeredAt->format('Y-m-d H:i:s.u')
                ]);
                $server->send($fd, json_encode($response->serialize()));
                $this->app->getLogger()->info("SERVICE SOCKET REGISTERED to {$fd} from {$reactorId}", (array)$response);
            }

            /**@todo PUT SOME REGISTRY HERE**/
            if ($request->getAction() === 'complete') {

            }
        }
    }
}