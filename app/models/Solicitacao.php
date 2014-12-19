<?php

    @require_once $_SERVER['DOCUMENT_ROOT'] . '/sods/app/models/Model.php';

    class Solicitacao extends Model {

        protected $id;
        protected $solicitante_id;
        protected $titulo;
        protected $detalhamento;
        protected $info_adicionais;
        protected $observacoes;
        protected $status;
        protected $observacoes_status;
        protected $data_criacao;
        protected $data_alteracao;
        protected $tipo_solicitacao_id;
        protected $prioridade;
    }
?>