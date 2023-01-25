<?php

namespace app\core\classes\context;

use app\core\helpers\ShopifyHelper;
use GuzzleHttp\Psr7\Query;

/**
 * Clase para las transacciones entre los modelos y la base de datos.
 * @author Jorge Echeverria <jecheverria@bytes4run.com>
 * @version 1.0.0
 */
class ExternalConnection
{
    private $scopes;
    private $url;
    private $session;
    private $shop;
    private $client;
    private $version;
    public function __construct()
    {
        $shopify = new ShopifyHelper;
        $this->scopes = "read_products,write_products,read_script_tags,write_script_tags";
        $this->url = "";
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
                if (is_null($campos)) {
                    $result = $this->shop->$elemento($valores)->get();
                } else {
                    $result = $this->shop->$elemento($valores)->get($campos);
                }
            } else {
                if (is_null($campos)) {
                    $result = $this->shop->$elemento->get();
                } else {
                    $result = $this->shop->$elemento->get($campos);
                }
            }
            $response = [
                'error' =>
                [],
                'data' =>
                $result
            ];
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
    protected function post($values): array
    {
        $elemento = $values['element'];
        $valores = $values['value'];
        try {
            if (!empty($valores)) {
                $response = $this->shop->$elemento($valores)->post();
            } else {
                $response = $this->shop->$elemento->post();
            }
        } catch (\Exception $e) {
            $response = $e;
        }
        return ['data' => $response];
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
            $serializedPageInfo = serialize($result->getPageInfo());
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
            $result = $shopify->grphQlClient->query(['query' => $query]);
        }
        if ($result->getStatusCode() == 200) {
            //$serializedPageInfo = serialize($result->getPageInfo());
            $datos = $result->getDecodedBody();
            /* echo "<pre>";
            var_dump($datos);
            echo "</pre>";
            exit; */
            return ['data' => $datos['data']['collections']['nodes'], 'pagination' => $datos['data']['collections']['pageInfo'], 'error' => []];
        }
        return ['data' => [], 'error' => $result->getStatusCode()];
    }
}
