<?php

    @require_once 'Controller.php';

    @require_once $_SERVER['DOCUMENT_ROOT'] . '/sods/app/dao/LotacaoDAO.php';

    class LotacoesController extends Controller {

        public function __construct() {
            // Chama o construtor da superclasse.
            parent::__construct();
            $this->dao = new LotacaoDAO();
            $this->paginator->totalrecords = $this->dao->count();
            $this->paginator->defaultUrl="/sods/admin/lotacoes/";
            $this->paginator->paginationUrl="/sods/admin/lotacoes/index.php?p=[p]";
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
            return $this->dao->count($criteria);
        }

        public function getRows($page=1, $size=5) {
            if (empty($page)) {
                $page = 1;
            }
            $this->paginator->pagesize = $size;
            $this->paginator->pagenumber = $page;
            return $this->dao->rowSet($size, $page * $size - $size);
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