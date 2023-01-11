<?php

/**
 * 
 */

namespace app\modules\collections\controllers;

use app\core\classes\ControllerClass;
use app\modules\collections\models\CollectionModel;

class CollectionsController extends ControllerClass
{
    private $model;
    public function __construct()
    {
        $this->model = new CollectionModel;
    }
    public function create($values)
    {
        return true;
    }
    public function read($values)
    {
        $response =  (!empty($values)) ? $this->getCollections($values) : $this->getCollections();
        return $response;
    }
    public function update($values)
    {
        return true;
    }
    public function delete($values)
    {
        return true;
    }

    private function getCollections($value = "all"): array
    {
        $result = $this->model->get($value);
        if (!empty($result['error'])) {
            $response = [
                'type' => ['name' => "view", 'code' => $result['error']['code']],
                'view' => [
                    'name' => "collections/list",
                    'data' => []
                ],
                'data' => $result
            ];
        } else {
            $response = [
                'type' => ['name' => "template", 'code' => ""],
                'view' => ['name' => "collections/list", 'data' => []],
                'data' => $result
            ];
        }
        return $response;
    }
}
