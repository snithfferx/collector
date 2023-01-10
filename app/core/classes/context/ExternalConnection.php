<?php

namespace app\core\classes\context;

use app\core\helpers\ShopifyHelper;

/**
 * Clase para las transacciones entre los modelos y la base de datos.
 * @author Jorge Echeverria <jecheverria@bytes4run.com>
 * @version 1.0.0
 */
class ExternalConecction extends ShopifyHelper
{
    private $scopes;
    private $url;
    private $token;
    public function __construct()
    {
        $this->scopes = "read_products,write_products,read_script_tags,write_script_tags";
        $this->url = "";
        $this->token = $this->shopRequest->createAuthRequest($this->scopes, null, null, null, true);
    }
    public function makeRequest(array $values): array
    {
        if ($values['type'] == "get") {
            $response = $this->get($values['request']);
        } elseif ($values['type'] == "post") {
            $response = $this->get($values);
        } elseif ($values['type'] == "put") {
            $response = $this->get($values);
        } else {
            $response = ['type' => "error", 'data' => array()];
        }
        return $response;
    }

    private function get($values): array
    {
        try {
            if (!empty($values['value'])) {
                $response = $this->shopRequest->$values['element']($values['value'])->get();
            } else {
                $response = $this->shopRequest->$values['element']->get();
            }
        } catch (\Exception $e) {
            $response = $e;
        }
        return ['data' => $response];
    }
}
