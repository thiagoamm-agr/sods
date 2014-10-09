<?php

    @require_once 'Controller.php';

    @require_once $_SERVER['DOCUMENT_ROOT'] . '/sods/app/dao/TipoSolicitacaoDAO.php';

    class TiposSolicitacoesController extends Controller {

        public function __construct() {
            //faz a chamada do construtor da superclasse
            parent::__construct();
            $this->dao = new TipoSolicitacaoDAO();
            $this->paginator->totalrecords = $this->dao->count();
            $this->paginator->defaultUrl = "/sods/admin/tiposSolicitacao/";
            $this->paginator->paginationUrl = "/sods/admin/tiposSolicitacao/index.php?p=[p]";
        }

        public function __destruct() {
            unset($this->dao);
            //faz a chamada do destrutor da superclasse
            parent::__destruct();
        }

        public function _list(){
            return $this->dao->getAll();
        }

        public function add($tipoSolicitacao) {
            return $this->dao->insert($tipoSolicitacao);
        }

        public function edit($tipoSolicitacao) {
            return $this->dao->update($tipoSolicitacao);
        }

        public function delete($tipoSolicitacao) {
            return $this->dao->delete($tipoSolicitacao);
        }

        public function count($criteria=null) {
            $this->dao->count($criteria);
        }

        public function getRows($page=1, $size=10) {
            if (empty($page)) {
                $page = 1;
            }
            $this->paginator->pagesize = $size;
            $this->paginator->pagenumber = $page;
            return $this->dao->rowSet($size, $page * $size - $size);
        }

    }

?>
