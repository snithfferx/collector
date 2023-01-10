<?php
/**
 * Clase para las transacciones entre los modelos y la base de datos.
 * @author Jorge Echeverria <jecheverria@bytes4run.com>
 * @version 1.0.0
 */
namespace app\modules\collections\models;
use app\core\classes\context\ExternalConnection;
class CollectionModel extends ExternalConnection
{
    public function get ($value) :array {
        return $this->getCollections($value);
    }

    private function getCollections($parameters) {
        if (!empty($parameters)) {
            return $this->makeRequest(['type'=>"get",'request'=>['element'=>"collections",'value'=>""]]);
        }
    }
}
?>