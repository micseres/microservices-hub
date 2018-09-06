<?php
/**
 * Created by PhpStorm.
 * User: zogxray
 * Date: 03.09.18
 * Time: 18:17
 */

namespace Micseres\ServiceHub\Protocol\Responses;

use ReflectionClass;

/**
 * Class Response
 * @package Micseres\ServiceHub\Protocol\Responses
 */
class Response
{
    private $protocol;

    private $action;

    private $route;

    private $message;

    private $payload = [];

    /**
     * @return mixed
     */
    public function getProtocol()
    {
        return $this->protocol;
    }

    /**
     * @param mixed $protocol
     */
    public function setProtocol($protocol): void
    {
        $this->protocol = $protocol;
    }

    /**
     * @return mixed
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * @param mixed $action
     */
    public function setAction($action): void
    {
        $this->action = $action;
    }

    /**
     * @return mixed
     */
    public function getRoute()
    {
        return $this->route;
    }

    /**
     * @param mixed $route
     */
    public function setRoute($route): void
    {
        $this->route = $route;
    }

    /**
     * @return mixed
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param mixed $message
     */
    public function setMessage($message): void
    {
        $this->message = $message;
    }

    /**
     * @return array
     */
    public function getPayload(): array
    {
        return $this->payload;
    }

    /**
     * @param array $payload
     */
    public function setPayload(array $payload): void
    {
        $this->payload = $payload;
    }

    public function serialize()
    {
        $json = [];

        try {
            $class = new ReflectionClass($this);
        } catch (\ReflectionException $e) {
        }

        foreach ($class->getProperties() as $key => $value) {
            $value->setAccessible(true);
            $json[$value->getName()] = $value->getValue($this);
        }

        return $json;
    }
}