<?php

/**
 * Clase para las transacciones entre los modelos y la base de datos.
 * @author Jorge Echeverria <jecheverria@bytes4run.com>
 * @version 1.0.0
 */

namespace app\modules\collections\models;

use app\core\classes\context\ContextClass;
use app\core\classes\context\ExternalContext;

class CollectionModel extends ContextClass
{
    private $external;
    public $fields;
    public $limit = 0;
    public $id = null;
    public $id_store = null;
    public $title = null;
    public $categoria = null;
    public $tipo = null;
    public $activo = false;
    public $page = null;
    public $cursor = null;
    public $handle = null;
    public $products = null;
    public $order = null;
    public $seo = null;
    public $rules = [];
    public $graphQL_id;
    private $element;
    public $letra;
    public $keywords;
    public function __construct()
    {
        $this->external = new ExternalContext;
        $this->element = 'collection';
        $this->base = "shopify";
    }
    public function storeGet(): array
    {
        //$response = $this->getCollections();
        $response = $this->grafkuel();
        return $response;
    }
    public function get(string $element = 'collections', array|string $fields = []): array
    {
        if ($element == "colecciones") {
            return $this->getAllColecciones($fields);
        } elseif ($element == "common_names") {
            return $this->getCommonNames($fields);
        } elseif ($element == "common_name") {
            return $this->getCommonNames($fields);
        } elseif ($element == "collection") {
            return $this->getLocalCollections($fields);
        } elseif ($element == "isCategory") {
            return $this->isCategory();
        } else {
            return ['error' => ['code' => 404, 'message' => "Element Not Found"], 'data' => []];
        }
    }
    public function calcular($elemento, $calculo = "count")
    {
        if ($elemento == "collections") {
            return $this->calcCollections($calculo);
        } elseif ($elemento == "common") {
            return $this->calcCommonNames($calculo);
        } else {
            return $this->countMetadata();
        }
    }
    public function hasMetafields()
    {
        return $this->countMetadata();
    }
    public function find($fields = "all")
    {
        return $this->getLocalCollections($fields);
    }
    public function search($value)
    {
        if ($value == "common_names") {
            return $this->getSearchedCommonNames();
        } elseif ($value == "collections") {
            return $this->getSearchedCollections();
        } else {
            return ['error' => ['code' => 404, 'message' => "Element Not Found"], 'data' => []];
        }
    }
    public function setVerification($method, $current)
    {
        if ($method === "set_collection") {
            $result = $this->setVerificationCollection($current);
        } elseif ($method === "set_common") {
            $result = $this->setVerificationCommon($current);
        } else {
            return ['error' => ['code' => "00404", 'message' => "No collection found"], 'data' => array()];
        }
        return $result;
    }
    public function deleteFrom($ubicacion) :array
    {
        if ($ubicacion == 'global') {
            $result = $this->deleteAll();
        } elseif ($ubicacion == 'store') {
            $result = $this->deleteInStore();
        } elseif ($ubicacion == 'local') {
            $result = $this->deleteInLocal();
        } else {
            return ['error' => ['code' => "00400", 'message' => "Ubicación no reconocida"], 'data' => array()];
        }
        return $result;
    }
    public function confirmChange ($change) {
        return $this->setChange($change);
    }
    public function newStoreCollection() {
        return $this->createStoreCollection();
    }


    public function getPage($value)
    {
        return $this->getCollectionsPage($value);
    }
    public function createCollection()
    {
        return $this->create('collection');
    }
    
