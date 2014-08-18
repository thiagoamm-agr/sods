<?php
	class Usuario {
		
		private $id;
		private $nome;
		private $secao_id;
		private $cargo;
		private $fone_ramal;
		private $email;
		private $login;
		private $senha;
		private $tipo_usuario;
		private $status;
		private $data_criacao;
		private $data_alteracao;
		
		public function __get($field) {
			return $this->$field;
		}
		
		public function __set($field, $value) {
			$this->$field = $value;
		}	
	}
?>