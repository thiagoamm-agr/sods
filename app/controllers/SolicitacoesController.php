<?php
    @require_once 'Controller.php';

    @require_once $_SERVER['DOCUMENT_ROOT'] . '/sods/app/dao/SolicitacaoDAO.php';

    class SolicitacoesController extends Controller {

        public function __construct() {
            // Faz chamada ao construtor da superclasse
            parent::__construct();
            $this->dao = new SolicitacaoDAO();
            $this->paginator->totalrecords = $this->dao->count();
            $this->paginator->defaultUrl = "/sods/admin/solicitacoes/";
            $this->paginator->paginationUrl = "/sods/admin/solicitacoes/";
        }

        public function __destruct() {
            unset($this->dao);
            // Faz chamada ao destrutor da superclasse
            parent::__destruct();
        }

        public function _list() {
            return $this->dao->getAll();
        }

        public function add($solicitacao) {
            return $this->dao->insert($solicitacao);
        }

        public function delete($solicitacao) {
            if ($_SESSION['usuario']['perfil'] == 'P') {
                if ($solicitacao->status == 'CRIADA') {
                    $solicitacao->status='CANCELADA';
                } else {
                    return true;
                }
            } else {
                $solicitacao->status='INDEFERIDA';
            }
            return $this->dao->update($solicitacao);
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
                        $value = intval($value);
                        $criteria = "so.{$filter} = {$value}";
                        break;
                    case 'nome':
                        $criteria = "s.{$filter} LIKE '%{$value}%'";
                        break;
                    case 'titulo':
                        $criteria = "so.{$filter} LIKE '%{$value}%'";
                        break;
                    case 'tipo':
                        $criteria = "t.id = '{$value}'";
                        break;
                    case 'status':
                        $criteria = "so.status = '{$value}'";
                        break;
                    case 'data_criacao':
                    case 'data_alteracao':
                        $data_inicio = $value['data_inicio'];
                        $data_fim = $value['data_fim'];
                        if (!empty($data_inicio)) {
                            // Divide a data pelo separador /
                            $data_inicio = explode('/', $data_inicio);
                            // Formata a data no padrão do SQL.
                            $data_inicio = "'{$data_inicio[2]}-{$data_inicio[1]}-{$data_inicio[0]}'";
                            $criteria = "so.$filter >= $data_inicio";
                        }
                        if (!empty($data_fim)) {
                            $data_fim = explode('/', $data_fim);
                            $data_fim = "'{$data_fim[2]}-{$data_fim[1]}-{$data_fim[0]}'";
                            $criteria = empty($criteria) ? '' : "$criteria and ";
                            $criteria .= "so.$filter <= $data_fim";
                        }
                        break;
                }
            }
            if ($_SESSION['usuario']['perfil'] == 'P') {
                if (empty($criteria)) {
                    $criteria = "s.login = '".$_SESSION['usuario']['login']."'";
                } else {
                    $criteria .= " and s.login = '".$_SESSION['usuario']['login']."'";
                }
            }
            $solicitacoes = $this->getPage($page_number, 10, $criteria);
            $total_records = empty($criteria) ? $this->dao->count() : $this->dao->count($criteria);
            $this->paginator->totalrecords = $total_records;
            if ($total_records > 0) {
                $html = "<table id=\"tablesorter\"\n";
                $html .= "    class=\"table table-striped table-bordered table-condensed tablesorter\">\n";
                $html .= "    <thead>\n";
                $html .= "        <tr>\n";
                $html .= "            <th width=\"5%\">ID</th>\n";
                $html .= "            <th width=\"10%\">Solicitante</th>\n";
                $html .= "            <th width=\"22%\">Titulo</th>\n";
                $html .= "            <th width=\"10%\">Status</th>\n";
                $html .= "            <th width=\"18%\">Tipo</th>\n";
                $html .= "            <th width=\"9%\">Criação</th>\n";
                $html .= "            <th width=\"9%\">Alteração</th>\n";
                $html .= "            <th width=\"17%\" class=\"nonSortable\">Ação</th>\n";
                $html .= "        </tr>\n";
                $html .= "    </thead>\n";
                $html .= "    <tbody>\n";
                $tooltip = '';
                foreach ($solicitacoes as $solicitacao) {
                    $html .= "        <tr>\n";
                    $html .= "            <td>{$solicitacao['id']}</td>\n";
                    $tooltip = "<span data-toggle=\"tooltip\" data-placement=\"bottom\" \n";
                    $tooltip .= "title=\"{$solicitacao['nome']} \">{$solicitacao['solicitante']}</span>";
                    $html .= "            <td>$tooltip</td>\n";
                    $html .= "            <td>{$solicitacao['titulo']}</td>\n";
                    $html .= "            <td>{$solicitacao['status']}</td>\n";
                    $html .= "            <td>{$solicitacao['tipo_solicitacao']}</td>\n";
                    if ($solicitacao['data_criacao'] != null) {
                        $data_criacao = date('d/m/Y', @strtotime($solicitacao['data_criacao']));
                        $hora_criacao = date('H:i:s', @strtotime($solicitacao['data_criacao']));
                        $tooltip = "<span data-toggle=\"tooltip\" data-placement=\"bottom\" \n";
                        $tooltip .= "title=\"Data: $data_criacao Hora: $hora_criacao \">$data_criacao</span>";
                        $html .= "            <td>$tooltip</td>\n";
                    } else {
                        $html .= "            <td></td>\n";
                    }
                    if ($solicitacao['data_alteracao'] != null) {
                        $hora_alteracao = date('H:i:s', @strtotime($solicitacao['data_alteracao']));
                        $data_alteracao = date('d/m/Y', @strtotime($solicitacao['data_alteracao']));
                        $tooltip = "<span data-toggle=\"tooltip\" data-placement=\"bottom\" \n";
                        $tooltip .= "title=\"Data: $data_alteracao Hora: $hora_alteracao \">$data_alteracao</span>";
                        $html .= "            <td>$tooltip</td>\n";
                    } else {
                        $html .= "            <td></td>\n";
                    }
                    $html .= "            <td colspan=\"2\">\n";
                    $html .= "                <button\n";
                    $html .= "                    class=\"btn btn-warning btn-sm\"\n";
                    $status = $solicitacao['status'];
                    $perfil = $_SESSION['usuario']['perfil'];
                    if (($status == 'CRIADA' && $perfil == 'P') ||
                        ($status == 'EM ANÁLISE' && $perfil == 'P') || 
                        ($status == 'DEFERIDA' && $perfil == 'P')) {
                        $html .= "                    data-toggle=\"modal\"\n";
                        $html .= "                    data-target=\"#modal-edit\"\n";
                    }
                    if ($perfil == 'A') {
                        $html .= "                    data-toggle=\"modal\"\n";
                        $html .= "                    data-target=\"#modal-edit\"\n";
                    }
                    $html .= "                    onclick='edit(".json_encode($solicitacao).",".$page_number.")'>\n";
                    $html .= "                    <strong>\n";
                    $html .= "                        Editar&nbsp;\n";
                    $html .= "                        <span class=\"glyphicon glyphicon-edit\"></span>\n";
                    $html .= "                    </strong>\n";
                    $html .= "                </button>&nbsp;&nbsp;\n";
                    $html .= "                <button\n";
                    $html .= "                    class=\"delete-type btn btn-danger btn-sm\"\n";
                    $html .= "                    data-toggle=\"modal\"\n";
                    $html .= "                    data-target=\"#modal-del\"\n";
                    $html .= "                    onclick='del(" . json_encode($solicitacao) . "," . $page_number;
                    $html .=  "," . $total_records .")'>\n"; 
                    if ($perfil == 'P') {
                        $html .= "                    <strong>\n";
                        $html .= "                        Cancelar&nbsp;\n";
                        $html .= "                        <span class=\"glyphicon glyphicon-remove\"></span>\n";
                        $html .= "                    </strong>\n";
                    } else {
                        $html .= "                    <strong>\n";
                        $html .= "                        Indeferir&nbsp;\n";
                        $html .= "                        <span class=\"glyphicon glyphicon-remove\"></span>\n";
                        $html .= "                    </strong>\n";
                    } 
                    $html .= "                </button>\n";
                    $html .= "            </td>\n";
                    $html .= "        </tr>\n";
                }
                $html .= "    </tbody>\n";
                if ($total_records > 10) {
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
    }
?>