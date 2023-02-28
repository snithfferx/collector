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
     * Cambia el valor de verificación de una colección en la base de datos
     * @param mixed $values Contiene los datos necesarios para realizar la verificación de una colección.
     * ['id_collection' ID de la colección,'id_common' ID del nombre común,'current' Estado actual de la verificaión]
     * @return array
     */
    public function verify($values)
    {
        return $this->collectionVerified($values);
    }
    public function download($val)
    {
        if (!empty($val)) {
            if ($val == "proceed") {
                return $this->getdownloadedCollections();
            } else {
                return $this->getCheckDownloads();
            }
        }
        return $this->createViewData('collections/Downloadedlist');
    }
    public function fill($value)
    {
        $this->model->title = $value['term'];
        return $this->getDataFill();
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
        $result = $this->deleteElement($values);
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
    /* public function compare($value)
    {
        return (!is_null($value)) ? $this->getCompareData($value) : $this->getCompareData();
    } */
    /* public function sync($value)
    {
    } */


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
            $result = $this->createViewData($viewName, $result,
                $this->createBreadcrumbs([
                'view'=>$viewName,
                'children'=>[
                    ['main'=>"collections", 'module'=>"collections", 'method'=>"index"],
                    ['module'=>"collections", 'method'=>"read", 'params'=>$value]
                ]]));
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
    protected function getCheckDownloads()
    {
        return $this->checkDownloadProgress();
    }
    protected function deleteElement(array $values): array
    {
        $this->model->id = $values['id'];
        $result = $this->model->delete($values['where']);
        if (!empty($result['error'])) {
            return [
                'data'=>$result['data'],
                'error'=>$this->messenger->build('error',[]),
            ];
        } else {
            return [
                'data'=>$result['data'],
                'message'=>$this->messenger->build('message',[]),
                'error'=>[]];
        }
    }



    /* protected function getCompareData($value = "all"): array
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
    } */
    //protected function getNextPage($values)
    //{
    /* $page = $values['page'];
        $viewData = [];
        $viewtype = "template";
        $viewCode = null;
        $viewName = "collections/list";
        if (is_array($values)) { */
    //    $result = $this->commonNames($values);
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
    //    return $result['data'];
    //}
    /* protected function getPreviousPage($values): array
    {
        $page = $values['page'];
        $viewData = [];
        $viewtype = "template";
        $viewCode = null;
        $viewName = "collections/list";
        if (is_array($values)) { */
    /* if ($values['view_origin'] == "compare") {
                $result = $this->getCollectionsPage($values['page']);
            } else { */
    /* $result = $this->commonNames($values);
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
    } */
    /* protected function createCollection($values)
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
    } */
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
            sleep(30);
        } while ($page['hasNextPage'] == true);
        if (empty($result)) {
            $response = $this->createViewData('_shared/_error', ['error' => ['message' => "Hay errores al crear collecciones", 'data' => $result]], [], 'template', 500);
        } else {
            $response = $this->collectionByParams();
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
        if (isset($values['cat']) || isset($values['scat'])) {
            return $this->searchCommonNames();
        } else {
            return $this->searchCollections();
        }
    }
    protected function collectionVerified($values): array
    {
        $result = $this->setVerification($values);
        if (!empty($result['error'])) {
            $msg = $this->messenger->build('message', $result['error']);
        } else {
            $msg = $this->messenger->build(
                'message',
                [
                    'type' => "success",
                    'code' => "00200",
                    'message' => "Petición realizada con éxito"
                ]
            );
        }
        return $this->messenger->messageBuilder('alert', $msg);
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
    /* protected function syncronizeCommonName($values)
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
    } */

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
                $this->unsetVars(['k', 'collection', 'nombre', 'i']);
                $response = ['collections' => $data, 'view' => "collection_details"];
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
            if (is_numeric($value)) {
                $this->model->id = $value;    
            } else {
                $this->model->title = $value;
            }
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
                        if (isset($data[$col['collection_id']])) {
                            $data[$col['collection_id']]['common'][] = $nombre;
                        } else {
                            $data[$col['collection_id']] = $col;
                            $data[$col['collection_id']]['common'][] = $nombre;
                        }
                    }
                } else {
                    if (isset($data['No asignado'])) {
                        $data['No asignado']['common'][] = $nombre;
                    } else {
                        $data['No asignado'] = [
                            'id' => null,
                            'seo' => null,
                            'sortr' => null,
                            'store_meta' => null,
                            'title' => null,
                            'handle' => null,
                            'products' => null,
                            'rules' => null,
                            'common' => $nombre
                        ];
                    }
                }
            }
            $response = [
                'view' => "detail",
                'collections' => $data,
                'error' => []
            ];
            $this->unsetVars(['k', 'collection', 'nombre', 'i', 'col']);
        } else {
            $response = [
                'view'=>"detail",
                'collections'=>[],
                'error' => $this->messenger->build('error', ['code' => '00404'])
            ];
        }
        return  $response;
    }
    private function collectionByParams($values = null)
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
                    foreach ($cn['data'] as $i => $nombre) {
                        $data[$k]['common'][$i] = $nombre;
                    }
                }
            }
            $this->unsetVars(['k', 'collection', 'nombre', 'i']);
            $response = ['collections' => $this->rowTableData($data), 'pagination' => []];
        } else {
            $msg = $this->messenger->build('error', ['code' => "00404"]);
            $response = $this->messenger->messageBuilder('alert', $msg, 'json');
        }
        return $response;
    }
    private function commonNameByParams($values = null)
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
            $response = $this->rowTableData($data);
        } else {
            $msg = $this->messenger->build('error', ['code' => "00404"]);
            $response = $this->messenger->messageBuilder('alert', $msg, 'json');
        }
        return $response;
    }

    /* private function compareCollections($value = 100)
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
                $commonName = $this->model->get();
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
    } */
    /**
     * Función que devuelve las colecciones creadas
     * @param int $limit
     * @return array
     */
    /* private function commonNames($value = []): array
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
    } */

    /* private function makeURL($datos)
    {
        $urlString = "";
        if (is_array($datos)) {
            $x = 0;
            $s = sizeof($datos);
            $urlString = "?";
            $urlString .= preg_replace("/%5B[0-9]+%5D/", "%5B%5D", http_build_query($datos));
        }
        return $urlString;
    } */
    /* private function pagination($prev, $next, $max, $limit)
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
    } */
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
                    $rules = json_decode($item['rules'], true);
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
            $state = 'false';
            $text = 'No Verificado';
            $check = '';
            if (isset($item['verified']) && $item['verified'] != 0) {
                $state = 'true';
                $text = 'Verificado';
                $check = 'checked="checked"';
            }
            $verify = '<div class="switch">
                        <label>                          
                            <input type="checkbox" id="' . $item['collection_id'] . '-verify_' . $item['collection_id'] . '"
                             onclick="verifyCollection(' . $item['collection_id'] . ',' . $state . ')" data-toggle="tooltip" 
                             data-placement="top" title="' . $text . '" ' . $check . '/>
                            <span class="lever"></span> 
                        </label>
                    </div>';
            $countColor = ($item['products'] > 0) ? "success" : "danger";
            $collectionTitle = '<a href="/collections/read?id=' . $item['id'] .
                '" class="btn btn-block btn-outline-info btn-sm" target="_self" title="' .
                $item['title'] . '" type="text">' . $item['title'] . '
                                    <span class="badge badge-pill badge-' . $countColor . '">' . $item['products'] . ' Prods</span>
                                </a>';
            $response[$index] = [
                'title' => $item['title'],
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
                'id_tienda' => null,
                'keywords' => null,
                'active' => null,
                'possition' => null,
                'name' => null,
                'handle' => null,
                'category' => null,
                'sub_category' => null,
                'metadatos' => null,
                'actions' => "
                    <div class='btn-group'>
                        <button type='button' class='btn btn-outline-info btn-block btn-sm dropdown-toggle dropdown-icon' data-toggle='dropdown'>
                            Eleija...
                        </button>
                        <span class='sr-only'>Acciones</span>
                        <div class='dropdown-menu' role='menu'>"
            ];
            $acciones = ['del_global', 'del_store', 'del_base', 'edit'];
            $noName = false;
            $noHandle = false;
            if (!empty($item['common'])) {
                foreach ($item['common'] as $commonName) {
                    if ($commonName['store_id'] != $item['id']) array_push($acciones, 'create');
                    if ($item['title'] != $commonName['name']) {
                        $response[$index]['store_id']   = null;
                        $response[$index]['title']      = null;
                        $response[$index]['store_seo']  = null;
                        $response[$index]['store_type'] = null;
                        $response[$index]['sort_order'] = null;
                        $response[$index]['store_title'] = null;
                        $response[$index]['store_handle'] = null;
                        $response[$index]['product_count'] = null;
                        $noName = true;
                    }
                    if ($item['handle'] != $commonName['handle']) {
                        $response[$index]['store_id']   = null;
                        $response[$index]['title']   = null;
                        $response[$index]['store_seo']  = null;
                        $response[$index]['store_type'] = null;
                        $response[$index]['sort_order'] = null;
                        $response[$index]['store_title'] = null;
                        $response[$index]['store_handle'] = null;
                        $response[$index]['product_count'] = null;
                        $noHandle = true;
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
                            <span class="badge badge-' . $badgeColor . '">' . $metadatos . ' Tags</span>
                        </a>';
                    $response[$index]['active'] = $commonName['active'];
                    $response[$index]['handle'] = '<a href="#" class="btn btn-block btn-outline-secondary btn-sm" onclick="deleteCommonName(\'' . $commonName['id'] . '\')" 
                                                    target="_self" title="' . $commonName['handle'] . '" type="text">' . $commonName['handle'] . '</a>';
                    $response[$index]['keywords'] = $commonName['keywords'];
                    $response[$index]['category'] = '<button type="button" class="btn btn-';
                    if (!empty($commonName['tc_id'])) {
                        $response[$index]['categoria'] = ['id' => $commonName['tc_id'], 'name' => $commonName['category']];
                        $response[$index]['category'] .= "primary";
                    } else {
                        $response[$index]['category'] .= "danger";
                        $response[$index]['categoria'] = ['id' => null, 'name' => null];
                    }
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
                    if (!empty($commonName['tp_id'])) {
                        $response[$index]['sub_category'] .= 'primary btn-block btn-sm" data-toggle="modal" data-target="#type-Changer" data-collectid="' . $commonName['store_id'] . '"
                                                        data-prodtype="' . $commonName['tp_id'] . '">' . $commonName['sub_category'] . '</button>';
                        $response[$index]['subcategoria'] = ['id' => $commonName['tp_id'], 'name' => $commonName['sub_category']];
                    } else {
                        $response[$index]['subcategoria'] = ['id' => null, 'name' => null];
                        $response[$index]['sub_category'] = 'danger btn-block btn-sm" data-toggle="modal" 
                                                    data-target="#type-Changer" data-collectid="' . $item['store_id'] . '" data-prodcat="">Asignar</button>';
                    }
                    $response[$index]['metadatos'] = $metadatos;
                    foreach ($acciones as $action) {
                        switch ($action) {
                            case "create":
                                $response[$index]['actions'] .= '
                                <a href="/collections/create/' . $commonName['id'] . '"
                                    title="Crear colección" target="_self"
                                    type="text" class="btn btn-Primary btn-block btn-sm">
                                    <i class="fas fa-box mr-3"></i>Crear
                                </a>';
                                break;
                            case "edit":
                                $response[$index]['actions'] .= '
                                <a href="/collections/update/' . $commonName['id'] . '"
                                    title="Editar colección" target="_self"
                                    type="text" class="btn btn-info btn-block btn-sm">
                                    <i class="fas fa-pen mr-3"></i>Editar
                                </a>';
                                break;
                            case "del_global":
                                $response[$index]['actions'] .= '
                                <a href="/collections/delete?id=' . $item['id'] . '&where=global"
                                    title="Crear colección" target="_self"
                                    type="text" class="btn btn-danger btn-block btn-sm">
                                    <i class="fas fa-globe mr-3"></i>Borrar
                                </a>';
                                break;
                            case "del_store":
                                $response[$index]['actions'] .= '
                                <a href="/collections/delete?id=' . $item['id'] . '&where=store"
                                    title="Borrar colección en tienda" target="_self"
                                    type="text" class="btn btn-warning btn-block btn-sm">
                                    <i class="fas fa-cloud mr-3"></i>Borrar en tienda
                                </a>';
                                break;
                            case "del_base":
                                $response[$index]['actions'] .= '
                                <a href="/collections/delete?id=' . $commonName['id'] . '&where=local"
                                    title="Borrar colección en local" target="_self"
                                    type="text" class="btn btn-secondary btn-block btn-sm">
                                    <i class="fas fa-database mr-3"></i>Borrar en local
                                </a>';
                                break;
                            default:
                                $response[$index]['actions'] .= '
                                <a href="/collections/sync?id=' . $commonName['id'] . '"
                                    title="Sincronizar" target="_self"
                                    type="text" class="btn btn-dark btn-block btn-sm">
                                    <i class="fas fa-sync mr-3"></i>Sincronizar
                                </a>';
                                break;
                        }
                    }
                    $response[$index]['actions'] .= '<div class="dropdown-divider"></div>' . $activador;
                    $response[$index]['actions'] .= '</div></div>';
                    $index++;
                }
            } else {
                $response[$index]['name'] = null;
                $response[$index]['active'] = null;
                $response[$index]['handle'] = null;
                $response[$index]['keywords'] = null;
                $response[$index]['category'] = null;
                $response[$index]['id_tienda'] = null;
                $response[$index]['possition'] = null;
                $response[$index]['sub_category'] = null;
                $response[$index]['metadatos'] = null;
                $response[$index]['actions'] .= '
                <a href="/collections/create/' . $item['id'] . '"
                    title="Crear colección" target="_self"
                    type="text" class="btn btn-Primary btn-block btn-sm">
                    <i class="fas fa-box mr-3"></i>Crear
                </a>
                <a href="/collections/delete?id=' . $item['id'] . '&where=global"
                    title="Crear colección" target="_self"
                    type="text" class="btn btn-danger btn-block btn-sm">
                    <i class="fas fa-globe mr-3"></i>Borrar
                </a>
                <a href="/collections/delete?id=' . $item['id'] . '&where=store"
                    title="Borrar colección en tienda" target="_self"
                    type="text" class="btn btn-warning btn-block btn-sm">
                    <i class="fas fa-cloud mr-3"></i>Borrar en tienda
                </a>
                <a href="/collections/sync?id=' . $item['id'] . '"
                    title="Sincronizar" target="_self"
                    type="text" class="btn btn-dark btn-block btn-sm">
                    <i class="fas fa-sync mr-3"></i>Sincronizar
                </a></div></div>';
            }
        }
        return $response;
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
        $existe = $this->model->find([
            'collection_id',
            'id'
        ]);
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
        if (empty($commonNames['error'])) {
            if (!empty($commonNames['data'])) {
                $commonNamesFull = $this->getCommonName($commonNames['data']);
                if (!empty($commonNamesFull['error'])) {
                    $result = [
                        'collections' => [],
                        'pagination' => [],
                        'error' => $this->messenger->messageBuilder('alert', $this->messenger->build('error', $commonNamesFull['error']))
                    ];
                } else {
                    $result = [
                        'collections' => $this->rowTableData($commonNamesFull),
                        'pagination' => []
                    ];
                }
            } else {
                $result = [
                    'collections' => [],
                    'pagination' => [],
                    'error' => $this->messenger->messageBuilder('alert', $this->messenger->build('error', ['code' => "00404", 'message' => "No common name found"]))
                ];
            }
        } else {
            $result = [
                'collections' => [],
                'pagination' => [],
                'error' => $this->messenger->messageBuilder('alert', $this->messenger->build('error', $commonNames['error']))
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
                $collectionsFull = $this->getCommonName($collections['data']);
                if (!empty($collectionsFull['error'])) {
                    if (!empty($collectionsFull['data'])) {
                        $mixedcommonNames = [
                            'collections' => [],
                            'pagination' => [],
                            'error' => $this->messenger->messageBuilder(
                                'alert',
                                $this->messenger->build('error', $collectionsFull['error'])
                            )
                        ];
                    } else {
                        $mixedcommonNames = [
                            'collections' => [],
                            'pagination' => [],
                            'error' => $this->messenger->messageBuilder(
                                'alert',
                                $this->messenger->build('error', ['code' => "00404", 'message' => "Data not found"])
                            )
                        ];
                    }
                } else {
                    $mixedcommonNames = [
                        'collections' => $this->rowTableData($collectionsFull),
                        'pagination' => []
                    ];
                }
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
        $result = $this->model->get('common_name');
        $data = array();
        foreach ($result['data'] as $key => $nombre) {
            $data[$key] = [
                'id' => $nombre['store_id'],
                'value' => $nombre['name']
            ];
        }
        return $data;
    }
    private function setVerification($data): array
    {
        $this->model->id = ($data['id_collection']) ?? null;
        //$this->model->id_store = ($data['id_common']) ?? null;
        $state = ($data['current']) ?? false;
        $result = $this->model->setVerification('set_collection', $state);
        /* if (!empty($result['error'])) return $result;
        if (!is_null($data['id_common'])) {
            $this->model->id = ($data['id_common']) ?? null;
            $result = $this->model->setVerification('set_common', $state);
            if (!empty($result['error'])) return $result;
        } */
        return $result;
    }
    private function syncData($values)
    {
        if (!empty($values['id'])) $this->model->id;
        $collection = $this->model->find();
        if (empty($collection['error'])) {
            if (!empty($collection['data'])) {
                $common = $this->model->get('commonName');
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
    private function getCollection(int|array $values): array
    {
        if (is_array($values)) {
            foreach ($values as $k => $nombre) {
                $this->model->id = $nombre['store_id'];
                $collections = $this->model->get('collection', 'all');
                $data = array();
                if (!empty($collections['data'])) {
                    if (sizeof($collections['data']) > 1) {
                        foreach ($collections as $key => $collection) {
                            $data[$key] = $collection;
                            $data[$key]['common'] = array();
                            if ($collection['title'] === $nombre['name']) {
                                if ($collection['handle'] === $nombre['handle']) {
                                    $data[$key]['common'] = $nombre;
                                }
                            }
                        }
                    } elseif (sizeof($collections['data']) == 1) {
                        $data = $collections['data'][0];
                        $data['common'] = $nombre;
                    } else {
                        return ['error' => [
                            'message' => "No hay colección",
                            'code' => "00404"
                        ]];
                    }
                } else {
                    return $collections;
                }
                $values[$k] = $data;
            }
            return $values;
        } else {
            $this->model->id = $values;
            $collections = $this->model->get('collection', 'all');
            if (!empty($collections['data'])) {
                return $collections['data'];
            } else {
                return $collections;
            }
        }
    }
    private function getCommonName(int|array $values): array
    {
        if (is_array($values)) {
            $data = array();
            foreach ($values as $k => $collection) {
                $data[$k] = $collection;
                $data[$k]['common'] = array();
                $this->model->id_store = $collection['id'];
                $cn = $this->model->get('common_name', 'all');
                if (empty($cn['error']) && !empty($cn['data'])) {
                    foreach ($cn['data'] as $i => $nombre) {
                        $data[$k]['common'][$i] = $nombre;
                    }
                } else {
                    if (!empty($cn['error'])) return $cn;
                }
            }
            return $data;
        } else {
            $this->model->id_store = $values;
            $cn = $this->model->get('common_name', 'all');
            if (empty($cn['error']) && !empty($cn['data'])) {
                return $cn['data'];
            } else {
                return $cn;
            }
        }
    }
    private function checkDownloadProgress()
    {
        $result = $this->model->calcular('collections');
        if (!empty($result['error'])) {
            return $result;
        } else {
            return [
                'data' => $result['data'][0]['res'],
                'erro' => []
            ];
        }
    }
}
