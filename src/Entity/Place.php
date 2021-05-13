<?php
namespace LapayGroup\MetaShipSdk\Entity;

class Place
{
    /** @var array  */
    private $items = []; // Вложения

    /**
     * Формирует массив параметров для запроса
     *
     * @return array
     * @throws \InvalidArgumentException
     */
    public function asArr()
    {
        $params = [];

        /** @var Item $item */
        foreach ($this->items as $key => $item) {
            $params['items'][] = $item->asArr();
        }

        return $params;
    }

    /**
     * @return array
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @param Item $item
     */
    public function setItem($item)
    {
        $this->items[] = $item;
    }
}