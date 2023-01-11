<?php
/**
 * Clase para las transacciones entre los modelos y la base de datos.
 * @author Jorge Echeverria <jecheverria@bytes4run.com>
 * @version 1.0.0
 */
namespace app\modules\collections\models;
use app\core\classes\context\ExternalContext;

class CollectionModel extends ExternalContext
{
    public function get ($values) :array {
        if (is_string($values) && $values == "all") {
            $response = $this->getCollections();
        } else {
            $response = $this->getCollections($values);
        }
        return $response;
    }

    private function getCollections(array $parameters = []) :array {
        if (!empty($parameters['value'])) {
            $result = (!empty($parameters['fields'])) ? $this->makeRequest("get", "CustomCollection", $parameters['value']) : $this->makeRequest("get", "CustomCollection", $parameters['value'], $parameters['fields']);
        } else {
            $result = $this->makeRequest("get", "CustomCollection");
        }
        return $result;
    }
}
?>