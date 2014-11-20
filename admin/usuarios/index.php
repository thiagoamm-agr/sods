<?php
    /* CADASTRO DE USUÁRIOS */

    // Models
    @include $_SERVER['DOCUMENT_ROOT'] . '/sods/app/models/Lotacao.php';
    @include $_SERVER['DOCUMENT_ROOT'] . '/sods/app/models/Usuario.php';

    // Controllers
    @include $_SERVER['DOCUMENT_ROOT'] . '/sods/app/controllers/LotacoesController.php';
    @include $_SERVER['DOCUMENT_ROOT'] . '/sods/app/controllers/UsuariosController.php';

    $controller = new UsuariosController();

    if (isset($_POST['action'])) {
        //Recuperando dados do post
        $action = $_POST['action'];
        if (isset($_POST['json'])) {
            $json = $_POST['json'];
            if (!empty($json)){
                $usuario = new Usuario();
                $usuario->loadJSON($json);
            }
        }

        switch ($action) {
            case 'add':
                $controller->add($usuario);
                exit();
                break;
            case 'edit':
                $controller->edit($usuario);
                exit();
                break;
            case 'delete':
                $controller->delete($usuario);
                exit();
                break;
            case 'list':
                $filter = isset($_POST['filter']) ? $_POST['filter'] : '';
                $value = isset($_POST['value']) ? $_POST['value'] : '';
                $page = isset($_POST['p']) ? $_POST['p'] : 1;
                echo $controller->getGrid($page, $filter, $value);
                exit();
                break;
            case 'check_login':
                if (isset($_POST['login'])) {
                     $login = $_POST['login'];
                     // Edição
                     if (isset($_POST['login_antigo'])) {
                         $login_antigo = $_POST['login_antigo'];
                     } else{
                         $login_antigo = null; 
                     }
                     $valid = $controller->checkLogin($login, $login_antigo);
                     echo json_encode(array(
                         'valid' => $valid
                     ));
                 }
                exit();
                break;
        }
    }
?>
<?php 
    @include $_SERVER['DOCUMENT_ROOT'] . '/sods/includes/topo.php';
