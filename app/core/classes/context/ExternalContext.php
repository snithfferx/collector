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
        /*   collections(first: 10) {
                nodes {
                    handle
                    id
                    title
                    sortOrder
                    productsCount
                    ruleSet {
                        rules {
                            condition
                            column
                            relation
                        }
                    }
                    metafields(first: 10) {
                        nodes {
                            id
                            type
                            createdAt
                 {        }
                    }
                    seo {
                        title
                    }
                }
                pageInfo {
                    startCursor
                    hasPreviousPage
                    hasNextPage
                    endCursor
                }
            } */
        $rules = implode("\n", ['relation', 'column', 'condition']);
        $meta_nodes = implode("\n", ['id', 'type', 'createdAt']);
        $glued = (!is_null($fields) && !empty($fields)) ? implode("\n", $fields) : '';
        $request = 'query {' . $element;
        /* 'metafields'=>['first'=>10,['nodes'=>['id','type']]] */
        if (isset($values['query']['id']) && !empty($values['query']['id'])) {
            $id = $values['query']['id'];
            $request .= '(id:"' . $id . '",first:' . $limit . ') { edges { node {' . $glued;
        } elseif (isset($values['query']['title']) && !empty($values['query']['title'])) {
            $title = $values['query']['title'];
            $request .= '(title:"' . $title . '",first:' . $limit . ') { edges { node {' . $glued;
        } else {
            //$request .= 'metafields(first: ' . $limit . ') {nodes {type}}';
            $request = 'query {' . $pluralized;
            $request .= '(first:' . $limit . ') { nodes {' . $glued;
        }
        $request .= 'ruleSet { rules { ' . $rules;
        $request .= ' } } metafields(first: 10) { nodes { ' . $meta_nodes . ' } } seo { title }';
        if (isset($values['query']['page']) && !empty($values['query']['page'])) {
            $paginado = [
                'startCursor' => $values['query']['page']['start_cursor'],
                'endCursor' => $values['query']['page']['end_cursor']
            ];
        } else {
            $paginado = ['hasPreviousPage', 'hasNextPage', 'startCursor', 'endCursor'];
        }
        $pagesinfo = implode("\n", $paginado);
        $request .= (isset($values['query']['id']) || isset($values['query']['title'])) ? '} cursor } pageInfo {' . $pagesinfo : $request .= '} pageInfo {' . $pagesinfo;
        $request .= '} } }';
        return $this->graphQLRequest($request);
    }
}
