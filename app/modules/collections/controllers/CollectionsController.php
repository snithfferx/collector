<?php

/**
 * Controlador de colecciones
 * @author Jorge Echeverria <jecheverria@bytes4run.com>
 * @category Controller
 * @version 1.6.4
 * @package app\modules\collections\controllers
 * 18-01-2023
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
     * @version 1.0.0
     * 18/01/23
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
    /**
     * Función que devuelve la lista de colecciones en comparativa
     * Puede recibir un nombre de colección o nombre común, limite de datos a representar [max:250 | default:100]
     * 
     * @param string $value
     * @return array
     * @version 1.0.0
     * 18/01/23
     */
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
        $viewtype = "view";
        $viewCode = null;
        $viewName = "collections/list";
        $breadcrumbs = $this->breadcrumbs($value);
        if (is_numeric($value) && strlen($value) > 4) {
            $result = $this->getCollection($value);
            if (!empty($result['error'])) {
                $viewtype = "error_view";
                $viewCode = $result['error']['code'];
                $viewName = "collections/detail";
            } else {
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
                    $viewName = "collections/detail";
                }
            } else {
                $result = ($value == "all") ? $this->getAllCollections() : $this->getAllCollections($value);
                if (!empty($result['error'])) {
                    $viewtype = "error_view";
                    $viewCode = $result['error']['code'];
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
            'data' => [
                'breadcrumbs'=>$breadcrumbs,
                'datos'=>(!empty($result['error'])) ? $result['error'] : $result['data']
            ]
        ];
        return $response;
    }
    protected function getCompareData($value = "all"): array
    {
        $viewData = [];
        $viewtype = "view";
        $viewCode = null;
        $viewName = "collections/compareList";
        $breadcrumbs = $this->breadcrumbs($value);
        $collections = $this->getCompareCollections($value);
        if (!empty($collections['error'])) {
            $viewtype = "error_view";
            $viewCode = $collections['error']['code'];
            $data = [
                'breadcrumbs' => $breadcrumbs,
                'datos' => $collections['data']
            ];
        } else {
            $data = [
                'breadcrumbs' => $breadcrumbs,
                'datos'=> $collections['data']
            ];
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
            'data' => $data
        ];
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
    protected function breadcrumbs ($value) {
        return [
            'main'=>'Colecciones',
            'routes'=>[
                ['text'=>'colecciones', 'controller'=>'collections', 'method'=>'read', 'param'=>[]],
                ['text' => 'colecciones', 'controller' => 'collections', 'method' => 'compare', 'param' => $value]
            ]
        ];
    }

    /* private function getStoreCollections($value = "all"): array
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
    } */
    private function getCompareCollections($value)
    {
        $mixedCollections = array();
        $error = array();
        if (!is_string($value)) {
            $collections = $this->getAllCollections($value);
            if (empty($collections['error'])) {
                foreach ($collections['data'] as $collection) {
                    $mixedCollections[] = [
                        'local' => [
                            'id' => $collection['id'],
                            'name' => $collection['name'],
                            'possition' => $collection['possition'],
                            'date' => $collection['date'],
                            'active' => $collection['active'],
                            'category' => $collection['category'],
                            'sub_category' => $collection['sub_category']
                        ],
                        'store' => [
                            'id' => $collection['store_id'],
                            'title' => $collection['store_title'],
                            'handle' => $collection['store_handle']
                        ]
                    ];
                }
            } else {
                $error = $collections['error'];
            }
        } else {
            $this->model->title = $value;
            $local = $this->model->localGet();
            $store = $this->model->storeGet();
            $localLenght = count($local['data']);
            $storeLenght = count($store['data']);
            if ($localLenght < $storeLenght) {
                for ($a = 0; $a < $storeLenght; $a++) {
                    $mixedCollections[$a] = [
                        'local' => ($local['data'][$a]) ?? null,
                        'store' => $store['data'][$a]
                    ];
                }
            } else {
                for ($a = 0; $a < $localLenght; $a++) {
                    $mixedCollections[$a] = [
                        'local' => $local['data'][$a],
                        'store' => ($store['data'][$a]) ?? null
                    ];
                }
            }
        }
        return [
            'data' => $mixedCollections,
            'error' => $error
        ];
    }
    /* ,
            'next_page' => $collections['next'],
            'prev_page' => $collections['prev'] */
    /**
     * Función que devuelve las colecciones creadas
     * @param int $limit
     * @return array
     */
    private function getAllCollections(int $limit = 100): array
    {
        $this->model->limit = $limit;
        $commonNames = $this->model->localGet('all');
        $times = 1;
        $prev = 0;
        $next = 0;
        if (empty($commonNames['error'])) {
            foreach ($commonNames['data'] as $key => $commonName) {
                if ($key == 0 && $commonName['id'] > 1) $prev = $commonName['id'];
                if ($key <= ($limit - 1))  $next = $commonName['id'];
                $this->model->id = $commonName['store_id'];
                $result = $this->model->storeGet();
                $commonNames['data'][$key]['local'] = $commonName;
                $commonNames['data'][$key]['store'] = [
                    'store_id' => ($result['data']['collections']['id']) ?? null,
                    'store_title' => ($result['data']['collections']['title']) ?? null,
                    'store_handle' => ($result['data']['collections']['handle']) ?? null
                ];
                if ($times == 50) {
                    sleep(2);
                    $times = 1;
                } else {
                    $times++;
                }
            }
            $commonNames['data']['prev_page'] = $prev;
            $commonNames['data']['next_page'] = $next;
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
    private function getCollection(int $id): array
    {
        $response = [];
        $this->model->id = $id;
        $result = $this->model->storeGet();
        if (!$result['error']) {
            foreach ($result['data'] as $k => $collection) {
                $coleccion = $this->model->localGet($collection['id']);
                $response['data'][$k] = [
                    'store' => $collection,
                    'local' => (empty($coleccion['error'])) ? $coleccion['data'] : null
                ];
            }
        }
        return (!empty($response)) ?? $result;
    }
    private function getCommonName(int $id): array
    {
        $result = $this->model->localGet($id);
        $response = [];
        if (!$result['error']) {
            foreach ($result['data'] as $k => $coleccion) {
                $this->model->id = $coleccion['id'];
                $collection = $this->model->storeGet();
                $response['data'][$k] = [
                    'local' => $coleccion,
                    'store' => (!$collection['error']) ? $collection['data'] : ['id' => null, 'title' => null, 'handle' => null]
                ];
            }
        }
        return (!empty($response)) ?? $result;
    }
}
