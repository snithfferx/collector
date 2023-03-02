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
    public function delete($values)
    {
        # code...
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
        $rules = implode("\n", ['relation', 'column', 'condition']);
        $meta_nodes = implode("\n", ['id', 'type', 'createdAt']);
        if (!is_null($fields) && !empty($fields)) {
            $glued = (!is_string($fields)) ? implode("\n", $fields) : $fields;
        }
        $request = 'query {' . $element;
        if (isset($values['query']['id']) && !empty($values['query']['id'])) {
            $id = $values['query']['id'];
            $request .= '(id:"' . $id . '",first:' . $limit . ') { edges { node {' . $glued;
        } elseif (isset($values['query']['title']) && !empty($values['query']['title'])) {
            $title = $values['query']['title'];
            $request .= '(title:"' . $title . '",first:' . $limit . ') { edges { node {' . $glued;
        } else {
            if (isset($values['query']['page']) && !empty($values['query']['page'])) {
                //$cursor = $values['query']['page']['cursor'];
                //$page = $values['query']['page']['info'];
                $request = 'query {' . $pluralized . '(';
                /* switch ($cursor) {
                    case "next" :
                        $request .= 'first:' . $limit . ', after:"' . $page;
                        break;
                    case "prev" :
                        $request .= 'last:' . $limit . ', before:"' . $page;
                        break;
                } */
                if ($values['query']['page']['hasNextPage'] == true) {
                    $request .= 'first:' . $limit . ', after:"' . $values['query']['page']['endCursor'];
                }
                $request .= '") { nodes {' . $glued;
            } else {
                $request = 'query {' . $pluralized . '(first:' . $limit . ') { nodes {' . $glued;
            }
        }
        $request .= ' ruleSet { rules { ' . $rules . ' } }';
        $request .= ' metafields(first: 10) { nodes { ' . $meta_nodes . ' } } seo { title }';
        $paginado = ['hasPreviousPage', 'hasNextPage', 'startCursor', 'endCursor'];
        $pagesinfo = implode("\n", $paginado);
        $request .= (isset($values['query']['id']) || isset($values['query']['title'])) ? '} cursor } pageInfo { ' . $pagesinfo : ' } pageInfo { ' . $pagesinfo;
        $request .= '} } }';
        return $this->graphQLRequest($request);
    }
    /* DELETE
    $input = {
    "input": {
        "id": "gid://shopify/Collection/1009501285"
        }
    }
    <<<QUERY
        mutation collectionDelete($input: CollectionDeleteInput!) {
            collectionDelete(input: $input) {
            deletedCollectionId
            shop {
                id
                name
            }
            userErrors {
                field
                message
            }
            }
        }
        QUERY;
    */
}
