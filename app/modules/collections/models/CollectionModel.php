<?php

/**
 * Clase para las transacciones entre los modelos y la base de datos.
 * @author Jorge Echeverria <jecheverria@bytes4run.com>
 * @version 1.0.0
 */

namespace app\modules\collections\models;

use app\core\classes\context\ContextClass;
use app\core\classes\context\ExternalContext;

class CollectionModel extends ContextClass
{
    private $external;
    public $fields;
    public $limit;
    public $id = null;
    public $title = null;
    public $categoria = null;
    public $tipo = null;
    public $activo;
    public $page = null;
    public $cursor = null;
    private $element;
    public function __construct()
    {
        $this->external = new ExternalContext;
        $this->element = 'collection';
    }
    public function storeGet(): array
    {
        //$response = $this->getCollections();
        $response = $this->grafkuel();
        return $response;
    }
    public function localGet(): array
    {
        return $this->getCommonNames();
    }
    public function calcular($ubicacion, $calculo = "count")
    {
        if ($ubicacion == "store") {
            return $this->getCountCollection();
        } else {
            return $this->getLocalCalc($calculo);
        }
    }
    public function getPage()
    {
        return $this->getCollectionsPage();
    }
    public function hasMetafields() {
        return $this->getMetafields();
    }

    protected function getMetafields () {
        return $this->getMetadata();
    }
    /* private function getCollections(array $parameters = []) :array {
        if (!empty($parameters['value'])) {
            if (!empty($parameters['fields'])) {
                $result = $this->external->makeRequest("get", "CustomCollection", $parameters['value'], $parameters['fields']);
            } else {
                $fields = ['id', 'handle', 'title'];
                $result = $this->external->makeRequest("get", "CustomCollection", $parameters['value'], $fields);
            }
        } else {
            $result = $this->external->makeRequest("get", "CustomCollection");
        }
        return $result;
    } */
    private function getCommonNames(): array
    {
        $query = [
            'fields' => [
                'nombre_comun' => [
                    'id_nombre_comun=id', 'nombre_comun=name', 'posicion=possition', 'fecha_creacion=date',
                    'activo=active', 'id_tienda=store_id', 'handle', 'terminos_de_busqueda=keywords'
                ],
                'tipo_producto' => ['tipo_producto=sub_category'],
                'tipo_categoria' => ['tipo_categoria=category']
            ],
            'joins' => [
                [
                    'type' => "INNER",
                    'table' => "tipo_producto",
                    'filter' => "id_tipo_producto",
                    'compare_table' => "nombre_comun",
                    'compare_filter' => "id_tipo_producto"
                ],
                [
                    'type' => "INNER",
                    'table' => "tipo_categoria",
                    'filter' => "id_tipo_categoria",
                    'compare_table' => "tipo_producto",
                    'compare_filter' => "id_tipo_categoria"
                ]
            ]
        ];
        $query['params'] = "";
        /* Pedir nombre común por ID */
        if (!is_null($this->id)) $query['params'] = "nombre_comun.id_nombre_comun=:$this->id";
        if (!is_null($this->id) && (!is_null($this->title) || !is_null($this->categoria) || !is_null($this->tipo))) $query['params'] .= ",";
        /* Pedir Nombre común por Nombre */
        if (!is_null($this->title)) $query['params'] = "nombre_comun.nombre_comun~:$this->title";
        /* Pedir Nombre común por Categoría */
        if (!is_null($this->categoria)) $query['params'] = "tipo_categoria.id_tipo_categoria=:$this->categoria";
        /* Pedir Nombre común por Tipo */
        if (!is_null($this->tipo)) $query['params'] = "nombre_comun.id_tipo_producto=:$this->tipo";
        /* if (!is_null($this->page)) {
            $top = $this->id + $this->limit;
            $query['params'] = "nombre_comun.id_nombre_comun>:$this->page,nombre_comun.id_nombre_comun<=:$top";
        } */
        if (!isset($response)) $response = $this->select("nombre_comun", $query, $this->limit);
        return $response;
    }
    private function getCollections()
    {
        $request['element'] = $this->element;
        $request['query'] = array();
        if (!empty($this->id)) $request['query']['id'] = $this->id;
        if (!empty($this->page)) $request['query']['page_info'] = $this->page;
        $request['query']['fields'] = (!empty($this->fields)) ? $this->fields : [];
        $request['query']['limit'] = $this->limit;
        return $this->external->getShopifyResponse($request);
    }
    private function getCollectionsPage()
    {
        $request['element'] = $this->element;
        if (!empty($this->page)) $request['query']['page']['info'] = $this->page;
        if (!empty($this->cursor)) $request['query'][ 'page']['cursor'] = $this->cursor;
        $request['query']['fields'] = (!empty($this->fields)) ? $this->fields : ['id', 'title', 'handle', 'productsCount'];
        $request['query']['limit'] = $this->limit;
        //return $this->external->getShopifyResponse($request);
        return $this->external->graphQL($request);
    }
    private function getCountCollection()
    {
        $request['element'] = $this->element . "s";
        $request['query'] = [
            'id' => "count",
            'fields' => (!empty($this->fields)) ? $this->fields : []
        ];
        return $this->external->getShopifyResponse($request);
    }
    private function getLocalCalc($calculo)
    {
        return $this->calculate("nombre_comun", $calculo, "id_nombre_comun");
    }
    private function grafkuel()
    {
        $request['element'] = $this->element;
        $request['query']['limit'] = $this->limit;
        if (empty($this->fields)) {
            $request['query']['fields'] = (!empty($this->fields)) ? $this->fields : ['id','title','handle','productsCount'];
        }
        if (!empty($this->id)) $request['query']['id'] = $this->id;
        if (!empty($this->title)) $request['query']['title'] = $this->title;
        if (!empty($this->page)) $request['query']['page'] = $this->page;
        return $this->external->graphQL($request);
    }
    private function getMetadata () {
        return $this->select("metadatos", [
            'fields' => [
                'metadatos' => ['id_metadato=id', 'activo']
            ],
            'joins' => [
                [
                    'type' => "INNER",
                    'table' => "tipo_producto",
                    'filter' => "id_tipo_producto",
                    'compare_table' => "metadatos",
                    'compare_filter' => "id_tipo_producto"
                ]
            ],
            'params' => "tipo_producto.tipo_producto=:$this->tipo,metadatos.id_nombre_comun=:$this->id,metadatos.activo=:1;metadatos.id_metadato=:1220;metadatos.id_metadato=:1221"
        ]);
    }

    /* private function getGuz()
    {
        $product_ids = [];
        $nextPageToken = null;
        if (!empty($this->fields)) {
            $request = [
                'method' => "get",
                'element' => 'collections.json?limit=250&page_info=' . $nextPageToken,
                'query' => $this->fields
            ];
        } else {
            $request = [
                'method' => "get",
                'element' => 'collections.json?limit=250&page_info=' . $nextPageToken
            ];
        }
        do {
            $response = $this->external->getShopifyGuzResponse($request);
            foreach ($response['resource'] as $product) {
                array_push($product_ids, $product['id']);
            }
            $nextPageToken = $response['next']['page_token'] ?? null;
        } while ($nextPageToken != null);
    } */
}
