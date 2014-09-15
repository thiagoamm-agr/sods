<?php
	@require_once $_SERVER['DOCUMENT_ROOT'] . '/sods/app/lib/db.php';
	
	@require_once $_SERVER['DOCUMENT_ROOT'] . '/sods/app/models/TipoSolicitacao.php';
	
	class TipoSolicitacaoDAO {
		
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
		
		public function getAll() {
			$query = "select * from tipo_solicitacao where status like 'A'";
		
			$result = mysql_query($query, $this->connection);
			$all = array();
			while ($row = mysql_fetch_assoc($result)){
				array_push($all, $row);
			}
		
			return $all;
		}
		
		public function all() {
			
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
					mysql_query($query, $this->connection);
				}
			}
			return;			
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
						$query = "update tipo_solicitacao set status = 'I' where id = $values";
						mysql_query($query, $this->connection);
					} catch (Exception $e) {
						echo $e;
					}
				}
			}
			return;
		}
		
		public function update($tipoSolicitacao) {
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
						if($column != 'id'){
							$values .= "'{$value}', ";
						}else{
							$id = "{$value}";
						}
					}
				}
				$columns = substr($columns, 0, strrpos($columns, ", "));
				$values = substr($values, 0, strrpos($values, ", "));
				if (!empty($columns) && !empty($values)) {
					try {
						$query = "update tipo_solicitacao set $columns = $values where id = $id";
						mysql_query($query, $this->connection);
					} catch (Exception $e) {
						echo $e;
					}					
				}
			}
			return;		
		}
		
	}

?>