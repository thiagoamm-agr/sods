<?php
    @require_once 'Controller.php';

    @require_once $_SERVER['DOCUMENT_ROOT'] . '/sods/app/dao/SolicitacaoDAO.php';

    class SolicitacoesController extends Controller {

        public function __construct() {
            //faz chamada ao construtor da superclasse
            parent::__construct();
            $this->dao = new SolicitacaoDAO();
            $this->paginator->totalrecords = $this->dao->count();
            // URL para requisições sem AJAX.
            //$this->paginator->defaultUrl = "/sods/admin/solicitacoes/";
            //$this->paginator->paginationUrl = "/sods/admin/solicitacoes/index.php?p=[p]";
            // URL para requisições com AJAX.
            $this->paginator->defaultUrl = "/sods/admin/solicitacoes/";
            $this->paginator->paginationUrl = "/sods/admin/solicitacoes/";
        }
        
        public function getGrid($page_number=1, $solicitacoes=null) {
            $solicitacoes = empty($solicitacoes) ? $this->getPage($page_number) : $solicitacoes;
            if (count($solicitacoes) > 0) {
                $html = "<table id=\"tablesorter\"\n";
                $html .= "    class=\"table table-striped table-bordered table-condensed tablesorter\">\n";
                $html .= "    <thead>\n";
                $html .= "        <tr>\n";
                $html .= "            <th>ID</th>\n";
                $html .= "            <th>Solicitante</th>\n";
                $html .= "            <th>Titulo</th>\n";
                $html .= "            <th>Status</th>\n";
                $html .= "            <th>Tipo</th>\n";
                $html .= "            <th>Data Abertura</th>\n";
                $html .= "            <th>Data Alteração</th>\n";                
                $html .= "            <th class=\"nonSortable\">Ação</th>\n";
                $html .= "        </tr>\n";
                $html .= "    </thead>\n";
                $html .= "    <tbody>\n";
                foreach ($solicitacoes as $solicitacao) {
                    $html .= "        <tr>\n";
                    $html .= "            <td>{$solicitacao['id']}</td>\n";
                    $html .= "            <td>{$solicitacao['nome']}</td>\n";
                    $html .= "            <td>{$solicitacao['titulo']}</td>\n";
                    $html .= "            <td>{$solicitacao['status']}</td>\n";
                    $html .= "            <td>{$solicitacao['tipo_solicitacao']}</td>\n";
                    if ($solicitacao['data_abertura'] != null){
                        $data_abertura = date('d/m/Y H:i:s', strtotime ($solicitacao['data_abertura']));
                        $html .= "            <td>$data_abertura</td>\n";
                    }else{
                        $html .= "            <td></td>\n";
                    }
                    if ($solicitacao['data_alteracao'] != null){
                        $data_alteracao = date('d/m/Y H:i:s', strtotime ($solicitacao['data_alteracao']));
                        $html .= "            <td>$data_alteracao</td>\n";
                    }else{
                        $html .= "            <td></td>\n";
                    }
                    $html .= "            <td colspan=\"2\" style=\"width: 16%;\">\n";
                    $html .= "                <button\n";
                    $html .= "                    class=\"btn btn-warning btn-sm\"\n";
                    $html .= "                    data-toggle=\"modal\"\n";
                    $html .= "                    data-target=\"#modal-edit\"\n";
                    $html .= "                    onclick='edit(" . json_encode($solicitacao) .")'>\n";
                    $html .= "                    <strong>Editar&nbsp;<span class=\"glyphicon glyphicon-edit\"></span></strong>\n";
                    $html .= "                </button>&nbsp;&nbsp;\n";
                    $html .= "                <button\n";
                    $html .= "                    class=\"delete-type btn btn-danger btn-sm\"\n";
                    $html .= "                    data-toggle=\"modal\"\n";
                    $html .= "                    data-target=\"#modal-del\"\n";
                    $html .= "                    onclick='del(" . json_encode($solicitacao) . ")'>\n";
                    $html .= "                    <strong>Excluir&nbsp;<span class=\"glyphicon glyphicon-remove\"></span></strong>\n";
                    $html .= "                </button>\n";
                    $html .= "            </td>\n";
                    $html .= "        </tr>\n";
                }
                $html .= "    </tbody>\n";
                if ($this->count() > 10) {
                    $html .= "<tfoot>\n";
                    $html .= "    <tr><td colspan=\"8\">{$this->paginator}</td></tr>\n";
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
            return !$this->dao->delete($solicitacao);
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