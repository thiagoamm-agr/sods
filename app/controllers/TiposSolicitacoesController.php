<?php
	
	@require_once 'Controller.php';
	
	@require_once $_SERVER['DOCUMENT_ROOT'] . '/sods/app/dao/TipoSolicitacaoDAO.php';
	
	class TiposSolicitacoesController extends Controller {
		
		public function __construct() {
			//faz a chamada do construtor da superclasse
			parent::__construct();
			$this->dao = new TipoSolicitacaoDAO();
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
		
	}

?>
