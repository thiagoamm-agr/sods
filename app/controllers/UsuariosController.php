<?php
    @require_once 'Controller.php';

    @require_once $_SERVER['DOCUMENT_ROOT'] . '/sods/app/dao/UsuarioDAO.php';

    class UsuariosController extends Controller {
        
        public function __construct() {
            //faz a chamada do construtor da superclasse
            parent::__construct();
            $this->dao = new UsuarioDAO();
            $this->paginator->totalrecords = $this->dao->count();
            $this->paginator->defaultUrl = "/sods/admin/usuarios/";
            $this->paginator->paginationUrl = "/sods/admin/usuarios/index.php?p=[p]";
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

        public function count($criteria = null) {
            $this->dao->count($criteria);
        }
        
        public function getUsuario($id) {
            return $this->dao->get($id);
        }

        public function getRows($page=1, $size=5) {
        	if (empty($page)) {
        		$page = 1;
        	}
        	$this->paginator->pagesize = $size;
        	$this->paginator->pagenumber = $page;
        	return $this->dao->rowSet($size, $page * $size - $size);
        }

    }
?>