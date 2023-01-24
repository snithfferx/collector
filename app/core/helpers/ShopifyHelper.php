<?php

namespace app\core\helpers;

use PHPShopify\Exception\ApiException;
use Shopify\Auth\FileSessionStorage;
use Shopify\Clients\Rest;
use Shopify\Clients\Graphql;
use Shopify\Context;

class ShopifyHelper
{
    /**
     * Almacena el objeto de HTTPMethodRequest
     * @var object $shopRequest
     */
    public $shopRequest;
    public $dsStore;
    private $confs;
    private $app_cnf;
    public $version;
    public $grphQlClient;
    function __construct()
    {
        $confController = new ConfigHelper;
        $this->confs = $confController->get("shopify");
        $this->app_cnf = $confController->get("config");
        $this->version = $this->confs['api_version'];
        $this->shopifyInit();
    }
    function getAccess()
    {
        return $this->accessToStore();
        //return $this->shopifyInit();
    }
    function graphAccess () {
        return $this->graphQLRequest();
    }
    protected function getGuzzleResponse(array $values)
    {
        return $this->useGuzzleRequest($values);
    }
    private function accessToStore()
    {
        return $this->dsStore = new Rest($this->confs['store_url'] . ".myshopify.com", $this->confs['api_token']);
        /* return $this->shopRequest->config([
            'ShopUrl' => $this->confs['url'],
            'ApiKey' => $this->confs['key'],
            'Password' => $this->confs['pass'],
            'SharedSecret' => $this->confs['s_secret'],
            'ApiVersion' => '2022-10'
        ]); */
    }
    private function shopifyInit()
    {
        $scopes = ['read_products', 'write_products', 'read_product_listings', 'read_publications', 'write_publications'];
        $path = dirname(_APP_, 4);
        $storage = new FileSessionStorage("$path/tmp/php_sessions");
        try {
            Context::initialize(
                $this->confs['api_key'],
                $this->confs['api_secret'],
                $scopes,
                $this->app_cnf['app_url'],
                $storage
            );
            //OAuth::begin($this->confs['store_url'], "auth/login/callback", false);
        } catch (ApiException $apiex) {
            return ['error' => ['message' => $apiex->getMessage(), 'code' => $apiex->getCode(), 'trace' => $apiex->getTraceAsString()], 'data' => array()];
        }
        return true;
    }
    private function useGuzzleRequest($values)
    {
        $guz = new GuzzleHelper($this->confs);
        if (isset($values['query'])) {
            return $guz->request($values['method'], $values['element'], $values['query']);
        } else {
            return $guz->request($values['method'], $values['element']);
        }
    }
    private function graphQLRequest () {
        try {
            $this->grphQlClient = new Graphql($this->confs['store_url'] . ".myshopify.com", $this->confs['api_token']);
        } catch (ApiException $apex) {
            return $apex->getMessage();
        }
        return true;
    }
}
class GuzzleHelper
{
    private $shop_user = '';
    private $shop_password = '';
    private $shop_url = '';
    function __construct($shop)
    {
        $this->shop_url = $shop->url;
        $this->shop_user = $shop->key;
        $this->shop_password = $shop->pass;
        //$this->syncShopifyOrder($shop);
    }
    /* public function getRequest($request)
    {
        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET', $request, [
            'auth' => [$this->shop_user, $this->shop_password]
        ]);
    }
    private function syncShopifyOrder($shop)
    {
        $count = 0;
        try {
            if ($shop) {
                $nextPageToken = null;
                do {
                    $param = ($nextPageToken) ? '&page_info=' . $nextPageToken : '&status=any&fulfillment_status=any&order=created_at asc&created_at_min=2020-08-10T13:30:33+02:00';
                    $url = $this->shop_url . 'admin/api/2020-01/orders.json?limit=250' . $param;

                    $data = $this->getOrderRequest($url);
                    $all_orders = $data['all_orders'];
                    $nextPageToken = isset($data['next']) ? $data['next'] : null;

                    if ($all_orders) {
                        $count += count((array) $all_orders);
                        $this->bulkorderInsertShopify($shop, $all_orders);
                    }
                } while ($nextPageToken);
            } else {
                throw new OrderSyncException('You have not configured shop!');
            }
            $this->syncSuccessReport($shop, $count);
        } catch (OrderSyncException $e) {
            $this->syncErrorReport($shop, $count, $e->getMessage());
        } catch (\Exception $e) {
            $this->syncErrorReportAdmin($shop, $count, $e);
        }
    }
    private function getOrderRequest($url)
    {
        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET', $url, [
            'auth' => [$this->shop_user, $this->shop_password]
        ]);

        if ($response->getStatusCode() !== 200) {
            throw new OrderSyncException('Connection problem!');
        }

        $data = [];
        $paginate_links = $response->getHeader('Link');

        if ($paginate_links) {

            $page_link = $paginate_links[0];
            $links_arr = explode(",", $page_link);

            if ($links_arr) {

                $tobeReplace = ["<", ">", 'rel="next"', ";", 'rel="previous"'];
                $tobeReplaceWith = ["", "", "", ""];

                foreach ($links_arr as $link) {
                    $link_type  = strpos($link, 'rel="next') !== false ? "next" : "previous";
                    parse_str(parse_url(str_replace($tobeReplace, $tobeReplaceWith, $link), PHP_URL_QUERY), $op);
                    $data[$link_type] = trim($op['page_info']);
                }
            }
        }

        $order_data = $response->getBody()->getContents();
        $data['all_orders'] = (json_decode($order_data))->orders;
        return $data;
    } */
    public function request($method, $url, $param = [])
    {
        $client = new \GuzzleHttp\Client();
        $url = 'https://' . $this->shop_user . ':' . $this->shop_password . '@' . $this->shop_url . '/admin/api/2019-10/' . $url;
        $parameters = [
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json'
            ]
        ];
        if (!empty($param)) {
            $parameters['json'] = $param;
        }
        $response = $client->request($method, $url, $parameters);
        $responseHeaders = $response->getHeaders();
        $tokenType = 'next';
        if (array_key_exists('Link', $responseHeaders)) {
            $link = $responseHeaders['Link'][0];
            $tokenType  = strpos($link, 'rel="next') !== false ? "next" : "previous";
            $tobeReplace = ["<", ">", 'rel="next"', ";", 'rel="previous"'];
            $tobeReplaceWith = ["", "", "", ""];
            parse_str(parse_url(str_replace($tobeReplace, $tobeReplaceWith, $link), PHP_URL_QUERY), $op);
            $pageToken = trim($op['page_info']);
        }
        $rateLimit = explode('/', $responseHeaders["X-Shopify-Shop-Api-Call-Limit"][0]);
        $usedLimitPercentage = (100 * $rateLimit[0]) / $rateLimit[1];
        if ($usedLimitPercentage > 95) {
            sleep(5);
        }
        $responseBody = json_decode($response->getBody(), true);
        $r['resource'] =  (is_array($responseBody) && count($responseBody) > 0) ? array_shift($responseBody) : $responseBody;
        $r[$tokenType]['page_token'] = isset($pageToken) ? $pageToken : null;
        return $r;
    }
    /*http://collector.sx/?
    hmac=6ae477743844e67edd448581282179ffd5dcbe9b4378336a6e0f5934560bffca&
    host=ZGlnaXRhbC1zb2x1dGlvbnMtYmV0YS5teXNob3BpZnkuY29tL2FkbWlu&
    shop=digital-solutions-beta.myshopify.com&
    timestamp=1673896679 */
}
