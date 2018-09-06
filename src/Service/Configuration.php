<?php
/**
 * Created by PhpStorm.
 * User: zogxray
 * Date: 03.09.18
 * Time: 17:03
 */

namespace Micseres\ServiceHub\Service;


use Dotenv\Dotenv;

/**
 * Class Configuration
 * @package Micseres\ServiceHub\Service
 */
final class Configuration
{
    /**
     * @var array
     */
    private $parameters = [];

    /**
     * Configuration constructor.
     * @param Dotenv $dotenv
     */
    public function __construct(Dotenv $dotenv)
    {
        $parameters = [];

        foreach ($dotenv->getEnvironmentVariableNames() as $name) {
            $parameters[$name] = getenv($name);
        }

        $this->parameters = $parameters;
    }

    /**
     * @param string $name
     * @return string
     * @throws ConfigurationException
     */
    public function getParameter(string $name): string
    {
        return $this->parameters[$name];
    }
}