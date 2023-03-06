<?php

namespace app\core\classes\context;

use app\core\helpers\ShopifyHelper;
use GuzzleHttp\Psr7\Query;
use PHPShopify\Exception\ApiException;

/**
 * Clase para las transacciones entre los modelos y la base de datos.
 * @author Jorge Echeverria <jecheverria@bytes4run.com>
 * @version 1.0.0
 */
class ExternalConnection
{
    //private $scopes;
    //private $url;
    //private $session;
    private $shop;
    private $client;
    private $version;
    public function __construct()
    {
        $shopify = new ShopifyHelper;
        //$this->scopes = "read_products,write_products,read_script_tags,write_script_tags";
        //$this->url = "";
        $this->client = $shopify->getAccess();
        $this->version = $shopify->version;
    }
    /**
     * Método para realizar una solicitud get a la tienda
     * @param array $values Contiene la solicitud dividida en las siguientes partes:
     * 
     * ELEMENT => contiene el nombre del elemento al cual se le realizará la acción ej.: [products, orders]
     * 
     * VALUE   => contiene el valor por el cual se le realizará la acción ej.: [id, handler]; [products(458887999)]
     * 
     * FIELDS  => contiene los campos a ser solicitados del elemento enviado ej.: [products()->get(['id','images'])]
     * 
     * Devuelve un arreglo conteniendo la información solicitada de forma asociativa.
     * [información](https://shopify.dev/api/admin-rest)
     * 
     * @return array
     */
    protected function _get($values): array
    {
        $elemento =  $values['element'];
        $valores = $values['value'];
        $campos = $values['fields'];
        try {
            if (!is_null($valores)) {
                $result = (is_null($campos)) ? $this->client->$elemento($valores)->get() : $this->client->$elemento($valores)->get($campos);
            } else {
                $result = (is_null($campos)) ? $this->client->$elemento->get() : $this->client->$elemento->get($campos);
            }
            $response = ['error' =>[],'data' =>$result];
        } catch (ApiException $e) {
            $response = [
                'error' => [
                    'message' => $e->getMessage(),
                    'line' => $e->getLine(),
                    'code' => $e->getCode(),
                    'file' => $e->getFile(),
                    'trace' => $e->getTraceAsString()
                ],
                'data' => $values
            ];
        }
        return $response;
    }
    protected function _post($values): array
    {
        $elemento = $values['element'];
        $valores = $values['value'];
        $campos = $values['fields'];
        try {
            for ($a = 0; $a < 2;$a++) {
                $exists = $this->_find($elemento,$campos[$a],$valores[$a]);
                if (!empty($exists['data'])) {
                    return [
                        'error'=>[
                            'code'=>"00405",
                            'message'=>"$elemento, ya existe. Elija un nombre diferente.",
                            'extra'=>$exists['data']
                        ]
                    ];
                }
            }
            $params = array();
            foreach ($campos as $i=>$v) {
                $params[$v]=$values[$i];
            }
            $result = $this->client->$elemento->post($params);
            if ($result->getStatusCode() == 200) {
                $datos = $result->getDecodedBody();
                return ['data' => $datos, 'error' => []];
            } else {
                return ['data' => [], 'error' => ['code' => $result->getStatusCode(), 'message' => $result->getHeaders()]];
            }
        } catch (ApiException $e) {
            $response = [
                'error' => [
                    'message' => $e->getMessage(), 'code' => $e->getCode()
                ],
                'data' => $values
            ];
        }
        return $response;
    }
    protected function _put ($values) {
        $elemento =  $values['element'];
        $id = $values['params'];
        $changes = array();
        foreach ($values['fields'] as $i=>$campo) {
            $changes[$campo] = $values['values'][$i];
        }
        try {
            $result = $this->client->$elemento($id)->put($changes);
            $response = ['error' => [], 'data' => $result];
        } catch (ApiException $e) {
            $response = [
                'error' => [
                    'message' => $e->getMessage(), 'code' => $e->getCode()
                ],
                'data' => $values
            ];
        }
        return $response;
    }
    protected function _delete ($request) {
        $elemento = $request['element'];
        $valores = $request['value'];
        $campos = $request['field'];
        try {
            $result = $this->client->$elemento->delete($campos . "=" . $valores);
        } catch (ApiException $apex) {
            $result = [
                'code'=>$apex->getCode(),
                'message'=>$apex->getMessage()
            ];
        }
        return (isset($result['code'])) ? $result : ['code'=>"00200", 'message'=>"$elemento #$valores, eliminado exitosamente."];
    }
    protected function getHttp($values)
    {
        $el = $values['element'];
        $hasNext = false;
        $hasPrev = false;
        try {
            $resultados = $this->storeGet($values);
            if (!empty($resultados['error'])) {
                throw new \Exception("Error Processing Request", $resultados['error']);
            } else {
                $pagination = unserialize($resultados['pagination']);
                if (isset($values['query']['id'])) {
                    $data = ($values['query']['id'] == "count") ? $resultados['data'] : ($resultados['data'][$el]);
                } else {
                    $data = $resultados['data'][$el];
                }
                if (!is_null($pagination)) {
                    if ($pagination->hasNextPage()) $hasNext = $pagination->getNextPageQuery();
                }
                if (!is_null($pagination)) {
                    if ($pagination->hasPreviousPage()) $hasPrev = $pagination->getPreviousPageQuery();
                }
                $response = [
                    'data' => [
                        'collections' => $data,
                        'next' => $hasNext,
                        'prev' => $hasPrev 
                    ],
                    'error' => []
                ];
            }
        } catch (\Exception $e) {
            $response = [
                'error' => [
                    'message' => $e->getMessage(),
                    'line' => $e->getLine(),
                    'code' => $e->getCode(),
                    'file' => $e->getFile(),
                    'trace' => $e->getTraceAsString()
                ],
                'data' => $values
            ];
        }
        return $response;
    }
    protected function guzzleConnect($values)
    {
        try {
            $response = ['data' => $this->guzzle($values), 'error' => []];
        } catch (\Exception $e) {
            $response = [
                'error' => [
                    'message' => $e->getMessage(),
                    'line' => $e->getLine(),
                    'code' => $e->getCode(),
                    'file' => $e->getFile(),
                    'trace' => $e->getTraceAsString()
                ],
                'data' => $values
            ];
        }
        return $response;
    }
    protected function graphQLRequest ($values) {
        return $this->getGraphQLResponse($values);
    }

