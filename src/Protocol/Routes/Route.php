<?php
/**
 * Created by PhpStorm.
 * User: zogxray
 * Date: 03.09.18
 * Time: 18:12
 */

namespace Micseres\ServiceHub\Protocol\Routes;


/**
 * Class Route
 * @package Micseres\ServiceHub\Protocol\Routes
 */
class Route implements RouteInterface
{
    private $ip;
    private $port;
    private $load;

    public function __construct(string $ip, int $port, int $load)
    {
        $this->ip = $ip;
        $this->port = $port;
        $this->load = $load;
    }

    /**
     * @return string
     */
    public function getIp(): string
    {
        return $this->ip;
    }

    /**
     * @return int
     */
    public function getPort(): int
    {
        return $this->port;
    }

    /**
     * @return int
     */
    public function getLoad(): int
    {
        return $this->load;
    }

    /**
     * @param int $load
     */
    public function setLoad(int $load): void
    {
        $this->load = $load;
    }

}