    protected function getMetafields()
    {
        return $this->getMetadata();
    }
    protected function deleteAll()
    {
        $oldCollection = $this->select(
            "temp_shopify_collector",
            [
                'fields' => [
                    'temp_shopify_collector' => [
                        'collection_id',
                        'id',
                        'title',
                        'handle',
                        'productsCount=products',
                        'sortOrder=sort',
                        'ruleSet=rules',
                        'metafields=meta',
                        'seo',
                        'gqid',
                        'verified'
                    ]
                ],
                'params' => [
                    'condition' => [
                        [
                            'type' => "COMPARE",
                            'table' => "temp_shopify_collector",
                            'field' => "id",
                            'value' => $this->id
                        ]
                    ],
                    'separator' => array()
                ]
            ]
        );
        $this->base = "default";
        $oldCommon = $this->select(
            'nombre_comun',
            [
                'fields' => [
                    'nombre_comun' => [
                        'id_nombre_comun=id',
                        'nombre_comun=name',
                        'posicion=possition',
                        'fecha_creacion=date',
                        'activo=active',
                        'id_tienda=store_id',
                        'handle',
                        'terminos_de_busqueda=keywords'
                    ],
                    'tipo_producto' => ['id_tipo_producto=tp_id', 'tipo_producto=sub_category'],
                    'tipo_categoria' => ['id_tipo_categoria=tc_id', 'tipo_categoria=category']
                ],
                'joins' => [
                    [
                        'type' => "INNER",
                        'table' => "tipo_producto",
                        'filter' => "id_tipo_producto",
                        'compare_table' => "nombre_comun",
                        'compare_filter' => "id_tipo_producto"
                    ],
                    [
                        'type' => "INNER",
                        'table' => "tipo_categoria",
                        'filter' => "id_tipo_categoria",
                        'compare_table' => "tipo_producto",
                        'compare_filter' => "id_tipo_categoria"
                    ]
                ],
                'params' => [
                    'condition' => [
                        [
                        'type' => "COMPARE",
                        'table' => "nombre_comun",
                        'field' => "id_tienda",
                        'value' => $this->id
                    ]
                    ],
                    'separator' => array()
                ]
            ]
        );
        $changes = array();
        $errores = array();
        $this->base = "shopify";
        foreach ($oldCollection['data'] as $collection) {
            $query = [
                'fields' => ['change','field','ubication','collection','syncronized','executed'],
                'values' => ["delete","all","global", $collection['collection_id'],0,0]
            ];
            $res = $this->insert("changes", $query);
            if (empty($res['error'])) {
                $changes[] = $res['data'];
            } else {
                $errores[] = $res['error'];
            }
        }
        foreach ($oldCommon['data'] as $commonName) {
            $query = [
                'fields' => ['change', 'field', 'ubication', 'common', 'syncronized', 'executed'],
                'values' => ["delete", "all", "global", $commonName['id'], 0, 0]
            ];
            $res = $this->insert("changes", $query);
            if (empty($res['error'])) {
                $changes[] = $res['data'];
            } else {
                $errores[] = $res['error'];
            }
        }
        return ['collections'=>$oldCollection['data'],'commonNames'=>$oldCommon['data'],'changes'=>$changes,'errors'=>$errores];
    }
    protected function deleteInStore()
    {
        $oldCollection = $this->getLocalCollections('all');
        $this->id_store = $this->id;
        $this->id = null;
        $oldCommon = $this->getCommonNames('all');
        $changes = array();
        $errores = array();
        $this->base = "shopify";
        if (sizeof($oldCollection['data']) > 0) {
            foreach ($oldCollection['data'] as $collection) {
                $query = [
                    'fields' => ['change', 'field', 'ubication', 'collection', 'syncronized', 'executed'],
                    'values' => ["delete", "all", "store", $collection['collection_id'], $collection['verified'], 0]
                ];
                $res = $this->insert("changes", $query);
                if (empty($res['error'])) {
                    $changes[$collection['collection_id']] = $res['data'][0];
                } else {
                    $errores[$collection['collection_id']] = $res['error'];
                }
            }
        } else {
            return ['collections' => $oldCollection['data'],
                'commonNames' => $oldCommon['data'],
                'changes' => [], 
                'errors' => ['code'=>"00404"]];
        }
        return ['collections' => $oldCollection['data'], 'commonNames' => $oldCommon['data'], 'changes' => $changes, 'errors' => $errores];
    }
    protected function deleteInLocal()
    {
        $oldCollection = $this->select(
            "temp_shopify_collector",
            [
                'fields' => [
                    'temp_shopify_collector' => [
                        'collection_id',
                        'id',
                        'title',
                        'handle',
                        'productsCount=products',
                        'sortOrder=sort',
                        'ruleSet=rules',
                        'metafields=meta',
                        'seo',
                        'gqid',
                        'verified'
                    ]
                ],
                'params' => [
                    'condition' => [
                        [
                            'type' => "COMPARE",
                            'table' => "temp_shopify_collector",
                            'field' => "collection_id",
                            'value' => $this->id
                        ]
                    ],
                    'separator' => array()
                ]
            ]
        );
        $data = array();
        $changes = array();
        $errores = array();
        foreach ($oldCollection['data'] as $collection) {
            $query = [
                'fields' => ['change', 'field', 'ubication', 'collection', 'syncronized', 'executed'],
                'values' => ["delete", "all", "local", $collection['collection_id'], 0, 0]
            ];
            $res = $this->insert("changes", $query);
            if (empty($res['error'])) {
                $changes[] = $res['data'];
            } else {
                $errores[] = $res['error'];
            }
            $this->id_store = $collection['id'];
            $this->id = null;
            $oc = $this->getCommonNames('all');
            foreach ($oc['data'] as $oldCommon) {
                $data[] = $oldCommon;
            }
            $this->base = "shopify";
        }
        return ['collections' => $oldCollection['data'], 'commonNames' => $data, 'changes' => $changes, 'errors' => $errores];
    }
    protected function createStoreCollection()
    {
        $result = $this->external->create([
            'type' => "post",
            'request' => [
                'element' => $this->element,
                'values' => [$this->title, $this->handle, $this->activo],
                'fields' => ['title', 'handle', 'published']
            ]
        ]);
        $this->id_store = $result['data'][0]['id'];
        $this->order = $result['data'][0]['sort_order'];
        $cmps = array();
        $vlrs = array();
        $args = ['id', 'tipo', 'handle', 'categoria', 'title', 'id_store'];
        foreach ($args as $item => $campo) {
            if (!is_null($this->$item) && !empty($this->$item)) {
                array_push($cmps, $campo);
                array_push($vlrs, $this->$item);
            }
        }
        $commonName = $this->insert('nombre_comun', [
            'fields' => $cmps,
            'values' => $vlrs,
            'params' => [
                'condition' => [
                    ['type' => "COMPARE", 'table' => "nombre_comun", 'field' => "id", 'value' => $cmps]
                ],
                'separator' => array()
            ]
        ]);
        return $commonName;
    }
    


