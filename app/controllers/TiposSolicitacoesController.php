<?php
	@require_once $_SERVER['DOCUMENT_ROOT'] . '/sods/app/dao/TipoSolicitacaoDAO.php';
	
	class TiposSolicitacoesController {
		
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
		
		public function getTipos(){
			return $this->dao->getAll();
		}
		
		public function all() {
			
		}
		
		public function insert($tipoSolicitacao) {
			return $this->dao->insert($tipoSolicitacao);			
		}
		
		public function delete($tipoSolicitacao) {
			return $this->dao->delete($tipoSolicitacao);
		}
		
		public function update($tipoSolicitacao) {
			return $this->dao->update($tipoSolicitacao);
		}
	}

?>
