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
        return $this->createViewData('collections/create');
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
     * @return array
     */
    public function update($values)
    {
        return $this->createViewData('collections/update');
    }
    /**
     * Summary of delete
     * @param mixed $values
     * @return array<array>
     */
    public function delete($values)
    {
        return $this->createViewData('collections/delete');
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
    public function previous($value)
    {
        return $this->getPreviousPage($value);
    }
    public function lista()
    {
        return (!empty($values)) ? $this->getCollections($values) : $this->getCollections();
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
        $viewtype = "template";
        $viewCode = null;
        $viewName = "collections/list";
        if (is_numeric($value) && strlen($value) > 4) {
            $result = $this->getCollection($value);
            if (!empty($result['error'])) {
                $viewtype = "error_view";
                $viewCode = $result['error']['code'];
                $viewName = "collections/detail";
            } else {
                $viewtype = "template";
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
                $result = ($value == "all") ? $this->getCommonNames() : $this->getCommonNames($value);
                if (!empty($result['error'])) {
                    $viewtype = "error_view";
                    $viewCode = $result['error']['code'];
                }
                //return $this->getCommonNames();
            }
        }
        $breadcrumbs = $this->createBreadcrumbs(['view' => $viewName, 'method' => 'read', 'params' => $value]);
        $datos = (!empty($result['error'])) ? $result['error'] : $result['data'];
        $response = $this->createViewData($viewName, $datos, $breadcrumbs, $viewtype, $viewCode, $viewData);
        return $response;
    }
    protected function getCompareData($value = "all"): array
    {
        $viewData = [];
        $viewtype = "view";
        $viewCode = null;
        $viewName = "collections/compareList";
        $collections = $this->getCompareCollections($value);
        if (!empty($collections['error'])) {
            $viewtype = "error_view";
            $viewCode = $collections['error']['code'];
        }
        $breadcrumbs = $this->createBreadcrumbs(['view' => $viewName, 'method' => 'compare', 'params' => $value]);
        $response = $this->createViewData($viewName, $collections, $breadcrumbs, $viewtype, $viewCode, $viewData);
        return $response;
    }
    protected function getNextPage($values)
    {
        if (is_array($values)) {
            if ($values['view'] == "compare") {
                $result = $this->getCollectionsPage($values['page']);
            } else {
                $result = $this->getCommonNames($values);
            }
        } else {
            ## Error ###
        }
    }
    protected function getPreviousPage($values): array
    {
        if (is_array($values)) {
            if ($values['view'] == "compare") {
                $result = $this->getCollectionsPage($values['page']);
            } else {
                $result = $this->getCommonNames($values['page']);
            }
        } else {
            ## Error ###
        }
        return array();
    }


    private function getCompareCollections($value = 5)
    {
        if (is_numeric($value)) {
            if ($value <= 250) {
                $limit = $value;
            } else {
                $this->model->id = $value;
            }
        } elseif (is_array($value)) {
            $limit = $value['limit'];
            $this->model->id = $value['sort'];
        } else {
            $limit = 100;
            $this->model->id = $value;
        }
        $this->model->limit = $limit;
        $collections = $this->model->storeGet();
        $times = 1;
        $count = $this->model->calcular('store');
        $max = ceil(1 / $limit);
        $mixedCollections = array();
        $error = array();
        if (empty($collections['error'])) {
            $mixedCollections['prev_page'] = "/collections/previous";
            $mixedCollections['next_page'] = "/collections/next";
            foreach ($collections['data'] as $collection) {
                $mixedCollections['store'] = $collection;
                $this->model->title = $collection['title'];
                $commonName = $this->model->localGet();
                $mixedCollections['local'] = (empty($commonName['error'])) ? $commonName['data'] : null;
            }
            $mixedCollections['prev_page'] .= $this->makeURL($collections['prev']);
            $mixedCollections['next_page'] .= $this->makeURL($collections['next']);
            $mixedCollections['max_page'] = $max;
        } else {
            $error = $collections['error'];
        }
        return [
            'data' => $mixedCollections,
            'error' => $error
        ];
    }
    /**
     * Función que devuelve las colecciones creadas
     * @param int $limit
     * @return array
     */
    private function getCommonNames($value = 50): array
    {
        if (is_array($value)) {
            $this->model->page = ($value['page']) ?? '';
            $this->model->id = ($value['page']) ?? '';
            $this->model->limit = $value['limit'];
        }
        $this->model->limit = $value;
        $collections = $this->model->storeGet();
        $limit = ($value['limit']) ?? $value;
        $mixedcommonNames = [];
        if (empty($collections['error'])) {
            $count = $this->model->calcular('store');
            $max = ceil($count['data']['collections']['count'] / $limit);
            $mixedcommonNames['prev_page'] = $this->makeURL($collections['data']['prev']); /* != "N;") ? $collections['prev'] : 0;*/
            $mixedcommonNames['next_page'] = $this->makeURL($collections['data']['next']); /* != "N;") ? $collections['next'] : 0;*/
            //echo $mixedcommonNames['next_page'];
            $mixedcommonNames['max_page'] = $max;
            foreach ($collections['data']['collections'] as $key => $collection) {
                $this->model->title = $collection['title'];
                $result = $this->model->localGet();
                $mixedcommonNames['data'][$key] = [
                    'id' => ($result['data'][0]['id']) ?? null,
                    'name' => ($result['data'][0]['name']) ?? null,
                    'possition' => ($result['data'][0]['possition']) ?? null,
                    'date' => ($result['data'][0]['date']) ?? null,
                    'active' => ($result['data'][0]['active']) ?? null,
                    'sub_category' => ($result['data'][0]['sub_category']) ?? null,
                    'category' => ($result['data'][0]['category']) ?? null,
                    'handle' => ($result['data'][0]['handle']) ?? null,
                    'keywords' => ($result['data'][0]['keywords']) ?? null,
                    'store_id' => ($collection['id']) ?? null,
                    'store_title' => ($collection['title']) ?? null,
                    'store_handle' => ($collection['handle']) ?? null
                ];
            }
        }
        return $mixedcommonNames;
    }
    private function getCollectionsPage($values)
    {
        $this->model->page = $values;
        return $this->model->storeGet();
    }
    private function getCollection(int $id): array
    {
        $response = [];
        $this->model->id = $id;
        $result = $this->model->storeGet();
        if (!$result['error']) {
            foreach ($result['data'] as $k => $collection) {
                $this->model->title = $collection['title'];
                $coleccion = $this->model->localGet();
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
        $this->model->id = $id;
        $result = $this->model->localGet();
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
    private function makeURL ($datos) {
        $urlString = "";
        if (is_array($datos)) {
            $x = 0;
            $s = sizeof($datos);
            foreach ($datos as $k => $v) {
                if ($x == 0) $urlString .= "?";
                $urlString .= $k . "=" . $v;
                if ($x < $s) $urlString .= "&";
                $x++;
            }
        }
        return $urlString;
    }
}
