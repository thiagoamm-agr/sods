<?php
	class Solicitacao {
		
		private $id;
		private $solicitante_id;
		private $detalhamento;
		private $info_adicionais;
		private $observacoes;
		private $status;
		private $observacoes_status;
		private $data_abertura;
		private $data_alteracao;
		private $tipo_solicitacao_id;
		
		public function __get($field) {
			return $this->$field;
		}
		
		public function __set($field, $value) {
			$this->$field = $value;
		}
	}
?>