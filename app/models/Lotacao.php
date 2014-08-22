<?php
	class Lotacao {
		
		private $id;
		private $nome;
		private $sigla;
		private $gerencia_id;
		
		public function __get($field) {
			return $this->$field;
		}
		
		public function __set($field, $value) {
			$this->$field = $value;
		}
	}
?>