<?php
    @require_once $_SERVER['DOCUMENT_ROOT'] . '/sods/app/lib/db.php';

    @require_once $_SERVER['DOCUMENT_ROOT'] . '/sods/app/dao/DAO.php';

    @require_once $_SERVER['DOCUMENT_ROOT'] . '/sods/app/models/Lotacao.php';

    class LotacaoDAO implements DAO {

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
                $pairs = "";
                foreach ($properties as $property) {
                    $property->setAccessible(true);
                    $column = $property->getName();
                    $value = $property->getValue($lotacao);
                    if ($column != 'id') {
                        if (isset($value) && !empty($value)) {
                            if (gettype($value) == 'string') {
                                $pairs .= "$column = '{$value}', ";
                            } else {
                                if (endsWith($column, '_id')) {
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
                return mysql_query($query, $this->connection);
            } else {
                return false;
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
            $query = "select * from lotacao order by nome";
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

        public function count($criteria=null) {
            $query = "select * from lotacao";
            if (isset($criteria)) {
                $query .= " where $criteria";
            }
            $result = mysql_query($query, $this->connection);
            $rows = mysql_num_rows($result);
            return $rows;
        }

        public function rowSet($size=10, $start=0) {
            $all = array();
            $query = "select * from lotacao limit $size offset $start";
            $result = mysql_query($query, $this->connection);
            while ($row = mysql_fetch_array($result)) {
                $row['gerencia'] = $this->get('id', $row['gerencia_id']);
                array_push($all, $row);
            }
            return $all;
        }
    }

?>