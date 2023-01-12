<?php

/**
 * Controlador de colecciones
 * @author Jorge Echeverria <jecheverria@bytes4run.com>
 * @category Controller
 * @version 1.0.0
 * @package app\modules\collections\controllers
 * 12-01-2023
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
                'view' => [
                    'type' => [
                        'name' => "error_view", 
                        'code' => $result['error']['code']],
                    'name' => "collections/list",
                    'data' => []
                ],
                'data' => $result
            ];
        } else {
            $response = [
                'view' => [
                    'type' => [
                        'name' => "view", 
                        'code' => ""],
                    'name' => "collections/list", 
                    'data' => []],
                'data' => $result['data']
            ];
        }
        return $response;
    }
}
