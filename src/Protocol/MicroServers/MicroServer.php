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
     * @var int
     */
    private $reactorId;

    /**
     * MicroServer constructor.
     * @param int $fd
     * @param int $reactorId
     * @param string $ip
     * @param int $port
     * @param int $load
     * @param \DateTime $time
     */
    public function __construct(int $fd, int $reactorId, string $ip, int $port, int $load, \DateTime $time)
    {
        $this->fd = $fd;
        $this->reactorId = $reactorId;
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
     * @return int
     */
    public function getReactorId(): int
    {
        return $this->reactorId;
    }

    /**
     * @param int $reactorId
     */
    public function setReactorId(int $reactorId): void
    {
        $this->reactorId = $reactorId;
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