<?php
/**
 * Created by PhpStorm.
 * User: zogxray
 * Date: 07.09.18
 * Time: 16:05
 */

namespace Micseres\ServiceHub\Server\Exchange;

/**
 * Class RequestStack
 * @package Micseres\ServiceHub\Server\Exchange
 */
class ClientRequestQuery
{
    /**
     * @var RequestQueryItem[]
     */
    protected $items = [];

    /**
     * @return RequestQueryItem|null
     */
    public function get(): ?RequestQueryItem
    {
        if (!isset($this->items[0])) {
            return null;
        }

        return $this->items[0];
    }

    /**
     * @param RequestQueryItem $item
     */
    public function push(RequestQueryItem $item): void
    {
        $this->items[] = $item;
    }
}