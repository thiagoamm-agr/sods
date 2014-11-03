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
            $html = "<table id=\"tablesorter\"\n";
            $html .= "    class=\"table table-striped table-bordered table-condensed tablesorter\">\n";
            $html .= "    <thead>\n";
            $html .= "        <tr>\n";
            $html .= "            <th>ID</th>\n";
            $html .= "            <th>Nome</th>\n";
            $html .= "            <th>Sigla</th>\n";
            $html .= "            <th>Gerência</th>\n";
            $html .= "            <th class=\"nonSortable\">Ação</th>\n";
            $html .= "        </tr>\n";
            $html .= "    </thead>\n";
            $html .= "    <tbody>\n";
            foreach ($this->getRows($page) as $lotacao) {
                $html .= "        <tr>\n";
                $html .= "            <td>{$lotacao['id']}</td>\n";
                $html .= "            <td>{$lotacao['nome']}</td>\n";
                $html .= "            <td>{$lotacao['sigla']}</td>\n";
                if (isset($lotacao['gerencia'])) {
                    $html .= "        <td>{$lotacao['gerencia']->sigla}</td>\n";
                } else {
                    $html .= "        <td></td>\n";
                }
                $html .= "            <td colspan=\"2\" style=\"width: 15%;\">\n";
                $html .= "                <button\n"; 
                $html .= "                    class=\"btn btn-warning btn-sm\"\n"; 
                $html .= "                    data-toggle=\"modal\"\n"; 
                $html .= "                    data-target=\"#modal-edit\"\n";
                $html .= "                    onclick='edit(" . json_encode($lotacao) .")'>\n";
                $html .= "                    <strong>Editar</strong>\n";
                $html .= "                </button>&nbsp;&nbsp;\n";
                $html .= "                <button\n";
                $html .= "                    class=\"delete-type btn btn-danger btn-sm\"\n"; 
                $html .= "                    data-toggle=\"modal\"\n"; 
                $html .= "                    data-target=\"#modal-del\"\n";
                $html .= "                    onclick='del(" . json_encode($lotacao) . ")'>\n";
                $html .= "                    <strong>Excluir</strong>\n";
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
            return $html;
        }
    }
?>