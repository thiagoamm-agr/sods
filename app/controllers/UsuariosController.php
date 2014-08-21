<?php
	@require_once $_SERVER['DOCUMENT_ROOT'] . '/sods/app/dao/UsuarioDAO.php';
	
	class UsuariosController {
		
		private $dao;
		
		public function __construct() {
			$this->dao = new UsuarioDAO();
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
			return $this->dao->all();
		}
		
		public function insert($usuario) {
			$this->dao->insert($usuario);
		}
	}
?>