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
    public function __construct() {
        $this->external = new ExternalContext;
        $this->local = new ContextClass;
    }
    public function storeGet ($values) :array {
        if (is_string($values) && $values == "all") {
            $response = $this->getCollections();
        } else {
            $response = $this->getCollections($values);
        }
        return $response;
    }
    public function localGet($values): array
    {
        return $this->getLocalColections($values);
    }

    private function getCollections(array $parameters = []) :array {
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
    }
    private function getLocalColections (string $value) :array {
        $query = [
            'fields'=>[
                'nombre_comun'=>[
                    'id_nombre_comun=id', 'nombre_comun=name', 'posicion=possition', 'fecha_creacion=date', 'activo=active'
                ],
                'tipo_categoria'=>['tipo_categoria=categoria'],
                'tipo_producto'=>['tipo_producto=tipo']
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
            $query['params'] = "id_nombre_comun=:$value";
        } else {
            $query['params'] = "nombre_comun=:$value";
        }
        return $this->local->select("nombre_comun",$query);
    }
}
