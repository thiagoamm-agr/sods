<?php
	@require_once $_SERVER['DOCUMENT_ROOT'] . '/sods/app/lib/db.php';
		
	@require_once $_SERVER['DOCUMENT_ROOT'] . '/sods/app/models/Usuario.php';	
	
	class UsuarioDAO {
		
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
		
		public function insert($usuario) {
			if (isset($usuario)) {
				
			}
		}
		
		public function update($usuario) {
			echo "Atualizar usuário";
			var_dump($usuario);			
		}
		
		public function delete($id) {
			
		}
		
		public function all() {
			$query = "select " .
					     "s.id, s.nome, sec.nome as secao, s.cargo, " .
					     "s.fone_ramal, s.login, s.tipo_usuario, s.status " .
					 "from " .
					     "solicitante as s " .
					 "inner join secao as sec " .
					     "on s.secao_id = sec.id;";
			
			$result_set = mysql_query($query, $this->connection);
			
			return $result_set;
		}
	}
?>