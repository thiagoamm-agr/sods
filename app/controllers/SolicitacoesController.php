<?php
	@require_once 'Controller.php';
	
	@require_once $_SERVER['DOCUMENT_ROOT'] . '/sods/app/dao/SolicitacaoDAO.php';
	
	class SolicitacoesController extends Controller {
		
		public function __construct() {
			//faz chamada ao construtor da superclasse
			parent::__construct();
			$this->dao = new SolicitacaoDAO();
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
		
		public function allUser($login) {
			return $this->dao->allUser($login);
		}
	}
?>