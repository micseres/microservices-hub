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
use Micseres\ServiceHub\Protocol\Requests\ClientRequest;
use Micseres\ServiceHub\Protocol\Requests\RequestInterface;
use ReflectionClass;

/**
 * Class RequestQueryItem
 * @package Micseres\ServiceHub\Server\Exchange
 */
class RequestQueryItem
{
    /** @var string */
    private $id;

    /** @var ClientRequest */
    private $request;

    /** @var MicroServer */
    private $server;

    /** @var Client */
    private $client;

    /**
     * RequestQueryItem constructor.
     * @param ClientRequest $request
     * @param MicroServer $server
     * @param Client $client
     */
    public function __construct(ClientRequest $request, MicroServer $server, Client $client)
    {
        $this->id = uniqid('', true);
        $this->request = $request;
        $this->server = $server;
        $this->client = $client;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return ClientRequest
     */
    public function getRequest(): ClientRequest
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