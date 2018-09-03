<?php
/**
 * Created by PhpStorm.
 * User: zogxray
 * Date: 03.09.18
 * Time: 16:45
 */

namespace Micseres\ServiceHub;
use Micseres\ServiceHub\Service\Configuration;


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
     * App constructor.
     * @param Configuration $configuration
     */
    public function __construct(Configuration $configuration)
    {
        $this->configuration = $configuration;
    }
}