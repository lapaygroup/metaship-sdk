<?php
namespace LapayGroup\MetaShipSdk\Enum;

class PaymentType
{
    const PAID            = 'Paid'; // Полная предоплата (значение по умолчанию)
    const PAY_ON_DELIVERY = 'PayOnDelivery'; // Оплата при получении (наложенный платёж)
    const CASH            = 'cash'; // Оплата наличными
    const CARD            = 'card'; // Оплата картой
}