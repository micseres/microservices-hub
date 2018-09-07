<?php
/**
 * Created by PhpStorm.
 * User: zogxray
 * Date: 03.09.18
 * Time: 16:45
 */

namespace Micseres\ServiceHub;
use Micseres\ServiceHub\Protocol\Router;
use Micseres\ServiceHub\Server\Exchange\ClientRequestQuery;
use Micseres\ServiceHub\Service\Configuration;
use Monolog\Logger;


/**
 * Class App
 * @package Micseres\ServiceHub
 */
class App
{
    /**
     * @var Configuration
     */
    private $configuration;
    /**
     * @var Logger
     */
    private $logger;
    /**
     * @var Router
     */
    private $router;
    /**
     * @var ClientRequestQuery
     */
    private $clientRequestQuery;

    /**
     * App constructor.
     * @param Configuration $configuration
     * @param Logger $logger
     * @param Router $router
     * @param ClientRequestQuery $clientRequestQuery
     */
    public function __construct(Configuration $configuration, Logger $logger, Router $router, ClientRequestQuery $clientRequestQuery)
    {
        $this->configuration = $configuration;
        $this->logger = $logger;
        $this->router = $router;
        $this->clientRequestQuery = $clientRequestQuery;
    }

    /**
     * @return Configuration
     */
    public function getConfiguration(): Configuration
    {
        return $this->configuration;
    }

    /**
     * @return Logger
     */
    public function getLogger(): Logger
    {
        return $this->logger;
    }

    /**
     * @return Router
     */
    public function getRouter(): Router
    {
        return $this->router;
    }

    /**
     * @return ClientRequestQuery
     */
    public function getClientRequestQuery(): ClientRequestQuery
    {
        return $this->clientRequestQuery;
    }
}