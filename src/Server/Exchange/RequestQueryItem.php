<?php
/**
 * Created by PhpStorm.
 * User: zogxray
 * Date: 07.09.18
 * Time: 16:15
 */

namespace Micseres\ServiceHub\Server\Exchange;

use Micseres\ServiceHub\Protocol\Client\Client;
use Micseres\ServiceHub\Protocol\MicroServers\MicroServer;
use Micseres\ServiceHub\Protocol\Requests\RequestInterface;

/**
 * Class RequestQueryItem
 * @package Micseres\ServiceHub\Server\Exchange
 */
class RequestQueryItem
{
    /** @var RequestInterface */
    private $request;

    /** @var MicroServer */
    private $server;

    /** @var Client */
    private $client;

    /**
     * RequestQueryItem constructor.
     * @param RequestInterface $request
     * @param MicroServer $server
     * @param Client $client
     */
    public function __construct(RequestInterface $request, MicroServer $server, Client $client)
    {
        $this->request = $request;
        $this->server = $server;
        $this->client = $client;
    }

    /**
     * @return RequestInterface
     */
    public function getRequest(): RequestInterface
    {
        return $this->request;
    }

    /**
     * @return MicroServer
     */
    public function getServer(): MicroServer
    {
        return $this->server;
    }

    /**
     * @return Client
     */
    public function getClient(): Client
    {
        return $this->client;
    }
}