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
     * @var int
     */
    private $fd;

    /**
     * MicroServer constructor.
     * @param int $fd
     * @param string $ip
     * @param int $port
     * @param int $load
     * @param \DateTime $time
     */
    public function __construct(int $fd, string $ip, int $port, int $load, \DateTime $time)
    {
        $this->fd = $fd;
        $this->ip = $ip;
        $this->port = $port;
        $this->load = $load;
        $this->time = $time;
    }

    /**
     * @return int
     */
    public function getFd(): int
    {
        return $this->fd;
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

    /**
     * @param \DateTime $time
     */
    public function setTime(\DateTime $time): void
    {
        $this->time = $time;
    }
}