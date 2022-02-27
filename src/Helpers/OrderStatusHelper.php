<?php

namespace LapayGroup\MetaShipSdk\Helpers;

class OrderStatusHelper
{
    /** @var array */
    public static $status_list = [
        'draft'	=> 'Черновик',
        'error'	=> 'Ошибка создания заказа',
        'dispatched-to-a-courier' => 'Передан курьеру',
        'created' => 'Создан',
        'wait-delivery' => 'Готов к передаче в службу доставки',
        'arrived-warehouse'	=> 'Заказ поступил на склад службы',
        'intransit'	=> 'Доставляется',
        'arrived-to-city' => 'Заказ прибыл в город получателя',
        'stored' => 'В пункте самовывоза',
        'delivered'	=> 'Доставлен',
        'expected-return' => 'Готовится к возврату',
        'return-arrived-warehouse' => 'Возвращeн на склад службы доставки',
        'return-completed' => 'Возвращен в магазин',
        'losted' => 'Утерян',
        'unknown' => 'Статус уточняется',
        'cancelled'	=> 'Отменен',
        'not-accepted-in-delivery-service' => 'Не принят в СД',
        'partially-delivered' => 'Частично доставлен',
        'pending' => 'Ждет подтверждения службы доставки'
    ];

    /**
     * Возвращает текстовое описание статуса по коду
     *
     * @param int $code - код статуса
     * @throws \InvalidArgumentException
     * @return string - Наименование статуса
     */
    public static function getStatusByCode($code)
    {
        if (!isset(self::$status_list[$code])) {
            throw new \InvalidArgumentException('Передан не существующий код статуса заказа MetaShip: '.$code);
        }

        if (is_array(self::$status_list)) {
            return (string) self::$status_list[$code];
        }

        return '';
    }

    /**
     * Возвращает признак конечный статус или нет
     *
     * @param string $code - код статуса исполнения заказа
     * @return bool
     */
    static public function isFinal($code)
    {
        if (in_array($code, ['cancelled', 'delivered', 'error','return-completed'])) {
            return true;
        } else {
            return false;
        }
    }
}