?>
    <!-- Javascript -->
    <script type="text/javascript" src="/sods/static/js/models/Usuario.js"></script>
    <script type="text/javascript" src="/sods/static/js/validators/UsuarioFormValidator.js"></script>
    <script type="text/javascript" src="/sods/static/js/validators/PesquisaFormValidator.js"></script>
    <script type="text/javascript" src="/sods/static/js/maskedInput.js"></script>

    <script type="text/javascript">
        var usuario = null;
        var action = null;
        var form = null;
        var formValidator = null;
        var page = 1;
        var current_page = 1;
        var filter = null;
        var value = null;

        function add() {
            action = 'add';
            usuario = new Usuario();
            usuario.id = null;
            usuario.status = null;
            form= $('#form-add');
            formValidator = new UsuarioFormValidator(form);
            filter = null;
            value = null;
            current_page = 1;
        }

        function edit(usuario_json, page) {
            try {
                if (usuario_json != null) {
                    action = 'edit';
                    form = $('#form-edit');
                    usuario = new Usuario();
                    formValidator = new UsuarioFormValidator(form);
                    usuario.id = usuario_json.id;
                    $('#nome', form).val(usuario_json.nome_sol);
                    $('#login', form).val(usuario_json.login);
                    $('#login_antigo', form).val(usuario_json.login);
                    $('#lotacao', form).val("" + usuario_json.lotacao_id);
                    $('#cargo', form).val(usuario_json.cargo);
                    $('#fone', form).val(usuario_json.telefone);
                    $('#email', form).val(usuario_json.email);
                    $('#senha', form).val(usuario_json.senha);
                    $('#confirmaSenha', form).val(usuario_json.senha);
                    if (usuario_json.status == 'A') {
                        $('#statusEdit', form).prop('checked', true);
                        $('#statusEdit', form).attr('disabled', true);
                    }else {
                        $('#statusEdit', form).prop('checked', false);
                        $('#statusEdit', form).attr('disabled', false);
                    }
                    if (usuario_json.tipo_usuario == 'A') {
                        $('#tipo_usuario_a', form).prop('checked', true);
                    } else {
                        $('#tipo_usuario_u', form).prop('checked', true);
                    }
                    current_page = page;
                } else {
                    throw 'Não é possível editar uma alteração que não existe.';
                }
            } catch(e) {
                alert(e);
            }
        }

        function del(usuario_json, page, totalRecords) {
            try {
                if (usuario_json != null) {
                    action = 'delete';
                    usuario = new Usuario();
                    usuario.id = usuario_json.id;
                    usuario.nome = usuario_json.nome_sol;
                    usuario.lotacao_id = usuario_json.lotacao_id;
                    usuario.cargo = usuario_json.cargo;
                    usuario.telefone = usuario_json.telefone;
                    usuario.email = usuario_json.email;
                    usuario.login = usuario_json.login;
                    usuario.senha = usuario_json.senha;
                    usuario.tipo_usuario = usuario_json.tipo_usuario;
                    usuario.status = usuario_json.status;
                    usuario.data_criacao = usuario_json.data_criacao;
                    usuario.data_alteracao = usuario_json.data_alteracao;
                    form = $('#form-del');
                    formValidator = new UsuarioFormValidator(form);
                    totalRecords = totalRecords - 1;
                    var manipulatedPage = Math.ceil(totalRecords/10);
                    if (manipulatedPage < page) {
                        current_page = manipulatedPage;
                    } else {
                        current_page = page;
                    }
                }
            } catch(e) {
                alert(e);
            }
        }

        function createAJAXPagination() {
            $('.pagination-css').on({
                click: function(e) {
                    var page = $(this).attr('id');
                    page = page.replace('pg_', '');
                    page = page.replace('pn_', '');
                    page = page.replace('pl_', '');
                    page = page.replace('pp_', '');
                    page = page.replace('p_', '');
                    list(page);
                    e.preventDefault();
                    return false;
                }
            });
        }

        function list(page) {
            if ($(form).attr('id') == 'form-search') {
                formValidator.validate();
            }
            $.ajax({
                type: 'post',
                url: '/sods/admin/usuarios/',
                dataType: 'text',
                cache: false,
                timeout: 70000,
                async: true,
                data: {
                    'action': 'list',
                    'p': page,
                    'filter': filter,
                    'value': value
                },
                success: function (data, status, xhr) {
                    // Carrega o HTML da Grid.
                    $('#grid').html(data);
                    // Paginação AJAX na Grid.
                    createAJAXPagination();
                    //Ordenação dos registros da Grid
                    $("table thead .nonSortable").data("sorter", false);
                    $("#tablesorter").tablesorter({
                        emptyTo: 'none',
                        theme : 'default',
                        headerTemplate : '{content}{icon}',
                        widgetOptions : {
                          columns : [ "primary", "secondary", "tertiary" ]
                        }
                    });
                    $('[data-toggle="tooltip"]').tooltip({'placement': 'bottom'});
                    // Mostra saída no console do Firebug.
                    console.log(data);
                },
                error: function(xhr, status, error) {
                    console.log(error);
                },
                complete: function(xhr, status) {
                    console.log('A requisição foi completada.');
                    if ($(form).attr('id') == 'form-search') {
                        clean();
                    }
                }
            });
        }

        function clean() {
            if (formValidator != null) {
                formValidator.reset();
            }
        }

        function save() {
            if (usuario !=  null) {
                var json = null;
                switch (action) {
                    case 'add':
                        usuario.status = "";
                        usuario.senha = ""; 
                    case 'edit':
                        usuario.nome = $('#nome', form).val();
                        usuario.lotacao_id = $('#form-' + action + ' select[name="lotacao"]').val();
                        usuario.cargo = $('#cargo', form).val();
                        usuario.telefone = $('#fone', form).val();
                        usuario.email = $('#email', form).val();
                        usuario.login = $('#login', form).val();
                        usuario.senha = $('#senha', form).val();
                        usuario.tipo_usuario = $('#form-' + action  + ' input:radio[name="tipo_usuario"]:checked').val();
                        usuario.status = $('#form-' + action  + ' input:checkbox[name="statusEdit"]:checked').val();
                        json = usuario.toJSON();
                        break;
                    case 'delete':
                        json = usuario.toJSON();
                        break;
                } 
                formValidator = new UsuarioFormValidator(form);
                if (formValidator.validate()) {
                // Requisição AJAX!
                    $.ajax({
                        type: 'post',
                        url: '/sods/admin/usuarios/',
                        dataType: 'text',
                        cache: false,
                        timeout: 70000,
                        async: true,
                        data: {
                            'action': action,
                            'json': json
                        },
                        success: function(data, status, xhr) {
                            modal=''
                            if (data == 'LOGIN-EXISTENTE') {
                                modal='#modal-danger';
                                $('#alert-msg').text('Registro Existente !');
                                $(modal).modal('show');
                            } else {
                                modal='#modal-success';
                                $('#alert-msg').text('Dados Atualizados !');
                                $(modal).modal('show');
                                list(current_page);
                            }
    
                            window.setTimeout(function() {
                                $(modal).modal('hide');
                            }, 3000);
    
                            console.log(data);
                        },
                        error: function(xhr, status, error) {
                            console.log(error);
                        },
                        complete: function(xhr, status) {
                            console.log('data');
                            clean();
                        }
                    });
                }
            }
            return false;
        }

        $(document).ready(function() {
            $('#form-add').submit(function(event) {
                event.preventDefault();
                save();
            });

            $('#form-edit').submit(function(event) {
                event.preventDefault();
                save();
            });

            $('#form-del').submit(function(event) {
                event.preventDefault();
                save();
            });

            $('#form-search').submit(function(event) {
                event.preventDefault();
                filter = $('#filtro', this).val();
                value = $('#valor', this).val();
                page = 1;
                list(page);
            });
             // Máscara telefone.
            $('#fone', '#form-add').mask('(99) 9999-9999');
            $('#fone', '#form-edit').mask('(99) 9999-9999');
            createAJAXPagination();
        });
        
        function search() {
            form = $('#form-search');
            formValidator = new PesquisaFormValidator(form);
        }
    </script>
        
    <div class="container">
        <h2>Usuários</h2>
        <div class="row">
            <div class="col-md-12">
                <div class="col-md-11">
                    <button
                        id="btn-add"
                        class="btn btn-primary btn-sm pull-right" 
                        data-toggle="modal" 
                        data-target="#modal-add"
                        onclick="add()">
                        <b>Adicionar</b>
                        <span class="glyphicon glyphicon-plus"></span>
                    </button>
                </div>
                    <button
                        id="btn-search"
                        class="btn btn-info btn-sm pull-right" 
                        data-toggle="modal" 
                        data-target="#modal-search"
                        onclick="search()">
                        <b>Pesquisar</b>
                        <span class="glyphicon glyphicon-search"></span>
                    </button>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">&nbsp;</div>
        </div>
        <div id="grid" class="table-responsive">
