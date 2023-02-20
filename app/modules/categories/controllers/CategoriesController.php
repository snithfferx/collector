<?php
/**
 * Controller de categorías
 * @package Sipi\Modules\categories\controllers
 * @author Jorge Echeverria <jecheverria@bytes4run.com>
 * @version 1.0.0
 */
namespace app\Modules\categories\controllers;
use app\core\classes\ControllerClass;
use app\Modules\categories\models\CategoryModel;
class CategoriesController extends ControllerClass {
    private $model;
    public function __construct() {
        $this->model = new CategoryModel;
    }
    public function categorieslist () {
        return $this->getCategoriesList();
    }
    public function subcategoriesList ($value) {
        return $this->getSubCategoriesList($value);
    }

    private function getCategoriesList () {
        $result = $this->model->getCategoriesList();
        return $result['data'];
    }
    private function getSubCategoriesList($value)
    {
        $result = $this->model->getSubCategoriesList($value);
        return $result['data'];
    }
}