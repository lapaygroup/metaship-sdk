<?php
namespace LapayGroup\MetaShipSdk\Entity;

class Warehouse
{
    /** @var string|null  */
    private $name = null; // Наименование склада (напр. Romashka-1)

    /** @var string|null  */
    private $address = null; // Адрес склада

    /**
     * Формирует массив параметров для запроса к API
     *
     * @return array
     */
    public function asArr()
    {
        $params = [];
        $params['name'] = $this->name;
        $params['address']['raw'] = $this->address;
        return $params;
    }

    /**
     * @return string|null
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string|null
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param string|null $address
     */
    public function setAddress($address)
    {
        $this->address = $address;
    }
}