<?php
	@require $_SERVER['DOCUMENT_ROOT'] . '/sods/app/dao/SolicitacaoDAO.php';
	
	class SolicitacoesController {
		
		private $dao;
		
		public function __construct(){
			$this->dao = new SolicitacaoDAO();
		}
		
		public function __destruct(){
			unset($this->dao);
		}
		
		public function __get($field){
			return $this->$field;
		}
		
		public function __set($field, $value){
			$this->$field = $value;
		}
		
		public function allAdmin(){
			return $this->dao->allAdmin();
		}
		
		public function allUser($login){
			return $this->dao->allUser($login);
		}
	}

?>