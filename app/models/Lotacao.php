<?php
	@require_once $_SERVER['DOCUMENT_ROOT'] . '/sods/app/models/Model.php';
	@require_once $_SERVER['DOCUMENT_ROOT'] . '/sods/app/models/Usuario.php';

	class Lotacao extends Model {		
		protected $nome;
		protected $sigla;
		protected $gerencia_id;
		protected $usuario;
		
		public function __construct() {
			$this->usuario = new Usuario();
		}
	}
?>