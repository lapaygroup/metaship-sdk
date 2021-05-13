<?php
namespace LapayGroup\MetaShipSdk\Enum;

class Services
{
    const NO_RETURN = 'no-return'; // Возврату не подлежит
    const NOT_OPEN = 'not-open'; // Не вскрывать до получения оплаты с клиента
    const PARTIAL_SALE = 'partial-sale'; // Частичная реализация
    const OPEN = 'open'; // Можно вскрывать до получения оплаты с клиента
    const DRESS_FITTING = 'dress-fitting'; // Имеется возможность примерки
    const SMS = 'sms'; // SMS информирование
    const OPEN_TEST = 'open-test'; // Можно вскрывать до получения оплаты с клиента для проверки работоспособности
    const WEEKEND_PICKUP = 'weekend-pickup'; // Приём в выходные дни
    const WEEKEND_DELIVERY = 'weekend-delivery'; // Доставка в выходные дни
}