<?php 
            echo $controller->getGrid(1);
?>
        </div>

        <!--  Modais -->
        
        <!-- Adicionar Usuário -->
        <div class="modal fade" id="modal-add" tabindex="-1" role="dialog" 
             aria-labelledby="modal-add" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                                &times;
                        </button>
                        <h3 class="modal-title" id="modal-add">Adicionar Usuário</h3>
                    </div>
                    <div class="modal-body">
                        <form id="form-add" action="" role="form" method="post">
                            <div class="form-group">
                                <label for="nome">Nome</label>
                                <input type="text" class="form-control" name="nome" id="nome" maxlength="255"/>
                            </div>
                            <div class="form-group">
                                <label for="lotacao">Lotação</label>
                                <select id="lotacao" name="lotacao" class="form-control">
                                <option value="">SELECIONE UMA LOTAÇÃO</option>
<?php
                                    $lotacoesController = new LotacoesController();

                                    $lotacoes = $lotacoesController->_list();
                                    
                                    foreach ($lotacoes as $lotacao){
?>
                                    <option value="<?php echo $lotacao['id'] ?>"><?php echo $lotacao['nome']?>
                                    </option>
<?php
                                    } 
?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="cargo">Cargo</label>
                                <input type="text" class="form-control" name="cargo" id="cargo" maxlength="255"/>
                            </div>
                            <div class="form-group">
                                <label for="fone">Telefone / Ramal</label>
                                <input id="fone" name="fone" type="text" class="form-control" maxlength="30" />
                            </div>
                            <div class="form-group">
                                <label for="email">E-mail</label>
                                <input type="text" class="form-control" name="email" id="email" maxlength="100"/>
                            </div>
                            <div class="form-group">
                                <label for="login">Login</label>
                                <input type="text" class="form-control" name="login" id="login" maxlength="50"/>
                            </div>
                            <div class="form-group">
                                <input type="hidden" 
                                       class="form-control" 
                                       name="senha" 
                                       id="senha" />
                            </div>
                            <div class="form-group">
                                <div>
                                    <label for="tipo_usuario">Tipo</label>
                                </div>
                                <div class="form-group">
                                    <input type="radio" name="tipo_usuario" value="U" checked="checked"/>Usuário &nbsp;&nbsp;
                                    <input type="radio" name="tipo_usuario" value="A"/>Administrador
                                </div>
                            </div>
                            <div class="modal-footer">
                                 <button 
                                    type="submit" 
                                    class="btn btn-success">
                                    Salvar
                                    <span class="glyphicon glyphicon-floppy-disk"></span>
                                </button>
                                <button 
                                    type="reset" 
                                    class="btn btn-primary" 
                                    onclick="clean()">
                                    Limpar
                                   <span class="glyphicon glyphicon-file"></span>
                                </button>
                                <button 
                                    type="button" 
                                    class="btn btn-default" 
                                    data-dismiss="modal" 
                                    onclick="clean()">
                                    Cancelar
                                    <span class="glyphicon glyphicon-floppy-remove"></span>
                               </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
                    
        <!-- Editar Usuário -->
        <div class="modal fade" id="modal-edit" tabindex="-1" role="dialog" aria-labelledby="modal-edit" 
             aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                            &times;
                        </button>
                        <h3 class="modal-title" id="modal-edit">Editar Usuário</h3>
                    </div>
                    <div class="modal-body">
                        <form id="form-edit" role="form" action="" method="post">
                            <div class="form-group">
                                <label for="nome">Nome</label>
                                    <input id="nome" name="nome" type="text" class="form-control" maxlength="255" />
                            </div>
                            <div class="form-group">
                                <label for="login">Login</label>
                                <input id="login" name="login" type="text" class="form-control" maxlength="50"/>
                                <input id="login_antigo" name="login_antigo" type="hidden" class="form-control"/>
                            </div>
                            <div class="form-group">
                                <label for="lotacao">Lotação</label>
                                <select id="lotacao" name="lotacao" class="form-control">
                                <option value="">SELECIONE UMA LOTAÇÃO</option>
