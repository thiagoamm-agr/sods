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
				$cls = new ReflectionClass('Lotacao');
				$properties = $cls->getProperties();
				$columns = "";
				$values = "";
				foreach ($properties as $property) {
					$property->setAccessible(true);
					$value = $property->getValue($lotacao);
					$value = trim($value);
					if ($property->getName() != 'id') {
						$columns .= "{$property->getName()}, ";
					}
					if (!empty($value)) {						
						if (gettype($value) == "string") {
							$values .= "'{$property->getValue($lotacao)}', ";
						} else {
							$values .= "{$property->getValue($lotacao)}, ";
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
			$query = "select * from lotacao";			
			$result = mysql_query($query, $this->connection);
			$all = array();
			while ($row = mysql_fetch_assoc($result)) {
				$row['gerencia'] = $this->get('id', $row['gerencia_id']);
				array_push($all, $row);				
			}			
			return $all;
		}
	}
?>