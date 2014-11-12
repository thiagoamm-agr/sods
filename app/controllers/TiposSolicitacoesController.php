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
            $this->paginator->paginationUrl = "/sods/admin/tiposSolicitacao/";
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
            return !$this->dao->insert($tipoSolicitacao);
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

        public function getPage($page_number=1, $rows=10) {
            if (empty($page_number)) {
                $page_number = 1;
            }
            $this->paginator->pagesize = $rows;
            $this->paginator->pagenumber = $page_number;
            return $this->dao->paginate($rows, $page_number * $rows - $rows);
        }

        public function getGrid($page_number=1, $tiposSolicitacao = null) {
            $tiposSolicitacao = empty($tiposSolicitacao) ? $this->getPage($page_number) : $tiposSolicitacao;
            $html = "<table\n";
            $html .= "    id=\"tablesorter\"\n";
            $html .= "    class=\"table table-striped table-bordered table-condensed tablesorter\">\n";
            $html .= "    <thead>\n";
            $html .= "        <tr>\n";
            $html .= "            <th>ID</th>\n";
            $html .= "            <th>Nome do tipo de Solicitação</th>\n";
            $html .= "            <th>Status</th>\n";
            $html .= "            <th class=\"nonSortable\">Ação</th>\n";
            $html .= "        </tr>\n";
            $html .= "    </thead>\n";
            $html .= "    <tbody>\n";
            foreach ($this->getPage($page_number) as $tipo) {
                $html .= "        <tr>\n";
                $html .= "            <td width=\"5%\">{$tipo['id']}</td>\n";
                $html .= "            <td>{$tipo['nome']}</td>\n";
                 				      if ($tipo['status'] == "A") {
                $html .= "                <td width=\"10%\">Ativo</td>\n";
                                      }else{
                $html .= "                <td width=\"10%\">Inativo</td>\n";
                                      }
                $html .= "            <td colspan=\"2\" style=\"width: 17%;\">\n";
                $html .= "                <button\n"; 
                $html .= "                    class=\"btn btn-warning btn-sm\"\n"; 
                $html .= "                    data-toggle=\"modal\"\n"; 
                $html .= "                    data-target=\"#modal-edit\"\n";
                $html .= "                    onclick='edit(" . json_encode($tipo) .", ".$page_number .")'>\n";
                $html .= "                    <strong>Editar&nbsp;<span class=\"glyphicon glyphicon-edit\"></span></strong>\n";
                $html .= "                </button>&nbsp;&nbsp;\n";
                $html .= "                <button\n";
                $html .= "                    class=\"delete-type btn btn-danger btn-sm\"\n"; 
                $html .= "                    data-toggle=\"modal\"\n"; 
                $html .= "                    data-target=\"#modal-del\"\n";
                $html .= "                    onclick='del(" . json_encode($tipo) . ", ". $page_number .", " . $this->count() .")'>\n";
                $html .= "                    <strong>Excluir&nbsp;<span class=\"glyphicon glyphicon-remove\"></span></strong>\n";
                $html .= "                </button>\n";
                $html .= "            </td>\n";
                $html .= "        </tr>\n";
                            }
            $html .= "    </tbody>\n";
            if ($this->paginator->totalrecords > 10) {
                $html .= "    <tfoot>\n";
                $html .= "        <tr><td colspan=\"5\">{$this->paginator}</td></tr>\n";
                $html .= "    </tfoot>\n";
            }
            $html .= "</table>\n";
            return $html;
        }
    }
?>
