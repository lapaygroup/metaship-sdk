<?php
namespace LapayGroup\MetaShipSdk\Entity;

class Order
{
    /** @var string|null */
    private $warehouse_id = null; // UUID склада забора

    /** @var string|null */
    private $shop_id = null; // UUID магазина

    /** @var string|null */
    private $number = null; // Номер заказа

    /** @var string|null */
    private $payment_type = null; // Тип оплаты

    /** @var float|null */
    private $declared_value = null; // Объявленная стоимость

    /** @var float|null */
    private $delivery_sum = null; // Стоимость доставки

    /** @var string|null */
    private $attribute = null; // Способ оплаты

    /** @var integer|null */
    private $length  = null; // Длина, см

    /** @var integer|null */
    private $width  = null; // Ширина, см

    /** @var integer|null */
    private $height  = null; // Высота, см

    /** @var float|null */
    private $weight  = null; // Вес, кг

    /** @var string|null */
    private $delivery_type = null; // Тип доставки

    /** @var string|null */
    private $delivery_service_code = null; // Код службы доставки

    /** @var string|null */
    private $tariff_code = null; // Код тарифа в СД

    /** @var string|null  */
    private $pvz_code = null; // Код ПВЗ

    /** @var \DateTime|null  */
    private $planned_receive_date = null; // Желаемая дата доставки

    /** @var string|null  */
    private $planned_receive_time_from = null; // Время доставки, с

    /** @var string|null  */
    private $planned_receive_time_to = null; // Время доставки, до

    /** @var string|null  */
    private $lastname = null; // Фамилия получателя заказа

    /** @var string|null  */
    private $firstname = null; // Имя получателя заказа

    /** @var string|null  */
    private $secondtname = null; // Отчество получателя заказа

    /** @var string|null  */
    private $phone = null; // Телефон получателя в формате +79012345678

    /** @var string|null  */
    private $email = null; // E-mail получателя заказа

    /** @var string|null  */
    private $address = null; // Адрес доставки

    /** @var string|null  */
    private $comment = null; // Комментарий к заказу

    /** @var string[]|null  */
    private $services = null; // Дополнительные услуги

    /** @var string|null  */
    private $pickupTimePeriod = null; // Интервал привоза на склад DPD

    /** @var string|null  */
    private $datePickup = null; // Дата привоза на склад DPD

    /** @var array|null  */
    private $places = null; // Места в заказе

    /**
     * Формирует массив параметров для запроса
     *
     * @return array
     * @throws \InvalidArgumentException
     */
    public function asArr()
    {
        $required_fields = [
            'warehouse_id',
            'shop_id',
            'number',
            'payment_type',
            'declared_value',
            'delivery_sum',
            'length',
            'width',
            'height',
            'weight',
            'delivery_type',
            'delivery_service_code',
            'lastname',
            'firstname',
            'phone',
            'places'
        ];

        foreach ($required_fields as $property) {
            if (is_null($this->$property))
                throw new \InvalidArgumentException('В заказе не заполнено обязательное поле '.$property);
        }

        $params = [];
        $params['warehouse']['id'] = $this->warehouse_id;
        $params['shop']['id'] = $this->shop_id;
        $params['shop']['number'] = $this->number;
        $params['payment'] = [];
        $params['dimension'] = [];
        $params['weight'] = $this->weight;
        $params['delivery']['type'] = $this->delivery_type;
        $params['delivery']['service'] = $this->delivery_service_code;
        $params['recipient'] = [];
        $params['places'] = [];

        if (!empty($this->payment_type))
            $params['payment']['type'] = $this->payment_type;

        if (!is_null($this->declared_value))
            $params['payment']['declaredValue'] = $this->declared_value;

        if (!is_null($this->delivery_sum))
            $params['payment']['deliverySum'] = $this->delivery_sum;

        if (!is_null($this->attribute))
            $params['payment']['attribute'] = $this->attribute;

        if (!empty($this->length) && !empty($this->width) && !empty($this->height)) {
            $params['dimension']['length'] = $this->length;
            $params['dimension']['width'] = $this->width;
            $params['dimension']['height'] = $this->height;
        }

        if (!empty($this->tariff_code))
            $params['delivery']['tariff'] = $this->tariff_code;

        if (!empty($this->pvz_code))
            $params['delivery']['deliveryPointCode'] = $this->pvz_code;

        if (!empty($this->planned_receive_date)) {
            $params['delivery']['date'] = $this->planned_receive_date->format('Y-m-d');
        }

        if (!empty($this->planned_receive_time_from) && !empty($this->planned_receive_time_to)) {
            $params['delivery']['time']['from'] = $this->planned_receive_time_from;
            $params['delivery']['time']['to'] = $this->planned_receive_time_to;
        }

        if (!empty($this->lastname))
            $params['recipient']['familyName'] = $this->lastname;

        if (!empty($this->firstname))
            $params['recipient']['firstName'] = $this->firstname;

        if (!empty($this->secondtname))
            $params['recipient']['secondName'] = $this->secondtname;

        if (!empty($this->phone))
            $params['recipient']['phoneNumber'] = $this->phone;

        if (!empty($this->email))
            $params['recipient']['email'] = $this->email;

        if (!empty($this->address))
            $params['recipient']['address']['raw'] = $this->address;

        if (!empty($this->comment))
            $params['comment'] = $this->lastname;

        if (!empty($this->services))
            $params['services'] = $this->services;

        if (!empty($this->pickupTimePeriod))
            $params['pickupTimePeriod'] = $this->pickupTimePeriod;

        if (!empty($this->datePickup))
            $params['datePickup'] = $this->datePickup;

        /** @var Place $place */
        foreach ($this->places as $place) {
            $params['places'][] = $place->asArr();
        }

        return $params;
    }