<?php                                   
                                    foreach ($lotacoes as $lotacao){
?>
                                    <option value="<?php echo $lotacao['id'] ?>"><?php echo trim($lotacao['nome']) ?>
                                    </option>
<?php
                                    } 
?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="cargo">Cargo</label>
                                <input id="cargo" name="cargo" type="text" class="form-control" maxlength="255"/>
                            </div>          
                            <div class="form-group">
                                <label for="fone">Telefone / Ramal</label>
                                <input id="fone" name="fone" type="text" class="form-control" maxlength="30" />
                            </div>
                            <div class="form-group">
                                <label for="email">E-mail</label>
                                <input id="email" name="email" type="email" class="form-control" maxlength="100"/>
                            </div>
                            <div class="form-group">
                                <label for="senha">Senha</label>
                                <input type="password" id="senha" name="senha" class="form-control"/>
                            </div>
                            <div class="form-group">
                                <label for="confirmaSenha"> Repita a senha</label>
                                <input type="password" id="confirmaSenha" name="confirmaSenha" class="form-control"/>
                            </div>
                            <div class="form-group">
                                <div>
                                    <label for="tipo_usuario">Tipo</label>
                                </div>
                                <div class="form-group">
                                    <input type="radio" 
                                           id="tipo_usuario_u"
                                           name="tipo_usuario"
                                           value="U" checked="checked"/>Usuário
                                           &nbsp;&nbsp;
                                    <input type="radio"
                                           id="tipo_usuario_a"
                                           name="tipo_usuario" 
                                           value="A"/>Administrador
                                </div>
                            </div>
                            <div class="form-group">
                                <div>
                                    <label for="status">Status</label>
                                </div>
                                <div class="form-group">
                                    <input type="checkbox" 
                                           id="statusEdit" 
                                           name="statusEdit" 
                                           value="A" 
                                           checked="checked"/>Ativar &nbsp;
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button 
                                    type="submit" 
                                    class="btn btn-success" >Salvar
                                    <span class="glyphicon glyphicon-floppy-disk"></span>
                                </button>
                                <button 
                                    type="reset" 
                                    class="btn btn-primary" 
                                    onclick="clean()">Limpar
                                    <span class="glyphicon glyphicon-file"></span>
                                </button>
                                <button 
                                    type="button" 
                                    class="btn btn-default" 
                                    data-dismiss="modal"
                                    onclick="clean()">Cancelar
                                    <span class="glyphicon glyphicon-floppy-remove"></span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>            
            
        <!-- Excluir Usuário -->
        <div class="modal fade .bs-example-modal-sm" id="modal-del" tabindex="-1" role="dialog" 
             aria-labelledby="modal-del" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                                &times;
                        </button>
                        <h4 class="modal-title" id="modal-del">Exclusão de Usuário</h4>
                    </div>
                    <form id="form-del" action="" method="post">
                        <div class="modal-body">
                            <h5>Confirma exclusão de usuário?</h5>
                        </div>
                        <div class="modal-footer">
                            <button 
                                type="submit" 
                                class="btn btn-danger">Sim
                                <span class="glyphicon glyphicon-floppy-disk"></span>
                            </button>
                            <button 
                                type="button" 
                                class="btn btn-primary" 
                                data-dismiss="modal">Não
                                <span class="glyphicon glyphicon-floppy-remove"></span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div> 
        <!--  Pesquisar -->
            <div 
                id="modal-search"
                class="modal fade"
                tabindex="-1"
                role="dialog" 
                aria-labelledby="modal-edit" 
                aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button 
                                type="button" 
                                class="close" 
                                data-dismiss="modal" 
                                aria-hidden="true">&times;
                            </button>
                            <h3 class="modal-title">Pesquisar Usuário</h3>
                        </div>
                        <form id="form-search" role="form" method="post">
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="nome">Atributo:</label>
                                    <select 
                                        id="filtro" 
                                        name="filtro"
                                        class="form-control">
                                        <option value="">SELECIONE UM ATRIBUTO</option>
                                        <option value="id">ID</option>
                                        <option value="nome">Nome</option>
                                        <option value="nome_lotacao">Lotação</option>
                                        <option value="cargo">Cargo</option>
                                        <option value="login">Login</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="valor">Valor:</label>
                                    <input id="valor" name="valor" type="text" class="form-control" />
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button 
                                    type="submit" 
                                    class="btn btn-success">Pesquisar
                                    <span class="glyphicon glyphicon-search"></span>
                                </button>
                                <button 
                                    type="reset" 
                                    class="btn btn-primary"
                                    onclick="clean()">Limpar
                                    <span class="glyphicon glyphicon-file"></span>
                                </button>
                                <button 
                                    type="button" 
                                    class="btn btn-default" 
                                    data-dismiss="modal">Cancelar
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        <!-- Modais -->
        <div class="modal fade" id="modal-success" tabindex="-1" role="dialog" aria-labelledby="modal-del" 
            aria-hidden="true">
            <div class="alert alert-success fade in" role="alert">
                <button type="button" class="close" onclick="$('#modal-success').modal('toggle');">
                    <span aria-hidden="true">&times;</span><span class="sr-only">Fechar</span>
                </button>
                <strong>SUCESSO:</strong>
                <span id="alert-msg">Dados atualizados</span>
           </div>
        </div>
            <!-- Alerta -->
        <div id="modal-danger" class="modal fade" tabindex="-1" role="dialog" 
            aria-labelledby="modal-del" aria-hidden="true">
            <div class="alert alert-danger fade in" role="alert">
                <button type="button" class="close" onclick="$('#modal-danger').modal('toggle');">
                    <span aria-hidden="true">&times;</span><span class="sr-only">Fechar</span>
                </button>
                <strong>FALHA:</strong>
                <span id="alert-msg">Login já existente.</span>
            </div>
        </div>      
    </div> <!-- Container -->
<?php    
    @include $_SERVER ['DOCUMENT_ROOT'] . '/sods/includes/rodape.php';
?>