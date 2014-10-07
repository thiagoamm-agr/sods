<?php
    @require_once 'Controller.php';

    @require_once $_SERVER['DOCUMENT_ROOT'] . '/sods/app/dao/UsuarioDAO.php';

    class UsuariosController extends Controller {
        
        public function __construct() {
            //faz a chamada do construtor da superclasse
            parent::__construct();
            $this->dao = new UsuarioDAO();
        }

        public function __destruct() {
            unset($this->dao);
            //faz a chamada do destrutor da superclasse
            parent::__destruct();
        }

        public function _list() {
            return $this->dao->getAll();
        }

        public function add($usuario) {
            $this->dao->insert($usuario);
        }

        public function delete($usuario) {
            $this->dao->delete($usuario);
        }

        public function edit($usuario) {
            $this->dao->update($usuario);
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
        
        public function getUsuario($id) {
            return $this->dao->get($id);
        }

        public function getRows($page=1, $size=5) {
            
        }

    }
?>