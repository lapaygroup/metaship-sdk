<?php

namespace LapayGroup\MetaShipSdk\Enum;

class OrderStatus
{
    /**
     * Возвращает признак конечный статус или нет
     *
     * @param string $code - код статуса исполнения заказа
     * @return bool
     */
    static public function isFinal($code)
    {
        if (in_array($code, [])) { // TODO добавить конечные статусы
            return true;
        } else {
            return false;
        }
    }
}