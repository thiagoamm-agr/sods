<?php
	@require $_SERVER['DOCUMENT_ROOT'] . '/sods/app/dao/TipoSolicitacaoDAO.php';
	
	class TipoSolicitacoesController {
		
		private $dao;
		
		public function __construct() {
			$this->dao = new TipoSolicitacaoDAO();
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
		
		public function insert() {
			
		}
		
		public function delete() {
			
		}
		
		public function update() {
			
		}
	}

?>
