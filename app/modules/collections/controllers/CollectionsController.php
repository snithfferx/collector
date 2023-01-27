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
        if (!empty($values)) {
            return $this->createCollection($values);
        }
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
    public function sync ($value) {

    }
    /* #################### Protecteds #################### */
    /**
     * Función que devuelve la lista de colecciones o una colección a partir del ID de tienda o local
     * 
     * @param string|int $value Puede contener el nombre de la colección o el ID de la colección
     * @return array
     */
    protected function getCollections($value = ''): array
    {
        $viewData = [];
        $viewtype = "template";
        $viewCode = null;
        $viewName = "collections/list";
        $lista = true;
        $viewOrigin = "read";
        if (!empty($value)) {
            if (is_numeric($value) && strlen($value) > 6) {
                $result = $this->collection($value);
            } elseif (is_numeric($value) && strlen($value) <= 6) {
                $result = $this->commonName($value);
            } else {
                $result = $this->commonNames($value);
            }
        } else {
            $result = $this->commonNames($value);
        }
        if (!$lista) {
            if (!empty($result['error'])) {
                $viewtype = "layout";
                $viewCode = $result['error']['code'];
                $viewName = "_shared/_error";
            } else {
                $viewtype = "template";
                $viewName = "collections/detail";
            }
            $viewOrigin = "detail";
        } else {
            if (!empty($result['error'])) {
                $viewtype = "layout";
                $viewCode = $result['error']['code'];
                $viewName = "_shared/_error";
            }
        }
        $breadcrumbs = $this->createBreadcrumbs(['view' => $viewName, 'method' => 'read', 'params' => $value]);
        $datos = (!empty($result['error'])) ? $result['error'] : $result['data'];
        $datos['view_origin'] = $viewOrigin;
        $response = $this->createViewData($viewName, $datos, $breadcrumbs, $viewtype, $viewCode, $viewData);
        return $response;
    }
    protected function getCompareData($value = "all"): array
    {
        $viewData = [];
        $viewtype = "view";
        $viewCode = null;
        $viewName = "collections/compareList";
        $collections = $this->compareCollections($value);
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
        $page = $values['page'];
        $viewData = [];
        $viewtype = "template";
        $viewCode = null;
        $viewName = "collections/list";
        if (is_array($values)) {
            $result = $this->commonNames($values);
            /* if ($values['view_origin'] == "compare") {
            } else {
                $result = $this->getCommonNames($values);
            } */
            $breadcrumbs = $this->createBreadcrumbs(['view' => $viewName, 'method' => 'read', 'params' => $values]);
            $datos = (!empty($result['error'])) ? $result['error'] : $result['data'];
            $datos['current_page'] = ($page < $result['data']['max_page']) ? $page + 1 : $page;
        } else {
            $datos = ['message' => "Request Type not supported"];
            $breadcrumbs = $this->createBreadcrumbs(['view' => $viewName, 'method' => 'read', 'params' => $values]);
            $viewtype = "error_view";
            $viewCode = 400;
        }
        $datos['view_origin'] = $values['view_origin'];
        return $this->createViewData($viewName, $datos, $breadcrumbs, $viewtype, $viewCode, $viewData);
    }
    protected function getPreviousPage($values): array
    {
        $page = $values['page'];
        $viewData = [];
        $viewtype = "template";
        $viewCode = null;
        $viewName = "collections/list";
        if (is_array($values)) {
            /* if ($values['view_origin'] == "compare") {
                $result = $this->getCollectionsPage($values['page']);
            } else { */
            $result = $this->commonNames($values);
            //}
            $breadcrumbs = $this->createBreadcrumbs(['view' => $viewName, 'method' => 'read', 'params' => $values]);
            $datos = (!empty($result['error'])) ? $result['error'] : $result['data'];
            $datos['current_page'] = ($page > 1) ? $page - 1 : $page;
        } else {
            $datos = ['message' => "Request Type not supported"];
            $breadcrumbs = $this->createBreadcrumbs(['view' => $viewName, 'method' => 'read', 'params' => $values]);
            $viewtype = "error_view";
            $viewCode = 400;
        }
        $datos['view_origin'] = $values['view_origin'];
        return $this->createViewData($viewName, $datos, $breadcrumbs, $viewtype, $viewCode, $viewData);
    }
    protected function createCollection () {
        return array();
    }


    private function compareCollections($value = 50)
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
    private function commonNames($value = 10): array
    {
        //$this->model->limit = $value;
        if (is_array($value)) {
            $this->model->page = ($value['page_info']) ?? '';
            //$this->model->id = ($value['id']) ?? '';
            $this->model->limit = $value['limit'];
            $collections = $this->model->getPage();
        } else {
            $collections = $this->model->storeGet();
        }
        $limit = ($value['limit']) ?? $value;
        $mixedcommonNames = [];
        if (empty($collections['error'])) {
            //$count = $this->model->calcular('store');
            //$max = ceil($count['data']['collections']['count'] / $limit);
            if (isset($collections['data']['next'])) {
                $next_page = $collections['data']['next'];
                $prev_page = $collections['data']['prev'];
                $mixedcommonNames['data']['collections'] = $this->nombresComunes($collections['data']['collections']);
            } else {
                $next_page = ($collections['pagination']['hasNextPage']) ? $collections['pagination']['endCursor'] : '';
                $prev_page = ($collections['pagination']['hasPreviousPage']) ? $collections['pagination']['startCursor'] : '';
                $mixedcommonNames['data']['collections'] = $this->nombresComunes($collections['data']);
            }
            //$mixedcommonNames['data']['pagination'] = $this->pagination($prev_page, $next_page, $max, $limit);
            $mixedcommonNames['data']['pagination'] = ['prev_page' => $prev_page, 'next_page' => $next_page, 'page_id' => 1];
            //$mixedcommonNames['data']['max_page'] = $max;
        }
        return $mixedcommonNames;
    }
    private function collection(int $id): array
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
    private function commonName(int $id): array
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
    private function makeURL($datos)
    {
        $urlString = "";
        if (is_array($datos)) {
            $x = 0;
            $s = sizeof($datos);
            $urlString = "?";
            $urlString .= preg_replace("/%5B[0-9]+%5D/", "%5B%5D", http_build_query($datos));
        }
        return $urlString;
    }
    private function nombresComunes($collections)
    {
        $response = array();
        foreach ($collections as $key => $collection) {
            $this->model->title = $collection['title'];
            $result = $this->model->localGet();
            $idArray = explode("/", $collection['id']);
            $id_store = $idArray[4];
            $fecha = "Sin Fecha";
            $response[$key] = [
                'id'            => "",
                'name'          => "No asociado",
                'date'          => $fecha,
                'active'        => 0,
                'handle'        => "No asignado",
                'category'      => "No asociado",
                'keywords'      => "",
                'store_id'      => $id_store,
                'id_tienda'     => "No asociado",
                'possition'     => null,
                'sort_order' => ($collection['sortOrder']) ?? null,
                'store_title' => ($collection['title']) ?? null,
                'store_handle' => ($collection['handle']) ?? null,
                'sub_category' => "No asociado",
                'product_count' => ($collection['productsCount']) ?? null
            ];
            if (sizeof($result['data']) > 1) {
                foreach ($result['data'] as $v) {
                    if ($v['store_id'] == $id_store) {
                        if (!empty($v['date'])) $fecha = date("d/m/Y", strtotime($v['date']));
                        if (!empty($v['id'])) $response[$key]['id'] = $v['id'];
                        if (!empty($v['name'])) $response[$key]['name'] = $v['name'];
                        if (!empty($v['date'])) $response[$key]['date'] = $fecha;
                        if (!empty($v['active'])) $response[$key]['active'] = $v['active'];
                        if (!empty($v['handle'])) $response[$key]['handle'] = $v['handle'];
                        if (!empty($v['category'])) $response[$key]['category'] = $v['category'];
                        if (!empty($v['keywords'])) $response[$key]['keywords'] = $v['keywords'];
                        if (!empty($v['store_id'])) $response[$key]['store_id'] = $id_store;
                        if (!empty($v['possition'])) $response[$key]['possition'] = $v['possition'];
                        if (!empty($v['sub_category'])) $response[$key]['sub_category'] = $v['sub_category'];
                        if (!empty($v['store_id'])) $response[$key][ 'id_tienda'] = $v['store_id'];
                        break;
                    }
                }
            } else {
                $commonName = $result['data'];
                if (!empty($commonName['date'])) $fecha = date("d/m/Y", strtotime($commonName['date']));
                if (!empty($commonName['id'])) $response[$key]['id'] = $commonName['id'];
                if (!empty($commonName['name'])) $response[$key]['name'] = $commonName['name'];
                if (!empty($commonName['date'])) $response[$key]['name'] = $fecha;
                if (!empty($commonName['active'])) $response[$key]['active'] = $commonName['active'];
                if (!empty($commonName['handle'])) $response[$key]['handle'] = $commonName['handle'];
                if (!empty($commonName['category'])) $response[$key]['category'] = $commonName['category'];
                if (!empty($commonName['keywords'])) $response[$key]['keywords'] = $commonName['keywords'];
                if (!empty($commonName['store_id'])) $response[$key]['id_tienda'] = $commonName['store_id'];
                if (!empty($commonName['possition'])) $response[$key]['possition'] = $commonName['possition'];
                if (!empty($commonName['sub_category'])) $response[$key]['sub_category'] = $commonName['sub_category'];
            }
        }
        return $response;
    }
    private function pagination($prev, $next, $max, $limit)
    {
        $response = array();
        $this->model->limit = $limit;
        for ($j = 0; $j <= $max; $j++) {
            $response[$j] = [
                'prev_page' => $this->makeURL($prev),
                'next_page' => (isset($next)) ? $this->makeURL($next) : "",
                'active'    => ($j == 0) ? true : false,
                'page_id'   => $j + 1
            ];
            if ($next != false) {
                $this->model->page = $next['page_info'];
                $prevData = $this->model->getPage();
                $next = $prevData['data']['next'];
                $prev = $prevData['data']['prev'];
            }
        }
        return $response;
    }
}
