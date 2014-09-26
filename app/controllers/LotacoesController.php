<?php

    @require_once 'Controller.php';

    @require_once $_SERVER['DOCUMENT_ROOT'] . '/sods/app/dao/LotacaoDAO.php';

    class LotacoesController extends Controller {

        public function __construct() {
            // Chama o construtor da superclasse.
            parent::__construct();
            $this->dao = new LotacaoDAO();
        }

        public function __destruct() {
            unset($this->dao);
            // Chama o destrutor da superclasse.
            parent::__destruct();
        }

        public function _list() {
            return $this->dao->getAll();
        }

        public function add($lotacao) {
            $this->dao->insert($lotacao);
        }

        public function edit($lotacao) {
            $this->dao->update($lotacao);
        }
        
        public function delete($id) {
            $this->dao->delete($id);
        }

        public function count($criteria) {
            $this->dao->count($criteria);
        }

        public function paginate() {
            $this->paginator->pagenumber = 1;
            $this->paginator->pagesize = 1;
            $this->paginator->totalrecords = 4;
            $this->paginator->showfirst = true;
            $this->paginator->showlast = true;
            $this->paginator->paginationcss = "pagination-normal";
            $this->paginator->paginationstyle = 1; // 1: advance, 0: normal
            $this->paginator->defaultUrl = "/sods/admin/lotacoes/index.php";
            $this->paginator->paginationUrl = "/sods/admin/lotacoes/index.php?p=[p]";
            return $this->paginator->paginate();
        }

        public function getLotacoes() {
            return $this->_list();
        }

        public function getLotacao($id) {
            return $this->dao->get("id", (int) $id);
        }

        public function getGerencias() {
            return $this->dao->filter('gerencia_id is null');
        }
    }
?>