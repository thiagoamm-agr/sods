<?php
    @require_once 'Controller.php';

    @require_once $_SERVER['DOCUMENT_ROOT'] . '/sods/app/dao/UsuarioDAO.php';

    @include_once $_SERVER['DOCUMENT_ROOT'] . '/sods/app/controllers/SolicitacoesController.php';

    class UsuariosController extends Controller {

        public function __construct() {
            //faz a chamada do construtor da superclasse
            parent::__construct();
            $this->dao = new UsuarioDAO();
            $this->paginator->totalrecords = $this->dao->count();
            $this->paginator->defaultUrl = "/sods/admin/usuarios/";
            $this->paginator->paginationUrl = "/sods/admin/usuarios/index.php?p=[p]";
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
            $this->dao->insert($usuario);
        }

        public function delete($usuario) {
            $this->dao->delete($usuario);
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

        public function getPage($page_number=1, $rows=10) {
            if (empty($page_number)) {
                $page_number = 1;
            }
            $this->paginator->pagesize = $rows;
            $this->paginator->pagenumber = $page_number;
            return $this->dao->paginate($rows, $page_number * $rows - $rows);
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
            $html .= "<button type=\"submit\" class=\"btn btn-success\">Salvar</button>";
            $html .= "<button type=\"reset\" class=\"btn btn-default\" onclick=\"limpar()\">Resetar</button>";
            $html .= "</div>";
            return $html;
        }
    }
?>