<?php

/**
 * Controlador de colecciones
 * @author Jorge Echeverria <jecheverria@bytes4run.com>
 * @category Controller
 * @version 1.9.7
 * @package app\modules\collections\controllers
 * 31-01 | 21-2-2023
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
    /**
     * Helper Messenger
     * @var object $messenger Contiene el modelo de las colecciones
     */
    private $messenger;
    public function __construct()
    {
        $this->model = new CollectionModel;
        $this->messenger = new MessengerHelper;
    }
    /**
     * Función que devuelve la lista de colecciones creadas en la base de datos y la tienda
     * 
     * @param mixed $values
     * @return array
     * @version 1.0.0
     * 27/01 | 21/02 2023
     */
    public function read($values)
    {
        if (empty($values) || is_null($values)) {
            $result = $this->createViewData('collections/list');
        } else {
            if (is_array($values) && sizeof($values) > 1) {
                $result = $this->getCollectionsByParams($values);
            } elseif (isset($values['id'])) {
                $result = $this->getCollectionsById($values['id']);
            } elseif (is_numeric($values)) {
                $result = $this->getCollectionsById($values);
            } else {
                $result = $this->getCollectionsByParams($values);
            }
        }
        return $result;
    }
    /**
     * Devuelve lista de colecciones
     * @param array $value REcibe un arreglo con el limite y parametros para iltrar la lista
     * @return array Devuelve un arreglo listo para ser usado por la vista
     */
    public function lista($value = [])
    {
        $result = $this->getCollectionsByParams($value);
        if (!empty($result['error'])) {
            $result['error'] = $this->messenger->messageBuilder('alert', $this->messenger->build('error', $result['error']));
        }
        return $result;
    }
    /**
     * Function que devuelve la vista de lista de las colecciones
     * @param mixed $algo
     * @return array
     */
    public function index($algo)
    {
        return $this->read($algo);
    }
    /**
     * Función que devuelve los detalles de una colección o nombre común.
     * @param mixed $values
     * @return array
     */
    public function search($values)
    {
        if (!empty($values)) {
            $result = $this->getSearchData($values);
        } else {
            $result['error'] = $this->messenger->messageBuilder('alert', $this->messenger->build('error', ['data' => ['code' => 400, 'message' => "Información invalida"]]));
        }
        return $result;
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
    public function sync($value)
    {
    }
    public function download()
    {
        return $this->getdownloadedCollections();
    }
    
    public function verify($values)
    {
        return $this->collectionVerified($values);
    }
    public function fill($value)
    {
        $this->model->title = $value['term'];
        return $this->getDataFill();
    }


    /* #################### Protecteds #################### */

    /**
     * Función que devuelve la lista de colecciones o una colección a partir del ID de tienda o local
     * 
     * @param string|int $value Puede contener el nombre de la colección o el ID de la colección
     * @return array
     */
    protected function getCollectionsById($value): array
    {
        if (!empty($value)) {
            if (isset($value['id'])) {
                $result = (strlen($value['id']) > 4) ? $this->collectionById($value['id']) : $this->commonNameById($value['id']);
            } else {
                $result = (strlen($value) > 4) ? $this->collectionById($value) : $this->commonNameById($value);
            }
            $viewName = 'collections/' . $result['view'];
            $result = $this->createViewData($viewName,$result['collections']);
        } else {
            $result = $this->messenger->messageBuilder('alert', $this->messenger->build('error', ['code' => "00400", 'message' => $value]));
        }
        return $result;
    }
    /**
     * Detruye variables
     * @param array $vars
     * @return void
     */
    protected function unsetVars(array $vars)
    {
        if (isset($vars)) unset($vars);
    }
    /**
     * Genera el array que se usará en una vista
     * @param array $values Lista de colecciones a ser usada para crear los datos.
     * @return array
     */
    protected function createDataForView(array $values): array
    {
        $response = array();
        echo "<pre>";
        var_dump($values);
        echo "</pre>";
        return $response;
    }
    protected function getCollectionsByParams(array|string $values = []): array
    {
        if (empty($value)) {
            $result = $this->collectionByParams($values);
        } elseif (is_string($values) && !empty($values)) {
            $result = $this->collectionByParams($values);
        } else {
            $result = $this->commonNameByParams($values);
        }
        return $result;
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
            if ($values['id']) {
                $collection = $this->commonName($values['id']);
                $this->crearColeccion($collection);
            } else {
            }
            return $this->getCollections();
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
    protected function getSearchData($values)
    {
        $this->model->activo = ($values['active']) ?? false;
        if (isset($values['name']) && !empty($values['name'])) $this->model->title = $values['name'];
        if (isset($values['letter']) && !empty($values['letter'])) $this->model->letra = $values['letter'];
        if (isset($values['cat']) && !empty($values['cat'])) $this->model->categoria = (!empty($values['cat']) && $values['cat'] > 0) ? $values['cat'] : null;
        if (isset($values['scat']) && !empty($values['scat'])) $this->model->tipo = (!empty($values['scat']) && $values['scat'] > 0) ? $values['scat'] : null;
        if (!is_null($values['cat']) || !is_null($values['scat'])) {
            return $this->searchCommonNames();
        } else {
            return $this->searchCollections();
        }
    }
    protected function collectionVerified($values)
    {
        if (isset($values['id_collection']) && !empty($values['id_collection'])) {
            if (isset($values['id_common']) && !empty($values['id_common'])) {
                if (isset($values['current']) && !empty($values['current'])) {
                    $result = $this->unverified($values['id_collection'], $values['id_common']);
                } else {
                    $result = $this->setVerification($values['id_collection'], $values['id_common']);
                }
            } else {
                $result = $this->setVerification($values['id_collection']);
            }
        } else {
        }
    }
    protected function syncronizeCollection($values)
    {
        if (isset($values['id']) && !empty($values['id'])) {
            $result = $this->syncData($values);
            if ($result['type'] == "view") {
                return $this->createViewData('collections/edit', ['error' => ['message' => "Existen mas de un nombre comun"]], [], 'template', 400);
            }
        } else {
            $result = $this->createViewData('_shared/_error', ['error' => ['message' => "Something when worng!!"]], [], 'template', 500);
        }
        return $result;
    }
    protected function syncronizeCommonName($values)
    {
        if (isset($values['id']) && !empty($values['id'])) {
            $result = $this->createCollection($values);
            if ($result['type'] == "view") {
                return $this->createViewData('collections/edit', ['error' => ['message' => "Existen mas de un nombre comun"]], [], 'template', 400);
            }
        } else {
            $result = $this->createViewData('_shared/error', ['error' => ['messenge' => "Something when worng!!"]], [], 'template', 500);
        }
        return $result;
    }
    protected function getCollectionById()
    {
    }

    /* ############# Private ############## */
    /**
     * Funcion que descarga las colecciones desde la tienda a la base local
     * Devuelve la lista de colecciones según cursor de paginación
     * @param array $values Valores de paginación
     * @return array
     */
    private function downloadCollections(array $values = []): array
    {
        $this->cleanVars();
        if (empty($values)) {
            $limit = 50;
            $this->model->limit = $limit;
            $list = $this->model->storeGet();
        } else {
            $limit = $values['limit'];
            $this->model->page = $values;
            $list = $this->model->getPage('store');
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
    /**
     * Obtiene una colección a partir de su ID y devuelve su información
     * incluyendo los nombres comunes relacionados con ella.
     *
     * @param integer $id
     * @return array
     */
    private function collectionById(int $id): array
    {
        $response = [];
        $this->model->id = $id;
        $result = $this->model->get('collection', 'all');
        if (!empty($result['error'])) {
            return $result;
        } else {
            if (isset($result['data']) && !empty($result['data'])) {
                $data = array();
                foreach ($result['data'] as $k => $collection) {
                    $data[$k] = $collection;
                    $this->model->id_store = $collection['id'];
                    $cn = $this->model->get('common_name', 'all');
                    $data[$k]['common'] = array();
                    if (empty($cn['error']) && !empty($cn['data'])) {
                        $data[$k]['common'] = $cn['data'];
                    }
                }
                $this->unsetVars(['k', 'collection', 'nombre','i']);
                $response = ['collections'=>$data,'view'=>"collection_details"];
            } else {
                $response = [
                    'error' => $this->messenger->build('error', ['code' => '00404'])
                ];
            }
        }
        return $response;
    }
    /**
     * Obtiene un nombre común y buscar sus colecciones relacionadas
     *
     * @param int|string $value
     * @return array
     */
    private function commonNameById(int|string $value): array
    {
        if (is_string($value)) {
            $this->model->title = $value;
        } else {
            $this->model->id = $value;
        }
        $result = $this->model->get('common_name', 'all');
        $response = [];
        if (empty($result['error']) && !empty($result['data'])) {
            $data = array();
            foreach ($result['data'] as $k => $nombre) {
                $this->model->id = $nombre['store_id'];
                $collection = $this->model->get('collection', 'all');
                if (empty($collection['error']) && !empty($collection['data'])) {
                    foreach ($collection['data'] as $i => $col) {
                        if (isset($data[$col['id']])) {
                            $data[$col['id']]['common'][] = $nombre;
                        } else {
                            $data[$col['id']] = [
                                'store_id' => $col['id'],
                                'store_seo' => ($col['seo']) ?? null,
                                'sort_order' => ($col['sort']) ?? null,
                                'store_meta' => ($col['meta']) ?? null,
                                'store_title' => ($col['title']) ?? null,
                                'store_handle' => ($col['handle']) ?? null,
                                'product_count' => ($col['products']) ?? null,
                                'collection_type' => (!empty($col['rules'])) ? 'Smart' : 'Custom',
                                'common' => $nombre
                            ];
                        }
                    }
                } else {
                    if (isset($data['No asignado'])) {
                        $data['No asignado']['common'][] = $nombre;
                    } else {
                        $data['No asignado'] = [
                            'store_id' => null,
                            'store_seo' => null,
                            'sort_order' => null,
                            'store_meta' => null,
                            'store_title' => null,
                            'store_handle' => null,
                            'product_count' => null,
                            'collection_type' => null,
                            'common' => $nombre
                        ];
                    }
                }
                $response[] = $data;
            }
            $this->unsetVars(['k', 'collection', 'nombre', 'i', 'col']);
        } else {
            $response = [
                'error' => $this->messenger->build('error', ['code' => '00404'])
            ];
        }
        return $response;
    }
    private function collectionByParams($values)
    {
        if (is_string($values)) {
            $this->model->title = $values;
            $collections = $this->model->get('collection', 'all');
            if (empty($collections['error']) && empty($collections['data'])) {
                $this->model->handle;
                $collections = $this->model->get('collection', 'all');
                if (empty($collections['error']) && empty($collections['data'])) {
                    $msg = $this->messenger->build('error', ['code' => "00404"]);
                    $response = $this->messenger->messageBuilder('alert', $msg, 'json');
                }
            }
        } elseif (is_array($values) && !empty($values)) {
            if (!is_null($values['title']) && !empty($values['title'])) $this->model->title = $values['title'];
            if (!is_null($values['handle']) && !empty($values['handle'])) $this->model->title = $values['handle'];
            if (!is_null($values['id']) && !empty($values['id'])) $this->model->title = $values['id'];
            $collections = $this->model->get('collection', 'all');
        } else {
            $collections = $this->model->get('collection', 'all');
        }
        if (empty($collections['error']) && !empty($collections['data'])) {
            $data = array();
            foreach ($collections['data'] as $k => $collection) {
                $data[$k] = $collection;
                $this->model->id_store = $collection['id'];
                $cn = $this->model->get('common_name', 'all');
                $data[$k]['common'] = array();
                if (empty($cn['error']) && !empty($cn['data'])) {
                    foreach ($cn['data'] as $i=>$nombre) {
                        $data[$k]['common'][$i] = $nombre;
                    }
                }
            }
            $this->unsetVars(['k', 'collection', 'nombre','i']);
            $response = ['collections' => $this->rowTableData($data), 'pagination' => []];
        } else {
            $msg = $this->messenger->build('error', ['code' => "00404"]);
            $response = $this->messenger->messageBuilder('alert', $msg, 'json');
        }
        return $response;
    }
    private function commonNameByParams($values)
    {
        if (is_string($values)) {
            $this->model->title = $values;
            $commonNames = $this->model->get('common_name', 'all');
            if (empty($commonNames['error']) && empty($commonNames['data'])) {
                $this->model->handle = $values;
                $commonNames = $this->model->get('common_name', 'all');
                if (empty($commonNames['error']) && empty($commonNames['data'])) {
                    $msg = $this->messenger->build('error', ['code' => "00404"]);
                    $response = $this->messenger->messageBuilder('alert', $msg, 'json');
                }
            }
        } else {
            if (!is_null($values['title']) && !empty($values['title'])) $this->model->title = $values['title'];
            if (!is_null($values['handle']) && !empty($values['handle'])) $this->model->title = $values['handle'];
            if (!is_null($values['tipo']) && !empty($values['scat'])) $this->model->title = $values['scat'];
            if (!is_null($values['category']) && !empty($values['cat'])) $this->model->title = $values['cat'];
            $commonNames = $this->model->get('collection', 'all');
        }
        if (empty($commonNames['error']) && !empty($commonNames['data'])) {
            $data = array();
            foreach ($commonNames['data'] as $k => $nombre) {
                $this->model->id = $nombre['store_id'];
                $collection = $this->model->get('collection', 'all');
                if (empty($collection['error']) && !empty($collection['data'])) {
                    foreach ($collection['data'] as $i => $col) {
                        if (isset($data[$col['id']])) {
                            $data[$col['id']]['common'][] = $nombre;
                        } else {
                            $data[$col['id']] = [
                                'store_id' => $col['id'],
                                'store_seo' => ($col['seo']) ?? null,
                                'sort_order' => ($col['sort']) ?? null,
                                'store_meta' => ($col['meta']) ?? null,
                                'store_title' => ($col['title']) ?? null,
                                'store_handle' => ($col['handle']) ?? null,
                                'product_count' => ($col['products']) ?? null,
                                'collection_type' => (!empty($col['rules'])) ? 'Smart' : 'Custom',
                                'common' => $nombre
                            ];
                        }
                    }
                } else {
                    if (isset($data['No asignado'])) {
                        $data['No asignado']['common'][] = $nombre;
                    } else {
                        $data['No asignado'] = [
                            'store_id' => null,
                            'store_seo' => null,
                            'sort_order' => null,
                            'store_meta' => null,
                            'store_title' => null,
                            'store_handle' => null,
                            'product_count' => null,
                            'collection_type' => null,
                            'common' => $nombre
                        ];
                    }
                }
                $response[] = $data;
            }
            $this->unsetVars(['k', 'i', 'col', 'nombre']);
            $response = $this->createDataForView($data);
        } else {
            $msg = $this->messenger->build('error', ['code' => "00404"]);
            $response = $this->messenger->messageBuilder('alert', $msg, 'json');
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
    private function commonNames($value = []): array
    {
        $this->cleanVars();
        $mixedcommonNames = ['collections' => [], 'pagination' => []];
        $limit = ($value['limit']) ?? 0;
        if (isset($value['page'])) {
            $this->model->page = $value['page'];
            $this->model->cursor = ($value['cursor']) ?? null;
        }
        $this->model->limit = $limit;
        $collections = (isset($value['page'])) ? $this->model->getPage('local') : $this->model->get();
        $max = $this->model->calcular('collections');
        if (empty($collections['error'])) {
            if (!empty($collections['data'])) {
                $mixedcommonNames = [
                    'collections' => $this->nombresComunes($collections['data']),
                    'pagination' => [
                        'next' => $collections['pagination']['next_page'],
                        'prev' => $collections['pagination']['prev_page'],
                        'max' => $max['data'][0]['res'],
                        'limit' => $limit
                    ]
                ];
            }
        } else {
            $mixedcommonNames = [
                'collections' => [],
                'pagination' => [],
                'error' => $this->messenger->messageBuilder('alert', $this->messenger->build('error', $collections['error']))
            ];
        }
        return $mixedcommonNames;
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
        $key = 0;
        foreach ($collections as $collection) {
            $id_store = $collection['id'];
            $this->cleanVars();
            $datas = [];
            $data = [
                'id'            => "",
                'name'          => "No asociado",
                'date'          => "Sin Fecha",
                'active'        => 0,
                'handle'        => "No asignado",
                'actions'       => ['common' => [], 'collection' => []],
                'category'      => "No asociado",
                'keywords'      => "",
                'store_id'      => $id_store,
                'verified'      => false,
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
                'collection_type' => (!empty($collection['rules'])) ? 'Smart' : 'Custom',
            ];
            $this->model->categoria = $collection['title'];
            $result = $this->model->localGet('isCategory');
            if ($result['data']['isCategory'] === true) {
                if ($result['data']['isCommon'] === true) {
                    array_push($data['actions']['collection'], 'eliminar');
                    if (sizeof($result['data']['list']) > 0) {
                        $g = 0;
                        foreach ($result['data']['list'] as $common) {
                            $datas[$g] = $data;
                            array_push($datas[$g]['actions']['common'], 'eliminar');
                            if (!empty($common['id'])) {
                                $datas[$g]['id'] = $common['id'];
                                array_push($datas[$g]['actions']['common'], 'detalles');
                            }
                            if (!empty($common['name'])) {
                                $datas[$g]['name'] = $common['name'];
                                if ($common['name'] != $collection['title']) {
                                    array_push($datas[$g]['actions']['common'], 'name');
                                }
                            }
                            if (!empty($common['date'])) $datas[$g]['date'] = date("d/m/Y", strtotime($common['date']));
                            if (!empty($common['active'])) $datas[$g]['active'] = $common['active'];
                            if (!empty($common['handle'])) {
                                $datas[$g]['handle'] = $common['handle'];
                                if ($common['handle'] != $collection['handle']) {
                                    array_push($datas[$g]['actions']['common'], 'handle');
                                }
                            }
                            if (!empty($common['category'])) $datas[$g]['category'] = ['id' => $common['tc_id'], 'name' => $common['category']];
                            if (!empty($common['keywords'])) $datas[$g]['keywords'] = $common['keywords'];
                            if (!empty($common['store_id'])) {
                                if ($common['store_id'] != $id_store) {
                                    array_push($datas[$g]['actions']['common'], 'store_id');
                                    $datas[$g]['id_tienda'] = $common['store_id'];
                                    $datas[$g]['store_id'] = null;
                                    $datas[$g]['store_seo'] = null;
                                    $datas[$g]['sort_order'] = null;
                                    $datas[$g]['store_meta'] = null;
                                    $datas[$g]['product_count'] = null;
                                    $datas[$g]['collection_type'] = null;
                                }
                            }
                            if (!empty($common['possition'])) $datas[$g]['possition'] = $common['possition'];
                            if (!empty($common['sub_category'])) {
                                $datas[$g]['sub_category'] = ['id' => $common['tp_id'], 'name' => $common['sub_category']];
                                $this->model->tipo = $common['tp_id'];
                                $result = $this->model->hasMetafields();
                                $datas[$g]['metadatos'] = $result['data'][0]['res'];
                            }
                            array_push($datas[$g]['actions']['common'], 'editar');
                            $g++;
                        }
                        if (isset($common)) unset($common);
                    }
                } else {
                    array_push($data['actions']['collection'], 'sync');
                }
            } else {
                $this->cleanVars();
                $this->model->id = $id_store;
                $this->model->title = $collection['title'];
                $this->model->handle = $collection['handle'];
                $result = $this->model->localGet('commonNames');
                if (sizeof($result['data']) > 0) {
                    array_push($data['actions']['collection'], 'eliminar');
                    array_push($data['actions']['common'], 'eliminar');
                    foreach ($result['data'] as $k => $v) {
                        $datas[$k] = $data;
                        if (!empty($v['id'])) {
                            $datas[$k]['id'] = $v['id'];
                            array_push($datas[$k]['actions']['common'], 'detalles');
                        }
                        if (!empty($v['name'])) {
                            $datas[$k]['name'] = $v['name'];
                            if ($v['name'] != $collection['title']) {
                                array_push($datas[$k]['actions']['common'], 'name');
                            }
                        }
                        if (!empty($v['date'])) $datas[$k]['date'] = date("d/m/Y", strtotime($v['date']));
                        if (!empty($v['active'])) $datas[$k]['active'] = $v['active'];
                        if (!empty($v['handle'])) {
                            $datas[$k]['handle'] = $v['handle'];
                            if ($v['name'] != $collection['handle']) {
                                array_push($datas[$k]['actions']['common'], 'handle');
                            }
                        }
                        if (!empty($v['category'])) $datas[$k]['category'] = ['id' => $v['tc_id'], 'name' => $v['category']];
                        if (!empty($v['keywords'])) $datas[$k]['keywords'] = $v['keywords'];
                        if (!empty($v['store_id'])) {
                            $datas[$k]['id_tienda'] = $v['store_id'];
                            if ($v['store_id'] != $id_store) {
                                array_push($datas[$k]['actions']['common'], 'store_id');
                                $datas[$k]['store_id'] = null;
                                $datas[$k]['store_seo'] = null;
                                $datas[$k]['sort_order'] = null;
                                $datas[$k]['store_meta'] = null;
                                $datas[$k]['product_count'] = null;
                                $datas[$k]['collection_type'] = null;
                            }
                        }
                        if (!empty($v['possition'])) $datas[$k]['possition'] = $v['possition'];
                        if (!empty($v['sub_category'])) {
                            $datas[$k]['sub_category'] = ['id' => $v['tp_id'], 'name' => $v['sub_category']];
                            $this->model->id = $v['id'];
                            $result = $this->model->hasMetafields();
                            $datas[$k]['metadatos'] = $result['data'][0]['res'];
                        }
                        array_push($datas[$k]['actions']['common'], 'editar');
                    }
                    if (isset($v)) unset($v);
                    if (isset($k)) unset($k);
                } else {
                    $this->model->id = null;
                    $this->model->handle = null;
                    $this->model->categoria = null;
                    $sonIguales = false;
                    $result = $this->model->localGet('commonNames');
                    if (sizeof($result['data']) > 0) {
                        $i = 0;
                        foreach ($result['data'] as $commonName) {
                            $datas[$i] = $data;
                            if ($commonName['name'] === $collection['title']) {
                                $sonIguales = true;
                            }
                            if ($commonName['handle'] === $collection['handle']) {
                                $sonIguales = true;
                            } else {
                                if ($sonIguales === true) {
                                    $datas[$i]['store_handle'] = null;
                                    if (isset($datas[$i]['actions']['common'])) {
                                        array_push($datas[$i]['actions']['common'], 'handle');
                                    } else {
                                        $datas[$i]['actions']['common'][] = 'handle';
                                    }
                                }
                            }
                            if ($sonIguales === false) {
                                array_push($datas[$i]['actions']['common'], 'editar');
                                $this->model->id = $commonName['id'];
                                $result = $this->model->hasMetafields();
                                $cant = $result['data'][0]['res'];
                                if ($cant > 0) {
                                    $datas[$i]['metadatos'] = $cant;
                                    $sonIguales = true;
                                    array_push($datas[$i]['actions']['common'], 'sync');
                                }
                            }
                            if ($sonIguales === true) {
                                $datas[$i]['id'] = $commonName['id'];
                                $datas[$i]['name'] = $commonName['name'];
                                $datas[$i]['active'] = $commonName['active'];
                                $datas[$i]['handle'] = $commonName['handle'];
                                $datas[$i]['category'] = ['id' => $commonName['tc_id'], 'name' => $commonName['category']];
                                $datas[$i]['keywords'] = $commonName['keywords'];
                                $datas[$i]['id_tienda'] = $commonName['store_id'];
                                $datas[$i]['possition'] = $commonName['possition'];
                                if (!empty($commonName['data']['sub_category'])) {
                                    $datas[$i]['sub_category'] = ['id' => $commonName['tp_id'], 'name' => $commonName['sub_category']];
                                }
                                $i++;
                            }
                        }
                        if (isset($commonName)) unset($commonName);
                    }
                }
            }
            if (sizeof($datas) > 0) {
                foreach ($datas as $item) {
                    $response[$key] = $this->rowTableData($item);
                    $key++;
                }
            } else {
                $response[$key] = $this->rowTableData($data);
                $key++;
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
                $prevData = $this->model->getPage('store');
                $next = $prevData['data']['next'];
                $prev = $prevData['data']['prev'];
            }
        }
        return $response;
    }
    private function rowTableData($arreglo): array
    {
        $response = [];
        $index = 0;
        foreach ($arreglo as $item) {
            if (!is_null($item['handle']) && !empty($item['handle'])) {
                $eliminable = false;
                $handleParts = explode('-', $item['handle']);
                $handleSize = count($handleParts);
                for (
                    $p = 0;
                    $p < $handleSize;
                    $p++
                ) {
                    if (is_numeric($handleParts[$p])) {
                        if ($p == ($handleSize - 1)) {
                            $eliminable = true;
                        }
                    }
                }
                $collectionType = "Custom";
                $collectionTypeColor = "dark";
                if (!empty($item['rules'])) {
                    $rules = json_decode($item['rules'],true);
                    if (is_array($rules)) {
                        if (sizeof($rules['rules']) > 1) {
                            $collectionType = "Smart";
                            $collectionTypeColor = "primary";
                        }
                    }
                }
                if ($eliminable === false) {
                    $handleHandler = '<a href="#" class="btn btn-block btn-outline-secondary btn-sm" onclick="deleteCollection(\'' . $item['id'] . '\')" 
                                target="_self" title="' . $item['handle'] . '" type="text">' . $item['handle'];
                } else {
                    $handleHandler = '<a href="#" class="btn btn-block btn-outline-danger btn-sm" onclick="deleteCollection(\'' . $item['id'] . '\')" 
                            target="_self" title="' . $item['handle'] . '" type="text"><i class="fas fa-trash-alt mr-3"></i>' . $item['handle'];
                }
                $handleHandler .= '
                            <span class="badge badge-pill badge-' . $collectionTypeColor . '">' . $collectionType . '</span>
                            <span class="sr-only">Collection type</span>
                        </a>';
            }
            $state = false;
            $text = 'No Verificado';
            $check = '';
            if (isset($item['verified']) && $item['verified'] != 0) {
                $state = true;
                $text = 'Verificado';
                $check = 'checked="checked"';
            }
            $verify = '<div class="switch">
                        <label>                          
                            <input type="checkbox" id="' . $item['id'] . '-verify_' . $item['collection_id'] . '"';
            if (!is_null($item['id']) && !empty($item['id'])) {
                $verify .= ' onclick="verifyCollection(' . $item['id'] . ',' . $item['collection_id'] . ',\'' . $state . '\')"';
            } else {
                $verify .= ' onclick="verifyCollection(' . $item['id'] . ',null,\'' . $state . '\')"';
            }
            $verify .= 'data-toggle="tooltip" data-placement="top" title="' . $text . '" ' . $check . '>
                            <span class="lever"></span> 
                        </label>
                    </div>';
            $countColor = ($item['products'] > 0) ? "success" : "danger";
            $collectionTitle = '<a href="/collections/read?id=' . $item['id'] .
                                    '" class="btn btn-block btn-outline-info btn-sm" target="_self" title="' .
                                    $item['title'] . '" type="text">' . $item['title'] . '
                                    <span class="badge badge-pill badge-' . $countColor . '">' . $item['products'] . '</span>
                                </a>';
            $response[$index] = [
                'store_id' => '<a href="/collections/read?id=' . $item['id'] . '" target="_self" title="' . 
                                    $item['title'] . '" type="text">' . $item['id'] . '
                                </a>',
                'store_title' => $collectionTitle,
                'store_handle' => $handleHandler,
                'store_type' => $collectionType,
                'store_seo' => $item['seo'],
                'sort_order' => $item['sort'],
                'product_count' => $item['products'],
                'verified' => $verify,
                'id_tienda'=>null,
                'keywords' => null,
                'active' => null,
                'possition' => null,
                'name' => null,
                'handle' => null,
                'category' => null,
                'sub_category'=>null,
                'metadatos'=>null,
                'actions' => "
                    <div class='btn-group'>
                        <button type='button' class='btn btn-outline-info btn-block btn-sm dropdown-toggle dropdown-icon' data-toggle='dropdown'>
                            Eleija...
                        </button>
                        <span class='sr-only'>Acciones</span>
                        <div class='dropdown-menu' role='menu'>"
            ];
            foreach ($item['common'] as $commonName) {
                if ($item['title'] != $commonName['name']) {
                    $response[$index]['store_id']   = null;
                    $response[$index]['verified']   = null;
                    $response[$index]['store_seo']  = null;
                    $response[$index]['store_type'] = null;
                    $response[$index]['sort_order'] = null;
                    $response[$index]['store_title'] = null;
                    $response[$index]['store_handle'] = null;
                    $response[$index]['product_count'] = null;
                }
                if ($item['handle'] != $commonName['handle']) {
                    $response[$index]['store_id']   = null;
                    $response[$index]['verified']   = null;
                    $response[$index]['store_seo']  = null;
                    $response[$index]['store_type'] = null;
                    $response[$index]['sort_order'] = null;
                    $response[$index]['store_title'] = null;
                    $response[$index]['store_handle'] = null;
                    $response[$index]['product_count'] = null;
                }
                $state = 'active';
                $text = 'Inactivo';
                $check = '';
                if ($commonName['active'] > 0) {
                    $state = 'diactive';
                    $text = 'Activo';
                    $check = 'checked="checked"';
                }
                $metadatos = 0;
                $this->model->id = $commonName['id'];
                $result = $this->model->hasMetafields();
                $cant = $result['data'][0]['res'];
                if ($cant > 0) {
                    $metadatos = $cant;
                }
                if ($metadatos < 10) {
                    $badgeColor = "danger";
                } elseif ($metadatos < 25) {
                    $badgeColor = "warning";
                } elseif ($metadatos > 25) {
                    $badgeColor = "primary";
                } else {
                    $badgeColor = "danger";
                }
                $activador = '<div class="switch">
                    <label>                          
                        <input type="checkbox" id="' . $commonName['store_id'] . '-Switch" 
                            onclick="changeState(' . $commonName['id'] . ',\'' . $state . '\')" 
                            data-toggle="tooltip" data-placement="top" title="' . $text . '" ' . $check . '/>
                        <span class="lever"></span> 
                    </label>
                </div><span class="badge badge-pill badge-primary">' . $text . '</span>';
                $response[$index]['name'] = '<a href="/collections/read?id=' . $commonName['id'] . '" 
                        class="btn btn-block btn-outline-info btn-sm" target="_self" title="' . $commonName['name'] . '" 
                        type="text">' .
                        $commonName['name'] . '
                        <span class="badge badge-' . $badgeColor . '">' . $metadatos . ' <sub>Tags</sub></span>
                    </a>';
                $response[$index]['active'] = $commonName['active'];
                $response[$index]['handle'] = '<a href="#" class="btn btn-block btn-outline-secondary btn-sm" onclick="deleteCommonName(\'' . $commonName['id'] . '\')" 
                                                target="_self" title="' . $commonName['handle'] . '" type="text">' . $commonName['handle'] . '</a>';
                $response[$index]['keywords'] = $commonName['keywords'];
                $response[$index]['category'] = '<button type="button" class="btn btn-';
                $response[$index]['category'] .= (!empty($commonName['tc_id'])) ? "primary" : "danger";
                $response[$index]['id_tienda'] = $commonName['store_id'];
                $response[$index]['possition'] = $commonName['possition'];
                if (!empty($commonName['category'])) {
                    $response[$index]['category'] .= ' btn-block btn-sm" data-toggle="modal" data-target="#type-Changer" data-collectid="' . $commonName['store_id'] . '" 
                                                        data-prodcat="' . $commonName['category'] . '">' . $commonName['category'] . '</button>';
                } else {
                    $response['category'] = '<button type="button" class="btn btn-primary btn-block btn-sm" data-toggle="modal" 
                                            data-target="#type-Changer" data-collectid="' . $commonName['store_id'] . '" data-prodcat="">Asignar</button>';
                }
                $response[$index]['sub_category'] = '<button type="button" class="btn btn-';
                $response[$index]['sub_category'] .= (!empty($commonName['tp_id'])) ? "primary" : "danger";
                if (!empty($commonName['sub_category'])) {
                    $response[$index]['sub_category'] .= ' btn-block btn-sm" data-toggle="modal" data-target="#type-Changer" data-collectid="' . $commonName['store_id'] . '"
                                                    data-prodtype="' . $commonName['tp_id'] . '">' .
                    $commonName['sub_category'] . '</button>';
                } else {
                    $response[$index]['sub_category'] = '<button type="button" class="btn btn-danger btn-block btn-sm" data-toggle="modal" 
                                                data-target="#type-Changer" data-collectid="' . $item['store_id'] . '" data-prodcat="">Asignar</button>';
                }
                $response[$index]['metadatos'] = $metadatos;
                $response[$index]['actions'] .= '<div class="dropdown-divider"></div>' . $activador;
                $response[$index]['actions'] .= '</div></div>';
                $index++;
            }
        }
        
        /* 
        foreach ($item['actions']['common'] as $action) {
            switch ($action) {
                case "detalles":
                    $response['actions'] .= '
                            <a href="/collections/read?id=' . $item['id'] . '"
                                title="Ver detalles de colección" target="_self"
                                type="text" class="btn btn-success btn-block btn-sm">
                                <i class="fas fa-eye mr-3"></i>Detalles
                            </a>';
                    break;
                case "eliminar":
                    $response['actions'] .=
                        '<button type="button" class="btn btn-default" data-toggle="modal" data-target="#deleteCommon_modal"
                                data-CommonID="' . $item['id'] . '"
                                title="Borra una colección localmente" 
                                target="_self" type="text" 
                                class="btn btn-danger btn-block btn-sm">
                                <i class="fas fa-trash mr-3"></i>Borrar Nombre Común
                            </button>';
                    break;
                case "sync":
                    $response['actions'] .= '
                            <a href="#" onclick="syncCollection(\'' . $item['store_id'] . '\'")
                                title="Sincroniza datos de tienda a local" 
                                target="_self" type="text" class="btn btn-primary btn-block btn-sm">
                                <i class="fas fa-trash mr-3"></i>Sincronizar
                            </a>';
                    break;
                case "editar":
                    $response['actions'] .= '
                            <a href="/collections/edit?id=' . $item['id'] . '"
                                title="Edita datos local" 
                                target="_self" type="text" class="btn btn-warning btn-block btn-sm">
                                <i class="fas fa-edit mr-3"></i>Editar
                            </a>';
                    break;
                case "name":
                    $response['actions'] .= '
                            <button type="button" class="btn btn-default" data-toggle="modal" data-target="#editCommon_modal"
                                data-CommonID="' . $item['id'] . '"
                                data-CommonField="name"
                                title="Editar Nombre" 
                                target="_self" type="text" 
                                class="btn btn-danger btn-block btn-sm">
                                <i class="fas fa-pen mr-3"></i>Editar Nombre
                            </button>';
                    break;
                case "handle":
                    $response['actions'] .= '
                            <button type="button" class="btn btn-default" data-toggle="modal" data-target="#editCommon_modal"
                                data-CommonID="' . $item['id'] . '"
                                data-CommonField="handle"
                                title="Editar handle" 
                                target="_self" type="text" 
                                class="btn btn-danger btn-block btn-sm">
                                <i class="fas fa-pen mr-3"></i>Editar handle
                            </button>';
                    break;
                default:
                    $response['actions'] .= '
                            <a href="#" onclick="syncCollection(\'' . $item['store_id'] . '\'")
                                title="Sincroniza datos de tienda a local" 
                                target="_self" type="text" class="btn btn-primary btn-block btn-sm">
                                <i class="fas fa-trash mr-3"></i>Crear
                            </a>';
                    break;
            }
        }
        if (!empty($item['actions']['common'])) $response['actions'] .= '<div class="dropdown-divider"></div>';
        foreach ($item['actions']['collection'] as $action) {
            switch ($action) {
                case 'eliminar':
                    $response['actions'] .=
                        '<button type="button" class="btn btn-default" data-toggle="modal" data-target="#deleteCollection_modal"
                            data-CollectionID="' . $item['store_id'] . '"
                            title="Borra una colección en nube" 
                            class="btn btn-danger btn-block btn-sm">
                            <i class="fas fa-trash mr-3"></i>Borrar Colección
                        </button>';
                    break;
                case 'crear':
                    $response['actions'] .=
                        '<a href="/collections/crear/' . $item['store_id'] . '"
                            title="Crear colección" 
                            target="_self" type="text" 
                            class="btn btn-secondary btn-block btn-sm">
                            <i class="fas fa-list mr-3"></i>Crear Colección
                        </a>';
                    break;
                default:
                    $response['actions'] .= '
                        <a href="/collections/compare?id=' . $item['store_id'] . '"
                            title="Compara los datos de una colección" 
                            target="_self" type="text" class="btn btn-info btn-block btn-sm">
                            <i class="fas fa-copy mr-3"></i>Comparar
                        </a>';
                    break;
            }
        }
         */
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
    private function searchCommonNames()
    {
        $commonNames = $this->model->search('common_names');
        $data = [];
        if (empty($commonNames['error'])) {
            if (!empty($commonNames['data'])) {
                $indice = 0;
                foreach ($commonNames['data'] as $commonName) {
                    $data[$indice] = [
                        'id' => $commonName['id'],
                        'name' => $commonName['name'],
                        'date' => $commonName['date'],
                        'active' => $commonName['active'],
                        'handle' => $commonName['handle'],
                        'actions' => ['common' => [], 'collection' => []],
                        'category' => ['id' => $commonName['tc_id'], 'name' => $commonName['category']],
                        'keywords' => $commonName['keywords'],
                        'id_tienda' => $commonName['store_id'],
                        'possition' => $commonName['store_id'],
                        'sub_category' => ['id' => $commonName['tp_id'], 'name' => $commonName['sub_category']],
                    ];
                    $this->model->id = $commonName['store_id'];
                    $collection = $this->model->find();
                    if (empty($collection['data'])) {
                        $this->model->handle = $commonName['handle'];
                        $collection = $this->model->find();
                        /* if (empty($collection['data'])) {
                            $this->model->title = $commonName['name'];
                            $collection = $this->model->find();
                        } */
                    }
                    if (!empty($collection['data'])) {
                        if (sizeof($collection['data']) == 1) {
                            $metagatos = $this->hasMetadatos($commonName['tp_id']);
                            $data[$indice]['store_id'] = $collection['data'][0]['id'];
                            $data[$indice]['verified'] = $collection['data'][0]['verified'];
                            $data[$indice]['store_seo'] = $collection['data'][0]['seo'];
                            $data[$indice]['metadatos'] = (!empty($metagatos['data'])) ? $metagatos['data'][0]['res'] : 0;
                            $data[$indice]['sort_order'] = $collection['data'][0]['sort'];
                            $data[$indice]['store_meta'] = $collection['data'][0]['meta'];
                            $data[$indice]['store_title'] = $collection['data'][0]['title'];
                            $data[$indice]['store_handle'] = $collection['data'][0]['handle'];
                            $data[$indice]['product_count'] = $collection['data'][0]['products'];
                            $data[$indice]['collection_type'] = (!empty($collection['data'][0]['rules'])) ? 'Smart' : 'Custom';
                            $indice++;
                        }
                    }
                }
            }
        }
        if (!empty($commonNames['error'])) {
            $result = [
                'collections' => [],
                'pagination' => [],
                'error' => $this->messenger->messageBuilder('alert', $this->messenger->build('error', $commonNames['error']))
            ];
        } else {
            $result = [
                'collections' => $data,
                'pagination' => []
            ];
        }
        return $result;
    }
    private function searchCollections()
    {
        $collections = $this->model->search('collections');
        $mixedcommonNames = array();
        if (empty($collections['error'])) {
            if (!empty($collections['data'])) {
                $mixedcommonNames = [
                    'collections' => $this->nombresComunes($collections['data']),
                    'pagination' => []
                ];
            } else {
                $mixedcommonNames = [
                    'collections' => [],
                    'pagination' => [],
                    'error' => $this->messenger->messageBuilder(
                        'alert',
                        $this->messenger->build('error', [
                            'code' => '00404',
                            'message' => "Sin informacion"
                        ])
                    )
                ];
            }
        } else {
            $mixedcommonNames = [
                'collections' => [],
                'pagination' => [],
                'error' => $this->messenger->messageBuilder(
                    'alert',
                    $this->messenger->build('error', $collections['error'])
                )
            ];
        }
        return $mixedcommonNames;
    }
    private function getDataFill()
    {
        $this->model->limit = 0;
        $result = $this->model->localGet('commonName');
        $data = array();
        foreach ($result['data'] as $key => $nombre) {
            $data[$key] = [
                'id' => $nombre['store_id'],
                'value' => $nombre['name']
            ];
        }
        return $data;
    }
    private function unverified($collection, $common = null, $current = null): array
    {
        $this->model->id = $collection;
        $this->model->tipo = $common;
        return $this->model->setVerification('change', $current);
    }
    private function setVerification($collection, $common = null)
    {
        $this->model->id = $collection;
        $this->model->tipo = $common;
        return $this->model->setVerification('set', true);
    }
    private function syncData($values)
    {
        if (!empty($values['id'])) $this->model->id;
        $collection = $this->model->find();
        if (empty($collection['error'])) {
            if (!empty($collection['data'])) {
                $common = $this->model->localGet('commonName');
                if (sizeof($common['data']) > 1) {
                    return [
                        'type' => "view",
                        'collection' => $collection,
                        'common' => $common
                    ];
                } else {
                    $collectionData = $collection['data'][0];
                    $commonData = $common['data'][0];
                    if ($collectionData['title'] == $commonData['name']) $this->model->title = $collectionData['title'];
                    if ($collectionData['handle'] == $commonData['handle']) $this->model->handle = $collectionData['handle'];
                    return $this->messenger->messageBuilder('alert', [
                        'type' => "success",
                        'message' => "Informacion actualizada"
                    ]);
                }
            } else {
                return $this->messenger->messageBuilder('alert', [
                    'type' => "warning",
                    'message' => "Informacion invalida"
                ]);
            }
        } else {
            return $this->messenger->messageBuilder(
                'alert',
                $this->messenger->build('error', $collection['error'])
            );
        }
    }
}
