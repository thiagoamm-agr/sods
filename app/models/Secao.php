<?php
	class Secao {
		private $id;
		private $nome;
		private $sigla;
		private $lotacao_id;
		
		public function __get($field) {
			return $this->$field;
		}
		
		public function __set($field, $value) {
			$this->$field = $value;
		}
	}
?>