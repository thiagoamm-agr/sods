<?php
	@require_once $_SERVER['DOCUMENT_ROOT'] . '/sods/app/models/Model.php';
	
	class Usuario extends Model {				
		protected $id;
		protected $nome;
		protected $lotacao_id;
		protected $cargo;
		protected $telefone;
		protected $email;
		protected $login;
		protected $senha;
		protected $tipo_usuario;
		protected $status;
		protected $data_criacao;
		protected $data_alteracao;
	}
?>