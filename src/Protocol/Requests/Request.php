<?php
/**
 * Created by PhpStorm.
 * User: zogxray
 * Date: 03.09.18
 * Time: 18:19
 */

namespace Micseres\ServiceHub\Protocol\Requests;

/**
 * Class Request
 * @package Micseres\ServiceHub\Protocol\Requests
 */
class Request
{
    private $protocol;

    private $action;

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
     * @return null|Request
     */
    public function deserialize(string $json) :?self
    {
        $data = json_decode( preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $json), true );

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
}