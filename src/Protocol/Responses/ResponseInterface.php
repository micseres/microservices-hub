<?php
/**
 * Created by PhpStorm.
 * User: zogxray
 * Date: 06.09.18
 * Time: 10:54
 */

namespace Micseres\ServiceHub\Protocol\Responses;

/**
 * Interface ResponseInterface
 * @package Micseres\ServiceHub\Protocol\Responses
 */
interface ResponseInterface
{
    /**
     * @param null $object
     * @return array
     */
    public function serialize($object = null) :array;
}