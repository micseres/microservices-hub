<?php
/**
 * Created by PhpStorm.
 * User: zogxray
 * Date: 06.09.18
 * Time: 13:10
 */

namespace Micseres\ServiceHub\Protocol\MicroServers;


class MicroServer
{
    /**
     * @var string
     */
    private $ip;
    /**
     * @var int
     */
    private $port;
    /**
     * @var int
     */
    private $load;
    /**
     * @var \DateTime
     */
    private $time;

    /**
     * MicroServer constructor.
     * @param string $ip
     * @param int $port
     * @param int $load
     * @param \DateTime $time
     */
    public function __construct(string $ip, int $port, int $load, \DateTime $time)
    {
        $this->ip = $ip;
        $this->port = $port;
        $this->load = $load;
        $this->time = $time;
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
     * @return \DateTime
     */
    public function getTime(): \DateTime
    {
        return $this->time;
    }
}