    private function getCommonNames($fields): array
    {
        $this->base = "default";
        $query = [
            'fields' => [
                'nombre_comun' => [
                    'id_nombre_comun=id', 'nombre_comun=name', 'posicion=possition', 'fecha_creacion=date',
                    'activo=active', 'id_tienda=store_id', 'handle', 'terminos_de_busqueda=keywords'
                ],
                'tipo_producto' => ['id_tipo_producto=tp_id', 'tipo_producto=sub_category'],
                'tipo_categoria' => ['id_tipo_categoria=tc_id', 'tipo_categoria=category']
            ],
            'joins' => [
                [
                    'type' => "INNER",
                    'table' => "tipo_producto",
                    'filter' => "id_tipo_producto",
                    'compare_table' => "nombre_comun",
                    'compare_filter' => "id_tipo_producto"
                ],
                [
                    'type' => "INNER",
                    'table' => "tipo_categoria",
                    'filter' => "id_tipo_categoria",
                    'compare_table' => "tipo_producto",
                    'compare_filter' => "id_tipo_categoria"
                ]
            ]
        ];
        $args = ['id', 'tipo', 'handle', 'categoria', 'title', 'id_store'];
        $conditions = [];
        $separators = [];
        foreach ($args as $k => $arg) {
            if ($k != 0 && !is_null($this->$arg)) {
                if (!empty($conditions)) array_push($separators, 'Y');
            }
            switch ($arg) {
                case 'title':
                    /* Pedir Nombre común por Nombre */
                    if (!is_null($this->title)) {
                        array_push($conditions, [
                            'type' => "SIMILAR",
                            'table' => "nombre_comun",
                            'field' => "nombre_comun",
                            'value' => $this->title
                        ]);
                    }
                    break;
                case 'categoria':
                    /* Pedir Nombre común por Categoría */
                    if (!is_null($this->categoria)) {
                        array_push($conditions, [
                            'type' => "COMPARE",
                            'table' => "tipo_categoria",
                            'field' => "id_tipo_categoria",
                            'value' => $this->categoria
                        ]);
                    }
                    break;
                case 'tipo':
                    /* Pedir Nombre común por Tipo */
                    if (!is_null($this->tipo)) {
                        array_push($conditions, [
                            'type' => "COMPARE",
                            'table' => "nombre_comun",
                            'field' => "id_tipo_producto",
                            'value' => $this->tipo
                        ]);
                    }
                    break;
                case 'handle':
                    /* Pedir Nombre común por handler */
                    if (!is_null($this->handle)) {
                        array_push($conditions, [
                            'type' => "COMPARE",
                            'table' => "nombre_comun",
                            'field' => "handle",
                            'value' => $this->handle
                        ]);
                    }
                    break;
                case 'id_store':
                    /* Pedir Nombre común por id de tienda */
                    if (!is_null($this->id_store)) {
                        array_push($conditions, [
                            'type' => "COMPARE",
                            'table' => "nombre_comun",
                            'field' => "id_tienda",
                            'value' => $this->id_store
                        ]);
                    }
                    break;
                default:
                    /* Pedir nombre común por ID */
                    if (!is_null($this->id)) {
                        if (strlen($this->id) > 4) {
                            array_push($conditions, [
                                'type' => "COMPARE",
                                'table' => "nombre_comun",
                                'field' => "id_tienda",
                                'value' => $this->id
                            ]);
                        } else {
                            array_push($conditions, [
                                'type' => "COMPARE",
                                'table' => "nombre_comun",
                                'field' => "id_nombre_comun",
                                'value' => $this->id
                            ]);
                        }
                    }
                    break;
            }
        }
        $query['params'] = ['condition' => $conditions, 'separator' => $separators];
        /* Limite */
        if (!isset($response)) $response = $this->select("nombre_comun", $query, $this->limit);
        return $response;
    }
    private function getCollections()
    {
        $request['element'] = $this->element;
        $request['query'] = array();
        if (!empty($this->id)) $request['query']['id'] = $this->id;
        if (!empty($this->page)) $request['query']['page_info'] = $this->page;
        $request['query']['fields'] = (!empty($this->fields)) ? $this->fields : [];
        $request['query']['limit'] = $this->limit;
        return $this->external->getShopifyResponse($request);
    }
    private function getCollectionsPage($value)
    {
        if ($value == "store") {
            $request['element'] = $this->element;
            if (!empty($this->page)) $request['query']['page'] = $this->page;
            //if (!empty($this->cursor)) $request['query'][ 'page']['cursor'] = $this->cursor;
            $request['query']['fields'] = (!empty($this->fields)) ? $this->fields : ['id', 'title', 'handle', 'productsCount', 'sortOrder'];
            $request['query']['limit'] = $this->limit;
            //return $this->external->getShopifyResponse($request);
            return $this->external->graphQL($request);
        } else {
            return $this->getLocalCollectionsPage();
        }
    }
    private function getCountCollection()
    {
        /* $request['element'] = $this->element . "s";
        $request['query'] = [
            'id' => "count",
            'fields' => (!empty($this->fields)) ? $this->fields : []
        ];
        return $this->external->getShopifyResponse($request); */
    }
    private function calcCommonNames($calculo)
    {
        $this->base = "default";
        return $this->calculate("nombre_comun", $calculo, "id_nombre_comun");
    }
    private function calcCollections($calculo)
    {
        return $this->calculate("temp_shopify_collector", $calculo, "collection_id");
    }
    private function grafkuel()
    {
        $request['element'] = $this->element;
        $request['query']['limit'] = $this->limit;
        if (empty($this->fields)) {
            $request['query']['fields'] = (!empty($this->fields)) ? $this->fields : ['id', 'title', 'handle', 'productsCount', 'sortOrder'];
        }
        if (!empty($this->id)) $request['query']['id'] = $this->id;
        if (!empty($this->title)) $request['query']['title'] = $this->title;
        if (!empty($this->page)) $request['query']['page'] = $this->page;
        return $this->external->graphQL($request);
    }
    private function getMetadata()
    {
        $this->base = "default";
        $query = [
            'fields' => [
                'metadatos' => ['id_metadato=id', 'activo=active', 'feature'],
                'nombre_comun' => [
                    'id_nombre_comun=nc_id', 'nombre_comun=name', 'posicion=possition', 'fecha_creacion=date',
                    'id_tienda=store_id', 'handle', 'terminos_de_busqueda=keywords'
                ],
                'tipo_producto' => ['id_tipo_producto=tp_id', 'tipo_producto=sub_category'],
                'tipo_categoria' => ['id_tipo_categoria=tc_id', 'tipo_categoria=category']
            ],
            'joins' => [
                [
                    'type' => "INNER",
                    'table' => "nombre_comun",
                    'filter' => "id_nombre_comun",
                    'compare_table' => "metadatos",
                    'compare_filter' => "id_nombre_comun"
                ],
                [
                    'type' => "INNER",
                    'table' => "tipo_producto",
                    'filter' => "id_tipo_producto",
                    'compare_table' => "metadatos",
                    'compare_filter' => "id_tipo_producto"
                ],
                [
                    'type' => "INNER",
                    'table' => "tipo_categoria",
                    'filter' => "id_tipo_categoria",
                    'compare_table' => "tipo_producto",
                    'compare_filter' => "id_tipo_categoria"
                ]
            ],
        ];
        if (!empty($this->tipo) && !empty($this->id)) {
            $query['params'] = [
                'condition' => [
                    [
                        'type' => "COMPARE",
                        'table' => "tipo_producto",
                        'field' => "tipo_producto",
                        'value' => $this->tipo
                    ],
                    [
                        'type' => "COMPARE",
                        'table' => "metadatos",
                        'field' => "id_nombre_comun",
                        'value' => $this->id
                    ],
                    [
                        'type' => "COMPARE",
                        'table' => "metadatos",
                        'field' => "activo",
                        'value' => 1
                    ],
                    [
                        'type' => "COMPARE",
                        'table' => "metadatos",
                        'field' => "id_metadato",
                        'value' => 1220
                    ],
                    [
                        'type' => "COMPARE",
                        'table' => "metadatos",
                        'field' => "id_metadato",
                        'value' => 1221
                    ]
                ],
                'separator' => ['Y', 'Y', 'O', 'O']
            ];
        } else {
            if (!empty($this->title)) $query['params'] = [
                'condition' => [
                    'type' => "COMPARE",
                    'table' => "metadatos",
                    'field' => "metadato",
                    'value' => $this->title
                ],
                'separator' => null
            ];
        }
        return $this->select("metadatos", $query);
    }
    private function countMetadata()
    {
        $this->base = "default";
        return $this->calculate("metadatos", 'count', 'id_metadato', [
            'condition' => [
                [
                    'type' => "COMPARE",
                    'table' => "metadatos",
                    'field' => "id_nombre_comun",
                    'value' => $this->id
                ]
            ],
            'separator' => null
        ]);
    }
    private function create($element)
    {
        if ($element == "collection") {
            $response = $this->insert('temp_shopify_collector', [
                'fields' => [
                    'id', 'title', 'handle', 'productsCount', 'sortOrder',
                    'ruleSet', 'metafields', 'seo', 'gqid', 'verified'
                ],
                'values' => [
                    $this->id, $this->title, $this->handle, $this->products, $this->order,
                    $this->rules, $this->fields, $this->seo, $this->graphQL_id
                ]
            ]);
        } else {
            $this->base = "default";
            $response = $this->insert('nombre_comun', [
                'fields' => [
                    'id_nombre_comun', 'nombre_comun', 'handle', 'posicion', 'fecha_creacion',
                    'terminos_de_busqueda', 'metafields', 'seo'
                ],
                'values' => [
                    $this->id, $this->title, $this->handle, $this->products, $this->order,
                    $this->rules, $this->fields, $this->seo
                ]
            ]);
        }
        return $response;
    }

