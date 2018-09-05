<?php
/**
 * Created by PhpStorm.
 * User: zogxray
 * Date: 03.09.18
 * Time: 16:45
 */

namespace Micseres\ServiceHub;
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
     * App constructor.
     * @param Configuration $configuration
     * @param Logger $logger
     */
    public function __construct(Configuration $configuration, Logger $logger)
    {
        $this->configuration = $configuration;
        $this->logger = $logger;
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
}