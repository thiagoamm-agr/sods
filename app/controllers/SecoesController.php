<?php
	@require $_SERVER['DOCUMENT_ROOT'] . '/sods/app/dao/SecaoDAO.php';
	
	class SecoesController {
		
		private $dao;
		
		public function __construct() {
			$this->dao = new SecaoDAO();
		}
		
		public function __destruct() {
			unset($this->dao);
		}
		
		public function __get($field) {
			return $this->$field;
		}
		
		public function __set($field, $value) {
			$this->$field = $value;
		}
		
		public function all() {
				
		}
		
		public function add() {
				
		}
		
		public function del() {
				
		}
		
		public function upd() {
				
		}
	}

?>