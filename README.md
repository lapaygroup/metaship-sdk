
<a href="https://lapay.group/"><img align="left" width="200" src="https://lapay.group/img/lapaygroup.svg"></a>
<a href="https://metaship.ru/"><img align="right" width="200" src="https://lapay.group/metashiplogo.svg"></a>    

<br /><br /><br />

[![Latest Stable Version](https://poser.pugx.org/lapaygroup/metaship-sdk/v/stable)](https://packagist.org/packages/lapaygroup/metaship-sdk)
[![Total Downloads](https://poser.pugx.org/lapaygroup/metaship-sdk/downloads)](https://packagist.org/packages/lapaygroup/metaship-sdk)
[![License](https://poser.pugx.org/lapaygroup/metaship-sdk/license)](https://packagist.org/packages/lapaygroup/metaship-sdk)
[![Telegram Chat](https://img.shields.io/badge/telegram-chat-blue.svg?logo=telegram)](https://t.me/phpboxberrysdk)

# SDK для [интеграции с программным комплексом MetaShip API V2](https://metaship.ru).  

Посмотреть все проекты или подарить автору кофе можно [тут](https://lapay.group/opensource).    

[Документация к API V2](https://api.metaship.ru/doc/v2) MetaShip.    

# Содержание    
- [Changelog](#changelog)    
- [Конфигурация](#configuration)  
- [Отладка](#debugging)  
- [Расчет офферов (тарифы)](#tariffs)  
- [Список точек выдачи](#pvz-list)  
- [Склады](#warehouses)  
  - [x] [Создание склада](#warehouse-create)   
  - [x] [Список складов](#warehouse-list)   
  - [ ] [Информация о складе](#warehouse-info)   
  - [ ] [Обновление склада](#warehouse-update)   
  - [ ] [Удаление склада](#warehouse-delete)   
- [Магазины](#shops)    
  - [x] [Создание магазина](#shop-create)  
  - [x] [Список магазинов](#shop-list)   
  - [ ] [Информация о магазине](#shop-info)  
  - [ ] [Обновление магазина](#shop-update)  
  - [ ] [Удаление магазина](#shop-delete)  
- [Заказы](#orders)
  - [x] [Создание заказа](#order-create)   
  - [ ] [Список заказов](#orders-list)
  - [ ] [Информация о заказе](#order-info)
  - [ ] [Удаление заказа](#order-delete)   
  - [ ] [История статусов заказа](#order-statuses)
- [Партии](#batch)   
  - [ ] [Создание партии](#batch-create)   
  - [ ] [Список партий](#batch-list)   
  - [ ] [Информация о партии](#batch-info)   
  - [ ] [Обновление партии](#batch-update)   
- [Документы](#docs)   
    - [ ] [Получение этикетки заказа](#docs-label)   
    - [ ] [Получение АПП](#docs-app)   

<a name="links"><h1>Changelog</h1></a>
- 0.2.0 - Первая Alfa-версия SDK.  

# Установка  
Для установки можно использовать менеджер пакетов Composer

    composer require lapaygroup/metaship-sdk
    

<a name="configuration"><h1>Конфигурация</h1></a>  

Для работы с API необходимо получить токен и секретный ключ. Найти их можно в личном кабинете в разделе "Интеграция".    
С этими данными необходимо получить токен доступа в формате JWT и сохранить его. Токен живет 1 час с момента издания.     

SDK позволяет сохранять JWT, для этого необходимо использовать Helper, который должен реализовывать [JwtSaveInterface](https://github.com/lapaygroup/metaship-sdk/blob/master/src/Helpers/JwtSaveInterface.php).    
В SDK встроен Helper для сохранения токена в временный файл [JwtSaveFileHelper](https://github.com/lapaygroup/metaship-sdk/blob/master/src/Helpers/JwtSaveFileHelper.php).
Если Helper не передан в конструктор клиента, будет использоваться [JwtSaveFileHelper](https://github.com/lapaygroup/metaship-sdk/blob/master/src/Helpers/JwtSaveFileHelper.php).

```php
try {
    // Инициализация API клиента с таймаутом ожидания ответа 60 секунд
    $Client = new \LapayGroup\MetaShipSdk\Client('9e687410-62d5-5139-b712-37e7766922c6', '2091dcf8c89e12a9b8815b9e2d48d212fc9b4082d2e54a0ea4e5da260f5244ba20541d6b2e829133', 60, \LapayGroup\MetaShipSdk\Client::API_URI_TEST);
    $Client->getJwt();
    $jwt = $Client->getJwt(); // $jwt = eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJzdWIiOiJPcGVuQVBJIiwiYXVkIjoiQTEyMjAxOSEiLCJhcGlrZXkiOiJBSlMxU0lTRHJrNmRyMFpYazVsZVQxdFBGZDRvcXNIYSIsImlzcyI6InVybjovL0FwaWdlZSIsInBhcnRuZXJJZCI6ImIyNzNlYzQ0LThiMDAtNDliMS04OWVlLWQ4Njc5NjMwZDk0OCIsImV4cCI6MTU5NzA4OTk1OCwiaWF0IjoxNTk3MDg2MzU4LCJqdGkiOiI4YTIyZmUzNy1mMzc0LTQ0NDctOGMzMC05N2ZiYjJjOGQ3MTkifQ.G_XQ6vdk7bXfIeMJer7z5WUFqnwlp0qUt6RxaCINZt3b97ZUwPMI1-1FNKQhFwmCHJGpTYyBJKHgtY3uJZOWDAszjPMIHrQrcnJLSzJisNiy6z3cMbpf-UgD-RgebuaYyEgZ81rekL5aUN6r5rqWHbxcxEGY22lTy9uEWwxF_-UdVLEW9O9Z9M9IMlL5_7ACVu-ID2n6zFk_QJnEumJcBSqb6JFh2TWvUPnjnUt5AOiD7gNRXKsBvoC6InSfGoMA461cxu-rAazhNq5fkqFSdrIUyz0kvAb3UI4hs_6xJy9tXPpXIQY7LQUZqQGp5BT8pasfhAJ_4CCATbqxIHmY9w
    $result = \LapayGroup\MetaShipSdk\Jwt::decode($jwt); // Получения информации из токена (payload)

    // Ранее полученный токен можно добавить в клиент
    $Client->setJwt($jwt);

    // Токен можно сохранять в файл используя Helper
    $jwtHelper = new \LapayGroup\MetaShipSdk\Helpers\JwtSaveFileHelper();
    // Можно задать путь до временного файла отличный от заданного по умолчанию
    $jwtHelper->setTmpFile('/tmp/saved_jwt.txt');

    $Client = new LapayGroup\MetaShipSdk\Client('9e687410-62d5-5139-b712-37e7766922c6', '2091dcf8c89e12a9b8815b9e2d48d212fc9b4082d2e54a0ea4e5da260f5244ba20541d6b2e829133', 60, \LapayGroup\MetaShipSdk\Client::API_URI_TEST, $jwtHelper);
    $jwt = $Client->getJwt(); // $jwt = eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJzdWIiOiJPcGVuQVBJIiwiYXVkIjoiQTEyMjAxOSEiLCJhcGlrZXkiOiJBSlMxU0lTRHJrNmRyMFpYazVsZVQxdFBGZDRvcXNIYSIsImlzcyI6InVybjovL0FwaWdlZSIsInBhcnRuZXJJZCI6ImIyNzNlYzQ0LThiMDAtNDliMS04OWVlLWQ4Njc5NjMwZDk0OCIsImV4cCI6MTU5NzA4OTk1OCwiaWF0IjoxNTk3MDg2MzU4LCJqdGkiOiI4YTIyZmUzNy1mMzc0LTQ0NDctOGMzMC05N2ZiYjJjOGQ3MTkifQ.G_XQ6vdk7bXfIeMJer7z5WUFqnwlp0qUt6RxaCINZt3b97ZUwPMI1-1FNKQhFwmCHJGpTYyBJKHgtY3uJZOWDAszjPMIHrQrcnJLSzJisNiy6z3cMbpf-UgD-RgebuaYyEgZ81rekL5aUN6r5rqWHbxcxEGY22lTy9uEWwxF_-UdVLEW9O9Z9M9IMlL5_7ACVu-ID2n6zFk_QJnEumJcBSqb6JFh2TWvUPnjnUt5AOiD7gNRXKsBvoC6InSfGoMA461cxu-rAazhNq5fkqFSdrIUyz0kvAb3UI4hs_6xJy9tXPpXIQY7LQUZqQGp5BT8pasfhAJ_4CCATbqxIHmY9w
        
}

catch (\LapayGroup\MetaShipSdk\Exceptions\MetaShipException $e) {
    // Обработка ошибки вызова API MetaShip
    // $e->getMessage(); текст ошибки 
    // $e->getCode(); http код ответа сервиса MetaShip
    // $e->getRawResponse(); // ответ сервера MetaShip как есть (http request body)
}

catch (\Exception $e) {
    // Обработка исключения
}
```


<a name="debugging"><h1>Отладка</h1></a>  
Для логирования запросов и ответов используется [стандартный PSR-3 логгер](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-3-logger-interface.md). 
Ниже приведен пример логирования используя [Monolog](https://github.com/Seldaek/monolog).  

```php
<?php
    use Monolog\Logger;
    use Monolog\Handler\StreamHandler;
    
    $log = new Logger('name');
    $log->pushHandler(new StreamHandler('log.txt', Logger::INFO));

    $Client = new LapayGroup\MetaShipSdk\Client('9e687410-62d5-5139-b712-37e7766922c6', '2091dcf8c89e12a9b8815b9e2d48d212fc9b4082d2e54a0ea4e5da260f5244ba20541d6b2e829133', 60, \LapayGroup\MetaShipSdk\Client::API_URI_TEST);
    $Client->setLogger($log);
    $jwt = $Client->getJwt(); // $jwt = eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJzdWIiOiJPcGVuQVBJIiwiYXVkIjoiQTEyMjAxOSEiLCJhcGlrZXkiOiJBSlMxU0lTRHJrNmRyMFpYazVsZVQxdFBGZDRvcXNIYSIsImlzcyI6InVybjovL0FwaWdlZSIsInBhcnRuZXJJZCI6ImIyNzNlYzQ0LThiMDAtNDliMS04OWVlLWQ4Njc5NjMwZDk0OCIsImV4cCI6MTU5NzA4OTk1OCwiaWF0IjoxNTk3MDg2MzU4LCJqdGkiOiI4YTIyZmUzNy1mMzc0LTQ0NDctOGMzMC05N2ZiYjJjOGQ3MTkifQ.G_XQ6vdk7bXfIeMJer7z5WUFqnwlp0qUt6RxaCINZt3b97ZUwPMI1-1FNKQhFwmCHJGpTYyBJKHgtY3uJZOWDAszjPMIHrQrcnJLSzJisNiy6z3cMbpf-UgD-RgebuaYyEgZ81rekL5aUN6r5rqWHbxcxEGY22lTy9uEWwxF_-UdVLEW9O9Z9M9IMlL5_7ACVu-ID2n6zFk_QJnEumJcBSqb6JFh2TWvUPnjnUt5AOiD7gNRXKsBvoC6InSfGoMA461cxu-rAazhNq5fkqFSdrIUyz0kvAb3UI4hs_6xJy9tXPpXIQY7LQUZqQGp5BT8pasfhAJ_4CCATbqxIHmY9w
    $result = \LapayGroup\MetaShipSdk\Jwt::decode($jwt);
```

В log.txt будут логи в виде:
```
[2021-05-12T09:13:46.915568+00:00] metaship-api.INFO: MetaShip API POST request /v2/orders: {"warehouse":{"id":"cb4b1999-063f-4824-91d1-90301bc6971a"},"shop":{"id":"6d583c5d-0407-446a-ba69-741907f8171b","number":"ORD-123456"},"payment":{"type":"PayOnDelivery","declaredValue":999.99,"deliverySum":0},"dimension":{"length":10,"width":10,"height":10},"weight":1,"delivery":{"type":"Courier","service":"Boxberry"},"recipient":{"familyName":"\u0418\u0432\u0430\u043d\u043e\u0432","firstName":"\u0418\u0432\u0430\u043d","phoneNumber":"+79771234567","address":{"raw":"115551 \u041a\u0430\u0448\u0438\u0440\u0441\u043a\u043e\u0435 \u0448\u043e\u0441\u0441\u0435 94\u043a2, \u043a\u0432. 1"}},"places":[{"items":[{"article":"123456","name":"\u0422\u0435\u0441\u0442\u043e\u0432\u044b\u0439 \u0442\u043e\u0432\u0430\u0440","price":999.99,"count":1,"weight":1,"vat":"20"}]}]} [] []
[2021-05-12T09:13:47.521115+00:00] metaship-api.INFO: MetaShip API response /v2/orders: {"type":"https:\/\/wiki.metaship.ru\/api\/errors\/access-denied","title":"Delivery service \u0027Boxberry\u0027 aggregation credential data are not found","details":"Access to the requested resource is denied","status":403} {"Server":["nginx/1.15.10"],"Date":["Wed, 12 May 2021 09:13:47 GMT"],"Content-Type":["application/problem+json"],"Transfer-Encoding":["chunked"],"Connection":["keep-alive"],"X-Powered-By":["PHP/8.0.3"],"Cache-Control":["no-cache, private"],"Access-Control-Allow-Origin":["*"],"Access-Control-Allow-Credentials":["true"],"Access-Control-Allow-Methods":["GET, PUT, POST, DELETE, PATCH, OPTIONS"],"Access-Control-Allow-Headers":["DNT,User-Agent,X-Requested-With,If-Modified-Since,Cache-Control,Content-Type,Origin,Range,Authorization,Accept"],"Strict-Transport-Security":["max-age=15724800; includeSubDomains"],"http_status":403}
```


<a name="tariffs"><h1>Расчет офферов (тарифы)</h1></a>  

Для расчета стоимости доставки используйте метод **getOffers**. Он возвращает по заданным параметрам 
 
**Входные параметры:**    
- *$offerParams* - Параметры для расчета, объект [LapayGroup\MetaShipSdk\Entity\OfferParams](src/Entity/OfferParams.php);  

**Выходные параметры:**    
- *array* - доступные тарифы по подключенным СД;  

**Примеры вызова:**
```php
<?php
try {
    $offerParams = new \LapayGroup\MetaShipSdk\Entity\OfferParams();
    $offerParams->setShopId('6d583c5d-0407-446a-ba69-741907f8171b');
    $offerParams->setWarehouseId('cb4b1999-063f-4824-91d1-90301bc6971a');
    $offerParams->setAddress('115551 Каширское шоссе 94к2');
    $offerParams->setDeclaredValue(1000);
    $offerParams->setLength(10);
    $offerParams->setWidth(20);
    $offerParams->setHeight(5);
    $offerParams->setWeight(0.5);
    $offerParams->setTypes([
        \LapayGroup\MetaShipSdk\Enum\DeliveryType::COURIER,
        \LapayGroup\MetaShipSdk\Enum\DeliveryType::DELIVERY_POINT,
        \LapayGroup\MetaShipSdk\Enum\DeliveryType::POST_OFFICE
    ]);
    
    $Client = new LapayGroup\MetaShipSdk\Client('9e687410-62d5-5139-b712-37e7766922c6', '2091dcf8c89e12a9b8815b9e2d48d212fc9b4082d2e54a0ea4e5da260f5244ba20541d6b2e829133', 60, \LapayGroup\MetaShipSdk\Client::API_URI_TEST);
    $Client->getJwt();
    
    $result = $Client->getOffers($offerParams);
    
    /**
    * Array
        (
            [Courier] => Array
                (
                    [0] => Array
                        (
                            [delivery] => Array
                                (
                                    [code] => RussianPost
                                    [name] => Почта России
                                )
        
                            [service] => Array
                                (
                                    [base] => 209.20
                                    [service] => 0
                                    [total] => 251.04
                                )
        
                            [tariff] => Array
                                (
                                    [id] => 24
                                    [name] => «Курьер Онлайн»
                                )
        
                            [type] => Courier
                            [daysMin] => 1
                            [daysMax] => 1
                        )
        
                )
        
            [PostOffice] => Array
                (
                    [0] => Array
                        (
                            [delivery] => Array
                                (
                                    [code] => RussianPost
                                    [name] => Почта России
                                )
        
                            [service] => Array
                                (
                                    [base] => 119.20
                                    [service] => 0
                                    [total] => 143.04
                                )
        
                            [tariff] => Array
                                (
                                    [id] => 23
                                    [name] => «Посылка Онлайн»
                                )
        
                            [type] => PostOffice
                            [daysMin] => 1
                            [daysMax] => 1
                        )
        
                    [1] => Array
                        (
                            [delivery] => Array
                                (
                                    [code] => RussianPost
                                    [name] => Почта России
                                )
        
                            [service] => Array
                                (
                                    [base] => 180.20
                                    [service] => 0
                                    [total] => 216.24
                                )
        
                            [tariff] => Array
                                (
                                    [id] => 47
                                    [name] => «Посылка 1 Класса»
                                )
        
                            [type] => PostOffice
                            [daysMin] => 1
                            [daysMax] => 1
                        )
        
                    [2] => Array
                        (
                            [delivery] => Array
                                (
                                    [code] => RussianPost
                                    [name] => Почта России
                                )
        
                            [service] => Array
                                (
                                    [base] => 160.87
                                    [service] => 0
                                    [total] => 193.04
                                )
        
                            [tariff] => Array
                                (
                                    [id] => 4
                                    [name] => «Посылка Нестандартная»
                                )
        
                            [type] => PostOffice
                            [daysMin] => 1
                            [daysMax] => 1
                        )
        
                    [3] => Array
                        (
                            [delivery] => Array
                                (
                                    [code] => RussianPost
                                    [name] => Почта России
                                )
        
                            [service] => Array
                                (
                                    [base] => 315.00
                                    [service] => 0
                                    [total] => 378.00
                                )
        
                            [tariff] => Array
                                (
                                    [id] => 16
                                    [name] => «Бандероль 1 Класса»
                                )
        
                            [type] => PostOffice
                            [daysMin] => 1
                            [daysMax] => 1
                        )
        
                    [4] => Array
                        (
                            [delivery] => Array
                                (
                                    [code] => RussianPost
                                    [name] => Почта России
                                )
        
                            [service] => Array
                                (
                                    [base] => 148.00
                                    [service] => 0
                                    [total] => 177.60
                                )
        
                            [tariff] => Array
                                (
                                    [id] => 3
                                    [name] => «Бандероль»
                                )
        
                            [type] => PostOffice
                            [daysMin] => 2
                            [daysMax] => 2
                        )
        
                )
        )
     */
}

catch (\LapayGroup\MetaShipSdk\Exceptions\MetaShipException $e) {
    // Обработка ошибки вызова API MetaShip
    // $e->getMessage(); текст ошибки 
    // $e->getCode(); http код ответа сервиса MetaShip
    // $e->getRawResponse(); // ответ сервера MetaShip как есть (http request body)
}

catch (\Exception $e) {
    // Обработка исключения
}
```

<a name="pvz-list"><h1>Список точек выдачи</h1></a>   
Метод **getPvzList** возвращает список постаматов и пунктов выдачи заказов конкретной СД или в городе по всем СД.  
Должен быть передан один из параметров.  

**Входные параметры:** =  ,  = null
- *string|null $delivery_code* - Код СД;   
- *string|null $city_name* - Название города.  

**Выходные параметры:**
- *array* - Список точек выдачи по заданному фильтру.   

**Примеры вызова:**

```php
<?php
    try {
        $Client = new LapayGroup\MetaShipSdk\Client('9e687410-62d5-5139-b712-37e7766922c6', '2091dcf8c89e12a9b8815b9e2d48d212fc9b4082d2e54a0ea4e5da260f5244ba20541d6b2e829133', 60, \LapayGroup\MetaShipSdk\Client::API_URI_TEST);
        $Client->getJwt();
        $result = $Client->getPvzList(\LapayGroup\MetaShipSdk\Enum\DeliveryServiceCode::CDEK);
        /**
            Успешный ответ
            Array
            (
                [0] => Array
                    (
                        [deliveryServiceCode] => Boxberry
                        [deliveryServiceNumber] => 00557
                        [type] => pickup
                        [workTime] => Array
                            (
                                [raw] =>
                            )
    
                        [isOnlyPrepaid] =>
                        [isCashAllowed] =>
                        [isAcquiringAvailable] => 1
                        [name] => Москва Теплый Стан_00557_С
                        [comment] =>
                        [phone] =>
                        [address] => Array
                            (
                                [city] => Москва
                                [region] => Москва
                                [street] => Тёплый Стан ул
                                [house] => д.27
                                [building] =>
                                [apartment] =>
                                [raw] => 101000, Москва, Москва, Тёплый Стан ул, д.27, строение 1
                                [latitude] => 55.630614
                                [longitude] => 37.482026
                            )
    
                    )
    
                [1] => Array
                    (
                        [deliveryServiceCode] => Boxberry
                        [deliveryServiceNumber] => 77400
                        [type] => pickup
                        [workTime] => Array
                            (
                                [raw] =>
                            )
    
                        [isOnlyPrepaid] =>
                        [isCashAllowed] =>
                        [isAcquiringAvailable] => 1
                        [name] => Москва Новокузнецкая_7708
                        [comment] =>
                        [phone] =>
                        [address] => Array
                            (
                                [city] => Москва
                                [region] => Москва
                                [street] => Новокузнецкая ул
                                [house] => д.42
                                [building] =>
                                [apartment] =>
                                [raw] => 101000, Москва, Москва, Новокузнецкая ул, д.42, строение 5
                                [latitude] => 55.73148
                                [longitude] => 37.634668
                            )
    
                    )
    
                [2] => Array
                    (
                        [deliveryServiceCode] => Boxberry
                        [deliveryServiceNumber] => 77661
                        [type] => pickup
                        [workTime] => Array
                            (
                                [raw] =>
                            )
    
                        [isOnlyPrepaid] =>
                        [isCashAllowed] =>
                        [isAcquiringAvailable] => 1
                        [name] => Москва Люблинская_7766_С
                        [comment] =>
                        [phone] =>
                        [address] => Array
                            (
                                [city] => Москва
                                [region] => Москва
                                [street] => Люблинская ул
                                [house] => д.27/2
                                [building] =>
                                [apartment] =>
                                [raw] => 101000, Москва, Москва, Люблинская ул, д.27/2
                                [latitude] => 55.700694
                                [longitude] => 37.733419
                            )
    
                    )
                 [3] => Array
                    (
                        [deliveryServiceCode] => Cdek
                        [deliveryServiceNumber] => NKHD1
                        [type] => pickup
                        [workTime] => Array
                            (
                                [raw] => Пн-Пт 10:00-19:00, Сб 10:00-16:00
                            )
    
                        [isOnlyPrepaid] =>
                        [isCashAllowed] =>
                        [isAcquiringAvailable] => 1
                        [name] => В Южном
                        [comment] => Маршрут автобуса № 2 до остановки «1ый Южный Микрорайон».
                        [phone] => 74236606445
                        [address] => Array
                            (
                                [city] => Находка
                                [region] => Приморский
                                [street] => ул. Ленинградская
                                [house] => 17а
                                [building] =>
                                [apartment] =>
                                [raw] => 692924, Приморский, Находка, ул. Ленинградская, 17а
                                [latitude] => 42.77716
                                [longitude] => 132.846807
                            )
    
                    )
    
    
            Ответ с ошибкой
            Array
            (
                [type] => https://wiki.metaship.ru/api/errors/constraint-violations
                [title] => Constraint violations
                [details] => Parameters of the request violate restrictions
                [status] => 400
                [invalid-parameters] => Array
                    (
                        [0] => Array
                            (
                                [parameter] => deliveryServiceCode
                                [value] => Dostavista
                                [message] => The value you selected is not a valid choice.
                            )
    
                    )
    
            )
        **/
    }

    catch (\LapayGroup\MetaShipSdk\Exceptions\MetaShipException $e) {
        // Обработка ошибки вызова API MetaShip
        // $e->getMessage(); текст ошибки 
        // $e->getCode(); http код ответа сервиса MetaShip
        // $e->getRawResponse(); // ответ сервера MetaShip как есть (http request body)
    }
    
    catch (\Exception $e) {
        // Обработка исключения
    }
```



<a name="warehouses"><h1>Склады</h1></a>
Список методов для работы с складами MetaShip.    

<a name="warehouse-create"><h3>Создание склада</h3></a>  
Метод **createWarehouse** позволяет добавить склад забора заказов.    

**Входные параметры:**
- *Warehouse* - Параметры склада, объект [LapayGroup\MetaShipSdk\Entity\Warehouse](src/Entity/Warehouse.php).

**Выходные параметры:**
- *array* - Результат создания склада

**Примеры вызова:**

```php
<?php
    try {
        $Client = new LapayGroup\MetaShipSdk\Client('9e687410-62d5-5139-b712-37e7766922c6', '2091dcf8c89e12a9b8815b9e2d48d212fc9b4082d2e54a0ea4e5da260f5244ba20541d6b2e829133', 60, \LapayGroup\MetaShipSdk\Client::API_URI_TEST);
        $Client->getJwt();
        $warehouse = new \LapayGroup\MetaShipSdk\Entity\Warehouse();
        $warehouse->setName('Тестовый склад 3');
        $warehouse->setAddress('Москва, каширское шоссе 94к2');
        $result = $Client->createWarehouse($warehouse);
        /*
           Успешный ответ
           Array
            (
                [id] => 864aca33-e57b-498f-b96f-6ff873b4d771
                [type] => Warehouse
                [url] => /v2/customer/warehouses/864aca33-e57b-498f-b96f-6ff873b4d771
                [status] => 201
            )
         
           Ответ с ошибкой
           Array
            (
                [type] => https://wiki.metaship.ru/api/errors/constraint-violations
                [title] => Constraint violations
                [details] => Parameters of the request violate restrictions
                [status] => 400
                [invalid-parameters] => Array
                    (
                        [0] => Array
                            (
                                [parameter] => name
                                [value] =>
                                [message] => This value should not be blank.
                            )
    
                    )
    
            )
         */
    }

    catch (\LapayGroup\MetaShipSdk\Exceptions\MetaShipException $e) {
        // Обработка ошибки вызова API MetaShip
        // $e->getMessage(); текст ошибки 
        // $e->getCode(); http код ответа сервиса MetaShip
        // $e->getRawResponse(); // ответ сервера MetaShip как есть (http request body)
    }
    
    catch (\Exception $e) {
        // Обработка исключения
    }
```


<a name="warehouse-list"><h3>Список складов</h3></a>  
Метод **getWarehouses** возвращает список созданных складов.

**Выходные параметры:**
- *array* - список складов

**Примеры вызова:**
```php
<?php
    try {
        $Client = new LapayGroup\MetaShipSdk\Client('9e687410-62d5-5139-b712-37e7766922c6', '2091dcf8c89e12a9b8815b9e2d48d212fc9b4082d2e54a0ea4e5da260f5244ba20541d6b2e829133', 60, \LapayGroup\MetaShipSdk\Client::API_URI_TEST);
        $Client->getJwt();
        $result = $Client->getWarehouses();
        /*
         * Array
            (
                [0] => Array
                    (
                        [id] => cb4b1999-063f-4824-91d1-90301bc6971a
                        [number] => WH-45368
                        [name] => Тестовый склад
                        [address] => Array
                            (
                                [raw] => 109012, г Москва, Тверской р-н, Красная пл, д 1
                            )
        
                    )
        
                [1] => Array
                    (
                        [id] => dcc42cb3-ad82-4868-b17f-4663eda34775
                        [number] => WH-42634
                        [name] => Тестовый склад 2
                        [address] => Array
                            (
                                [raw] => 115551, г Москва, Орехово-Борисово Северное р-н, Каширское шоссе, д 94 к 2
                            )
        
                    )
        
        
                [2] => Array
                    (
                        [id] => 864aca33-e57b-498f-b96f-6ff873b4d771
                        [number] => WH-47924
                        [name] => Тестовый склад 3
                        [address] => Array
                            (
                                [raw] => 115551, г Москва, Орехово-Борисово Северное р-н, Каширское шоссе, д 94 к 2
                            )
        
                    )
        
            )
         */
    }

    catch (\LapayGroup\MetaShipSdk\Exceptions\MetaShipException $e) {
        // Обработка ошибки вызова API MetaShip
        // $e->getMessage(); текст ошибки 
        // $e->getCode(); http код ответа сервиса MetaShip
        // $e->getRawResponse(); // ответ сервера MetaShip как есть (http request body)
    }
    
    catch (\Exception $e) {
        // Обработка исключения
    }
```


<a name="shops"><h1>Магазины</h1></a>
Список методов для работы с магазинами MetaShip.       

<a name="shop-create"><h3>Создание магазина</h3></a>  
Метод **createShop** позволяет создать магазин.   

**Входные параметры:**
- *string $shop_name* - название магазина;   
- *string $shop_uri* - uri-адрес магазина;

**Выходные параметры:**
- *array* - данные созданного магазина

**Примеры вызова:**
```php
<?php
    try {
        $Client = new LapayGroup\MetaShipSdk\Client('9e687410-62d5-5139-b712-37e7766922c6', '2091dcf8c89e12a9b8815b9e2d48d212fc9b4082d2e54a0ea4e5da260f5244ba20541d6b2e829133', 60, \LapayGroup\MetaShipSdk\Client::API_URI_TEST);
        $Client->getJwt();
        $result = $Client->createShop('LapayGroupShop', 'https://lapay.group');
        /*
         Array
        (
            [id] => 2db8d753-8adb-4dbb-b8ca-e5d2e2daf17c
            [type] => Shop
            [url] => /v2/customer/shops/2db8d753-8adb-4dbb-b8ca-e5d2e2daf17c
            [status] => 201
        )
         */
    }

    catch (\LapayGroup\MetaShipSdk\Exceptions\MetaShipException $e) {
        // Обработка ошибки вызова API MetaShip
        // $e->getMessage(); текст ошибки 
        // $e->getCode(); http код ответа сервиса MetaShip
        // $e->getRawResponse(); // ответ сервера MetaShip как есть (http request body)
    }
    
    catch (\Exception $e) {
        // Обработка исключения
    }
```

<a name="shop-list"><h3>Список магазинов</h3></a>  
Метод **getShops** позволяет получить список созданных магазинов.    

**Выходные параметры:**
- *array* - данные созданного магазина

**Примеры вызова:**
```php
<?php
    try {
        $Client = new LapayGroup\MetaShipSdk\Client('9e687410-62d5-5139-b712-37e7766922c6', '2091dcf8c89e12a9b8815b9e2d48d212fc9b4082d2e54a0ea4e5da260f5244ba20541d6b2e829133', 60, \LapayGroup\MetaShipSdk\Client::API_URI_TEST);
        $Client->getJwt();
        $result = $Client->getShops();
        /*
         Array
         (
            [0] => Array
                (
                    [id] => 6d583c5d-0407-446a-ba69-741907f8171b
                    [number] => 29430936
                    [name] => TestShop
                    [uri] => http://test.ru
                    [phone] =>
                    [sender] =>
                    [trackingTag] =>
                )
             [1] => Array
                (
                    [id] => 2db8d753-8adb-4dbb-b8ca-e5d2e2daf17c
                    [number] => SH-55883
                    [name] => LapayGroupShop
                    [uri] => https://lapay.group
                    [phone] =>
                    [sender] =>
                    [trackingTag] => jg
                )

          )
         */
    }

    catch (\LapayGroup\MetaShipSdk\Exceptions\MetaShipException $e) {
        // Обработка ошибки вызова API MetaShip
        // $e->getMessage(); текст ошибки 
        // $e->getCode(); http код ответа сервиса MetaShip
        // $e->getRawResponse(); // ответ сервера MetaShip как есть (http request body)
    }
    
    catch (\Exception $e) {
        // Обработка исключения
    }
```

<a name="orders"><h1>Заказы</h1></a>
Список методов для работы с заказами MetaShip.

<a name="order-create"><h3>Создание заказа</h3></a>  
Метод **getWarehouses**

**Входные параметры:**
- *Order* - Параметры заказа, объект [LapayGroup\MetaShipSdk\Entity\Order](src/Entity/Order.php).

**Выходные параметры:**
- *array* - список

**Примеры вызова:**
```php
<?php
    try {
        $Client = new LapayGroup\MetaShipSdk\Client('9e687410-62d5-5139-b712-37e7766922c6', '2091dcf8c89e12a9b8815b9e2d48d212fc9b4082d2e54a0ea4e5da260f5244ba20541d6b2e829133', 60, \LapayGroup\MetaShipSdk\Client::API_URI_TEST);
        $Client->getJwt();
        
        // Минимальный набор данных в заказе
        $order = new \LapayGroup\MetaShipSdk\Entity\Order();
        $order->setWarehouseId('cb4b1999-063f-4824-91d1-90301bc6971a');
        $order->setShopId('6d583c5d-0407-446a-ba69-741907f8171b');
        $order->setNumber('ORD-123456');
        $order->setWeight(1);
        $order->setDeliveryServiceCode(\LapayGroup\MetaShipSdk\Enum\DeliveryServiceCode::BOXBERRY);
        $order->setLastname('Иванов');
        $order->setFirstname('Иван');
        $order->setPhone('+79771234567');
        $order->setPaymentType(\LapayGroup\MetaShipSdk\Enum\PaymentType::PAY_ON_DELIVERY);
        $order->setDeclaredValue(999.99);
        $order->setDeliverySum(0);
        $order->setLength(10);
        $order->setWidth(10);
        $order->setHeight(10);
    
        // Доставка курьером
        $order->setDeliveryType(\LapayGroup\MetaShipSdk\Enum\DeliveryType::COURIER);
        $order->setAddress('115551 Каширское шоссе 94к2, кв. 1');
    
        // Доставка до ПВЗ
        $order->setDeliveryType(\LapayGroup\MetaShipSdk\Enum\DeliveryType::DELIVERY_POINT);
        $order->setPvzCode('12669');
   
        // Создаем товар для места
        $item = new \LapayGroup\MetaShipSdk\Entity\Item();
        $item->setArticle('123456');
        $item->setWeight(1);
        $item->setName('Тестовый товар');
        $item->setCount(1);
        $item->setPrice(999.99);
        $item->setVatRate(\LapayGroup\MetaShipSdk\Enum\Vat::VAT_20);
    
        // Создаем место, добавляем в него товар и добавляем место в заказ 
        $place = new \LapayGroup\MetaShipSdk\Entity\Place();
        $place->setItem($item);
        $order->setPlace($place);
   
        $result = $Client->createOrder($order);
        /*
         Успешный ответ
         // TODO
         
         Ответ с ошибкой
         Array
         (
            [type] => https://wiki.metaship.ru/api/errors/access-denied
            [title] => Delivery service 'Boxberry' aggregation credential data are not found
            [details] => Access to the requested resource is denied
            [status] => 403
         )
        
         Ответ с ошибкой заполнения параметров
         Array
         (
            [type] => https://wiki.metaship.ru/api/errors/constraint-violations
            [title] => Constraint violations
            [details] => Parameters of the request violate restrictions
            [status] => 400
            [invalid-parameters] => Array
                (
                    [0] => Array
                        (
                            [parameter] => payment.type
                            [value] =>
                            [message] => This value should not be blank.
                        )
    
                    [1] => Array
                        (
                            [parameter] => payment.declaredValue
                            [value] =>
                            [message] => This value should not be blank.
                        )
    
                    [2] => Array
                        (
                            [parameter] => payment.deliverySum
                            [value] =>
                            [message] => This value should not be blank.
                        )
    
                    [3] => Array
                        (
                            [parameter] => dimension.length
                            [value] =>
                            [message] => This value should not be blank.
                        )
    
                    [4] => Array
                        (
                            [parameter] => dimension.width
                            [value] =>
                            [message] => This value should not be blank.
                        )
    
                    [5] => Array
                        (
                            [parameter] => dimension.height
                            [value] =>
                            [message] => This value should not be blank.
                        )
    
                    [6] => Array
                        (
                            [parameter] => recipient.firstName
                            [value] =>
                            [message] => This value should not be blank.
                        )
    
                )

            )
         */
    }

    catch (\LapayGroup\MetaShipSdk\Exceptions\MetaShipException $e) {
        // Обработка ошибки вызова API MetaShip
        // $e->getMessage(); текст ошибки 
        // $e->getCode(); http код ответа сервиса MetaShip
        // $e->getRawResponse(); // ответ сервера MetaShip как есть (http request body)
    }
    
    catch (\Exception $e) {
        // Обработка исключения
    }
```

<a name="batch"><h1>Партии</h1></a>
Список методов для работы с партиями MetaShip. 

<a name="docs"><h1>Документы</h1></a>
Список методов для работы с документами MetaShip. 