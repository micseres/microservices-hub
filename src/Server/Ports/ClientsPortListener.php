<?php
/**
 * Created by PhpStorm.
 * User: zogxray
 * Date: 07.09.18
 * Time: 10:13
 */

namespace Micseres\ServiceHub\Server\Ports;

use Micseres\ServiceHub\App;
use Micseres\ServiceHub\Protocol\Client\Client;
use Micseres\ServiceHub\Protocol\Requests\ClientRequest;
use Micseres\ServiceHub\Protocol\Responses\Response;
use Micseres\ServiceHub\Server\Exchange\RequestQueryItem;
use \Swoole\Server as SServer;

/**
 * Class ClientsPortListener
 * @package Micseres\ServiceHub\BaseServer\Ports
 */
class ClientsPortListener implements PortListenerInterface
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
        $this->app->getLogger()->info("CLIENT SOCKET connect {$fd} to {$reactorId}");
    }

    /**
     * @param SServer $server
     * @param int $fd
     * @param int $reactorId
     * @param string $data
     */
    public function onReceive(SServer $server, int $fd, int $reactorId, string $data)
    {
        $this->app->getLogger()->info("CLIENT SOCKET from {$fd} receive data to {$reactorId}");

        $request = new ClientRequest();

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

            $this->app->getLogger()->info("CLIENT SOCKET send ERROR RESPONSE to {$fd} from {$reactorId}", (array)$errorResponse);
        } else {
            $router = $this->app->getRouter();
            $serviceRoute = $router->getRoute($request->getRoute());

            $service = $serviceRoute->getServer();

            if (null !== $service) {
                $client = new Client($fd, $reactorId);

                $requestQueryItem = new RequestQueryItem($request, $service, $client);
                $query = $this->app->getClientRequestQuery();
                $query->push($requestQueryItem);

                $response = new Response();
                $response->setProtocol("1.0");
                $response->setAction("accepted");
                $response->setRoute($request->getRoute());
                $response->setMessage("Request accepted");
                $response->setPayload([
                    'time' => (new \DateTime('now'))->format('Y-m-d H:i:s.u')
                ]);

                $server->send($fd, json_encode($response->serialize()));

                $this->app->getLogger()->info("CLIENT SOCKET send ACCEPT RESPONSE to {$fd} from {$reactorId}", (array)$response);
            } else {
                $errorResponse = new Response();
                $errorResponse->setProtocol("1.0");
                $errorResponse->setAction("error");
                $errorResponse->setRoute($request->getRoute());
                $errorResponse->setMessage("Service not found request");
                $errorResponse->setPayload([
                    'time' => (new \DateTime('now'))->format('Y-m-d H:i:s.u')
                ]);

                $server->send($fd, json_encode($errorResponse->serialize()));

                $this->app->getLogger()->info("CLIENT SOCKET send ERROR RESPONSE to {$fd} from {$reactorId}", (array)$errorResponse);
            }
        }

    }

    /**
     * @param SServer $server
     * @param int $fd
     * @param int $reactorId
     */
    public function onClose(SServer $server, int $fd, int $reactorId)
    {
        $this->app->getLogger()->info("CLIENT SOCKET close {$fd} connect to {$reactorId}");
    }
}