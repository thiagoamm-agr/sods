<?php
    @require_once 'Controller.php';

    @require_once $_SERVER['DOCUMENT_ROOT'] . '/sods/app/dao/SolicitacaoDAO.php';

    class SolicitacoesController extends Controller {

        public function __construct() {
            //faz chamada ao construtor da superclasse
            parent::__construct();
            $this->dao = new SolicitacaoDAO();
            $this->paginator->totalrecords = $this->dao->count();
            $this->paginator->defaultUrl = "/sods/admin/solicitacoes/";
            $this->paginator->paginationUrl = "/sods/admin/solicitacoes/index.php?p=[p]";
        }

        public function __destruct() {
            unset($this->dao);
            //faz chamada ao destrutor da superclasse
            parent::__destruct();
        }

        public function _list() {
            return $this->dao->getAll();
        }

        public function add($solicitacao) {
            return $this->dao->insert($solicitacao);
        }

        public function delete($solicitacao) {
            return $this->dao->delete($solicitacao);
        }

        public function edit($solicitacao) {
            return $this->dao->update($solicitacao);
        }

        public function count($criteria = null) {
            return $this->dao->count($criteria);
        }

        public function allUser($login) {
            return $this->dao->allUser($login);
        }

        public function getPage($page_number=1, $rows=10) {
            if (empty($page_number)) {
                $page_number = 1;
            }
            $this->paginator->pagesize = $rows;
            $this->paginator->pagenumber = $page_number;
            return $this->dao->paginate($rows, $page_number * $rows - $rows);
        }
    }
?>