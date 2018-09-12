<?php
/**
 * Created by PhpStorm.
 * User: zogxray
 * Date: 10.09.18
 * Time: 11:41
 */

namespace Micseres\ServiceHub\Server\Ports;

use Micseres\ServiceHub\Protocol\Client\Client;
use Micseres\ServiceHub\Protocol\Middleware\RequestHandler;
use Micseres\ServiceHub\Protocol\Requests\ClientRequest;
use Micseres\ServiceHub\Protocol\Responses\Response;
use Micseres\ServiceHub\Server\Exchange\RequestQueryItem;
use \Swoole\Server as SServer;
use Micseres\ServiceHub\App;

/**
 * Class ClientsPortListener
 * @package Micseres\ServiceHub\Server\Ports
 */
abstract class ClientsPortListener
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
        $this->app->getLogger()->info("CLIENT SOCKET from {$fd} receive data to {$reactorId}");

        $request = new ClientRequest($data);
        $response = $this->requestHandler->handle($request);

        if (null !== $response) {
            $server->send($fd, json_encode($response->serialize()));
            $this->app->getLogger()->error("CLIENT SOCKET invalid request", (array)$response);
        } else {
            $router = $this->app->getRouter();
            $serviceRoute = $router->getRoute($request->getRoute());

            $service = $serviceRoute->getServer();

            if (null !== $service) {
                $client = new Client($fd, $reactorId);

                $requestQueryItem = new RequestQueryItem($request, $service, $client);
                $query = $serviceRoute->getClientRequestQuery();
                $query->push($requestQueryItem);

                $response = new Response();
                $response->setAction("accepted");
                $response->setRoute("system");
                $response->setMessage("Request accepted");

                $server->send($fd, json_encode($response->serialize()));

                $this->app->getLogger()->info("CLIENT SOCKET send ACCEPT RESPONSE to {$fd} from {$reactorId}", (array)$response);
            } else {
                $errorResponse = new Response();
                $errorResponse->setAction("error");
                $errorResponse->setRoute("system");
                $errorResponse->setMessage("Service not found");

                $server->send($fd, json_encode($errorResponse->serialize()));

                $this->app->getLogger()->error("CLIENT SOCKET server not found", (array)$errorResponse);
            }
        }
    }
}