<?php

/**
 * Conexión a servidor local para consumir recursos de la base de datos
 * @category Clase
 * @author JEcheverria <jecheverria@bytes4run.com>
 * @api SHOPINGUI
 * @version 1.5.2
 * 10-01-2023/27-02-2023
 */

namespace app\core\classes\context;

use app\core\helpers\ConfigHelper;

class ConnectionClass
{
    private $globalConf;
    private $conexion;
    private $host;
    private $port;
    private $user;
    private $password;
    private $dbName;
    private function stablish_connection()
    {
        $db_DNS = "mysql:host=$this->host;port=$this->port;dbname=$this->dbName;";
        try {
            $this->conexion = new \PDO($db_DNS, $this->user, $this->password);
            $this->conexion->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        } catch (\PDOException $excepcion) {
            $this->conexion = [
                'code' => "00500",
                'message' => "Error: " . $excepcion->getMessage() . "\nCode: " . $excepcion->getCode(),
                'extra'=>[
                    'linea' => "Linea del error: " . $excepcion->getLine(),
                    'file' => "Componente: " . $excepcion->getFile()]
            ];
        }
        return $this->conexion;
    }
    private function getDBResponse($query, $type, $base = null)
    {
        $retorna = [];
        $errors = null;
        $affected = null;
        $id = null;
        $rows = null;
        if ($this->getConfig($base)) {
            $this->stablish_connection();
            if (is_array($this->conexion)) {
                $errors = $this->conexion;
            } else {
                if (!empty($type)) {
                    $pdo_Statement = $this->conexion->prepare($query['prepare_string']);
                    if ($type == 'insert' || $type == 'update' || $type == 'delete') {
                        try {
                            if ($pdo_Statement->execute($query['params'])) {
                                if ($type == 'insert') $id = $this->conexion->lastInsertId();
                                if ($type == 'update') $affected = $pdo_Statement->rowCount();
                            } else {
                                throw new \Exception("Query not executed correctly, verify statement.", 1);
                            }
                        } catch (\PDOException $th) {
                            $errors = [
                                'code' => "00500",
                                'message' => "Error:&nbsp;&nbsp;&nbsp;" . $th->getMessage()
                                    . "\nCode: " . $th->getCode()
                            ];
                        }
                    } elseif ($type == 'select') {
                        try {
                            $pdo_Statement->execute($query['params']);
                            $pdo_Statement->setFetchMode(\PDO::FETCH_ASSOC);
                            //$raw = $pdo_Statement->fetch(\PDO::FETCH_ASSOC);
                            $rows = $pdo_Statement->fetchAll();
                        } catch (\PDOException $th) {
                            $errors = [
                                'code' => "00500",
                                'message' => "Error:&nbsp;&nbsp;&nbsp;" . $th->getMessage()
                                    . "\nCode: " . $th->getCode()
                            ];
                        }
                    } else {
                        $errors = [
                            'code' => "00500",
                            'message' => "Falta un tipo de consulta.",
                        ];
                    }
                    $errors['extra'] = $pdo_Statement->errorInfo();
                    $pdo_Statement->closeCursor();
                } else {
                    $errors = [
                        'code' => "00500",
                        'message' => "Falta un tipo de consulta.",
                    ];
                }
            }
        } else {
            $errors = [
                'code' => "00500",
                'message' => "La configuración de conexión a la base de datos, es erronea o tiene inconsistencias.<br>Favor revisar y reintentar."
            ];
        }
        $retorna = [
            'error'  => $errors,
            'affected' => $affected,
            'lastid'  => $id,
            'rows'    => $rows
        ];
        return $retorna;
    }
    private function getConfig($database)
    {
        $this->globalConf = new ConfigHelper;
        if ($database == null || $database == "default") {
            $__CONF = $this->globalConf->get('config');
        } else {
            $__CONF = $this->globalConf->get($database);
        }
        if (!empty($__CONF)) {
            $this->host     = $__CONF['dbhost'];
            $this->port     = $__CONF['dbport'];
            $this->user     = $__CONF['dbuser'];
            $this->password = $__CONF['dbpass'];
            $this->dbName   = $__CONF['dbname'];
            return true;
        } else {
            return false;
        }
    }
    public function getResponse(string $type, array $request, string $base): array
    {
        return $this->getDBResponse($request, $type, $base);
    }
}
