<?php
namespace LapayGroup\MetaShipSdk\Entity;

class OfferParams
{
    /** @var string */
    private $shop_id; // Магазин, для которого необходимо получить офферы

    /** @var string */
    private $warehouse_id; // Идентификатор склада забора

    /** @var string */
    private $address; // Адрес получателя

    /** @var float */
    private $declared_value = 0; // Объявленная стоимость, руб.

    /** @var integer */
    private $length; // Длина, см

    /** @var integer */
    private $width; // Ширина, см

    /** @var integer */
    private $height; // Высота, см

    /** @var float */
    private $weight; // Вес, кг

    /** @var string[] */
    private $types; // Тип доставки

    /** @var string|null */
    private $payment_type = null; // Тип оплаты

    /** @var string|null */
    private $delivery_point_number = null; // Идентификатор точки доставки (Должен быть указан совместно с deliveryServiceCode)

    /** @var string|null */
    private $delivery_service_code = null; // Код СД (Должен быть указан совместно с deliveryPointNumber)

    /**
     * Формирует массив параметров для запроса к API
     *
     * @return array
     */
    public function asArr()
    {
        $params = [];
        $params['shopId'] = $this->shop_id;
        $params['warehouseId'] = $this->warehouse_id;
        $params['address'] = $this->address;
        $params['declaredValue'] = round($this->declared_value, 2);
        $params['length'] = intval($this->length);
        $params['width'] = intval($this->width);
        $params['height'] = intval($this->height);
        $params['weight'] = round($this->weight, 3);
        $params['types'] = $this->types;

        foreach ($params as $param => $val) {
            if ($val != 0 && empty($val))
                throw new \InvalidArgumentException('В offerParams не заполнено обязательное поле '.$param);
        }

        if (!empty($this->payment_type))
            $params['paymentType'] = $this->payment_type;

        if (!empty($this->delivery_point_number))
            $params['deliveryPointNumber'] = $this->delivery_point_number;

        if (!empty($this->delivery_service_code))
            $params['deliveryServiceCode'] = $this->delivery_service_code;


        return $params;
    }

    /**
     * @return string
     */
    public function getShopId()
    {
        return $this->shop_id;
    }

    /**
     * @param string $shop_id
     */
    public function setShopId($shop_id)
    {
        $this->shop_id = $shop_id;
    }

    /**
     * @return string
     */
    public function getWarehouseId()
    {
        return $this->warehouse_id;
    }

    /**
     * @param string $warehouse_id
     */
    public function setWarehouseId($warehouse_id)
    {
        $this->warehouse_id = $warehouse_id;
    }

    /**
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param string $address
     */
    public function setAddress($address)
    {
        $this->address = $address;
    }

    /**
     * @return float
     */
    public function getDeclaredValue()
    {
        return $this->declared_value;
    }

    /**
     * @param float $declared_value
     */
    public function setDeclaredValue($declared_value)
    {
        $this->declared_value = $declared_value;
    }

    /**
     * @return int
     */
    public function getLength()
    {
        return $this->length;
    }

    /**
     * @param int $length
     */
    public function setLength($length)
    {
        $this->length = $length;
    }

    /**
     * @return int
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * @param int $width
     */
    public function setWidth($width)
    {
        $this->width = $width;
    }

    /**
     * @return int
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * @param int $height
     */
    public function setHeight($height)
    {
        $this->height = $height;
    }

    /**
     * @return float
     */
    public function getWeight()
    {
        return $this->weight;
    }

    /**
     * @param float $weight
     */
    public function setWeight($weight)
    {
        $this->weight = $weight;
    }

    /**
     * @return string[]
     */
    public function getTypes()
    {
        return $this->types;
    }

    /**
     * @param string[] $types
     */
    public function setTypes($types)
    {
        $this->types = $types;
    }

    /**
     * @return string|null
     */
    public function getPaymentType()
    {
        return $this->payment_type;
    }

    /**
     * @param string|null $payment_type
     */
    public function setPaymentType($payment_type)
    {
        $this->payment_type = $payment_type;
    }

    /**
     * @return string|null
     */
    public function getDeliveryPointNumber()
    {
        return $this->delivery_point_number;
    }

    /**
     * @param string|null $delivery_point_number
     */
    public function setDeliveryPointNumber($delivery_point_number)
    {
        $this->delivery_point_number = $delivery_point_number;
    }

    /**
     * @return string|null
     */
    public function getDeliveryServiceCode()
    {
        return $this->delivery_service_code;
    }

    /**
     * @param string|null $delivery_service_code
     */
    public function setDeliveryServiceCode($delivery_service_code)
    {
        $this->delivery_service_code = $delivery_service_code;
    }
}