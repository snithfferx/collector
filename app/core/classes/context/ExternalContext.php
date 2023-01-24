<?php

namespace app\core\classes\context;

/**
 * Clase para las transacciones entre los modelos y la base de datos.
 * @author Jorge Echeverria <jecheverria@bytes4run.com>
 * @version 1.0.0
 */
class ExternalContext extends ExternalConnection
{
    /**
     * Realiza una solicitud a un elemento en la tienda
     * @param string $type Tipo de petición
     * @param string $element Elemento al que se le realiza la petición
     * @param array|null $values Valores que conficionan el elemento
     * @param array|null $fields Campos que se esperan recibir
     * @return array
     */
    public function makeRequest(string $type, string $element, array $values = null, array $fields = null): array
    {
        return $this->getHttpResponse([
            'type' => $type,
            'request' => [
                'element' => $element,
                'value' => $values,
                'fields' => $fields
            ]
        ]);
        /*['error'=>['code'=>404,'message' => "No hay tabla para consultar."], 'data' => array()]; */
    }
    public function getShopifyResponse($values)
    {
        /* echo "<pre>";
        var_dump();
        echo "</pre>";
        exit; */
        return $this->getResponse($values);
    }
    public function getShopifyGuzResponse($values)
    {
        echo "<pre>";
        var_dump($this->getGuzResponse($values));
        echo "</pre>";
        exit;
    }
    public function graphQL($values)
    {
        return $this->_graphQLRequest($values);
    }

    private function getHttpResponse(array $values): array
    {
        if ($values['type'] == "get") {
            $response = $this->_get($values['request']);
        } elseif ($values['type'] == "post") {
            $response = $this->_get($values);
        } elseif ($values['type'] == "put") {
            $response = $this->_get($values);
        } else {
            $response = ['type' => "error", 'data' => ['message' => "Method not supported"]];
        }
        return $response;
    }
    private function getResponse($value)
    {
        return $this->getHttp($value);
    }
    private function getGuzResponse($values)
    {
        return $this->guzzleConnect($values);
    }
    private function _graphQLRequest($values)
    {
        $limit = $values['query']['limit'];
        $fields = ($values['query']['fields']) ?? null;
        $element = $values['element'];
        $pluralized = $element . 's';
        $glued = (!is_null($fields)) ? implode("\n", $fields) : '';
        if (isset($values['query']['id']) && !empty($values['query']['id'])) {
            $id = $values['query']['id'];
            $request = 'query {' . $element . '(id:"' . $id . '",first:' . $limit . ') {edges {node {' . $glued . '}}}}';
        } else {
            $pages = implode("\n", ['hasPreviousPage', 'hasNextPage', 'startCursor', 'endCursor']);
            $request = 'query {' . $pluralized . '(first:' . $limit . ') {nodes {' . $glued . '}pageInfo {' . $pages . '}}}';
        }
        return $this->graphQLRequest($request);
    }
}
