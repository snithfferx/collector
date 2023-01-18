<?php
/**
 * Clase para las transacciones entre los modelos y la base de datos.
 * @author Jorge Echeverria <jecheverria@bytes4run.com>
 * @version 1.0.0
 */
namespace app\modules\collections\models;
use app\core\classes\context\ContextClass;
use app\core\classes\context\ExternalContext;

class CollectionModel
{
    private $external;
    private $local;
    public $fields;
    public $limit;
    public $id;
    public $title;
    public $categoria;
    public $tipo;
    public $activo;
    private $table;
    private $element;
    public function __construct() {
        $this->external = new ExternalContext;
        $this->local = new ContextClass;
        $this->table = 'nombre_comun';
        $this->element = 'collections';
    }
    public function storeGet () :array {
        $response = $this->getStoreCollections();
        return $response;
    }
    public function localGet($value = ''): array
    {
        return $this->getLocalColections($value);
    }
    public function getNextPage()
    {
        return $this->getStoreCollections();
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
    private function getLocalColections ($value) :array {
        $query = [
            'fields'=>[
                'nombre_comun'=>[
                    'id_nombre_comun=id', 'nombre_comun=name', 'posicion=possition', 'fecha_creacion=date', 
                    'activo=active','id_tienda=store_id','handle','terminos_de_busqueda=keywords'
                ],
                'tipo_producto'=>['tipo_producto=sub_category'],
                'tipo_categoria'=>['tipo_categoria=category']
            ],
            'joins'=>[
                [
                    'type'=>"INNER",
                    'table'=> "tipo_producto",
                    'filter'=> "id_tipo_producto",
                    'compare_table'=>"nombre_comun",
                    'compare_filter'=> "id_tipo_producto"
                ],
                [
                    'type'=>"INNER",
                    'table' => "tipo_categoria",
                    'filter' => "id_tipo_categoria",
                    'compare_table' => "tipo_producto",
                    'compare_filter' => "id_tipo_categoria"
                ]
            ]];
        if ($value == "all") {
            $query['params'] ="";
        } elseif (is_numeric($value)) {
            $query['params'] = "nombre_comun.id_nombre_comun=:$value";
        } elseif (is_array($value)) {
            $top = $value['last'] + $this->limit;
            $query['params'] = "nombre_comun.id_nombre_comun>:$value[last],nombre_comun.id_nombre_comun<=:$top";
        } elseif (!empty($value) || !is_null($value)) {
                $query['params'] = "nombre_comun.nombre_comun=:$value";
        } else {
            if (!empty($this->title)) {
                $query['params'] = "nombre_comun.nombre_comun~:$this->title";
            } else {
                $response = ['data' => [], 'error' => ['message' => "Too few parameters", 'code' => 400]];
            }
        }
        if (!isset($response)) $response = $this->local->select("nombre_comun",$query,$this->limit);
        return $response;
    }
    private function getStoreCollections () {
        $request['element'] = $this->element;
        $request['query'] = array();
        if (!empty($this->id)) $request['query']['id'] = $this->id;
        $request['query']['fields'] = (!empty($this->fields)) ? $this->fields : ['id', 'handle', 'title'];
        $request['query']['limit'] = $this->limit;
        return $this->external->getShopifyResponse($request);
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
