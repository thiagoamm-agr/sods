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
			@mysql_close($this->connection); //VERIFICAR ERRO
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
				$cls = new ReflectionClass('Usuario');
				$properties = $cls->getProperties();
				$columns = "";
				$values = "";
				foreach ($properties as $property) {
					$property->setAccessible(true);
					$value = $property->getValue($usuario);
					$value = trim($value);
					if (!empty($value)) {
						$columns .= "{$property->getName()}, ";
						if (gettype($value) == "string") {
							$values .= "'{$property->getValue($usuario)}', ";
						} else {
							$values .= "{$property->getValue($usuario)}, ";
						}
					}
				}
				$columns = substr($columns, 0, strrpos($columns, ", "));
				$values = substr($values, 0, strrpos($values, ", "));
				$query = "insert into solicitante ($columns) values ($values)";
				mysql_query($query, $this->connection);
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
					     "s.id, s.nome, l.nome as lotacao, s.cargo, " .
					     "s.telefone, s.login, s.tipo_usuario, s.status " .
					 "from " .
					     "solicitante as s " .
					 "inner join lotacao as l " .
					     "on s.lotacao_id = l.id;";			
			$result = mysql_query($query, $this->connection);
			$all = array();
			while ($row = mysql_fetch_assoc($result)) {
				array_push($all, $row);
			}
			return $all;
		}
	}
?>