    private function storeGet($values): array
    {
        if (isset($values['query']['id']) && !empty($values['query']['id'])) {
            $url = $values['element'] . "/" . $values['query']['id'];
            array_shift($values['query']);
        } else {
            $url = $values['element'];
        }
        $result = $this->client->get($url, [], $values['query']);
        if ($result->getStatusCode() == 200) {
            $pageInfo = $result->getPageInfo();
            $serializedPageInfo = (!is_null($pageInfo)) ? serialize($pageInfo) : $pageInfo;
            $datos = $result->getDecodedBody();
            return ['data' => $datos, 'pagination' => $serializedPageInfo, 'error' => []];
        }
        return ['data' => [], 'error' => $result->getStatusCode()];
    }
    private function guzzle($values)
    {
        return $this->shop->getGuzzleResponse($values);
    }
    private function getGraphQLResponse ($values) {
        $shopify = new ShopifyHelper;
        $access = $shopify->graphAccess();
        $query = <<<QUERY
            $values
        QUERY;
        if ($access == true) {
            try {
                $result = $shopify->grphQlClient->query(['query' => $query]);
                if ($result->getStatusCode() == 200) {
                    $datos = $result->getDecodedBody();
                    $collections = $datos['data']['collections'];
                    return [
                        'data' => ($collections['nodes']) ?? $collections['edges'], 
                        'pagination' => $datos['data']['collections']['pageInfo'], 
                        'error' => []];
                } else {
                    $c = $result->getStatusCode();
                    throw new \Exception("Request error.", $c);
                }
            } catch (ApiException $e) {
                $response = [
                    'error' => [
                        'message' => $e->getMessage(),
                        'line' => $e->getLine(),
                        'code' => $e->getCode(),
                        'file' => $e->getFile(),
                        'trace' => $e->getTraceAsString()
                    ],
                    'data' => $values
                ];
            }
        } else {
            $response = [
                'error' => [
                    'message' => "Access error.\nVerify access authorization and try again", 
                    'line' => 187,
                    'code'=> 401,
                    'file'=>__DIR__,
                    'trace'=>[]
                ],
                'data' => $values];
        }
        return $response;
    }
    private function _find (string $element,string $field,$value) {
        $params = array($field => $value);
        $result = $this->client->$element->get($params);
        if ($result->getStatusCode() == 200) {
            $datos = $result->getDecodedBody();
            return ['data' => $datos, 'error' => []];
        }
        return ['data' => [], 'error' => ['code'=>$result->getStatusCode(),'message'=> $result->getHeaders()]];
    }
    /* DELETE
    $variables = [
    "input" => [
            "id" => "gid://shopify/Collection/1009501285",
        ],
    ];
    $response = $client->query(["query" => $query, "variables" => $variables]);
    */
}
