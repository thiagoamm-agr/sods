<?php
	@require_once $_SERVER['DOCUMENT_ROOT'] . '/sods/app/lib/db.php';
	
	@require_once $_SERVER['DOCUMENT_ROOT'] . '/sods/app/models/Solicitacao.php';
	
	class SolicitacaoDAO {
		
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
			if(isset($usuario)) {
				echo "Inserir Solicitação";
				var_dump($solicitacao);
			}
		}
		
		public function update($solicitacao) {
			echo "Atualizar Solicitação";
			var_dump($solicitacao);
		}
		
		public function delete($id) {
			
		}
		
		public function allAdmin() {
			$query= "select " . 
                        "so.id, s.nome, so.titulo, so.status, " .
						"t.nome as nome_sol, so.data_abertura, so.data_alteracao " .
					"from solicitante as s " . 
                    "inner join solicitacao as so " .
                        "on s.id = so.solicitante_id " . 
				    "inner join tipo_solicitacao as t " .
	    		        "on t.id = so.tipo_solicitacao_id";
			
			$result = mysql_query($query, $this->connection);
			$all = array();
			
			while($row = mysql_fetch_assoc($result)){
				array_push($all, $row);
			}
						
			return $all;
		}
		
		public function allUser($login) {
			$query= "select " .
					    "so.id, s.nome, so.titulo, so.status, " .
					    "t.nome as nome_sol, so.data_abertura, so.data_alteracao " .
					"from solicitante as s " .
					"inner join solicitacao as so " .
					    "on s.id = so.solicitante_id and s.login = '$login' " .
					"inner join tipo_solicitacao as t " .
                        "on t.id = so.tipo_solicitacao_id";
			
			$result = mysql_query($query, $this->connection);
			$all = array();
			
			while ($row = mysql_fetch_assoc($result)){
				array_push($all, $row);
			}
			
			return $all;
		}
	}
?>