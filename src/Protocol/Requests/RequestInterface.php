<?php
/**
 * Created by PhpStorm.
 * User: zogxray
 * Date: 06.09.18
 * Time: 10:54
 */

namespace Micseres\ServiceHub\Protocol\Requests;

/**
 * Interface RequestInterface
 * @package Micseres\ServiceHub\Protocol\Requests
 */
interface RequestInterface
{
    /**
     * @param string $json
     * @return RequestInterface|null
     */
    public function deserialize(string $json) :?RequestInterface;

    /**
     * @return array
     */
    public function serialize(): array;
}