<?php

/**
 * Controlador de colecciones
 * @author Jorge Echeverria <jecheverria@bytes4run.com>
 * @category Controller
 * @version 1.9.7
 * @package app\modules\collections\controllers
 * 31-01-2023
 */

namespace app\modules\collections\controllers;

use app\core\classes\ControllerClass;
use app\core\helpers\MessengerHelper;
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
    private $messenger;
    public function __construct()
    {
        $this->model = new CollectionModel;
        $this->messenger = new MessengerHelper;
    }
    /**
     * Función que crea una colección en la base de datos y en la tienda
     * 
     * Devuelve una vista de la colección
     * 
     * @param mixed $values Contiene la información a ser enviada a la base de datos
     * @return array
     */
    /* public function create($values)
    {
        if (!empty($values)) {
            return $this->createCollection($values);
        }
        return $this->createViewData('collections/create');
    } */
    /**
     * Función que devuelve la lista de colecciones creadas en la base de datos y la tienda
     * 
     * @param mixed $values
     * @return array
     * @version 1.0.0
     * 27/01/23
     */
    public function read($values)
    {
        $response = (!empty($values)) ? $this->getCollections($values) : $this->createViewData('collections/list');
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
    public function lista($value = [])
    {
        if (!empty($value)) {
            $result = $this->commonNames($value);
        } else {
            $result = $this->commonNames();
        }
        if (!empty($result['error'])) {
            $result['error'] = $this->messenger->messageBuilder('alert', $this->messenger->build('error', $result['error']));
        }
        return $result;
    }
    public function sync($value)
    {
    }
    public function download()
    {
        return $this->getdownloadedCollections();
    }
    /* #################### Protecteds #################### */
    /**
     * Función que devuelve la lista de colecciones o una colección a partir del ID de tienda o local
     * 
     * @param string|int $value Puede contener el nombre de la colección o el ID de la colección
     * @return array
     */
    protected function getCollections($value = []): array
    {
        $viewData = [];
        $viewtype = "template";
        $viewCode = null;
        $viewName = "collections/list";
        $lista = true;
        $viewOrigin = "read";
        if (!empty($value)) {
            if (isset($value['id'])) {
                $result = (strlen($value['id']) > 6) ? $this->collection($value) : $this->commonName($value);
                $lista = false;
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
        /* $page = $values['page'];
        $viewData = [];
        $viewtype = "template";
        $viewCode = null;
        $viewName = "collections/list";
        if (is_array($values)) { */
        $result = $this->commonNames($values);
        /* if ($values['view_origin'] == "compare") {
            } else {
                $result = $this->getCommonNames($values);
            } */
        /* $breadcrumbs = $this->createBreadcrumbs(['view' => $viewName, 'method' => 'read', 'params' => $values]);
            $datos = (!empty($result['error'])) ? $result['error'] : $result['data'];
            $datos['current_page'] = ($page < $result['data']['max_page']) ? $page + 1 : $page;
        } else {
            $datos = ['message' => "Request Type not supported"];
            $breadcrumbs = $this->createBreadcrumbs(['view' => $viewName, 'method' => 'read', 'params' => $values]);
            $viewtype = "error_view";
            $viewCode = 400;
        }
        $datos['view_origin'] = $values['view_origin'];
        return $this->createViewData($viewName, $datos, $breadcrumbs, $viewtype, $viewCode, $viewData); */
        return $result['data'];
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
    protected function createCollection($values)
    {
        if (empty($values)) {
            return ['error' => [], 'data' => []];
        } else {
            return $this->crearColeccion($values);
        }
    }
    protected function getdownloadedCollections(): array
    {
        $page = [];
        $result = [];
        do {
            $collections = $this->downloadCollections($page);
            $page = $collections['data']['pagination'];
            foreach ($collections['data']['collections'] as $collection) {
                $result[] = [
                    'values' => $collection,
                    'result' => $this->crearColeccion($collection)
                ];
            }
            sleep(15);
        } while ($page['hasNextPage'] == true);
        if (empty($result)) {
            $response = $this->createViewData('_shared/_error', ['error' => ['message' => "Something when worng!!"]], [], 'template', 500);
        } else {
            $response = $this->getCollections();
        }
        return $response;
    }

    private function downloadCollections($values)
    {
        $this->cleanVars();
        if (empty($values)) {
            $limit = 50;
            $this->model->limit = $limit;
            $list = $this->model->storeGet();
        } else {
            $limit = $values['limit'];
            $this->model->page = $values;
            $list = $this->model->getPage();
        }
        if (empty($list['error'])) {
            $response = [
                'data' => [
                    'collections' => $list['data'],
                    'pagination' => $list['pagination']
                ]
            ];
            $response['data']['pagination']['limit'] = $limit;
        } else {
            $response = [
                'data' => array(), 'error' => $list['error']
            ];
        }
        return $response;
    }



    private function compareCollections($value = 100)
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
    private function commonNames($value = 100): array
    {
        $this->cleanVars();
        $limit = $value;
        $this->model->limit = $limit;
        $collections = $this->model->localGet();
        $mixedcommonNames = ['collections' => [], 'pagination' => []];
        if (empty($collections['error'])) {
            if (!empty($collections['data'])) {
                $mixedcommonNames = [
                    'collections' => $this->nombresComunes($collections['data']),
                    'pagination' => [
                        'next' => null,
                        'prev' => null,
                        'max' => null,
                        'limit' => $limit
                    ]
                ];
            }
        } else {
            $mixedcommonNames = [
                'collections' => [],
                'pagination' => [],
                'error' => $collections['error']
            ];
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
            $id_store = $collection['id'];
            $this->cleanVars();
            $datas = [];
            $data = [
                'id'            => "",
                'name'          => "No asociado",
                'date'          => "Sin Fecha",
                'active'        => 0,
                'handle'        => "No asignado",
                'actions'       => ['delete'],
                'category'      => "No asociado",
                'keywords'      => "",
                'store_id'      => $id_store,
                'id_tienda'     => "No asociado",
                'possition'     => null,
                'store_seo'     => ($collection['seo']) ?? null,
                'metadatos'     => 0,
                'sort_order'    => ($collection['sort']) ?? null,
                'store_meta'    => ($collection['meta']) ?? null,
                'store_title'   => ($collection['title']) ?? null,
                'store_handle'  => ($collection['handle']) ?? null,
                'sub_category'  => "No asociado",
                'product_count' => ($collection['products']) ?? null,
                'collection_type' => (!empty($collection['rules'])) ? 'Custom' : 'Smart',
            ];
            $this->model->categoria = $collection['title'];
            $result = $this->model->localGet('commonNames');
            if (sizeof($result['data']) == 1) {
                $data['actions'] = ['create'];
                if (!empty($result['data']['id'])) {
                    $data['id'] = $result['data']['id'];
                    $data['actions'] = ['detalles'];
                }
                if (!empty($result['data']['name'])) $data['name'] = $result['data']['name'];
                if (!empty($result['data']['date'])) $data['date'] = date("d/m/Y", strtotime($result['data']['date']));
                if (!empty($result['data']['active'])) $data['active'] = $result['data']['active'];
                if (!empty($result['data']['handle'])) $data['handle'] = $result['data']['handle'];
                if (!empty($result['data']['category'])) $data['category'] = ['id' => $result['data']['tc_id'], 'name' => $result['data']['category']];
                if (!empty($result['data']['keywords'])) $data['keywords'] = $result['data']['keywords'];
                if (!empty($result['data']['store_id'])) $data['id_tienda'] = $result['data']['store_id'];
                if (!empty($result['data']['possition'])) $data['possition'] = $result['data']['possition'];
                if (!empty($result['data']['sub_category'])) {
                    $data['sub_category'] = ['id' => $result['data']['tp_id'], 'name' => $result['data']['sub_category']];
                    $this->model->tipo = $result['data']['tp_id'];
                    $result = $this->model->hasMetafields();
                    $data['metadatos'] = $result['data']['res'];
                }
            } else {
                $this->cleanVars();
                $this->model->id = $id_store;
                $this->model->title = $collection['title'];
                $this->model->handle = $collection['handle'];
                $result = $this->model->localGet('commonNames');
                if (sizeof($result['data']) == 1) {
                    $data['actions'] = ['details'];
                    if (!empty($result['data']['id'])) $data['id'] = $result['data']['id'];
                    if (!empty($result['data']['name'])) $data['name'] = $result['data']['name'];
                    if (!empty($result['data']['date'])) $data['date'] = date("d/m/Y", strtotime($result['data']['date']));
                    if (!empty($result['data']['active'])) $data['active'] = $result['data']['active'];
                    if (!empty($result['data']['handle'])) $data['handle'] = $result['data']['handle'];
                    if (!empty($result['data']['category'])) $data['category'] = ['id' => $result['data']['tc_id'], 'name' => $result['data']['category']];
                    if (!empty($result['data']['keywords'])) $data['keywords'] = $result['data']['keywords'];
                    if (!empty($result['data']['store_id'])) $data['id_tienda'] = $result['data']['store_id'];
                    if (!empty($result['data']['possition'])) $data['possition'] = $result['data']['possition'];
                    if (!empty($result['data']['sub_category'])) {
                        $data['sub_category'] = ['id' => $result['data']['tp_id'], 'name' => $result['data']['sub_category']];
                        $this->model->tipo = $result['data']['tp_id'];
                        $result = $this->model->hasMetafields();
                        $data['metadatos'] = $result['data']['res'];
                    }
                } else {
                    $this->model->id = null;
                    $this->model->handle = null;
                    $sonIguales = false;
                    $result = $this->model->localGet('commonNames');
                    if (sizeof($result['data']) > 0) {
                        $i = 0;
                        foreach ($result['data'] as $commonName) {
                            if ($commonName['name'] === $collection['title']) {
                                $sonIguales = true;
                            }
                            if ($commonName['handle'] === $collection['handle']) {
                                $sonIguales = true;
                            }
                            if ($sonIguales === false) {
                                $this->model->tipo = $commonName['tp_id'];
                                $result = $this->model->hasMetafields();
                                $cant = $result['data']['res'];
                                if ($cant > 0) {
                                    $data[$i]['metadatos'] = $cant;
                                    $sonIguales = true;
                                }
                            }
                            if ($sonIguales === true) {
                                $data[$i]['actions'] = ['details', 'sync'];
                                $data[$i]['id'] = $commonName['id'];
                                $data[$i]['name'] = $commonName['name'];
                                $data[$i]['active'] = $commonName['active'];
                                $data[$i]['handle'] = $commonName['handle'];
                                $data[$i]['category'] = ['id' => $commonName['tc_id'], 'name' => $commonName['category']];
                                $data[$i]['keywords'] = $commonName['keywords'];
                                $data[$i]['id_tienda'] = $commonName['store_id'];
                                $data[$i]['possition'] = $commonName['possition'];
                                if (!empty($commonName['data']['sub_category'])) {
                                    $data[$i]['sub_category'] = ['id' => $commonName['tp_id'], 'name' => $commonName['sub_category']];
                                }
                                $i++;
                            }
                        }
                    }
                }
            }
            if (sizeof($datas) > 0) {
                foreach ($data as $item) {
                    $response[$key] = $this->rowTableData($item);
                }
            } else {
                $response[$key] = $this->rowTableData($data);
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
    private function rowTableData($arreglo): array
    {
        if (isset($arreglo['date'])) {
            $displayDate = ($arreglo['date'] != "Sin Fecha") ? date("d/m/Y", strtotime($arreglo['date'])) : $arreglo['date'];
        }
        $state = 'active';
        $text = 'Inactivo';
        $check = '';
        if ($arreglo['active'] > 0) {
            $state = 'diactive';
            $text = 'Activo';
            $check = 'checked';
        }
        if (!is_null($arreglo['store_handle'])) {
            $eliminable = false;
            $handleParts = explode('-', $arreglo['store_handle']);
            $handleSize = count($handleParts);
            $handleHandler = '<a href="#" onclick="deleteCollection(\'' . $arreglo['store_id'] . '\')" target="_self" title="';
            for (
                $p = 0;
                $p < $handleSize;
                $p++
            ) {
                if (is_numeric($handleParts[$p])) {
                    if ($p == ($handleSize - 1)) {
                        $handleHandler .= $arreglo['store_handle'] . '" type="text">' . $arreglo['store_handle'] . '</a>';
                        $eliminable = true;
                    }
                }
            }
            if ($eliminable === false) $handleHandler = $arreglo['store_handle'];
        }
        $response = [
            'store_id' => '<a href="/collections/read?id=' . $arreglo['store_id'] . '" target="_self" title="' . $arreglo['name'] . '" type="text">' . $arreglo['store_id'] . '</a>',
            'store_title' => $arreglo['store_title'],
            'store_handle' => $handleHandler,
            'store_type' => $arreglo['collection_type'],
            'store_seo' => $arreglo['store_seo'],
            'sort_order' => $arreglo['sort_order'],
            'product_count' => $arreglo['product_count'],
            'date' => $arreglo['date'],
            'name' => ($arreglo['name'] == "No asociado") ? '<a href="/collections/create?id=' . $arreglo['store_id'] . '" target="_self" title="crear colección" type="text"> Crear|Sincronizar</a>' : '<a href="collections/read?id=' . $arreglo['id'] . '" target="_self" title="' . $arreglo['name'] . '" type="text">' . $arreglo['name'] . '</a>',
            'handle' => ($arreglo['handle'] == "No asociado") ? '<a href="/collections/create?id=' . $arreglo['store_id'] . '" target="_self" title="crear colección" type="text"> Crear|Sincronizar</a>' : '<a href="collections/read?id=' . $arreglo['id'] . '" target="_self" title="' . $arreglo['handle'] . '" type="text">' . $arreglo['handle'] . '</a>',
            'id_tienda' => $arreglo['id_tienda'],
            'keywords' => $arreglo['keywords'],
            'active' => '
                <div class="form-group">
                    <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                        <input type="checkbox" class="custom-control-input" id="' . $arreglo['store_id'] . '-Switch" onclick="changeState(' . $arreglo['id'] . ',\'' . $state . '\')"
                            data-toggle="tooltip" data-placement="top" title="' . $text . '" ' . $check . '>
                        <label class="custom-control-label" for="' . $arreglo['id'] . '-Switch"> </label>
                    </div>
                </div>',
            'possition' => $arreglo['possition'],
            'meta' => [
                'is_' => ($arreglo['store_meta']) ?? null,
                'id' => ($arreglo['id_meta']) ?? 0,
                'type' => ($arreglo['type']) ?? null
            ]
        ];
        if (is_array($arreglo['category'])) {
            $response['category'] = '
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#type-Changer" data-collectid="' . $arreglo['store_id'] . '" data-prodcat="' . $arreglo['category']['id'] . '">' . $arreglo['category']['name'] . '</button>';
        } else {
            $response['category'] = '
                <button type="button" class="btn btn-primary" 
                    data-toggle="modal" 
                    data-target="#type-Changer" 
                    data-collectid="' . $arreglo['store_id'] . '" 
                    data-prodcat="">
                    Asignar
                </button>';
        }
        if (is_array($arreglo['sub_category'])) {
                $response['subcategory'] =  '
                    <button type="button" class="btn btn-primary" 
                        data-toggle="modal" 
                        data-target="#type-Changer" 
                        data-collectid="' . $arreglo['store_id'] . '" 
                        data-prodtype="' . $arreglo['sub_category']['id'] . '">' . 
                        $arreglo['sub_category']['name'] . '
                    </button>';
        } else {
            $response['sub_category'] = '
                <button type="button" class="btn btn-primary" 
                    data-toggle="modal" 
                    data-target="#type-Changer" 
                    data-collectid="' . $arreglo['store_id'] . '" 
                    data-prodcat="">
                    Asignar
                </button>';
        }
        $response['metadatos'] = $arreglo['metadatos'];
        $response['actions'] = "
            <div class='btn-group'>
                <button type='button' class='btn btn-outline-info dropdown-toggle dropdown-icon' data-toggle='dropdown'>
                    Eleija...
                </button>
                <span class='sr-only'>Acciones</span>
                <div class='dropdown-menu' role='menu'>";
        if (!empty($arreglo['id'])) {
            foreach ($arreglo['actions'] as $action) {
                //if (is_string($action)) {
                    switch ($action) {
                        case "details":
                            $response['actions'] .= '
                                            <a href="/collections/read?id=' . $arreglo['id'] . '"
                                                title="Ver detalles de colección" target="_self"
                                                type="text" class="btn btn-success btn-sm btn-block">
                                                <i class="fas fa-eye mr-3"></i>Detalles
                                            </a>';
                            break;
                        case "delete":
                            $response['actions'] .=
                                '<a href="collections/delete?id=' . $arreglo['id'] . '"
                                                title="Borra una colección localmente" 
                                                target="_self" type="text" class="btn btn-danger btn-sm btn-block">
                                                <i class="fas fa-trash mr-3"></i>Borrar Nombre Común
                                            </a>';
                            break;
                        case "sync":
                            $response['actions'] .= '<a href="#" onclick="syncCollection(\'' . $arreglo['id'] . '\'")
                                                title="Sincroniza datos de tienda a local" 
                                                target="_self" type="text" class="btn btn-primary btn-sm btn-block">
                                                <i class="fas fa-trash mr-3"></i>Sincronizar
                                            </a>';
                            break;
                    }
                /*}  else {
                    if (is_array($action)) {
                        foreach ($action as $k => $v) {
                            $response['actions'] .= '<a href="#" onlcick="syncCollection(\'';
                            switch ($v) {
                                case "name":
                                    $response['actions'] .= 'name\',\'' . $arreglo['name'];
                                    break;
                                default:
                                    $response['actions'] .= 'id\',\'' . $arreglo['id'];
                                    break;
                            }
                            $response['actions'] .= '\'")
                                                title="Sincroniza datos de tienda a local" 
                                                target="_self" type="text" class="btn btn-primary btn-sm btn-block">
                                                <i class="fas fa-trash mr-3"></i>Sincronizar
                                            </a>';
                        }
                    }
                } */
            }
            $response['actions'] .= '
                            <div class="dropdown-divider"></div>
                            <a href="/collections/compare?id=' . $arreglo['store_id'] . '"
                                title="Compara los datos de una colección" 
                                target="_self" type="text" class="btn btn-info btn-sm btn-block">
                                <i class="fas fa-copy mr-3"></i>Comparar
                            </a>
                            <a href="/collections/edit?id=' . $arreglo['id'] . '"
                                title="Editar datos de la colección local"
                                target="_self" type="text" class="btn btn-warning btn-sm btn-block">
                                <i class="fas fa-edit mr-3"></i>Editar Nombre Común
                            </a>
                            <div class="dropdown-divider"></div>';
        }
        $response['actions'] .= '<a href="collections/edit?id=' . $arreglo['store_id'] . '"
                        title="Editar datos de la colección tienda"
                        target="_self" type="text" class="btn btn-warning btn-sm btn-block">
                        <i class="fas fa-edit mr-3"></i>Editar Colección
                    </a>
                    <a href="collections/delete?id=' . $arreglo['store_id'] . '"
                        title="Borra una colección en la nube"
                        target="_self" type="text" class="btn btn-danger btn-sm btn-block">
                        <i class="fas fa-trash mr-3"></i>Borrar Colección
                    </a>
                </div>
            </div>';
        return $response;
    }
    private function verifyCommonName($id, $tipo)
    {
        $this->cleanVars();
        $this->model->tipo = $tipo;
        $result = $this->model->getMetafields();
        if (!empty($result['error'])) {
            return $result;
        } else {
            if (sizeof($result) > 0) {
                return true;
            }
        }
        return false;
    }
    private function hasMetadatos($tipo)
    {
        $this->cleanVars();
        $this->model->tipo = $tipo;
        return $this->model->hasMetafields();
    }
    private function crearColeccion($values)
    {
        $idArray = explode("/", $values['id']);
        $this->model->id = $idArray[4];
        $this->model->graphQL_id = $values['id'];
        $this->model->title = $values['title'];
        $this->model->handle = $values['handle'];
        $this->model->products = $values['productsCount'];
        $this->model->order = $values['sortOrder'];
        $this->model->rules = json_encode($values['ruleSet']);
        $this->model->fields = json_encode($values['metafields']);
        $this->model->seo = $values['seo']['title'];
        $existe = $this->model->find();
        if (empty($existe['data'])) {
            return $this->model->createCollection();
        }
        $this->cleanVars();
        return false;
    }
    private function cleanVars()
    {
        $this->model->id = null;
        $this->model->graphQL_id = null;
        $this->model->title = null;
        $this->model->handle = null;
        $this->model->products = null;
        $this->model->order = null;
        $this->model->rules = null;
        $this->model->fields = null;
        $this->model->seo = null;
    }
}
