<?php
/**
 * Created by PhpStorm.
 * User: zogxray
 * Date: 03.09.18
 * Time: 18:19
 */

namespace Micseres\ServiceHub\Protocol\Requests;

use ReflectionClass;

/**
 * Class Request
 * @package Micseres\ServiceHub\Protocol\Requests
 */
abstract class Request implements RequestInterface
{
    /** @var string */
    private $action;

    /** @var string */
    private $route;

    /** @var string */
    private $message;

    /** @var array  */
    private $payload = [];

    public function __construct(string $json)
    {
        $this->deserialize($json);
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

    /**
     * @param string $json
     * @return null|RequestInterface
     */
    public function deserialize(string $json) :?RequestInterface
    {
        $data = json_decode(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $json), true );

        if (null === $data) {
            return null;
        }

        foreach ($data as $key => $value) {
            if (method_exists($this, $method = 'set'.ucfirst($key))) {
                $this->{$method}($value);
            }
        }

        return $this;
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