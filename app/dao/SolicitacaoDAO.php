<?php
    @require_once $_SERVER['DOCUMENT_ROOT'] . '/sods/app/lib/db.php';

    @require_once $_SERVER['DOCUMENT_ROOT'] . '/sods/app/dao/DAO.php';

    @require_once $_SERVER['DOCUMENT_ROOT'] . '/sods/app/models/Solicitacao.php';

    class SolicitacaoDAO implements DAO {

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

        public function insert($solicitacao) {
            if (isset($solicitacao)) {
                $class = new ReflectionClass('Solicitacao');
                $properties = $class->getProperties();
                $columns = "";
                $values = "";
                foreach ($properties as $property) {
                    $property->setAccessible(true);
                    $column = $property->getName();
                    $value = $property->getValue($solicitacao);
                    if (($column != 'id') && 
                        ($column != 'status') &&
                        ($column != 'observacoes_status') &&
                        ($column != 'data_criacao') && 
                        ($column != 'data_alteracao')) {
                            $columns .= "{$column}, ";
                        if (gettype($value) == "string"){
                            $values .= "'{$value}', ";
                        } else {
                            if (endsWith($value, '_id')){
                                $value = (int) $value;
                            }
                            $values .= "{$value}, ";
                        }
                    }
                }
                $columns = substr($columns, 0, strrpos($columns, ", "));
                $values = substr($values, 0, strrpos($values, ", "));
                if(!empty($columns) && !empty($values)){
                    $query = "insert into solicitacao ($columns) values ($values)";
                    mysql_query($query, $this->connection);
                }
            }
        }

        public function update($solicitacao) {
            if(isset($solicitacao)){
                $class = new ReflectionClass('Solicitacao');
                $properties = $class->getProperties();
                $columns = "";
                $values = "";
                $pairs = "";
                foreach ($properties as $property) {
                    $property->setAccessible(true);
                    $column = $property->getName();
                    $value = $property->getValue($solicitacao);
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
                $pairs .= "data_alteracao = current_timestamp, ";
                $pairs = substr($pairs, 0, strrpos($pairs, ", "));
                if (!empty($pairs)) {
                    $query = "update solicitacao set $pairs where id = {$solicitacao->id}";
                    mysql_query($query, $this->connection);
                } 
            }
        }

        public function save($solicitacao) {
            if (isset($solicitacao)) {
                
            }
        }

        public function delete($solicitacao) {
            if (isset($solicitacao)){
                try {
                    $query = "delete from solicitacao where id = {$solicitacao->id}";
                    return mysql_query($query, $this->connection);
                } catch (Exception $e) {
                    echo $e;
                    return false;
                }   
            }else{
                return false;
            }
        }

        public function get($field, $value) {
            if (isset($usuario)) {
                
            }
        }

        public function getAll() {
            $query= "select " . 
                        "so.id, s.nome, s.id as solicitante_id, so.titulo, " . 
                        "so.detalhamento, so.info_adicionais, so.observacoes, " . 
                        "so.status, so.observacoes_status, so.data_criacao, so.data_alteracao, " .
                        "t.nome as tipo_solicitacao, t.id as tipo_solicitacao_id " .
                    "from " . 
                        "solicitante as s " . 
                    "inner join " . 
                        "solicitacao as so " .
                        "on s.id = so.solicitante_id " . 
                    "inner join " . 
                        "tipo_solicitacao as t " .
                        "on t.id = so.tipo_solicitacao_id";
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
            $query = "select\n" . 
                        "\tso.id, s.login as solicitante, s.nome, s.id as solicitante_id, so.titulo, so.detalhamento,\n" .
                        "\tso.info_adicionais, so.observacoes, so.status, so.observacoes_status,\n " .
                        "\tso.data_criacao, so.data_alteracao,\n " .
                        "\tt.nome as tipo_solicitacao, t.id as tipo_solicitacao_id\n" .
                    "from\n" . 
                        "\tsolicitante as s\n" . 
                    "inner join\n" . 
                        "\tsolicitacao as so on s.id = so.solicitante_id\n" . 
                    "inner join\n" . 
                        "\ttipo_solicitacao as t on t.id = so.tipo_solicitacao_id\n" .
                     "where $criteria";
                $result = mysql_query($query, $this->connection);
                while ($row = mysql_fetch_assoc($result)) {
                    array_push($rows, $row);
                }
            }
            return $rows;
        }

        public function count($criteria = null){
        	$where = empty($criteria) ? "" : "\twhere $criteria\n";
            $query = "select\n" . 
                        "\tso.id, s.login as solicitante, s.nome, s.id as solicitante_id, so.titulo, so.detalhamento,\n" .
                        "\tso.info_adicionais, so.observacoes, so.status, so.observacoes_status,\n " .
                        "\tso.data_criacao, so.data_alteracao,\n " .
                        "\tt.nome as tipo_solicitacao, t.id as tipo_solicitacao_id\n" .
                    "from\n" . 
                        "\tsolicitante as s\n" . 
                    "inner join\n" . 
                        "\tsolicitacao as so on s.id = so.solicitante_id\n" . 
                    "inner join\n" . 
                        "\ttipo_solicitacao as t on t.id = so.tipo_solicitacao_id\n" .
                    "$where" . 
                    "order by\n" . 
                        "\tso.id desc\n";
            $result = mysql_query($query, $this->connection);
            $rows = mysql_num_rows($result);
            return $rows;
        }

        public function paginate($rows=10, $start=0, $criteria=null) {
            $all = array();
            $where = empty($criteria) ? "" : "where\n\t$criteria\n";
            $query = "select\n" . 
                        "\tso.id, s.login as solicitante, s.nome, s.id as solicitante_id, so.titulo, so.detalhamento,\n" .
                        "\tso.info_adicionais, so.observacoes, so.status, so.observacoes_status,\n " .
                        "\tso.data_criacao, so.data_alteracao,\n " .
                        "\tt.nome as tipo_solicitacao, t.id as tipo_solicitacao_id\n" .
                    "from\n" . 
                        "\tsolicitante as s\n" . 
                    "inner join\n" . 
                        "\tsolicitacao as so on s.id = so.solicitante_id\n" . 
                    "inner join\n" . 
                        "\ttipo_solicitacao as t on t.id = so.tipo_solicitacao_id\n" .
                    "$where" . 
                    "order by\n" . 
                        "\tso.id desc\n" . 
                    "limit $rows\n" . 
                    "offset $start\n";
            $result = mysql_query($query, $this->connection);
            while ($row = mysql_fetch_array($result)) {
                array_push($all, $row);
            }
            return $all;
        }
        
    }
?>