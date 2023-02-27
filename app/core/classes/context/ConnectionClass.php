<?php

/**
 * Conexión a servidor local para consumir recursos de la base de datos
 * @category Clase
 * @author JEcheverria <jecheverria@bytes4run.com>
 * @api SHOPINGUI
 * @version 1.2.0
 * 10-01-2023/25-02-2023
 */

namespace app\core\classes\context;

use app\core\helpers\ConfigHelper;

class ConnectionClass
{
    private $globalConf;
    private $ds_context;
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
            return $this->conexion;
        } catch (\PDOException $excepcion) {
            return [
                'error' => "Error: " . $excepcion->getMessage(),
                'linea' => "Linea del error: " . $excepcion->getLine(),
                'file' => "Componente: " . $excepcion->getFile()
            ];
        }
    }
    private function getDBResponse($query, $type, $base = null)
    {
        $retorna = [];
        $errors = null;
        $affected = null;
        $id = null;
        $rows = null;
        if ($this->getConfig($base)) {
            //$this->ds_context = ($base != null) ? $this->stablish_connection($base) : $this->stablish_connection($this->dbName);
            $this->stablish_connection();
            if (is_array($this->ds_context)) {
                $errors = $this->ds_context;
            } else {
                if (!empty($type)) {
                    $pdo_Statement = $this->conexion->prepare($query['prepare_string']);
                    if ($type == 'insert' || $type == 'update' || $type == 'delete') {
                        try {
                            $pdo_Statement->execute($query['params']);
                            if ($type == 'insert') $id = $this->conexion->lastInsertId();
                        } catch (\PDOException $th) {
                            $errors = [
                                'code' => "00500",
                                'message' => "Error:&nbsp;&nbsp;&nbsp;" . $th->getMessage()
                            ];
                        }
                        $id = null; //$this->ds_context->lastInsertId();
                    } elseif ($type == 'select') {
                        try {
                            $pdo_Statement->execute($query['params']);
                            $pdo_Statement->setFetchMode(\PDO::FETCH_ASSOC);
                            $rows = $pdo_Statement->fetchAll();
                        } catch (\PDOException $th) {
                            $errors = [
                                'code' => "00500",
                                'message' => "Error:&nbsp;&nbsp;&nbsp;" . $th->getMessage()
                            ];
                        }
                    } else {
                        $errors = [
                            'code' => "00500",
                            'message' => "Falta un tipo de consulta.",
                        ];
                    }
                    //$errors['extra'] = $this->ds_context->errorInfo();
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
            'row_aff' => $affected,
            'id_row'  => $id,
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
