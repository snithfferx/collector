<?php
namespace app\modules\commonnames\controllers;
use app\core\classes\ControllerClass;
use app\core\helpers\MessengerHelper;
use app\modules\commonnames\models\CommonNameModel;
class CommonNamesController extends ControllerClass{
    private $model;
    private $messenger;
    public function __construct() {
        $this->model = new CommonNameModel;
        $this->messenger = new MessengerHelper;
    }
    public function get($values)
    {
        return $this->getElement($values);
    }
    protected function getElement ($values) {
        if ($values['list'] == "single") {
            $this->model->id = $values['id'];
            $result = $this->model->_get();
            if (empty($result['error'])) {
                return $result;
            } else {
                return $this->messenger->build('error', $result['error']);
            }
        } else {
            return $this->createViewData('commonNames/list', [], $this->createBreadcrumbs([
                'view'=> "commonNames/list",
                'children'=>[[
                    'main'=>"Nombres Comunes",
                    'module'=>"commonnames",
                    'method'=>"list",
                    'params'=>null
                ], [
                    'module' => "commonnames",
                    'method' => "get",
                    'params' => $values['id']
                ]]
            ]));
        }
    }
}