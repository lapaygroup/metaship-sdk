<?php

namespace LapayGroup\MetaShipSdk\Helpers;

class OrderStatusHelper
{
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