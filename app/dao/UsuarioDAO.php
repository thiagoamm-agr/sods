<?php

    @require_once $_SERVER['DOCUMENT_ROOT'] . '/sods/app/lib/db.php';

    @require_once $_SERVER['DOCUMENT_ROOT'] . '/sods/app/dao/DAO.php';

    @require_once $_SERVER['DOCUMENT_ROOT'] . '/sods/app/models/Usuario.php';

    session_start();

    class UsuarioDAO implements DAO{

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

        public function insert($usuario) {
            if (isset($usuario)) {
                $class = new ReflectionClass('Usuario');
                $properties = $class->getProperties();
                $columns = "";
                $values = "";
                foreach ($properties as $property) {
                    $property->setAccessible(true);
                    $column = $property->getName();
                    $value = $property->getValue($usuario);
                    if ($column != 'id' && 
                        $column != 'status' && 
                        $column != 'perfil' &&
                        $column != 'data_criacao' && 
                        $column != 'data_alteracao') {
                            $columns .= "{$column}, ";
                        if (gettype($value) == "string") {
                            if ($column == 'senha') {
                                $values .= "md5('{$value}'), ";
                            } else{
                                $values .= "'{$value}', ";
                            }
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
                    $query = "insert into solicitante ($columns) values ($values)";
                    mysql_query($query, $this->connection);
                }
            }
        }

        public function update($usuario) {
            if (isset($usuario)) {
                $class = new ReflectionClass('Usuario');
                $properties = $class->getProperties();
                $pairs = "";
                foreach ($properties as $property) {
                    $property->setAccessible(true);
                    $column = $property->getName();
                    $value = $property->getValue($usuario);
                    if ($column != 'id' && $column != 'senha') {
                        if (!empty($value) && ($value != 'undefined')) {
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
                    
                    if($column == 'senha' && !empty($value)) {
                        if(strlen($value)<32){
                            $pairs .= "$column = md5('{$value}'),";
                        }
                    }
                }
                $pairs = substr($pairs, 0, strrpos($pairs, ", "));
                if (!empty($pairs)) {
                    $query = "update solicitante set $pairs where id = {$usuario->id}";
                    mysql_query($query, $this->connection);
                }
            }
        }

        public function save($usuario) {
            if (isset($usuario)) {
                if (isset($usuario->id)) {
                    
                }
            }
        }

        public function delete($usuario) {
            if (isset($usuario)){
                try {
                    $query = "delete from solicitante where id = {$usuario->id}";
                    mysql_query($query, $this->connection);
                    if ($usuario->id == $_SESSION['usuario']['id']) {
                        session_unset();
                        session_destroy();
                    }
                } catch (Exception $e) {
                    echo $e;
                }
            }
            return;
        }

        public function get($field, $value) {
            if (isset($field) && isset($value)) {
                if (gettype($value) == 'string' && $field != 's.id') {
                    $value = "'$value'";
                }
                $query = "" . 
                    "select\n" .
                        "\ts.id, s.nome, l.nome as nome_lotacao, l.id as id_lotacao,\n" . 
                        "\ts.funcao, s.telefone, s.email, s.login, s.senha, s.perfil,\n" . 
                        "\ts.status, s.data_criacao, s.data_alteracao\n" . 
                    "from\n" .
                        "\tsolicitante as s\n" . 
                    "inner join lotacao as l\n" . 
                        "\ton s.lotacao_id = l.id and $field = $value";
                $result = mysql_query($query, $this->connection);
                return mysql_fetch_assoc($result);
            }
        }

        public function getAll() {
            $query = "" . 
                "select\n" .
                    "\ts.id, s.nome as nome_sol, l.id as lotacao_id, l.nome as lotacao,\n" .
                    "\ts.funcao, s.telefone, s.login, s.perfil, s.status, s.email\n" .
                "from\n" .
                    "\tsolicitante as s\n" .
                "inner join lotacao as l\n" .
                    "\ton s.lotacao_id = l.id order by s.id desc";
            $result = mysql_query($query, $this->connection);
            $all = array();
            while ($row = mysql_fetch_assoc($result)) {
                array_push($all, $row);
            }
            return $all;
        }

        public function filter($criteria=null) {
            $rows = array();
            if (empty($criteria)) {
                $rows = $this->getAll(); 
            } else {
                $where = empty($criteria) ? "" : "\twhere $criteria\n";
                $query = "". 
                    "select " .
                        "\ts.id, s.nome, l.id as lotacao_id, l.nome as lotacao, s.funcao,\n" .
                        "\ts.telefone, s.login, s.perfil, s.status, s.email\n" .
                    "from\n" .
                        "\tsolicitante as s\n" .
                    "inner join lotacao as l\n" .
                        "\ton s.lotacao_id = l.id " .
                    "$where";
                $result = mysql_query($query, $this->connection);
                while ($row = mysql_fetch_assoc($result)) {
                    array_push($rows, $row);
                }
            }
            return $rows;
        }

        public function count($criteria=null) {
            $where = empty($criteria) ? "" : "\twhere $criteria\n";
            $query = "" . 
                "select\n" .
                    "\ts.id, s.nome as nome_sol, l.id as lotacao_id, l.nome as lotacao, s.funcao,\n" .
                    "\ts.telefone, s.login, s.senha, s.perfil, s.status, s.email,\n" .
                    "\ts.data_criacao, s.data_alteracao\n" .
                "from\n" .
                    "\tsolicitante as s\n" .
                "inner join lotacao as l\n" .
                    "\ton s.lotacao_id = l.id\n" .
                "$where";
            $result = mysql_query($query, $this->connection);
            $rows = mysql_num_rows($result);
            return $rows;
        }

        public function paginate($rows=10, $start=0, $criteria=null) {
            $all = array();
            $where = empty($criteria) ? "" : "\twhere $criteria\n";
            $query = "" . 
                "select\n" .
                    "\ts.id, s.nome as nome_sol, l.id as lotacao_id, l.nome as lotacao, l.sigla as sigla_lotacao,\n" .
                    "\ts.funcao, s.telefone, s.login, s.senha, s.perfil, s.status, s.email,\n" .
                    "\ts.data_criacao, s.data_alteracao\n" .
                "from\n" .
                    "\tsolicitante as s\n" .
                "inner join lotacao as l\n" .
                    "\ton s.lotacao_id = l.id\n" .
                "$where" . 
                "order by\n" . 
                    "\ts.id desc\n" . 
                "limit $rows\n" . 
                "offset $start";
            $result = mysql_query($query, $this->connection);
            while ($row = mysql_fetch_array($result)) {
                array_push($all, $row);
            }
            return $all;
        }

    }
?>