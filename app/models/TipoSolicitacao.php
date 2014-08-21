<?php
	class TipoSolicitacao {
		private $id;
		private $nome;
		
		public function __get($field) {
			return $this->$field;
		}
		
		public function __set($field, $value) {
			$this->$field = $value;
		}
	}
?>