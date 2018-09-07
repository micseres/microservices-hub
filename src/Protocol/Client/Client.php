<?php
/**
 * Created by PhpStorm.
 * User: zogxray
 * Date: 07.09.18
 * Time: 17:47
 */

namespace Micseres\ServiceHub\Protocol\Client;


/**
 * Class Client
 * @package Micseres\ServiceHub\Protocol\Client
 */
class Client implements ClientInterface
{
    /**
     * @var integer
     */
    private $fd;

    /**
     * @var integer
     */
    private $reactorId;

    /**
     * Client constructor.
     * @param int $fd
     * @param int $reactorId
     */
    public function __construct(int $fd, int $reactorId)
    {
        $this->fd = $fd;
        $this->reactorId = $reactorId;
    }

    /**
     * @return int
     */
    public function getFd(): int
    {
        return $this->fd;
    }

    /**
     * @param int $fd
     */
    public function setFd(int $fd): void
    {
        $this->fd = $fd;
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
}