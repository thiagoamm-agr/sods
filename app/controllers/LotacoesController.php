<?php
	@require_once $_SERVER['DOCUMENT_ROOT'] . '/sods/app/dao/LotacaoDAO.php';
	
	class LotacoesController {
		
		private $dao;
		
		public function __construct() {
			$this->dao = new LotacaoDAO();
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
		
		public function insert($lotacao) {
			$this->dao->insert($lotacao);
		}
		
		public function getLotacoes() {
			return $this->dao->getAll();
		}
		
		public function getLotacao($id) {
			return $this->dao->get("id", (int) $id);
		}
		
		public function getGerencias() {
			return $this->dao->filter('gerencia_id is null');
		}
	}
?>