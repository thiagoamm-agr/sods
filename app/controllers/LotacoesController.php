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
            return !$this->dao->delete($id);
        }

        public function count($criteria=null) {
            return $this->dao->count($criteria);
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
            $html .= "<th>Nome</th>";
            $html .= "<th>Sigla</th>";
            $html .= "<th>Gerência</th>";
            $html .= "<th class=\"nonSortable\">Ação</th>";
            $html .= "</tr>";
            $html .= "</thead>";
            $html .= "<tbody>";
            foreach ($this->getRows($page) as $lotacao) {
                $html .= "<tr>";
                $html .= "<td>{$lotacao['id']}</td>";
                $html .= "<td>{$lotacao['nome']}</td>";
                $html .= "<td>{$lotacao['sigla']}</td>";
                if (isset($lotacao['gerencia'])) {
                    $html .= "<td>{$lotacao['gerencia']->sigla}</td>";
                } else {
                    $html .= "<td></td>";
                }
                $html .= "<td colspan=\"2\" style=\"width: 15%;\">";
                $html .= "<button"; 
                $html .= "    class=\"btn btn-warning btn-sm\""; 
                $html .= "    data-toggle=\"modal\""; 
                $html .= "    data-target=\"#modal-edit\"";
                $html .= "    onclick='edit(" . json_encode($lotacao) .")'>";
                $html .= "    <strong>Editar</strong>";
                $html .= "</button>&nbsp;&nbsp;";
                $html .= "<button";
                $html .= "    class=\"delete-type btn btn-danger btn-sm\""; 
                $html .= "    data-toggle=\"modal\""; 
                $html .= "    data-target=\"#modal-del\"";
                $html .= "    onclick='del(" . json_encode($lotacao) . ")'>";
                $html .= "    <strong>Excluir</strong>";
                $html .= "</button>";
                $html .= "</td>";
                $html .= "</tr>";
            }
            $html .= "</tbody>";
            if ($this->count() > 10) {
                $html .= "<tfoot>";
                $html .= "<tr><td colspan=\"5\">{$this->paginator}</td></tr>";
                $html .= "</tfoot>";
            }
            $html .= "</table>";
            return $html;
        }
    }
?>