<?php

    @require_once $_SERVER['DOCUMENT_ROOT'] . '/sods/app/models/Solicitacao.php';

    class HistoricoSolicitacao extends Solicitacao {

        protected $solicitacao_id;

        public function __get($field) {
            return $this->$field;
        }
        
        public function __set($field, $value) {
            $this->$field = $value;
        }
    }
?>