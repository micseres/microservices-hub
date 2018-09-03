<?php
/**
 * Created by PhpStorm.
 * User: zogxray
 * Date: 03.09.18
 * Time: 18:09
 */

namespace Micseres\ServiceHub\Protocol\Routes;

/**
 * Interface RouteInterface
 * @package Micseres\ServiceHub\Protocol\Routes
 */
interface RouteInterface
{
    public function getIp(): string;

    public function getPort(): int;

    public function getLoad(): int;

    /**
     * @param int $load
     */
    public function setLoad(int $load): void;

}