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
class RequestQuery
{
    /**
     * @var RequestQueryItem[]
     */
    protected $items = [];

    /**
     * @return RequestQueryItem|null
     */
    public function shift(): ?RequestQueryItem
    {
        if (!count($this->items)) {
            return null;
        }

        return array_shift($this->items);
    }

    /**
     * @param string $id
     * @return RequestQueryItem|null
     */
    public function pick(string $id): ?RequestQueryItem
    {
        if (!isset($this->items[$id])) {
            return null;
        }

        $item = $this->items[$id];

        unset($this->items[$id]);

        return $item;
    }

    /**
     * @param RequestQueryItem $item
     */
    public function push(RequestQueryItem $item): void
    {
        $this->items[$item->getId()] = $item;
    }
}