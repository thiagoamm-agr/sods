<?php

	@require $_SERVER['DOCUMENT_ROOT'] . '/sods/app/lib/db.php';
	
	@require $_SERVER['DOCUMENT_ROOT'] . '/sods/app/models/Secao.php';
	
	class SecaoDAO {
	
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