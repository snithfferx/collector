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
use JetBrains\PhpStorm\Internal\ReturnTypeContract;

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
        $response = (!empty($values)) ? $this->getCollections($values) : $this->getCollections();
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
    public function next($value)
    {
        return $this->getNextPage($value);
    }
    /* #################### Protecteds #################### */
    /**
     * Función que devuelve la lista de colecciones o una colección a partir del ID de tienda o local
     * 
     * @param string|int $value Puede contener el nombre de la colección o el ID de la colección
     * @return array
     */
    protected function getCollections($value = 'all'): array
    {
        $viewData = [];
        if (is_numeric($value) && strlen($value) > 4) {
            $result = $this->getCollection($value);
            if (!empty($result['error'])) {
                $viewtype = "error_view";
                $viewCode = $result['error']['code'];
                $viewName = "collections/detail";
            } else {
                $viewtype = "view";
                $viewCode = null;
                $viewName = "collections/detail";
            }
        } else {
            if (is_numeric($value)) {
                $result = $this->getCommonName($value);
                if (!empty($result['error'])) {
                    $viewtype = "error_view";
                    $viewCode = $result['error']['code'];
                    $viewName = "collections/detail";
                } else {
                    $viewtype = "view";
                    $viewCode = null;
                    $viewName = "collections/detail";
                }
            } else {
                $result = ($value == "all") ? $this->getAllCollections() : $this->getAllCollections($value);
                if (!empty($result['error'])) {
                    $viewtype = "error_view";
                    $viewCode = $result['error']['code'];
                    $viewName = "collections/list";
                } else {
                    $viewtype = "view";
                    $viewCode = null;
                    $viewName = "collections/list";
                }
            }
        }
        $response = [
            'view' => [
                'type' => [
                    'name' => $viewtype,
                    'code' => $viewCode
                ],
                'name' => $viewName,
                'data' => $viewData
            ],
            'data' => (!empty($result['error'])) ? $result['error'] : $result['data']
        ];
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
    protected function getNextPage($value)
    {
        $result = $this->getCollectionsNextPage($value);
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
        $collections = $this->getAllCollections($value);
        //$commonNames = $this->model->localGet();
        $mixedCollections = array();
        if (empty($collections['error'])) {
            foreach ($collections['data']['list'] as $collection) {
                $commonName = $this->model->localGet($collection['id']);
                $mixedCollections['local'] = (empty($commonName['error'])) ? $commonName['data'] : null;
                $mixedCollections['store'] = $collection;
            }
        }
        return [
            'data' => $mixedCollections,
            'error' => $collections['error'] ?? array(),
            'next_page' => $collections['next'],
            'prev_page' => $collections['prev']
        ];
    }
    /**
     * Función que devuelve las colecciones creadas
     * @param int $limit
     * @return array
     */
    private function getAllCollections(int $limit = 100): array
    {
        $this->model->limit = $limit;
        $commonNames = $this->model->localGet();
        $times = 1;
        if (empty($commonNames['error'])) {
            foreach ($commonNames['data'] as $key => $commonName) {
                $this->model->id = $commonName['id'];
                $result = $this->model->storeGet();
                $commonNames['data'][$key]['store_id'] = ($result['data']['id']) ?? null;
                $commonNames['data'][$key]['store_title'] = ($result['data']['title']) ?? null;
                $commonNames['data'][$key]['store_handle'] = ($result['data']['handle']) ?? null;
                if ($times == 50) {
                    sleep(2);
                    $times = 1;
                } else {
                    $times++;
                }
            }
        }
        return $commonNames;
    }
    private function getCollectionList(array $fields)
    {
        if (!empty($fields)) $this->model->fields = $fields;
        return $this->model->_get();
    }
    private function getCollectionsNextPage($values)
    {
        return $this->model->storeGet($values);
    }
    private function getCollection (int $id) :array {
        $this->model->id = $id;
        return $this->model->storeGet();
    }
    private function getCommonName(int $id) :array {
        return $this->model->localGet($id);
    }
}
