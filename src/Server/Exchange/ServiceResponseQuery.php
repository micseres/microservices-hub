<?php
/**
 * Created by PhpStorm.
 * User: zogxray
 * Date: 10.09.18
 * Time: 14:48
 */

namespace Micseres\ServiceHub\Server\Exchange;


class ServiceResponseQuery
{
    /**
     * @var RequestQueryItem[]
     */
    protected $items = [];

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