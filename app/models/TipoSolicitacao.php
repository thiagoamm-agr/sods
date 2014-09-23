<?php
	@require_once $_SERVER['DOCUMENT_ROOT'] . '/sods/app/models/Model.php';
	
	class TipoSolicitacao extends Model {
		protected  $id;
		protected  $nome;
		protected  $status;
	}
?>