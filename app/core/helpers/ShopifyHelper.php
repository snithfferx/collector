<?php

namespace app\core\helpers;

use PHPShopify\ShopifySDK;

class ShopifyHelper
{
    /**
     * Almacena el objeto de HTTPMethodRequest
     * @var object $shopRequest
     */
    public $shopRequest;
    private $confs;
    function __construct()
    {
        $confController = new ConfigHelper;
        $this->confs = $confController->get("shopify");
        $this->shopRequest = new ShopifySDK([
            'ShopUrl' => $this->confs['url'],
            'ApiKey' => $this->confs['key'],
            'Password' => $this->confs['pass'],
            'SharedSecret' => $this->confs['s_secret'],
            'ApiVersion' => '2022-10'
        ]);
    }
}
