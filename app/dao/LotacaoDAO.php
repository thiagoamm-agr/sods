<?php
    @require_once $_SERVER['DOCUMENT_ROOT'] . '/sods/app/lib/db.php';

    @require_once $_SERVER['DOCUMENT_ROOT'] . '/sods/app/dao/IDAO.php';

    @require_once $_SERVER['DOCUMENT_ROOT'] . '/sods/app/models/Lotacao.php';

    class LotacaoDAO implements IDAO {

        private $connection;

        public function __construct() {
            $this->connection = get_db_connection();
        }

        public function __destruct() {
            mysql_close($this->connection);
            unset($connection);
        }

        public function __get($field) {
            return $this->$field;
        }

        public function __set($field, $value) {
            $this->$field = $value;
        }

        public function insert($lotacao) {
            if (isset($lotacao)) {
                $class = new ReflectionClass('Lotacao');
                $properties = $class->getProperties();
                $columns = "";
                $values = "";
                foreach ($properties as $property) {
                    $property->setAccessible(true);
                    $column = $property->getName();
                    $value = $property->getValue($lotacao);
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
                    } else {
                        if (endsWith($column, '_id')) {
                            $values .= "null, ";
                        }
                    }
                }
                $columns = substr($columns, 0, strrpos($columns, ", "));
                $values = substr($values, 0, strrpos($values, ", "));
                if (!empty($columns) && !empty($values)) {
                    $query = "insert into lotacao ($columns) values ($values)";
                    mysql_query($query, $this->connection);
                }
            }
        }

        public function update($lotacao) {
            if (isset($lotacao)) {
                $class = new ReflectionClass('Lotacao');
                $properties = $class->getProperties();
                $columns = "";
                $values = "";
                $pairs = "";
                foreach ($properties as $property) {
                    $property->setAccessible(true);
                    $column = $property->getName();
                    $value = $property->getValue($lotacao);
                    if ($column != 'id') {
                        if (!empty($value)) {
                            if (gettype($value) == "string") {
                                $pairs .= "$column = '{$value}', ";
                            } else {
                                if (endsWith($value, '_id')) {
                                    $value = (int) $value;
                                }
                                $pairs .= "$column = {$value}, ";
                            }
                        }
                    }
                }
                $pairs = substr($pairs, 0, strrpos($pairs, ", "));
                if (!empty($pairs)) {
                    $query = "update lotacao set $pairs where id = {$lotacao->id}";
                    mysql_query($query, $this->connection);
                }
            }
        }

        public function save($lotacao) {
            if (isset($lotacao)) {
                if (isset($lotacao->id)) {
                    
                }
            }
        }

        public function delete($id) {
            if (isset($id)) {
                $query = "delete from lotacao where id = $id";
                mysql_query($query, $this->connection);
            }
        }

        public function get($field, $value) {
            if (isset($field) && isset($value)) {
                if (gettype($value) == 'string') {
                    $value = "'$value'";
                }
                $query = "select * from lotacao where $field = $value";
                $result = mysql_query($query, $this->connection);
                return mysql_fetch_object($result);
            }
        }

        public function getAll() {
            $all = array();
            $query = "select * from lotacao";
            $result = mysql_query($query, $this->connection);
            while ($row = mysql_fetch_assoc($result)) {
                $row['gerencia'] = $this->get('id', $row['gerencia_id']);
                array_push($all, $row);
            }
            return $all;
        }

        public function filter($criteria) {
            $rows = array();
            if (isset($criteria)) {
                $query = "select * from lotacao where $criteria";
                $result = mysql_query($query, $this->connection);
                while ($row = mysql_fetch_assoc($result)) {
                    $row['gerencia'] = $this->get('id', $row['gerencia_id']);
                    array_push($rows, $row);
                }
            }
            return $rows;
        }
    }
?>