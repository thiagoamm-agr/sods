<?php
	@require_once $_SERVER['DOCUMENT_ROOT'] . '/sods/app/lib/db.php';

	@require_once $_SERVER['DOCUMENT_ROOT'] . '/sods/app/dao/IDAO.php';
	
	@require_once $_SERVER['DOCUMENT_ROOT'] . '/sods/app/models/Usuario.php';
	
	class UsuarioDAO implements IDAO {
		
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
					if (($column != 'id') && ($column != 'status') && ($column != 'data_criacao') && ($column != 'data_alteracao')) {
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
					try {
						$query = "insert into solicitante ($columns) values ($values)";
						mysql_query($query, $this->connection);
					} catch (mysqli_sql_exception $e) {
						echo $e;
					}
					
				}
			}
		}
		
		public function update($usuario) {
			if (isset($usuario)) {
				
			}
		}
		
		public function save($usuario) {
			if (isset($usuario)) {
				
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
				$query = "select * from solicitante where $field = $value";
				$result = mysql_query($query, $this->connection);
				return mysql_fetch_object($result);
			}
		}
		
		public function getAll() {
			$query = "select " .
					     "s.id, s.nome as nome_sol, l.nome as lotacao, s.cargo, " .
					     "s.telefone, s.login, s.tipo_usuario, s.status " .
					 "from " .
					     "solicitante as s " .
					 "inner join lotacao as l " .
					     "on s.lotacao_id = l.id order by s.id;";			
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
	}
?>