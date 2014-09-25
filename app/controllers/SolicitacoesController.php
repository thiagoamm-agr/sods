<?php
	@require_once $_SERVER['DOCUMENT_ROOT'] . '/sods/app/dao/SolicitacaoDAO.php';
	
	class SolicitacoesController {
		
		private $dao;
		
		public function __construct() {
			$this->dao = new SolicitacaoDAO();
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
		
		public function getAll() {
			return $this->dao->getAll();
		}
		
		public function allUser($login) {
			return $this->dao->allUser($login);
		}
		
		public function insert($solicitacao) {
			return $this->dao->insert($solicitacao);
		}
		
		public function delete($solicitacao) {
			return $this->dao->delete($solicitacao);
		}
		
		public function update($solicitacao) {
			return $this->dao->update($solicitacao);
		}
	}
?>