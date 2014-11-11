<?php

    @require_once 'Controller.php';

    @require_once $_SERVER['DOCUMENT_ROOT'] . '/sods/app/dao/LotacaoDAO.php';

    class LotacoesController extends Controller {

        public function __construct() {
            // Chama o construtor da superclasse.
            parent::__construct();
            $this->dao = new LotacaoDAO();
            $this->paginator->totalrecords = $this->dao->count();
            // Com AJAX
            $this->paginator->defaultUrl="/sods/admin/lotacoes/";
            $this->paginator->paginationUrl="/sods/admin/lotacoes/";
            // Sem AJAX
            //$this->paginator->defaultUrl="/sods/admin/lotacoes/";
            //$this->paginator->paginationUrl="/sods/admin/lotacoes/index.php?p=[p]";
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

        public function getPage($page_number=1, $rows=10) {
            if (empty($page_number)) {
                $page_number = 1;
            }
            $this->paginator->pagesize = $rows;
            $this->paginator->pagenumber = $page_number;
            return $this->dao->paginate($rows, $page_number * $rows - $rows);
        }

        public function getGrid($page_number=1, $lotacoes=null) {
            $lotacoes = empty($lotacoes) ? $this->getPage($page_number) : $lotacoes;
            if (count($lotacoes) > 0) {
                $html = "<table id=\"tablesorter\"\n";
                $html .= "    class=\"table table-striped table-bordered table-condensed tablesorter\">\n";
                $html .= "    <thead>\n";
                $html .= "        <tr>\n";
                $html .= "            <th width=\"5%\">ID</th>\n";
                $html .= "            <th width=\"55%\">Nome</th>\n";
                $html .= "            <th width=\"10%\">Sigla</th>\n";
                $html .= "            <th width=\"10%\">Gerência</th>\n";
                $html .= "            <th class=\"nonSortable\">Ação</th>\n";
                $html .= "        </tr>\n";
                $html .= "    </thead>\n";
                $html .= "    <tbody>\n";
                foreach ($lotacoes as $lotacao) {
                    $html .= "        <tr>\n";
                    $html .= "            <td>{$lotacao['id']}</td>\n";
                    $html .= "            <td>{$lotacao['nome']}</td>\n";
                    $html .= "            <td>{$lotacao['sigla']}</td>\n";
                    if (isset($lotacao['gerencia'])) {
                        $html .= "        <td>{$lotacao['gerencia']->sigla}</td>\n";
                    } else {
                        $html .= "        <td></td>\n";
                    }
                    $html .= "            <td colspan=\"2\" style=\"width: 17%;\">\n";
                    $html .= "                <button\n"; 
                    $html .= "                    class=\"btn btn-warning btn-sm\"\n"; 
                    $html .= "                    data-toggle=\"modal\"\n"; 
                    $html .= "                    data-target=\"#modal-edit\"\n";
                    $html .= "                    onclick='edit(" . json_encode($lotacao) . ", $page_number)'>\n";
                    $html .= "                    <strong>Editar&nbsp;<span class=\"glyphicon glyphicon-edit\"></span></strong>\n";
                    $html .= "                </button>&nbsp;&nbsp;\n";
                    $html .= "                <button\n";
                    $html .= "                    class=\"delete-type btn btn-danger btn-sm\"\n"; 
                    $html .= "                    data-toggle=\"modal\"\n"; 
                    $html .= "                    data-target=\"#modal-del\"\n";
                    $html .= "                    onclick='del(" . json_encode($lotacao) . ", $page_number)'>\n";
                    $html .= "                    <strong>Excluir&nbsp;<span class=\"glyphicon glyphicon-remove\"></span></strong>\n";
                    $html .= "                </button>\n";
                    $html .= "            </td>\n";
                    $html .= "        </tr>\n";
                }
                $html .= "    </tbody>\n";
                if ($this->count() > 10) {
                    $html .= "<tfoot>\n";
                    $html .= "    <tr><td colspan=\"5\">{$this->paginator}</td></tr>\n";
                    $html .= "</tfoot>\n";
                }
                $html .= "</table>\n";
            } else {
                $html = "<div class=\"alert alert-danger\" role=\"alert\">\n";
                $html .= "    <center><b>Não há registros de solicitações.</b></center>\n";
                $html .= "</div>\n";
            }
            return $html;
        }

        public function search($attribute, $criteria, $value, $page=1) {
            if (!empty($attribute) && !empty($criteria) || !empty($value)) {
                switch ($criteria) {
                    case 'igual_a':
                        $criteria = '=';
                        break;
                }
                $criteria = "{$attribute}{$criteria}{$value}";
                $lotacoes = $this->dao->filter($criteria);
                return $this->getGrid($page, $lotacoes);
            }
        }
    }
?>