<?php

namespace app\modules\commonnames\models;

use app\core\classes\context\ContextClass;

class CommonNameModel extends ContextClass
{
    public $id;
    public $name;
    public $posicion;
    public $fecha;
    public $activo;
    public $id_tienda;
    public $handle;
    public $terminos;
    public $tipo;
    public $categoria;
    public $id_store;
    private $element;
    public function __construct()
    {
        $this->base = "default";
        $this->element = "nombre_comun";
    }
    public function _get()
    {
        return $this->_getData();
    }
    private function _getData()
    {
        $query = [
            'fields' => [
                $this->element => [
                    'id_nombre_comun=id',
                    'nombre_comun=common',
                    'posicion',
                    'fecha_creacion=date',
                    'activo',
                    'id_tienda',
                    'handle',
                    'terminos_de_busqueda=terminos'
                ],
                'tipo_producto' => [
                    'id_tipo_producto=ids',
                    'tipo_producto=subcategoria'
                ],
                'tipo_categoria' => [
                    'id_tipo_categoria=idc',
                    'tipo_categoria=categoria'
                ]
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
        $args = ['id', 'tipo', 'handle', 'categoria', 'name', 'id_store'];
        $conditions = [];
        $separators = [];
        foreach ($args as $k => $arg) {
            if ($k != 0 && !is_null($this->$arg)) {
                if (!empty($conditions)) array_push($separators, 'Y');
            }
            switch ($arg) {
                case 'name':
                    /* Pedir Nombre común por Nombre */
                    if (!is_null($this->name)) {
                        array_push($conditions, [
                            'type' => "SIMILAR",
                            'table' => "nombre_comun",
                            'field' => "nombre_comun",
                            'value' => $this->name
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
        return $this->select($this->element, $query);
    }
}
