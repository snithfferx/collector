<?php

/**
 * Controlador de colecciones
 * @author Jorge Echeverria <jecheverria@bytes4run.com>
 * @category Controller
 * @version 1.2.0
 * @package app\modules\collections\controllers
 * 12-01-2023
 */

namespace app\modules\collections\controllers;

use app\core\classes\ControllerClass;
use app\modules\collections\models\CollectionModel;

/**
 * Collections Controller
 * @extends ControllerClass
 * @author JEcheverria <jecheverria@piensads.com>
 * @version 1.0.0
 */
class CollectionsController extends ControllerClass
{
    /**
     * Modelo Collections
     * @var object $model Contiene el modelo de las colecciones
     */
    private $model;
    public function __construct()
    {
        $this->model = new CollectionModel;
    }
    /**
     * Función que crea una colección en la base de datos y en la tienda
     * 
     * Devuelve una vista de la colección
     * 
     * @param mixed $values Contiene la información a ser enviada a la base de datos
     * @return array
     */
    public function create($values)
    {
        return true;
    }
    /**
     * Función que devuelve la lista de colecciones creadas en la base de datos y la tienda
     * 
     * @param mixed $values
     * @return array
     */
    public function read($values)
    {
        //$response =  (!empty($values)) ? $this->getCollections($values) : $this->getCollections();
        $response = $this->getAllCollections();
        //$response = $this->getAll();
        return $response;
    }
    /**
     * Función que edita la información de una colección.
     * @param mixed $values
     * @return bool
     */
    public function update($values)
    {
        return true;
    }
    public function delete($values)
    {
        return true;
    }
    public function compare($value)
    {
        return (!is_null($value)) ? $this->getCompareData($value) : $this->getCompareData();
    }

    protected function getCollections($value = "all")
    {
        $result = $this->getStoreCollections($value);
        if (!empty($result['error'])) {
            $response = [
                'view' => [
                    'type' => [
                        'name' => "error_view",
                        'code' => $result['error']['code']
                    ],
                    'name' => "collections/list",
                    'data' => []
                ],
                'data' => $result
            ];
        } else {
            $response = [
                'view' => [
                    'type' => [
                        'name' => "view",
                        'code' => ""
                    ],
                    'name' => "collections/list",
                    'data' => []
                ],
                'data' => $result['data']
            ];
        }
        return $response;
    }
    protected function getCompareData($value = "all"): array
    {
        $collections = $this->getCompareCollections($value);
        if (!empty($collections['error'])) {
            $response = [
                'view' => [
                    'type' => [
                        'name' => "error_view",
                        'code' => $collections['error']['code']
                    ],
                    'name' => "collections/list",
                    'data' => []
                ],
                'data' => $collections
            ];
        } else {
            $response = [
                'view' => [
                    'type' => [
                        'name' => "view",
                        'code' => ""
                    ],
                    'name' => "collections/list",
                    'data' => []
                ],
                'data' => $collections['data']
            ];
        }
        return $response;
    }
    protected function getAll()
    {
        return $this->getCollectionList([]);
    }

    private function getStoreCollections($value = "all"): array
    {
        if ($value == "all") {
            $result = $this->model->storeGet($value);
        } elseif (is_numeric($value)) {
            $result = $this->model->storeGet([
                'value' => ['id' => $value]
            ]);
        } else {
            $result = $this->model->storeGet([
                'value' => ['title' => $value, 'handle' => $value]
            ]);
        }
        return $result;
    }
    private function getCompareCollections($value)
    {
        $storeCollections = $this->getStoreCollections($value);
        $mixedCollections = array();
        if (empty($storeCollections['error'])) {
            foreach ($storeCollections['data'] as $store) {
                $localCollection = $this->model->localGet($store['id']);
                if (empty($localCollection['error'])) {
                    $mixedCollections[] = [
                        'local' => $localCollection['data'],
                        'store' => $store
                    ];
                } else {
                    $mixedCollections[] = [
                        'local' => null,
                        'store' => $store
                    ];
                }
            }
        }
        return ['data' => $mixedCollections, 'error' => $storeCollections['error'] ?? array()];
    }
    private function getAllCollections()
    {
        return $this->model->shopify("all");
    }
    private function getCollectionList(array $fields)
    {
        if (!empty($fields)) $this->model->fields = $fields;
        return $this->model->_get();
    }
}
