<?php
    @require_once 'Controller.php';

    @require_once $_SERVER['DOCUMENT_ROOT'] . '/sods/app/dao/UsuarioDAO.php';

    @include_once $_SERVER['DOCUMENT_ROOT'] . '/sods/app/controllers/SolicitacoesController.php';
    @include_once $_SERVER['DOCUMENT_ROOT'] . '/sods/app/lib/class.phpmailer.php';
    @include_once $_SERVER['DOCUMENT_ROOT'] . '/sods/app/lib/class.smtp.php';

    class UsuariosController extends Controller {

        public function __construct() {
            //faz a chamada do construtor da superclasse
            parent::__construct();
            $this->dao = new UsuarioDAO();
            $this->paginator->totalrecords = $this->dao->count();
            $this->paginator->defaultUrl = "/sods/admin/usuarios/";
            $this->paginator->paginationUrl = "/sods/admin/usuarios/";
        }

        public function __destruct() {
            unset($this->dao);
            //faz a chamada do destrutor da superclasse
            parent::__destruct();
        }

        public function _list() {
            return $this->dao->getAll();
        }

        public function add($usuario) {
            $usuario->senha = $this->generatePassword();
            $this->dao->insert($usuario);
        }
        
        public function generatePassword(){
            $tamanho = 8;
            $alfabeto = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
            $minimo = 0;
            $maximo = strlen($alfabeto) - 1;
            // Gerando a sequencia
            $sequencia = '';
            for ($i = $tamanho; $i > 0; --$i) {
                // Sorteando uma posicao do alfabeto
                $posicao_sorteada = mt_rand($minimo, $maximo);
                // Obtendo o simbolo correspondente do alfabeto
                $caractere_sorteado = $alfabeto[$posicao_sorteada];
                // Incluindo o simbolo na sequencia
                $sequencia .= $caractere_sorteado;
            }
            return $sequencia;
        }
        
        public function delete($usuario) {
            return $this->dao->delete($usuario);
        }

        public function edit($usuario) {
            $this->dao->update($usuario);
        }

        public function count($criteria = null) {
            return $this->dao->count($criteria);
        }

        public function getUsuario($field, $value) {
            return $this->dao->get($field, $value);
        }
        
        public function checkLogin($login, $login_antigo) {
            $row = $this->dao->filter("login = '$login'");
            if (count($row) == 0) {
                $valid = true;
            } else {
                // Edição
                if (!is_null($login_antigo)) {
                    if ($login == $login_antigo) {
                        $valid = true;
                    } else {
                        $valid = false;
                    }
                } else {
                    $valid = false;
                }
            }
            return $valid;
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
                        $criteria = "s.{$filter} = {$value}";
                        break;
                    case 'nome':
                        $criteria = "s.{$filter} LIKE '%{$value}%'";
                        break;
                    case 'nome_lotacao':
                        $criteria = "l.nome LIKE '%{$value}%'";
                        break;
                    case 'cargo':
                        $criteria = "s.{$filter} LIKE '%{$value}%'";
                        break;
                    case 'login':
                        $criteria = "s.{$filter} LIKE '%{$value}%'";
                        break;
                }
            }
            $usuarios = $this->getPage($page_number, 10, $criteria);
            $total_records = empty($criteria) ? $this->dao->count() : $this->dao->count($criteria);
            $this->paginator->totalrecords = $total_records;
            if ($total_records > 0) {
                $html = "<table class=\"table table-striped table-bordered table-condensed tablesorter " . 
                "tablesorter-default\" role=\"grid\"";
                $html .= "id=\"tablesorter\">";
                $html .= "<thead><tr>";
                $html .= "<th width=\"5%\">ID</th>";
                $html .= "<th width=\"17%\">Nome</th>";
                $html .= "<th width=\"17%\">Lotação</th>";
                $html .= "<th width=\"10%\">Cargo</th>";
                $html .= "<th width=\"10%\">Telefone/Ramal</th>";
                $html .= "<th width=\"10%\">Tipo de Usuário</th>";
                $html .= "<th width=\"5%\">Status</th>";
                $html .= "<th width=\"10%\">Login</th>";
                $html .= "<th width=\"16%\" class=\"nonSortable\">Ação</th>";
                $html .= "</tr></thead>";
                foreach ($usuarios as $usuario) {
                    $html .= "<tr>";
                    $html .= "<td>{$usuario['id']}</td>";
                    $html .= "<td>{$usuario['nome_sol']}</td>";
                    $html .= "<td>{$usuario['lotacao']}</td>";
                    $html .= "<td>{$usuario['cargo']}</td>";
                    $html .= "<td>{$usuario['telefone']}</td>";
                    $html .= "<td>";
                    if($usuario['tipo_usuario'] == "A") {
                        $html .= "Administrador";
                    } else {
                        $html .= "Usuário";
                    }
                    $html .= "</td>";
                    $html .= "<td>";
                    if ($usuario['status'] == "A") {
                        $html .= "Ativo";
                    } else {
                        $html .= "<font color='#FF0000'>Inativo</font>";
                    }
                    $html .= "</td>";
                    $html .= "<td>{$usuario['login']}</td>";
                    $html .= "<td colspan=\"2\">";
                    $html .= "<button class=\"btn btn-warning btn-sm\" ";
                    $html .= "data-toggle=\"modal\"";
                    $html .= "data-target=\"#modal-edit\"";
                    $html .= "onclick='edit(" . json_encode($usuario) .", ".$page_number .")'>";
                    $html .= "<strong>Editar&nbsp;<span class=\"glyphicon glyphicon-edit\"></span></strong></button>\n";
                    $html .= "&nbsp;&nbsp;<button class=\"btn btn-danger btn-sm\"";
                    $html .= "data-toggle=\"modal\"";
                    $html .= "data-target=\"#modal-del\"";
                    $html .= "onclick='del(" . json_encode($usuario) . ", ". $page_number .", " . $total_records .")'>";
                    $html .= "<strong>Excluir&nbsp;<span class=\"glyphicon glyphicon-remove\"></span></strong>\n";
                    $html .= "</td>";
                    $html .= "</tr>";
                }
                $html .= "</tbody>";
                if ($total_records > 10) {
                    $html .= "<tfoot><tr><td colspan=\"10\">{$this->paginator}</td></tr></tfoot>";
                }
                $html .= "</table>";
            } else {
                $html = "<div class=\"alert alert-danger\" role=\"alert\">\n";
                $html .= "    <center><b>Nenhum registro encontrado.</b></center>\n";
                $html .= "</div>\n";
            }
            return $html;
        }

        public function getForm($id) {
            $usuario = $this->getUsuario('s.id', $id);
            $html = "<div><h2>Conta</h2></div>";
            $html .= "<div class=\"form-group\">";
            $html .= "<label for=\"nome\">Nome</label>";
            $html .= "<input type=\"text\" id=\"nome\" name=\"nome\" class=\"form-control\"";
            $html .= "value=\"{$usuario['nome']}\"/>";
            $html .= "</div>";
            $html .= "<div class=\"form-group\">";
            $html .= "<label for=\"lotacao\">Lotação</label>";
            $html .= "<select id=\"lotacao_id\" name=\"lotacao_id\" class=\"form-control\">";
            $lotacoesController = new LotacoesController();
            $lotacoes = $lotacoesController->_list();
            foreach ($lotacoes as $lotacao) {
                $html .= "<option value=\"{$lotacao['id']}\"> {$lotacao['nome']} </option>";
            }
            $html .= "</select></div>";
            $html .= "<div class=\"form-group\">";
            $html .= "<label for=\"cargo\">Cargo</label>";
            $html .= "<input type=\"text\" id=\"cargo\" name=\"cargo\" class=\"form-control\"";
            $html .= "value=\"{$usuario['cargo']}\"/>";
            $html .= "</div>";
            $html .= "<div class=\"form-group\">";
            $html .= "<label for=\"fone\">Telefone / Ramal</label>";
            $html .= "<input type=\"text\" id=\"fone\" name=\"fone\" class=\"form-control\"";
            $html .= "value=\"{$usuario['telefone']}\"/>";
            $html .= "</div>";
            $html .= "<div class=\"form-group\">";
            $html .= "<label for=\"email\">E-mail</label>";
            $html .= "<input type=\"text\" id=\"email\" name=\"email\" class=\"form-control\"";
            $html .= "value=\"{$usuario['email']}\"/>";
            $html .= "</div>";
            $html .= "<div class=\"form-group\">";
            $html .= "<label for=\"login\">Login</label>";
            $html .= "<input type=\"text\" id=\"login\" name=\"login\" class=\"form-control\"";
            $html .= "value=\"{$usuario['login']}\" readonly/>";
            $html .= "</div>";
            $html .= "<div class=\"form-group\">";
            $html .= "<label for=\"senha\">Senha</label>";
            $html .= "<input type=\"password\" id=\"senha\" name=\"senha\" class=\"form-control\"";
            $html .= "value=\"{$usuario['senha']}\"/>";
            $html .= "</div>";
            $html .= "<div class=\"form-group\">";
            $html .= "<label for=\"confirmaSenha\">Repita a senha</label>";
            $html .= "<input type=\"password\" id=\"confirmaSenha\" name=\"confirmaSenha\" class=\"form-control\"";
            $html .= "value=\"{$usuario['senha']}\"/>";
            $html .= "</div>";
            $html .= "<div class=\"form-group\">";
            $html .= "<input type=\"hidden\" id=\"id\"
                name=\"id\" value=\"{$usuario['id']}\"/>";
            $html .= "<input type=\"hidden\" id=\"status\"
                name=\"status\" value=\"{$usuario['status']}\"/>";
            $html .= "<input type=\"hidden\" id=\"data_criacao\"
                name=\"data_criacao\" value=\"{$usuario['data_criacao']}\"/>";
            $html .= "<input type=\"hidden\" id=\"data_alteracao\"
                name=\"data_alteracao\" value=\"{$usuario['data_alteracao']}\"/>";
            $html .= "<input type=\"hidden\" id=\"tipo_usuario\"
                name=\"tipo_usuario\" value=\"{$usuario['tipo_usuario']}\"/>";
            $html .= "</div>";
            $html .= "<div class=\"btn-toolbar pull-right form-footer\">";
            $html .= "<button type=\"submit\" class=\"btn btn-success\">Salvar";
            $html .= "&nbsp;<span class=\"glyphicon glyphicon-floppy-save\"></span></button>";
            $html .= "<button type=\"reset\" class=\"btn btn-default\" onclick=\"limpar()\">Resetar";
            $html .= "&nbsp;<span class=\"glyphicon glyphicon-refresh\" style=\"color:black\"</button>";
            $html .= "</div>";
            
            return $html;
        }
    }
?>