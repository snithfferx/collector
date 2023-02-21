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
    public function localGet(string $list = 'collections'): array
    {
        if ($list == "collections") {
            return $this->getLocalCollections();
        } elseif ($list == "commonNames") {
            return $this->getCommonNames();
        } elseif ($list == "commonName") {
            return $this->getCommonNames();
        } elseif ($list == "collection") {
            return $this->getLocalCollections();
        } elseif ($list == "isCategory") {
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
    public function getPage($value)
    {
        return $this->getCollectionsPage($value);
    }
    public function hasMetafields()
    {
        return $this->countMetadata();
    }
    public function createCollection()
    {
        return $this->create('collection');
    }
    public function find()
    {
        return $this->getLocalCollections();
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
    public function setVerification ($method,$current) {
        if ($method === "set") {
            $result = $this->setVerificationCollection();
        } elseif ($method === "change") {
            $result = $this->setVerificationCollection($current);
        } else {
            return [];
        }
        return $result;
    }

    protected function getMetafields()
    {
        return $this->getMetadata();
    }
    /* private function getCollections(array $parameters = []) :array {
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
    } */
    private function getCommonNames(): array
    {
        $this->base = "inventario";
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
        $args = ['id', 'tipo', 'handle', 'categoria', 'title'];
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
        $this->base = "inventario";
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
        $this->base = "inventario";
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
        $this->base = "inventario";
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
                    'ruleSet', 'metafields', 'seo', 'gqid'
                ],
                'values' => [
                    $this->id, $this->title, $this->handle, $this->products, $this->order,
                    $this->rules, $this->fields, $this->seo, $this->graphQL_id
                ]
            ]);
        } else {
            $this->base = "inventario";
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

    private function getLocalCollections()
    {
        $this->base = "shopify";
        $query = [
            'fields' => [
                'temp_shopify_collector' => [
                    'collection_id', 'id', 'title', 'handle', 'productsCount=products',
                    'sortOrder=sort', 'ruleSet=rules', 'metafields=meta', 'seo', 'gqid'
                ]
            ],
            'joins' => []
        ];
        $args = ['id', 'handle', 'categoria', 'tipo', 'title'];
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
                            'type' => "NEGATIVA",
                            'table' => "temp_shopify_collector",
                            'field' => "ruleSet",
                            'value' => ''
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
        $this->base = "inventario";
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
        $this->base = "inventario";
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
    private function setVerificationCollection ($value = false) {
        if ($value === false) {
            if (!is_null($this->id) && !empty($this->id)) {
                $query = [
                    'fields'=>['verified'],
                    'values'=>[true],
                    'params'=>[
                        'type' => "COMPARE",
                        'table' => "temp_shopify_collector",
                        'field' => "id",
                        'value' => $this->id]
                ];
            }

        } else {

        }
        return $this->update("temp_shopify_collector", $query);
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
