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
			unset($connection);
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
						($column != 'data_abertura') && 
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
			return;
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
					mysql_query($query, $this->connection);
				} catch (Exception $e) {
					echo $e;
				}
				
			}
			return;
		}
		
		public function get($field, $value) {
			if (isset($usuario)) {
				
			}
		}
		
		public function getAll() {
			$query= "select " . 
                        "so.id, s.nome, s.id as solicitante_id, so.titulo, so.detalhamento, " .
                        "so.info_adicionais, so.observacoes, so.status, so.observacoes_status, " .
						"t.nome as tipo_solicitacao, t.id as tipo_solicitacao_id, so.data_abertura, so.data_alteracao " .
					"from solicitante as s " . 
                    "inner join solicitacao as so " .
                        "on s.id = so.solicitante_id " . 
				    "inner join tipo_solicitacao as t " .
	    		        "on t.id = so.tipo_solicitacao_id";
			
			$result = mysql_query($query, $this->connection);
			$all = array();
			while ($row = mysql_fetch_assoc($result)) {
				array_push($all, $row);
			}
			return $all;
		}
		
		public function filter($criteria) {
			if (isset($criteria)) {
				
			}
		}
		
		public function count($criteria = null){
			$query = "select * from solicitacao";
			if (isset($criteria)){
				$query .= "where $criteria";		
			}
			$result = mysql_query($query, $this->connection);
			$rows = mysql_num_rows($result);
			return $rows;
		}

		public function rowSet($size=10, $start=0) {
			$all = array();
			$query = "select " . 
                        "so.id, s.nome, s.id as solicitante_id, so.titulo, so.detalhamento, " .
                        "so.info_adicionais, so.observacoes, so.status, so.observacoes_status, " .
						"t.nome as tipo_solicitacao, t.id as tipo_solicitacao_id, so.data_abertura, so.data_alteracao " .
					"from solicitante as s " . 
                    "inner join solicitacao as so " .
                        "on s.id = so.solicitante_id " . 
				    "inner join tipo_solicitacao as t " .
	    		        "on t.id = so.tipo_solicitacao_id limit $size offset $start";
			$result = mysql_query($query, $this->connection);
			while ($row = mysql_fetch_array($result)) {
				array_push($all, $row);
			}
			return $all;
		}
		
	}
?>