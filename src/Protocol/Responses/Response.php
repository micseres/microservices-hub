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
class Response implements ResponseInterface
{

    /** @var string */
    private $protocol;

    /** @var string */
    private $action;

    /** @var string */
    private $route;

    /** @var string */
    private $message;

    /** @var array  */
    private $payload = [];

    /**
     * @return string
     */
    public function getProtocol(): string
    {
        return $this->protocol;
    }

    /**
     * @param string $protocol
     */
    public function setProtocol(string $protocol): void
    {
        $this->protocol = $protocol;
    }

    /**
     * @return string
     */
    public function getAction(): string
    {
        return $this->action;
    }

    /**
     * @param string $action
     */
    public function setAction(string $action): void
    {
        $this->action = $action;
    }

    /**
     * @return string
     */
    public function getRoute(): string
    {
        return $this->route;
    }

    /**
     * @param string $route
     */
    public function setRoute(string $route): void
    {
        $this->route = $route;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @param string $message
     */
    public function setMessage(string $message): void
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

    /**
     * @param $object
     * @return array
     * @throws \ReflectionException
     */
    public function serialize($object = null) :array
    {
        if (null === $object) {
            $object = $this;
        }

        $reflectionClass = new ReflectionClass($object);

        $properties = $reflectionClass->getProperties();

        $array = [];
        foreach ($properties as $property) {
            $property->setAccessible(true);
            $value = $property->getValue($object);
            if (is_object($value)) {
                $array[$property->getName()] = $this->serialize($value);
            } else {
                $array[$property->getName()] = $value;
            }
        }

        return $array;
    }
}