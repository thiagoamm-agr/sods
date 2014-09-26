<?php
	@require_once $_SERVER['DOCUMENT_ROOT'] . '/sods/app/lib/db.php';

	@require_once $_SERVER['DOCUMENT_ROOT'] . '/sods/app/dao/IDAO.php';
	
	@require_once $_SERVER['DOCUMENT_ROOT'] . '/sods/app/models/Usuario.php';
	
	class UsuarioDAO implements IDAO{
		
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
					if (($column != 'id') && 
						($column != 'status') && 
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
					$query = "insert into solicitante ($columns) values ($values)";
					mysql_query($query, $this->connection);
				}
			}
			return;
		}
		
		public function update($usuario) {
			if (isset($usuario)) {
                $class = new ReflectionClass('Usuario');
                $properties = $class->getProperties();
                $columns = "";
                $values = "";
                $pairs = "";
                foreach ($properties as $property) {
                    $property->setAccessible(true);
                    $column = $property->getName();
                    $value = $property->getValue($usuario);
                    if ($column != 'id') {
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
				$class = new ReflectionClass('Usuario');
				$properties = $class->getProperties();
				$columns = "";
				$values = "";
				foreach ($properties as $property) {
					$property->setAccessible(true);
					$column = $property->getName();
					$value = $property->getValue($usuario);					
					if ($column == 'id') {
						$columns .= "{$column}, ";
						if(!empty($value)){
							$values .= (int) $value;
						}
					}
				}
				$columns = substr($columns, 0, strrpos($columns, ", "));
				if (!empty($columns) && !empty($values)) {
					try {
						$query = "update solicitante set status = 'I' where id = $values";
						//$query = "delete from usuario where id = $values";
						mysql_query($query, $this->connection);
					} catch (Exception $e) {
						echo $e;
					}
				}
			}
			return;
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
					     "s.id, s.nome as nome_sol, l.id as lotacao_id, l.nome as lotacao, s.cargo, " .
					     "s.telefone, s.login, s.tipo_usuario, s.status, s.email " .
					 "from " .
					     "solicitante as s " .
					 "inner join lotacao as l " .
					     "on s.lotacao_id = l.id order by s.id desc";			
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
		
		public function count($criteria){
			if (isset($criteria)){
		
			}
		}
	}
?>