    /**
     * @return string|null
     */
    public function getWarehouseId()
    {
        return $this->warehouse_id;
    }

    /**
     * @param string|null $warehouse_id
     */
    public function setWarehouseId($warehouse_id)
    {
        $this->warehouse_id = $warehouse_id;
    }

    /**
     * @return string|null
     */
    public function getShopId()
    {
        return $this->shop_id;
    }

    /**
     * @param string|null $shop_id
     */
    public function setShopId($shop_id)
    {
        $this->shop_id = $shop_id;
    }

    /**
     * @return string|null
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * @param string|null $number
     */
    public function setNumber($number)
    {
        $this->number = $number;
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
     * @return float|null
     */
    public function getDeclaredValue()
    {
        return $this->declared_value;
    }

    /**
     * @param float|null $declared_value
     */
    public function setDeclaredValue($declared_value)
    {
        $this->declared_value = $declared_value;
    }

    /**
     * @return float|null
     */
    public function getDeliverySum()
    {
        return $this->delivery_sum;
    }

    /**
     * @param float|null $delivery_sum
     */
    public function setDeliverySum($delivery_sum)
    {
        $this->delivery_sum = $delivery_sum;
    }

    /**
     * @return string|null
     */
    public function getAttribute()
    {
        return $this->attribute;
    }

    /**
     * @param string|null $attribute
     */
    public function setAttribute($attribute)
    {
        $this->attribute = $attribute;
    }

    /**
     * @return int|null
     */
    public function getLength()
    {
        return $this->length;
    }

    /**
     * @param int|null $length
     */
    public function setLength($length)
    {
        $this->length = $length;
    }

    /**
     * @return int|null
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * @param int|null $width
     */
    public function setWidth($width)
    {
        $this->width = $width;
    }

    /**
     * @return int|null
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * @param int|null $height
     */
    public function setHeight($height)
    {
        $this->height = $height;
    }

    /**
     * @return float|null
     */
    public function getWeight()
    {
        return $this->weight;
    }

    /**
     * @param float|null $weight
     */
    public function setWeight($weight)
    {
        $this->weight = $weight;
    }

    /**
     * @return string|null
     */
    public function getDeliveryType()
    {
        return $this->delivery_type;
    }

    /**
     * @param string|null $delivery_type
     */
    public function setDeliveryType($delivery_type)
    {
        $this->delivery_type = $delivery_type;
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

    /**
     * @return string|null
     */
    public function getTariffCode()
    {
        return $this->tariff_code;
    }

    /**
     * @param string|null $tariff_code
     */
    public function setTariffCode($tariff_code)
    {
        $this->tariff_code = $tariff_code;
    }

    /**
     * @return string|null
     */
    public function getPvzCode()
    {
        return $this->pvz_code;
    }

    /**
     * @param string|null $pvz_code
     */
    public function setPvzCode($pvz_code)
    {
        $this->pvz_code = $pvz_code;
    }

    /**
     * @return \DateTime|null
     */
    public function getPlannedReceiveDate()
    {
        return $this->planned_receive_date;
    }

    /**
     * @param \DateTime|null $planned_receive_date
     */
    public function setPlannedReceiveDate($planned_receive_date)
    {
        $this->planned_receive_date = $planned_receive_date;
    }

    /**
     * @return string|null
     */
    public function getPlannedReceiveTimeFrom()
    {
        return $this->planned_receive_time_from;
    }

    /**
     * @param string|null $planned_receive_time_from
     */
    public function setPlannedReceiveTimeFrom($planned_receive_time_from)
    {
        $this->planned_receive_time_from = $planned_receive_time_from;
    }

    /**
     * @return string|null
     */
    public function getPlannedReceiveTimeTo()
    {
        return $this->planned_receive_time_to;
    }

    /**
     * @param string|null $planned_receive_time_to
     */
    public function setPlannedReceiveTimeTo($planned_receive_time_to)
    {
        $this->planned_receive_time_to = $planned_receive_time_to;
    }

    /**
     * @return string|null
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * @param string|null $lastname
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;
    }

    /**
     * @return string|null
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * @param string|null $firstname
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;
    }

    /**
     * @return string|null
     */
    public function getSecondtname()
    {
        return $this->secondtname;
    }

    /**
     * @param string|null $secondtname
     */
    public function setSecondtname($secondtname)
    {
        $this->secondtname = $secondtname;
    }

    /**
     * @return string|null
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param string|null $phone
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    }

    /**
     * @return string|null
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string|null $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
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

    /**
     * @return string|null
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * @param string|null $comment
     */
    public function setComment($comment)
    {
        $this->comment = $comment;
    }

    /**
     * @return string[]|null
     */
    public function getServices()
    {
        return $this->services;
    }

    /**
     * @param string[]|null $services
     */
    public function setServices($services)
    {
        $this->services = $services;
    }

    /**
     * @return string|null
     */
    public function getPickupTimePeriod()
    {
        return $this->pickupTimePeriod;
    }

    /**
     * @param string|null $pickupTimePeriod
     */
    public function setPickupTimePeriod($pickupTimePeriod)
    {
        $this->pickupTimePeriod = $pickupTimePeriod;
    }

    /**
     * @return string|null
     */
    public function getDatePickup()
    {
        return $this->datePickup;
    }

    /**
     * @param string|null $datePickup
     */
    public function setDatePickup($datePickup)
    {
        $this->datePickup = $datePickup;
    }

    /**
     * @return array
     */
    public function getPlaces()
    {
        return $this->places;
    }

    /**
     * @param Place $place
     */
    public function setPlace($place)
    {
        $this->places[] = $place;
    }
}
