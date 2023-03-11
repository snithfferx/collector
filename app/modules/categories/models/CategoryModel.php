<?php

/**
 * 
 */

namespace app\modules\categories\models;

use app\core\classes\context\ContextClass;

class CategoryModel extends ContextClass
{
    public function __construct()
    {
        $this->base = 'default';
    }
    public function getCategoriesList()
    {
        return $this->select('tipo_categoria', [
            'fields' => [
                'tipo_categoria' => ['id_tipo_categoria=id', 'tipo_categoria=name']
            ], 'joins' => array(), 'params' => array()
        ], 1000, 'ASC');
    }
    public function getSubCategoriesList($value)
    {
        $params = array();
        if (is_array($value) && !empty($value)) {
            if (!is_null($value['cid']) && !empty($value['cid'])) {
                $params = [
                    'condition' => [
                        [
                            'type' => "COMPARE",
                            'table' => "tipo_producto",
                            'field' => "id_tipo_categoria",
                            'value' => $value['cid']
                        ]
                    ], 'separator' => array()
                ];
            }
        }
        return $this->select('tipo_producto', [
            'fields' => [
                'tipo_producto' => ['id_tipo_producto=id', 'tipo_producto=name']
            ], 'joins' => array(), 'params' => $params
        ], 1000, 'ASC');
    }
}
