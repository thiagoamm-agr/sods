<?php
	@require_once $_SERVER['DOCUMENT_ROOT'] . '/sods/app/models/Model.php';

	class Lotacao extends Model {	
		protected $nome;
		protected $sigla;
		protected $gerencia_id; 
	}
?>