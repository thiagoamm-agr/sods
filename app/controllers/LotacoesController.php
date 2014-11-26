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
            $this->paginator->paginationUrl="/sods/admin/lotacoes/";
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

        public function getPage($page_number=1, $rows=10, $criteria=null) {
            if (empty($page_number)) {
                $page_number = 1;
            }
            $this->paginator->pagesize = $rows;
            $this->paginator->pagenumber = $page_number;
            return $this->dao->paginate($rows, $page_number * $rows - $rows, $criteria);
        }

        public function getGrid($page_number=1, $filter=null, $value=null) {
            $criteria = null;
            if (!empty($filter) && !empty($value)) {
                switch ($filter) {
                    case 'id':
                    case 'gerencia_id':
                        $value = intval($value);
                        $criteria = "{$filter} = {$value}";
                        break;
                    case 'nome':
                    case 'sigla':
                        $criteria = "{$filter} LIKE '%{$value}%'";
                        break;
                    case 'gerencia_nome':
                    case 'gerencia_sigla':
                        $filter = str_replace('gerencia_', '', $filter);
                        $criteria = "gerencia_id in (select id from lotacao as gerencia where {$filter} = '{$value}')";
                        break;
                }
            }
            $lotacoes = $this->getPage($page_number, 10, $criteria);
            $total_records = empty($criteria) ? $this->dao->count() : $this->dao->count($criteria);
            $this->paginator->totalrecords = $total_records; 
            if ($total_records > 0) {
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
                        $tooltip =  "<span data-toggle=\"tooltip\" data-placement=\"bottom\"\n" .
                            "title=\"{$lotacao['gerencia']->nome}\">{$lotacao['gerencia']->sigla}</span>";
                        $html .= "        <td>$tooltip</td>\n";
                    } else {
                        $html .= "        <td></td>\n";
                    }
                    $html .= "            <td colspan=\"2\" style=\"width: 16%;\">\n";
                    $html .= "                <button\n"; 
                    $html .= "                    class=\"btn btn-warning btn-sm\"\n"; 
                    $html .= "                    data-toggle=\"modal\"\n"; 
                    $html .= "                    data-target=\"#modal-edit\"\n";
                    $html .= "                    onclick='edit(" . json_encode($lotacao) . ", $page_number)'>\n";
                    $html .= "                    <strong>\n";
                    $html .= "                        Editar&nbsp;<span class=\"glyphicon glyphicon-edit\"></span>\n";
                    $html .= "                    </strong>\n";
                    $html .= "                </button>&nbsp;&nbsp;\n";
                    $html .= "                <button\n";
                    $html .= "                    class=\"delete-type btn btn-danger btn-sm\"\n"; 
                    $html .= "                    data-toggle=\"modal\"\n"; 
                    $html .= "                    data-target=\"#modal-del\"\n";
                    $html .= "                    onclick='del(" . json_encode($lotacao) . ", $page_number,\n";
                    $html .= "                        $total_records)'>\n";
                    $html .= "                    <strong>\n";
                    $html .= "                        Excluir&nbsp;\n";
                    $html .= "                        <span class=\"glyphicon glyphicon-remove\"></span>\n";
                    $html .= "                    </strong>\n";
                    $html .= "                </button>\n";
                    $html .= "            </td>\n";
                    $html .= "        </tr>\n";
                }
                $html .= "    </tbody>\n";
                if ($total_records > 10) {
                    $html .= "<tfoot>\n";
                    $html .= "    <tr><td colspan=\"5\">{$this->paginator}</td></tr>\n";
                    $html .= "</tfoot>\n";
                }
                $html .= "</table>\n";
            } else {
                $html = "<div class=\"alert alert-danger\" role=\"alert\">\n";
                $html .= "    <center><b>Nenhum registro encontrado.</b></center>\n";
                $html .= "</div>\n";
            }
            return $html;
        }
    }
?>