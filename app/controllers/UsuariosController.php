<?php
    @require_once 'Controller.php';

    @require_once $_SERVER['DOCUMENT_ROOT'] . '/sods/app/dao/UsuarioDAO.php';

    @include_once $_SERVER['DOCUMENT_ROOT'] . '/sods/app/controllers/SolicitacoesController.php';
    @include_once $_SERVER['DOCUMENT_ROOT'] . '/sods/app/lib/class.phpmailer.php';
    @include_once $_SERVER['DOCUMENT_ROOT'] . '/sods/app/lib/class.smtp.php';

    class UsuariosController extends Controller {

        public function __construct() {
            parent::__construct();
            $this->dao = new UsuarioDAO();
            $this->paginator->totalrecords = $this->dao->count();
            $this->paginator->defaultUrl = "/sods/admin/usuarios/";
            $this->paginator->paginationUrl = "/sods/admin/usuarios/";
        }

        public function __destruct() {
            unset($this->dao);
            parent::__destruct();
        }

        public function _list() {
            return $this->dao->getAll();
        }

        public function add($usuario) {
            $usuario->senha = $this->generatePassword();
            $this->dao->insert($usuario);
        }

        public function generatePassword() {
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
                        $value = intval($value);
                        $criteria = "s.{$filter} = {$value}";
                        break;
                    case 'status':
                    case 'perfil':
                        $value = strtoupper($value);
                        $criteria = "s.{$filter} = '{$value}'";
                    case 'nome':
                        $criteria = "s.{$filter} LIKE '%{$value}%'";
                        break;
                    case 'nome_lotacao':
                        $criteria = "l.nome LIKE '%{$value}%'";
                        break;
                    case 'funcao':
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
                $html =  "<table\n";
                $html .= "    id=\"tablesorter\"\n";
                $html .= "    role=\"grid\"\n";
                $html .= "    class=\"table table-striped table-bordered\n" . 
                         "           table-condensed tablesorter tablesorter-default\">\n";
                $html .= "    <thead>\n";
                $html .= "        <tr>\n";
                $html .= "            <th width=\"5%\">ID</th>\n";
                $html .= "            <th width=\"15%\">Login</th>\n";
                $html .= "            <th width=\"20%\">Função</th>\n";
                $html .= "            <th width=\"3%\">Perfil</th>\n";
                $html .= "            <th width=\"3%\">Status</th>\n";
                $html .= "            <th width=\"15%\">Lotação</th>\n";
                $html .= "            <th width=\"13%\" class=\"nonSortable\">Ação</th>\n";
                $html .= "        </tr>\n";
                $html .= "    </thead>\n";
                $html .= "    <tbody>\n";
                foreach ($usuarios as $usuario) {
                    $html .= "        <tr>\n";
                    $html .= "            <td>{$usuario['id']}</td>\n";
                    $tooltip =  "<span data-toggle=\"tooltip\" data-placement=\"bottom\" " . 
                        "title=\"{$usuario['nome_sol']}\">{$usuario['login']}</span>";
                    $html .= "            <td>$tooltip</td>\n";
                    $html .= "            <td>{$usuario['funcao']}</td>\n";
                    $tooltip = "<span data-toggle=\"tooltip\" data-placement=\"bottom\"";
                    if ($usuario['perfil'] == "A") {
                        $tooltip .= "title=\"Administrador do sistema\">Admin</span>";
                    } else {
                        $tooltip .= "title=\"Usuário padrão\">Padrão</span>";
                    }
                    $html .= "            <td>$tooltip</td>\n";
                    $tooltip = "<span data-toggle=\"tooltip\" data-placement=\"bottom\"";
                    if ($usuario['status'] == "A") {
                        $html .= "            <td>Ativo</td>\n";
                    } else {
                        $html .= "            <td>Inativo</td>\n";
                    }
                    $tooltip =  "<span data-toggle=\"tooltip\" data-placement=\"bottom\" " .
                        "title=\"{$usuario['lotacao']}\">{$usuario['sigla_lotacao']}</span>";
                    $html .= "            <td>$tooltip</td>\n";
                    $html .= "            <td colspan=\"2\">\n";
                    $html .= "                <button\n";
                    $html .= "                    class=\"btn btn-warning btn-sm\"\n";
                    $html .= "                    data-toggle=\"modal\"\n";
                    $html .= "                    data-target=\"#modal-edit\"\n";
                    $json = json_encode($usuario);
                    $indentation = str_repeat(' ', 20);
                    $json = jsonpp($json, $indentation);
                    $html .= "                    onclick='edit(\n$json, $page_number)'>\n";
                    $html .= "                    <strong>\n";
                    $html .= "                        Editar&nbsp;\n";
                    $html .= "                        <span class=\"glyphicon glyphicon-edit\"></span>\n";
                    $html .= "                    </strong>\n";
                    $html .= "                </button>\n";
                    $html .= "                &nbsp;\n";
                    $html .= "                <button\n";
                    $html .= "                    class=\"btn btn-danger btn-sm\"\n";
                    $html .= "                    data-toggle=\"modal\"\n";
                    $html .= "                    data-target=\"#modal-del\"\n";
                    $html .= "                    onclick='del($json, $page_number, $total_records)'>\n";
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
                    $html .= "    <tfoot>\n";
                    $html .= "        <tr>\n";
                    $html .= "            <td colspan=\"10\">{$this->paginator}</td>\n";
                    $html .= "        </tr>\n";
                    $html .= "    </tfoot>\n";
                }
                $html .= "</table>\n";
            } else {
                $html = "<div class=\"alert alert-danger\" role=\"alert\">\n";
                $html .= "    <center><b>Nenhum registro encontrado.</b></center>\n";
                $html .= "</div>\n";
            }
            return $html;
        }

        public function getInformacoesPessoais($id) {
            $usuario = $this->getUsuario('s.id', $id);
            $html = "";
            $html .= "<form role=\"form\" id=\"form-edit\" method=\"post\">\n";
            $html .= "    <div id=\"grid\" class=\"table-responsive\">\n";
            $html .= "        <div><h2>Informações Pessoais</h2></div>\n";
            $html .= "        <div class=\"row\">\n";
            $html .= "            <div class=\"col-md-12\">&nbsp;</div>\n";
            $html .= "        </div>\n";
            $html .= "        <div class=\"form-group\">\n";
            $html .= "            <label for=\"nome\">Nome</label>\n";
            $html .= "            <input type=\"text\" id=\"nome\" name=\"nome\" class=\"form-control\"\n";
            $html .= "                value=\"{$usuario['login']}\"/>\n";
            $html .= "        </div>";
            $html .= "        <div class=\"form-group\">\n";
            $html .= "            <label for=\"lotacao\">Lotação</label>\n";
            $html .= "            <select id=\"lotacao_id\" name=\"lotacao_id\" class=\"form-control\">\n";
            $lotacoes_controller = new LotacoesController();
            $lotacoes = $lotacoes_controller->_list();
            foreach ($lotacoes as $lotacao) {
                $html .= "            <option value=\"{$lotacao['id']}\">{$lotacao['nome']}</option>\n";
            }
            $html .= "            </select>\n";
            $html .= "        </div>\n";
            $html .= "        <div class=\"form-group\">\n";
            $html .= "            <label for=\"funcao\">Função</label>\n";
            $html .= "            <input type=\"text\" id=\"funcao\" name=\"funcao\" class=\"form-control\"\n";
            $html .= "                value=\"{$usuario['funcao']}\"/>\n";
            $html .= "        </div>";
            $html .= "        <div class=\"form-group\">\n";
            $html .= "            <label for=\"fone\">Telefone</label>\n";
            $html .= "            <input type=\"text\" id=\"telefone\" name=\"telefone\" class=\"form-control\"\n";
            $html .= "                value=\"{$usuario['telefone']}\"/>\n";
            $html .= "        </div>";
            $html .= "        <div class=\"form-group\">\n";
            $html .= "            <label for=\"email\">E-mail</label>\n";
            $html .= "            <input type=\"text\" id=\"email\" name=\"email\" class=\"form-control\"\n";
            $html .= "                value=\"{$usuario['email']}\"/>\n";
            $html .= "        </div>";
            $html .= "        <div class=\"form-group\">\n";
            $html .= "            <label for=\"login\">Login</label>\n";
            $html .= "            <input type=\"text\" id=\"login\" name=\"login\" class=\"form-control\"\n";
            $html .= "                value=\"{$usuario['login']}\" readonly/>\n";
            $html .= "        </div>";
            $html .= "        <div class=\"form-group\">\n";
            $html .= "            <label for=\"senha\">Senha</label>\n";
            $html .= "            <input type=\"password\" id=\"senha\" name=\"senha\" class=\"form-control\"\n";
            $html .= "                value=\"{$usuario['senha']}\"/>\n";
            $html .= "        </div>";
            $html .= "        <div class=\"form-group\">\n";
            $html .= "            <label for=\"confirmaSenha\">Confirme a senha</label>\n";
            $html .= "            <input type=\"password\" id=\"confirma_senha\" name=\"confirma_senha\"\n";
            $html .= "                class=\"form-control\" value=\"{$usuario['senha']}\"/>\n";
            $html .= "        </div>";
            $html .= "        <div class=\"form-group\">\n";
            $html .= "            <input type=\"hidden\" id=\"id\" name=\"id\" value=\"{$usuario['id']}\"/>\n";
            $html .= "            <input type=\"hidden\" id=\"status\" name=\"status\"\n";
            $html .= "                value=\"{$usuario['status']}\"/>\n";
            $html .= "            <input type=\"hidden\" id=\"data_criacao\" name=\"data_criacao\"\n";
            $html .= "                value=\"{$usuario['data_criacao']}\"/>\n";
            $html .= "            <input type=\"hidden\" id=\"data_alteracao\" name=\"data_alteracao\"\n";
            $html .= "                value=\"{$usuario['data_alteracao']}\"/>\n";
            $html .= "            <input type=\"hidden\" id=\"perfil\" name=\"perfil\"\n";
            $html .= "                value=\"{$usuario['perfil']}\"/>\n";
            $html .= "        </div>\n";
            $html .= "        <div class=\"btn-toolbar pull-right form-footer\">\n";
            $html .= "            <button type=\"submit\" class=\"btn btn-success\">Salvar&nbsp;\n";
            $html .= "                <span class=\"glyphicon glyphicon-floppy-save\"></span>\n";
            $html .= "            </button>\n";
            $html .= "            <button type=\"reset\" class=\"btn btn-default\" onclick=\"reset()\">Resetar&nbsp;\n";
            $html .= "                <span class=\"glyphicon glyphicon-refresh\" style=\"color:black\"></span>\n";
            $html .= "            </button>\n";
            $html .= "        </div>\n";
            $html .= "        <div class=\"row\">\n";
            $html .= "            <div class=\"col-md-12\">&nbsp;</div>\n";
            $html .= "        </div>\n";
            $html .= "    </div>\n";
            $html .= "</form>";
            return $html;
        }
    }
?>