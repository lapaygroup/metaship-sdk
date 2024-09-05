<?php

namespace LapayGroup\MetaShipSdk;

use GuzzleHttp\Handler\CurlHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\UploadedFile;
use LapayGroup\MetaShipSdk\Entity\OfferParams;
use LapayGroup\MetaShipSdk\Entity\Order;
use LapayGroup\MetaShipSdk\Entity\Warehouse;
use LapayGroup\MetaShipSdk\Exceptions\MetaShipException;
use LapayGroup\MetaShipSdk\Exceptions\MetaShipValidationException;
use LapayGroup\MetaShipSdk\Exceptions\TokenException;
use LapayGroup\MetaShipSdk\Helpers\JwtSaveFileHelper;
use LapayGroup\MetaShipSdk\Helpers\JwtSaveInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;

class Client implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    /** @var string|null */
    private $jwt = null;

    /** @var string|null */
    private $client_id = null;

    /** @var string|null */
    private $secret_key = null;

    /** @var \GuzzleHttp\Client|null */
    private $httpClient = null;

    /** @var JwtSaveInterface|null */
    private $jwtHelper = null;


    const API_URI_TEST = 'https://dev.api.metaship.ru';
    const API_URI_PROD = 'https://api.metaship.ru';

    const DATA_JSON   = 'json';
    const DATA_PARAMS = 'form_params';

    /**
     * Client constructor.
     *
     * @param string $client_id - ID клиента в системе MetaShip
     * @param string $secret_key - Секретный код в системе MetaShip
     * @param int $timeout - таймаут ожидания ответа от серверов 5post в секундах
     * @param string $api_uri - адрес API (тествоый или продуктовый)
     * @param JwtSaveInterface|null $jwtHelper - помощник для сохранения токена
     */
    public function __construct($client_id, $secret_key, $timeout = 300, $api_uri = self::API_URI_PROD, $jwtHelper = null)
    {
        $this->client_id = $client_id;
        $this->secret_key = $secret_key;
        $this->stack = new HandlerStack();
        $this->stack->setHandler(new CurlHandler());
        $this->stack->push($this->handleAuthorizationHeader());

        $this->httpClient = new \GuzzleHttp\Client([
            'handler'  => $this->stack,
            'base_uri' => $api_uri,
            'timeout' => $timeout,
            'exceptions' => false,
            'http_errors' => false
        ]);

        if (!$jwtHelper) {
            $jwtHelper = new JwtSaveFileHelper();
        }

        $this->jwtHelper = $jwtHelper;
    }

    /**
     * Инициализирует вызов к API
     *
     * @param $type
     * @param $method
     * @param array $params
     * @return array|boolean|UploadedFile
     * @throws MetaShipException
     */
    private function callApi($type, $method, $params = [], $data_type = self::DATA_JSON)
    {
        $is_file = false;

        switch ($type) {
            case 'GET':
                $request = http_build_query($params);
                if ($this->logger) {
                    $this->logger->info("MetaShip API {$type} request {$method}: " . $request);
                }
                $response = $this->httpClient->get($method, ['query' => $params]);
                break;
            case 'DELETE':
                $request = http_build_query($params);
                if ($this->logger) {
                    $this->logger->info("MetaShip API {$type} request {$method}: " . $request);
                }
                $response = $this->httpClient->request($type, $method, ['query' => $params]);
                break;
            case 'POST':
                $request = json_encode($params);
                if ($this->logger) {
                    $this->logger->info("MetaShip API {$type} request {$method}: " . $request);
                }
                $response = $this->httpClient->post($method, [$data_type => $params]);
                break;
        }

        $json = $response->getBody()->getContents();
        $http_status_code = $response->getStatusCode();
        $headers = $response->getHeaders();
        $headers['http_status'] = $http_status_code;
        $content_type = $response->getHeaderLine('Content-Type');

        if (preg_match('~^application/(pdf|zip|octet-stream)~', $content_type, $matches_type)) {
            $is_file = true;
            if ($this->logger) {
                $this->logger->info("MetaShip API response {$method}: получен файл с расширением ".$matches_type[1], $headers);
            }
        } else {
            if ($this->logger) {
                $this->logger->info("MetaShip API response {$method}: " . $json, $headers);
            }
        }

        $respMetaShip = json_decode($json, true);

        if ($http_status_code == 401)
            throw new MetaShipException('Отсутствует Bearer токен в запросе', $http_status_code, $json, $request);

        if ($http_status_code == 404)
            throw new MetaShipException('Объект не найден в базе MetaShip', $http_status_code, $json, $request);

        if ($http_status_code == 400) {
            if (empty($respMetaShip['invalid-parameters'])) $respMetaShip['invalid-parameters'] = [];
            throw new MetaShipValidationException($respMetaShip['title'] . ': ' . $respMetaShip['details'], $http_status_code, $respMetaShip['invalid-parameters']);
        }

        if ($is_file) {
            $response->getBody()->rewind();
            preg_match('~=(.+)~', $response->getHeaderLine('Content-Disposition'), $matches_name);
            return new UploadedFile(
                $response->getBody(),
                $response->getBody()->getSize(),
                UPLOAD_ERR_OK,
                "{$matches_name[1]}.{$matches_type[1]}",
                $response->getHeaderLine('Content-Type')
            );
        }

        if (empty($respMetaShip) && $json != '[]' && $http_status_code != 204)
            throw new MetaShipException('От сервера MetaShip при вызове метода ' . $method . ' пришел пустой ответ', $http_status_code, $json, $request);

        return (!empty($respMetaShip) || $json == '[]') ? $respMetaShip : true;
    }

    /**\
     * @return \Closure
     */
    private function handleAuthorizationHeader()
    {
        return function ($handler)
        {
            return function (RequestInterface $request, array $options) use ($handler)
            {
                if ($this->jwt) {
                    $request = $request->withHeader('Authorization', 'Bearer ' . $this->jwt);
                }

                return $handler($request, $options);
            };
        };
    }

    public function getJwt()
    {
        if ($this->jwtHelper)
            $this->jwt = $this->jwtHelper->getToken();

        if ($this->jwt) {
            try {
                Jwt::decode($this->jwt);
            }

            catch (TokenException $e) {
                $this->jwt = $this->generateJwt();
            }
        } else {
            $this->jwt = $this->generateJwt();
        }

        Jwt::decode($this->jwt);

        return $this->jwt;
    }

    /**
     * @param string $jwt - ранее полученный JWT токен
     */
    public function setJwt($jwt)
    {
        $this->jwt = $jwt;
    }

    /**
     * Получение JWT токена
     *
     * @return mixed
     * @throws MetaShipException
     */
    private function generateJwt()
    {
        $response = $this->callApi('POST', '/auth/access_token',
            [
                'grant_type' => 'client_credentials',
                'client_id' => $this->client_id,
                'client_secret' => $this->secret_key
            ], self::DATA_PARAMS
        );

        if ($this->jwtHelper)
            $this->jwt = $this->jwtHelper->setToken($response['access_token']);

        sleep(1); // Так как access_token начинает действовать не сразу делаем задержку
        return $response['access_token'];
    }

    /**
     * Создание склада забора заказов
     *
     * @param Warehouse $warhouse
     * @return array
     * @throws MetaShipException
     */
    public function createWarehouse($warhouse)
    {
        return $this->callApi('POST', '/v2/customer/warehouses', $warhouse->asArr());
    }

    /**
     * Получение списка складов забора заказов
     *
     * @return array
     * @throws MetaShipException
     */
    public function getWarehouses()
    {
        return $this->callApi('GET', '/v2/customer/warehouses');
    }

    /**
     * Получение склада забора заказов
     *
     * @param $warehouse_id - uuid склада в MetaShip
     * @return array
     * @throws MetaShipException
     */
    public function getWarehouse($warehouse_id)
    {
        return $this->callApi('GET', '/v2/customer/warehouses/'.$warehouse_id);
    }

    /**
     * Удаление склада забора заказов
     *
     * @param $warehouse_id - uuid склада в MetaShip
     * @return array
     * @throws MetaShipException
     */
    public function deleteWarehouse($warehouse_id)
    {
        return $this->callApi('DELETE', '/v2/customer/warehouses/'.$warehouse_id);
    }

    /**
     * Получение списка офферов
     *
     * @param OfferParams $offerParams
     * @return array
     * @throws MetaShipException
     */
    public function getOffers($offerParams)
    {
        return $this->callApi('GET', '/v2/offers', $offerParams->asArr());
    }

    /**
     * Создание магазина
     *
     * @param string $shop_name
     * @param string $shop_uri
     * @return array
     * @throws MetaShipException
     */
    public function createShop($shop_name, $shop_uri)
    {
        return $this->callApi('POST', '/v2/customer/shops', ['name' => $shop_name, 'uri' => $shop_uri]);
    }

    /**
     * Получение списка магазинов
     *
     * @return array
     * @throws MetaShipException
     */
    public function getShops()
    {
        return $this->callApi('GET', '/v2/customer/shops');
    }

    /**
     * Возврат списка ПВЗ
     *
     * @param string $delivery_code - Код службы доставки
     * @param string $city_name - Название города
     * @throws MetaShipException
     */
    public function getPvzList($shop_id, $delivery_code = null, $city_name = null)
    {
        if (empty($delivery_code) && empty($city_name))
            throw new \InvalidArgumentException('Вы должны указать код СД или название города');

        return $this->callApi('GET', '/v2/customer/info/delivery_service_points', ['shopId' => $shop_id, 'deliveryServiceCode' => $delivery_code, 'cityRaw' => $city_name]);
    }

    /**
     * Создание заказа
     *
     * @param Order $order - данные заказа
     * @return array
     * @throws MetaShipException
     * @throws \InvalidArgumentException
     */
    public function createOrder($order)
    {
        return $this->callApi('POST', '/v2/orders', $order->asArr());
    }

    /**
     * История статусов заказа
     *
     * @param string $order_id - id заказа в системе MetaShip
     * @return array
     * @throws MetaShipException
     * @throws \InvalidArgumentException
     */
    public function getOrderStatuses($order_id)
    {
        return $this->callApi('GET', "/v2/orders/$order_id/statuses");
    }

    /**
     * Получение информации о заказе
     *
     * @param string $order_id - id заказа в системе MetaShip
     * @return array
     * @throws MetaShipException
     * @throws \InvalidArgumentException
     */
    public function getOrderInfo($order_id)
    {
        return $this->callApi('GET', "/v2/orders/$order_id");
    }

    /**
     * Получение подробной информации о заказе
     *
     * @param string $order_id - id заказа в системе MetaShip
     * @return array
     * @throws MetaShipException
     */
    public function getOrderDetails($order_id)
    {
        return $this->callApi('GET', "/v2/orders/$order_id/details");
    }

    /**
     * Удаление заказа
     *
     * @param string $order_id - id заказа в системе MetaShip
     * @return array
     * @throws MetaShipException
     * @throws \InvalidArgumentException
     */
    public function deleteOrder($order_id)
    {
        return $this->callApi('DELETE', "/v2/orders/$order_id");
    }

    /**
     * Создание склада забора заказов
     *
     * @param array $order_ids - список id заказов в системе MetaShip
     * @param string $shipment_date - Дата отгрузки
     * @return array
     * @throws MetaShipException
     */
    public function createParcel($order_ids, $shipment_date)
    {
        $shipment_date = (new \DateTime($shipment_date))->format('Y-m-d');
        return $this->callApi('POST', '/v2/parcels', ['orderIds' => $order_ids, 'shipmentDate' => $shipment_date]);
    }

    /**
     * Получение списка партий
     *
     * @param string $status - Статус партий
     * @param string $page - Номер страницы
     * @param string $limit - Количество партий на странице
     * @return array|bool
     * @throws MetaShipException
     */
    public function getParcels($status = null, $page = null, $limit = null)
    {
        $params = [];
        if ($status) $params['status'] = $status;
        if ($page) $params['page'] = $page;
        if ($limit) $params['limit'] = $limit;
        return $this->callApi('GET', "/v2/parcels", $params);
    }

    /**
     * Получение информации о партии
     *
     * @param string $parcel_id - Идентификатор партии
     * @return array
     * @throws MetaShipException
     */
    public function getParcelInfo($parcel_id)
    {
        return $this->callApi('GET', "/v2/parcels/".$parcel_id);
    }

    /**
     * Получение этикетки заказа
     *
     * @param string $order_id - id заказа в системе MetaShip
     * @return UploadedFile
     * @throws MetaShipException
     */
    public function getOrderLabel($order_id)
    {
        return $this->callApi('GET', "/v2/orders/$order_id/label");
    }

    /**
     * Получение этикеток заказов из партии
     *
     * @param string $parcel_id - id партии в системе MetaShip
     * @param array $order_ids - список id заказов в системе MetaShip
     * @return UploadedFile
     * @throws MetaShipException
     */
    public function getParcelLabels($parcel_id, $order_ids)
    {
        return $this->callApi('POST', "/v2/parcels/$parcel_id/labels", ['orderIds' => $order_ids]);
    }

    /**
     * Получение АПП
     *
     * @param $parcel_id - id партии в системе MetaShip
     * @return UploadedFile
     * @throws MetaShipException
     */
    public function getParcelAcceptance($parcel_id)
    {
        return $this->callApi('GET', "/v2/parcels/$parcel_id/acceptance");
    }
}
