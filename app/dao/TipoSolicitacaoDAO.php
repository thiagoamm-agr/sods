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
			$query = "select * from tipo_solicitacao";
		
			$result = mysql_query($query, $this->connection);
			$all = array();
			while ($row = mysql_fetch_assoc($result)){
				array_push($all, $row);
			}
		
			return $all;
		}
		
		public function all() {
			
		}
		
		public function insert() {
			
		}
		
		public function delete() {
			
		}
		
		public function update() {
			
		}
		
	}

?>