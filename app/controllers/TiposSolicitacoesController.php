<?php

    @require_once 'Controller.php';

    @require_once $_SERVER['DOCUMENT_ROOT'] . '/sods/app/dao/TipoSolicitacaoDAO.php';

    class TiposSolicitacoesController extends Controller {

        public function __construct() {
            // Chamada ao construtor da super-classe
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

        public function _list() {
            return $this->dao->getAll();
        }

        public function add($tipoSolicitacao) {
            return $this->dao->insert($tipoSolicitacao);
        }

        public function edit($tipoSolicitacao) {
            return $this->dao->update($tipoSolicitacao);
        }

        public function delete($tipoSolicitacao) {
            return !$this->dao->delete($tipoSolicitacao);
        }

        public function count($criteria=null) {
            return $this->dao->count($criteria);
        }

        public function getRows($page=1, $size=10) {
            if (empty($page)) {
                $page = 1;
            }
            $this->paginator->pagesize = $size;
            $this->paginator->pagenumber = $page;
            return $this->dao->rowSet($size, $page * $size - $size);
        }
        
        public function getGrid($page=1) {
        $html = "<table class=\"table table-striped table-bordered table-condensed tablesorter\"";
        $html .= "id=\"tablesorter\">";
        $html .= "<thead>";
        $html .= "<tr>";
        $html .= "<th>ID</th>";
        $html .= "<th>Nome do tipo de Solicitação</th>";
        $html .= "<th>Status</th>";
        $html .= "<th class=\"nonSortable\">Ação</th>";
        $html .= "</tr>";
        $html .= "</thead>";
        $html .= "<tbody>";
        foreach ($this->getRows($page) as $tipo) {
        $html .= "<tr>";
        $html .= "<td>{$tipo['id']}</td>";
        $html .= "<td>{$tipo['nome']}</td>";
        $html .= "<td>{$tipo['status']}</td>";
        $html .= "<td colspan=\"2\">";
        $html .= "<button";
        $html .= "    class=\"btn btn-warning btn-sm\"";
        $html .= "    data-toggle=\"modal\"";
        $html .= "    data-target=\"#modal-edit\"";
        $html .= "    onclick='edit(" . json_encode($tipo) .")'>";
        $html .= "    <strong>Editar</strong>";
        $html .= "</button> ";
        $html .= "<button";
        $html .= "    class=\"delete-type btn btn-danger btn-sm\"";
        $html .= "    data-toggle=\"modal\"";
        $html .= "    data-target=\"#modal-del\"";
        $html .= "    onclick='del(" . json_encode($tipo) . ")'>";
        $html .= "    <strong>Excluir</strong>";
        $html .= "</button>";
        $html .= "</td>";
        $html .= "</tr>";
        }
        $html .= "</tbody>";
        if ($this->paginator->totalrecords > 10) {
        $html .= "<tfoot>";
        $html .= "<tr><td colspan=\"5\">{$this->paginator}</td></tr>";
        $html .= "</tfoot>";
        }
        $html .= "</table>";
        return $html;
        }

    }

?>
