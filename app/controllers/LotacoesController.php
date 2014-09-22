<?php
    @require_once $_SERVER['DOCUMENT_ROOT'] . '/sods/app/lib/util.php';

    @require_once $_SERVER['DOCUMENT_ROOT'] . '/sods/app/lib/paginator.php';

    @require_once $_SERVER['DOCUMENT_ROOT'] . '/sods/app/dao/LotacaoDAO.php';

    class LotacoesController {

        private $dao;
        private $paginator;

        public function __construct() {
            $this->dao = new LotacaoDAO();
            $this->paginator = new Paginator();
        }

        public function __destruct() {
            unset($this->dao);
        }

        public function __get($field) {
            return $this->$field;
        }

        public function __set($field, $value) {
            $this->$field = $value;
        }

        public function insert($lotacao) {
            $this->dao->insert($lotacao);
        }

        public function getLotacoes() {
            return $this->dao->getAll();
        }

        public function getLotacao($id) {
            return $this->dao->get("id", (int) $id);
        }

        public function getGerencias() {
            return $this->dao->filter('gerencia_id is null');
        }

        public function add($lotacao) {
            $this->dao->insert($lotacao);
        }

        public function edit($lotacao) {
            $this->dao->update($lotacao);
        }
        
        public function del($id) {
            $this->dao->delete($id);
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
    }
?>