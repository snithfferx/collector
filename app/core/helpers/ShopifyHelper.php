<?php

namespace app\core\helpers;

use Shopify\Clients\Rest;
use PHPShopify\ShopifySDK;

class ShopifyHelper
{
    /**
     * Almacena el objeto de HTTPMethodRequest
     * @var object $shopRequest
     */
    public $shopRequest;
    public $dsStore;
    private $confs;
    function __construct()
    {
        $confController = new ConfigHelper;
        $this->confs = $confController->get("shopify");
        $this->shopRequest = new ShopifySDK();
    }
    function getAccess() {
        return $this->accessToStore();
    }
    private function accessToStore() {
        $this->dsStore = new Rest($this->confs['url']);
        return $this->shopRequest->config([
            'ShopUrl' => $this->confs['url'],
            'ApiKey' => $this->confs['key'],
            'Password' => $this->confs['pass'],
            'SharedSecret' => $this->confs['s_secret'],
            'ApiVersion' => '2022-10'
        ]);
    }
}