    private function getLocalCollections($values)
    {
        if (!empty($values)) {
            if ($values == "all") {
                $query = [
                    'fields' => [
                        'temp_shopify_collector' => [
                            'collection_id',
                            'id',
                            'title',
                            'handle',
                            'productsCount=products',
                            'sortOrder=sort',
                            'ruleSet=rules',
                            'metafields=meta',
                            'seo',
                            'gqid',
                            'verified'
                        ]
                    ]
                ];
            } else {
                $query = [
                    'fields' => [
                        'temp_shopify_collector' => $values
                    ]
                ];
            }
        }
        $this->base = "shopify";
        $args = ['id', 'handle', 'title'];
        $conditions = array();
        $separators = array();
        foreach ($args as $k => $arg) {
            if ($k != 0 && !is_null($this->$arg)) {
                if (!empty($conditions)) array_push($separators, 'Y');
            }
            switch ($arg) {
                case 'title':
                    /* Pedir Nombre común por Nombre */
                    if (!is_null($this->title)) {
                        array_push($conditions, [
                            'type' => "COMPARE",
                            'table' => "temp_shopify_collector",
                            'field' => "title",
                            'value' => $this->title
                        ]);
                    }
                    break;
                case 'handle':
                    /* Pedir Nombre común por handler */
                    if (!is_null($this->handle)) {
                        array_push($conditions, [
                            'type' => "COMPARE",
                            'table' => "nombre_comun",
                            'field' => "handle",
                            'value' => $this->handle
                        ]);
                    }
                    break;
                default:
                    /* Pedir nombre común por ID */
                    if (!is_null($this->id)) {
                        array_push($conditions, [
                            'type' => "COMPARE",
                            'table' => "temp_shopify_collector",
                            'field' => "id",
                            'value' => $this->id
                        ]);
                    }
                    break;
            }
        }
        $query['params'] = (!empty($conditions)) ? ['condition' => $conditions, 'separator' => $separators] : null;
        $response = $this->select("temp_shopify_collector", $query, $this->limit);
        if (!empty($response['data'])) {
            $number = abs($this->limit - 1);
            $last = $response['data'][$number]['collection_id'];
            $next = $this->limit;
            $prev = $last - ($this->limit - 1);
            $response['pagination'] = [
                'next_page' => $next,
                'prev_page' => $prev,
                'limit' => $this->limit
            ];
        }
        return $response;
    }
    private function isCategory()
    {
        $this->base = "default";
        $isCategory = false;
        $isCommon = false;
        $collections = [];
        $isCat = $this->select('tipo_categoria', [
            'fields' => 'all',
            'params' => [
                'condition' => [
                    [
                        'type' => "COMPARE",
                        'table' => "tipo_categoria",
                        'field' => "tipo_categoria",
                        'value' => $this->categoria
                    ]
                ],
                'separator' => null
            ]
        ]);
        if (!empty($isCat['data'])) $isCategory = true;
        $query = [
            'fields' => [
                'nombre_comun' => [
                    'id_nombre_comun=id',
                    'nombre_comun=name',
                    'posicion=possition',
                    'fecha_creacion=date',
                    'activo=active',
                    'id_tienda=store_id',
                    'handle',
                    'terminos_de_busqueda=keywords'
                ],
                'tipo_producto' => ['id_tipo_producto=tp_id', 'tipo_producto=sub_category'],
                'tipo_categoria' => ['id_tipo_categoria=tc_id', 'tipo_categoria=category']
            ],
            'joins' => [
                [
                    'type' => "INNER",
                    'table' => "tipo_producto",
                    'filter' => "id_tipo_producto",
                    'compare_table' => "nombre_comun",
                    'compare_filter' => "id_tipo_producto"
                ],
                [
                    'type' => "INNER",
                    'table' => "tipo_categoria",
                    'filter' => "id_tipo_categoria",
                    'compare_table' => "tipo_producto",
                    'compare_filter' => "id_tipo_categoria"
                ]
            ],
            'params' => [
                'condition' => [
                    [
                        'type' => "COMPARE",
                        'table' => "nombre_comun",
                        'field' => "nombre_comun",
                        'value' => $this->categoria
                    ]
                ],
                'separator' => null
            ]
        ];
        $result = $this->select("nombre_comun", $query);
        if (!empty($result['data'])) {
            $isCommon = true;
            $collections = $result['data'];
        }
        return [
            'data' => [
                'isCategory' => $isCategory,
                'isCommon' => $isCommon,
                'list' => $collections
            ], 'error' => [
                $isCat['error'], $result['error']
            ]
        ];
    }
    private function getLocalCollectionsPage()
    {
        $query = [
            'fields' => [
                'temp_shopify_collector' => [
                    'collection_id', 'id', 'title', 'handle', 'productsCount=products',
                    'sortOrder=sort', 'ruleSet=rules', 'metafields=meta', 'seo', 'gqid'
                ]
            ],
            'joins' => []
        ];
        //$args = ['id', 'handle', 'categoria', 'tipo', 'title'];
        $conditions = array();
        $separators = array();
        /* foreach ($args as $k => $arg) {
            if ($k != 0 && !is_null($this->$arg)) {
                if (!empty($conditions)) array_push($separators, 'Y');
            }
            switch ($arg) {
                case 'title':
                    // Pedir Nombre común por Nombre 
                    if (!is_null($this->title)) {
                        array_push($conditions, [
                            'type' => "SIMILAR",
                            'table' => "temp_shopify_collector",
                            'field' => "title",
                            'value' => $this->title
                        ]);
                    }
                    break;
                case 'categoria':
                    // Pedir Nombre común por Categoría
                    if (!is_null($this->categoria)) {
                        array_push($conditions, [
                            'type' => "COMPARE",
                            'table' => "tipo_categoria",
                            'field' => "id_tipo_categoria",
                            'value' => $this->categoria
                        ]);
                    }
                    break;
                case 'tipo':
                    // Pedir Nombre común por Tipo 
                    if (!is_null($this->tipo)) {
                        array_push($conditions, [
                            'type' => "NEGATIVA",
                            'table' => "temp_shopify_collector",
                            'field' => "ruleSet",
                            'value' => ''
                        ]);
                    }
                    break;
                case 'handle':
                    // Pedir Nombre común por handler 
                    if (!is_null($this->handle)) {
                        array_push($conditions, [
                            'type' => "COMPARE",
                            'table' => "nombre_comun",
                            'field' => "handle",
                            'value' => $this->handle
                        ]);
                    }
                    break;
                default:
                    // Pedir nombre común por ID
                    if (!is_null($this->id)) {
                        array_push($conditions, [
                            'type' => "COMPARE",
                            'table' => "temp_shopify_collector",
                            'field' => "id",
                            'value' => $this->id
                        ]);
                    }
                    break;
            }
        } */
        if ($this->cursor == 'page') {
            #debe traer los datos de la página
            $query['params'] = [
                'condition' => [
                    'type' => "COMPARE_MA",
                    'table' => "temp_shopify_collector",
                    'field' => "id",
                    'value' => $this->page
                ],
                'separator' => $separators
            ];
        } elseif ($this->cursor == 'next') {
            $query['params'] = [
                'condition' => [
                    'type' => "COMPARE_MA",
                    'table' => "temp_shopify_collector",
                    'field' => "id",
                    'value' => $this->page
                ],
                'separator' => $separators
            ];
        } elseif ($this->cursor == 'prev') {
            $query['params'] = [
                'condition' => [
                    'type' => "COMPARE_ME",
                    'table' => "temp_shopify_collector",
                    'field' => "id",
                    'value' => $this->page
                ],
                'separator' => $separators
            ];
        }
        $response = $this->select("temp_shopify_collector", $query, $this->limit);
        if (!empty($response['data'])) {
            $number = $this->limit - 1;
            $last = $response['data'][$number]['collection_id'];
            $next = $this->limit;
            $prev = $this->page - ($this->limit - 1);
            $response['pagination'] = [
                'next_page' => $next,
                'prev_page' => $prev,
                'limit' => $this->limit
            ];
        }
        return $response;
    }
    private function getSearchedCollections()
    {
        $query = [
            'fields' => [
                'temp_shopify_collector' => [
                    'collection_id', 'id', 'title', 'handle', 'productsCount=products',
                    'sortOrder=sort', 'ruleSet=rules', 'metafields=meta', 'seo', 'gqid'
                ]
            ],
            'joins' => []
        ];
        // Pedir Nombre común por Nombre 
        $conditions = array();
        $separators = array();
        if (!is_null($this->title)) {
            if (is_numeric($this->title)) {
                $numero = intval($this->title);
                if ($numero > 4) {
                    array_push($conditions, [
                        'type' => "COMPARE",
                        'table' => "temp_shopify_collector",
                        'field' => "collection_id",
                        'value' => $numero
                    ]);
                } else {
                    array_push($conditions, [
                        'type' => "SIMILAR",
                        'table' => "temp_shopify_collector",
                        'field' => "title",
                        'value' => $this->title
                    ]);
                }
            } else {
                array_push($conditions, [
                    'type' => "COMPARE",
                    'table' => "temp_shopify_collector",
                    'field' => "title",
                    'value' => $this->title
                ]);
            }
            if (!is_null($this->letra)) $separators = ['Y'];
        }
        if (!is_null($this->letra)) {
            array_push($conditions, [
                'type' => "START",
                'table' => "temp_shopify_collector",
                'field' => "title",
                'value' => $this->letra
            ]);
        }
        $query['params'] = (!empty($conditions)) ? ['condition' => $conditions, 'separator' => $separators] : null;
        return $this->select("temp_shopify_collector", $query);
    }
    private function getSearchedCommonNames()
    {
        $this->base = "default";
        $conditions = array();
        $separators = array();
        $query = [
            'fields' => [
                'nombre_comun' => [
                    'id_nombre_comun=id', 'nombre_comun=name', 'posicion=possition', 'fecha_creacion=date',
                    'activo=active', 'id_tienda=store_id', 'handle', 'terminos_de_busqueda=keywords'
                ],
                'tipo_producto' => ['id_tipo_producto=tp_id', 'tipo_producto=sub_category'],
                'tipo_categoria' => ['id_tipo_categoria=tc_id', 'tipo_categoria=category']
            ],
            'joins' => [
                [
                    'type' => "INNER",
                    'table' => "tipo_producto",
                    'filter' => "id_tipo_producto",
                    'compare_table' => "nombre_comun",
                    'compare_filter' => "id_tipo_producto"
                ],
                [
                    'type' => "INNER",
                    'table' => "tipo_categoria",
                    'filter' => "id_tipo_categoria",
                    'compare_table' => "tipo_producto",
                    'compare_filter' => "id_tipo_categoria"
                ]
            ]
        ];
        $args = ['categoria', 'tipo', 'title', 'activo'];
        foreach ($args as $k => $arg) {
            if ($k != 0 && !is_null($this->$arg)) {
                if (!empty($conditions)) array_push($separators, 'Y');
            }
            switch ($arg) {
                case 'title':
                    // Pedir Nombre común por Nombre 
                    if (!is_null($this->title)) {
                        if (strlen($this->title) > 4) {
                            array_push($conditions, [
                                'type' => "SIMILAR",
                                'table' => "nombre_comun",
                                'field' => "nombre_comun",
                                'value' => $this->title
                            ]);
                        } else {
                            if (is_numeric($this->title)) {
                                array_push($conditions, [
                                    'type' => "COMPARE",
                                    'table' => "nombre_comun",
                                    'field' => "id_nombre_comun",
                                    'value' => $this->title
                                ]);
                            } else {
                                array_push(
                                    $conditions,
                                    [
                                        'type' => "START",
                                        'table' => "nombre_comun",
                                        'field' => "nombre_comun",
                                        'value' => $this->title
                                    ]
                                );
                            }
                        }
                    }
                    break;
                case 'categoria':
                    // Pedir Nombre común por Categoría
                    if (!is_null($this->categoria)) {
                        array_push($conditions, [
                            'type' => "COMPARE",
                            'table' => "tipo_categoria",
                            'field' => "id_tipo_categoria",
                            'value' => $this->categoria
                        ]);
                    }
                    break;
                case 'tipo':
                    // Pedir Nombre común por Tipo 
                    if (!is_null($this->tipo)) {
                        array_push($conditions, [
                            'type' => "COMPARE",
                            'table' => "tipo_producto",
                            'field' => "id_tipo_producto",
                            'value' => $this->tipo
                        ]);
                    }
                    break;
            }
        }
        $query['params'] = ['condition' => $conditions, 'separator' => $separators];
        return $this->select('nombre_comun', $query);
    }
    private function setVerificationCollection($value = false)
    {
        if ($value === false || $value === 'false') {
            if (!is_null($this->id) && !empty($this->id)) {
                $query = [
                    'fields' => ['verified'],
                    'values' => [1],
                    'params' => [
                        'condition' => [
                            [
                                'type' => "COMPARE",
                                'table' => "temp_shopify_collector",
                                'field' => "collection_id",
                                'value' => $this->id
                            ]
                        ], 'separator' => array()
                    ]
                ];
            } else {
                return [
                    'error' => [
                        'code' => "00400",
                        'message' => "La información está incompleta o es erronea"
                    ]
                ];
            }
        } else {
            $query = [
                'fields' => ['verified'],
                'values' => [0],
                'params' => [
                    'condition' => [
                        [
                            'type' => "COMPARE",
                            'table' => "temp_shopify_collector",
                            'field' => "collection_id",
                            'value' => $this->id
                        ]
                    ], 'separator' => array()
                ]
            ];
        }
        return $this->update("temp_shopify_collector", $query);
    }
    private function setVerificationCommon($state)
    {
        return ['data' => ['message' => "OK"], 'error' => array()];
    }
    private function setChange($id) :array
    {
        $change = $this->select('changes', [
            'fields' => [
                'changes' => ['id','change','field','ubicacion','collection','common','syncronized','executed']
            ],
            'params' => [
                'condition' => [
                    ['type' => "COMPARE", 'table' => "changes", 'field' => "id", 'value' => $id]
                ],
                'separator' => array()
            ]
        ]);
        if (!empty($change['data'])) {
            $col = $change['data'][0]['collection'];
            $com = $change['data'][0]['common'];
            $ubi = $change['data'][0]['ubicacion'];
            $typ = $change['data'][0]['change'];
            $args = [
                'id'=>'id_tipo_producto', 'title'=>'nombre_comun', 'handle'=> 'handle',
                'keywords'=>'terminos_de_busqueda', 'id_store'=>'id_tienda', 'activo'=> 'activo'
            ];
            switch ($typ) {
                case "delete":
                    if ($ubi == "global") {
                        $this->delete('temp_shopify_collector',[
                            'params'=>[
                                'condition'=>[
                                    ['type'=>"COMPARE",'table'=> "temp_shopify_collector",'field'=>"id",'value'=>$col]
                                ],
                                'separator'=>array()
                            ]
                        ]);
                        $this->base = "default";
                        $this->delete('nombre_comun', [
                            'params' => [
                                'condition' => [
                                    ['type' => "COMPARE", 'table' => "nombre_comun", 'field' => "id", 'value' => $com]
                                ],
                                'separator' => array()
                            ]   
                        ]);
                        $result = $this->external->delete([
                            'type'=>"delete",
                            'request'=>[
                                'element' => $this->element, 
                                'field' => "id", 
                                'value' => $col
                            ]
                        ]);
                    } elseif ($ubi == 'store') {
                        $result = $this->external->delete([
                            'type' => "delete",
                            'request' => [
                                'element' => "collection",
                                'field' => "id",
                                'value' => $col
                            ]
                        ]);
                    } else if ($ubi == "local") {
                        $this->base = "default";
                        $result = $this->delete('nombre_comun', [
                            'params' => [
                                'condition' => [
                                    ['type' => "COMPARE", 'table' => "nombre_comun", 'field' => "id", 'value' => $com]
                                ],
                                'separator' => array()
                            ]
                        ]);
                    }
                    break;
                case "edit":
                    $cmps = array();
                    $vlrs = array();
                    foreach ($args as $item => $campo) {
                        if (!is_null($this->$item) && !empty($this->$item)) {
                            array_push($cmps, $campo);
                            array_push($vlrs, $this->$item);
                        }
                    }
                    if ($ubi == "global") {
                        $this->external->update([
                            'type'=>"put",
                            'request'=>[
                                'element'=>$this->element,
                                'fields' => $cmps,
                                'values' => $vlrs,
                                'params' => $col
                                ]
                            ]);
                        $this->base = "default";
                        $this->update('nombre_comun', [
                            'fields'=>$cmps,
                            'values'=>$vlrs,
                            'params' => [
                                'condition' => [
                                    ['type' => "COMPARE", 'table' => "nombre_comun", 'field' => "id", 'value' => $com]
                                ],
                                'separator' => array()
                            ]
                        ]);
                    } elseif ($ubi == 'store') {
                        $result = $this->external->update([
                            'type'=>"put",
                            'request' => [
                                'element'=> $this->element,
                                'params'=>$col,
                                'fields' => $cmps,
                                'values' => $vlrs
                            ]
                        ]);
                    } else if ($ubi == "local") {
                        $this->base = "default";
                        $result = $this->update('nombre_comun', [
                            'fields' => $cmps,
                            'values' => $vlrs,
                            'params' => [
                                'condition' => [
                                    ['type' => "COMPARE", 'table' => "nombre_comun", 'field' => "id", 'value' => $com]
                                ],
                                'separator' => array()
                            ]
                        ]);
                    }
                    unset($cmps,$vlrs);
                    break;
                case "sync":
                    $cmps = array();
                    $vlrs = array();
                    foreach ($args as $item => $campo) {
                        if (!is_null($this->$item) && !empty($this->$item)) {
                            array_push($cmps, $campo);
                            array_push($vlrs, $this->$item);
                        }
                    }
                    if ($ubi == "global") {
                        $this->external->update([
                            'type'=>"put",
                            'request' => [
                                'element' => $this->element,
                                'fields' => $cmps,
                                'values' => $vlrs,
                                'params' => $col
                                ]
                        ]);
                        $this->base = "default";
                        $this->update('nombre_comun', [
                            'fields' => $cmps,
                            'values' => $vlrs,
                            'params' => [
                                'condition' => [
                                    ['type' => "COMPARE", 'table' => "nombre_comun", 'field' => "id", 'value' => $com]
                                ],
                                'separator' => array()
                            ]
                        ]);
                    } elseif ($ubi == 'store') {
                        $this->external->update([
                            'type' => "put",
                            'request' => [
                                'element' => $this->element,
                                'fields' => $cmps,
                                'values' => $vlrs,
                                'params' => $col
                            ]
                        ]);
                    } else if ($ubi == "local") {
                        $this->update('nombre_comun', [
                            'fields' => $cmps,
                            'values' => $vlrs,
                            'params' => [
                                'condition' => [
                                    ['type' => "COMPARE", 'table' => "nombre_comun", 'field' => "id", 'value' => $com]
                                ],
                                'separator' => array()
                            ]
                        ]);
                        unset($cmps);
                        unset($vlrs);
                    }
                    unset($cmps, $vlrs);
                    break;
                case "create":
                    if ($ubi == "global" || $ubi == "store") {
                        $result = $this->external->create([
                            'type' => "post",
                            'request' => [
                                'element' => $this->element,
                                'values' => [$this->title,$this->handle,$this->activo],
                                'fields' => ['title','handle', 'published']
                            ]
                        ]);
                    }
                    $this->id_store = $result['data'][0]['id'];
                    $this->order = $result['data'][0]['sort_order'];
                    $cmps = array();
                    $vlrs = array();
                    foreach ($args as $item => $campo) {
                        if (!is_null($this->$item) && !empty($this->$item)) {
                            array_push($cmps, $campo);
                            array_push($vlrs, $this->$item);
                        }
                    }
                    if ($ubi == "global" || $ubi == "local") {
                        $this->insert('nombre_comun', [
                            'fields'=>$cmps,
                            'values'=>$vlrs,
                            'params' => [
                                'condition' => [
                                    ['type' => "COMPARE", 'table' => "nombre_comun", 'field' => "id", 'value' => $com]
                                ],
                                'separator' => array()
                            ]
                        ]);
                    }
                    unset($cmps, $vlrs);
                    break;
            }
        }
        return [
            'error'=>(!empty($change['error'])) ? $change['error'] : [],
            'data'=>(!empty($result['data'])) ? $result['data'] : []
        ];
    }
    private function getAllColecciones($campos) : array
    {
        if (empty($campos) || $campos == "all") {
            $query = [
                'fields' => [
                    'temp_shopify_collector' => [
                        'collection_id',
                        'id',
                        'title',
                        'handle',
                        'productsCount=products',
                        'sortOrder=sort',
                        'ruleSet=rules',
                        'metafields=meta',
                        'seo',
                        'gqid',
                        'verified'
                    ]
                ]
            ];
        } else {
            $query = [
                'fields' => [
                    'temp_shopify_collector' => $campos
                ]
            ];
        }
        $this->base = "shopify";
        $args = ['id', 'handle', 'title'];
        $conditions = array();
        $separators = array();
        foreach ($args as $k => $arg) {
            if ($k != 0 && !is_null($this->$arg)) {
                if (!empty($conditions)) array_push($separators, 'Y');
            }
            switch ($arg) {
                case 'title':
                    /* Pedir Nombre común por Nombre */
                    if (!is_null($this->title)) {
                        array_push($conditions, [
                            'type' => "COMPARE",
                            'table' => "temp_shopify_collector",
                            'field' => "title",
                            'value' => $this->title
                        ]);
                    }
                    break;
                case 'handle':
                    /* Pedir Nombre común por handler */
                    if (!is_null($this->handle)) {
                        array_push($conditions, [
                            'type' => "COMPARE",
                            'table' => "nombre_comun",
                            'field' => "handle",
                            'value' => $this->handle
                        ]);
                    }
                    break;
                default:
                    /* Pedir nombre común por ID */
                    if (!is_null($this->id)) {
                        array_push($conditions, [
                            'type' => "COMPARE",
                            'table' => "temp_shopify_collector",
                            'field' => "id",
                            'value' => $this->id
                        ]);
                    }
                    break;
            }
        }
        $query['params'] = (!empty($conditions)) ? ['condition' => $conditions, 'separator' => $separators] : null;
        $response = $this->select("temp_shopify_collector", $query, $this->limit);
        return $response;
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
