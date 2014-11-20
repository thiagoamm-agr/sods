<?php
    @require_once $_SERVER['DOCUMENT_ROOT'] . '/sods/app/lib/db.php';

    @require_once $_SERVER['DOCUMENT_ROOT'] . '/sods/app/dao/DAO.php';

    @require_once $_SERVER['DOCUMENT_ROOT'] . '/sods/app/models/TipoSolicitacao.php';

    class TipoSolicitacaoDAO implements DAO {

        private $connection;

        public function __construct() {
            $this->connection = get_db_connection();
        }

        public function __destruct() {
            mysql_close($this->connection);
            unset($this->connection);
        }

        public function __get($field) {
            return $this->$field;
        }

        public function __set($field, $value) {
            $this->$field = $value;
        }

        public function insert($tipoSolicitacao) {
            if (isset($tipoSolicitacao)){
                $class = new ReflectionClass('TipoSolicitacao');
                $properties = $class->getProperties();
                $columns = "";
                $values = "";
                foreach ($properties as $property) {
                    $property->setAccessible(true);
                    $column = $property->getName();
                    $value = $property->getValue($tipoSolicitacao);
                    if ($column != 'id') {
                        $columns .= "{$column}, ";
                    }
                    if (!empty($value)) {
                        if (gettype($value) == "string") {
                            $values .= "'{$value}', ";
                        } else {
                            if (endsWith($value, '_id')) {
                                $value = (int) $value;
                            }
                            $values .= "{$value}, ";
                        }
                    }
                }
                $columns = substr($columns, 0, strrpos($columns, ", "));
                $values = substr($values, 0, strrpos($values, ", "));
                if (!empty($columns) && !empty($values)) {
                    $query = "insert into tipo_solicitacao ($columns) values ($values)";
                    return mysql_query($query, $this->connection);
                }
            }else{
                return false;
            }
        }

        public function update($tipoSolicitacao) {
            if (isset($tipoSolicitacao)) {
                $class = new ReflectionClass('TipoSolicitacao');
                $properties = $class->getProperties();
                $pairs = "";
                foreach ($properties as $property) {
                    $property->setAccessible(true);
                    $column = $property->getName();
                    $value = $property->getValue($tipoSolicitacao);
                    if ($column != 'id') {
                        if (isset($value) && !empty($value)) {
                            if (gettype($value) == "string") {
                                $pairs .= "$column = '{$value}', ";
                            } else {
                                if (endsWith($value, '_id')) {
                                    $value = (int) $value;
                                }
                                $pairs .= "$column = {$value}, ";
                            }
                        } else {
                            if (gettype($value) == 'string') {
                                $pairs .= "$column = '', ";
                            } else {
                                if (endsWith($column, '_id')) {
                                    $pairs .= "$column = null, ";
                                }
                            }
                        }
                    }
                }
                $pairs = substr($pairs, 0, strrpos($pairs, ", "));
                if (!empty($pairs)) {
                    $query = "update tipo_solicitacao set $pairs where id = {$tipoSolicitacao->id}";
                    mysql_query($query, $this->connection);
                }
            }
        }

        public function save($tipoSolicitacao) {
            if (isset($tipoSolicitacao)){
        
            }
        }

        public function delete($tipoSolicitacao) {
            if (isset($tipoSolicitacao)){
                $class = new ReflectionClass('TipoSolicitacao');
                $properties = $class->getProperties();
                $columns = "";
                $values = "";
                foreach ($properties as $property) {
                    $property->setAccessible(true);
                    $column = $property->getName();
                    $value = $property->getValue($tipoSolicitacao);
                    if ($column == 'id') {
                        $columns .= "{$column}, ";
                        if(!empty($value)){
                            $values .= (int) $value;
                        }
                    }
                }
                $columns = substr($columns, 0, strrpos($columns, ", "));
                //$values = substr($values, 0, strrpos($values, ", "));
                if (!empty($columns) && !empty($values)) {
                    try {
                        //$query = "update tipo_solicitacao set status = 'I' where id = $values";
                        $query = "delete from tipo_solicitacao where id = {$tipoSolicitacao->id}";
                       return mysql_query($query, $this->connection);
                    } catch (Exception $e) {
                        echo $e;
                    }
                }
            }else{
                return false;
            }
        }

        public function get($field, $value) {
            if (isset($field) && isset($value)) {
                
            }
        }

        public function getAll() {
            $query = "select * from tipo_solicitacao order by id desc";
            $result = mysql_query($query, $this->connection);
            $all = array();
            while ($row = mysql_fetch_assoc($result)){
                array_push($all, $row);
            }
            return $all;
        }
        
        public function getActiveElements() {
            $query = "select * from tipo_solicitacao where status != 'I' order by nome" ;
            $result = mysql_query($query, $this->connection);
            $all = array();
            while ($row = mysql_fetch_assoc($result)){
                array_push($all, $row);
            }
            return $all;
        }

    public function filter($criteria=null) {
            $rows = array();
            if (empty($criteria)) {
                $rows = $this->getAll(); 
            } else {
                $query = "select * from tipo_solicitacao where $criteria";
                $result = mysql_query($query, $this->connection);
                while ($row = mysql_fetch_assoc($result)) {
                    array_push($rows, $row);
                }
            }
            return $rows;
        }

        public function count($criteria=null) {
            $query = "select * from tipo_solicitacao";
            if (isset($criteria)) {
                $query .= " where $criteria";
            }
            $result = mysql_query($query, $this->connection);
            $rows = mysql_num_rows($result);
            return $rows;
        }

        public function paginate($rows=10, $start=0, $criteria=null) {
            $all = array();
            $where = empty($criteria) ? "" : "where $criteria";
            $query = "select * from tipo_solicitacao $where order by id desc limit $rows offset $start";
            $result = mysql_query($query, $this->connection);
            while ($row = mysql_fetch_array($result)) {
                array_push($all, $row);
            }
            return $all;
        }
    }
?>