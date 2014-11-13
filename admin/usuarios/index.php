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
                $page = isset($_POST['p']) ? $_POST['p'] : 1;
                echo $controller->getGrid($page);
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
    <script type="text/javascript" src="/sods/static/js/maskedInput.js"></script>
    <script type="text/javascript" src="/sods/static/js/md5.js"></script>

    <script type="text/javascript">
        var usuario = null;
        var action = null;
        var form = null;
        var formValidator = null;
        var resposta = null;
        var current_page = null;

        function add() {
            action = 'add';
            usuario = new Usuario();
            usuario.id = null;
            usuario.status = null;
            form= $('#form-add');
            formValidator = new UsuarioFormValidator(form);
            current_page=1;
        }

        function edit(usuario_json, page) {
        	current_page=page;
            try {
                if (usuario_json.status == 'A') {
                    $('statusEdit').prop('checked', true);
                } else {
                $('statusEdit').prop('checked', false);
                }
                if (usuario_json.tipo_usuario == 'A') {
                    $('tipo_usuario_a').prop('checked', true);
                } else {
                $('tipo_usuario_a').prop('checked', false);
                }
                if (usuario_json != null) {
                    action = 'edit';
                    form = $('#form-edit');
                    formValidator = new UsuarioFormValidator(form);
                    $('#nome', form).val(usuario_json.nome_sol);
                    $('#login', form).val(usuario_json.login);
                    $('#lotacao', form).val("" + usuario_json.lotacao_id);
                    $('#cargo', form).val(usuario_json.cargo);
                    $('#fone', form).val(usuario_json.telefone);
                    $('#email', form).val(usuario_json.email);
                    usuario = new Usuario();
                    usuario.id = usuario_json.id;
                } else {
                    throw 'Não é possível editar uma alteração que não existe.';
                }
            } catch(e) {
                alert(e);
            }
        }

        function del(usuario_json, page, totalRecords ) {
            totalRecords = totalRecords - 1;
            var manipulatedPage = Math.ceil(totalRecords/10);
            if(manipulatedPage < page){
                current_page = manipulatedPage;
            }else{
                current_page = page;
            }
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
            $.ajax({
                type: 'post',
                url: '/sods/admin/usuarios/',
                dataType: 'text',
                cache: false,
                timeout: 7000,
                async: true,
                data: {
                    action: 'list',
                    p:page
                },
                success: function (data, status, xhr) {
                    if (data == 'ERRO') {
                        //implementar uma modal de erro caso haja
                        alert();
                    } else {
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
                    }
                    // Mostra saída no console do Firebug.
                    console.log(data);
                },
                error: function(xhr, status, error) {
                    // console.log(error);
                },
                complete: function(xhr, status) {
                    //console.log('A requisição foi completada.');
                }
            });
            return false;
        }
        
        function save() {
            if (usuario !=  null) {
                var json = null;
                switch (action) {
                    case 'add':
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
                        if (action == 'add') {
                            usuario.status = "";
                            var psw = usuario.senha;
                            usuario.senha = CryptoJS.MD5(psw);
                        }
                        json = usuario.toJSON();
                        break;
                    case 'delete':
                        json = usuario.toJSON();
                        break;
                }                
                // Requisição AJAX
                $.ajax({
                    type: 'post',
                    url: '/sods/admin/usuarios/',
                    dataType: 'text',
                    contentType: 'application/x-www-form-urlencoded',
                    cache: false,
                    timeout: 7000,
                    async: false,
                    data: {
                        'action': action,
                        'json': json
                    },
                    success: function(data, status, xhr) {
                        if (data == 'ERRO') {
                           //implementar modal caso haja erro
                            alert('erro');
                            console.log(data);
                        }
                        console.log(data);
                        // Recarrega a grid.
                        list(current_page);
                    },
                    error: function(xhr, status, error) {
                        // console.log(error);
                    },
                    complete: function(xhr, status) {
                        console.log('data');
                    }
                });
            }
            return false;
        }
        
        function resetForm() {
            if (formValidator != null) {
                formValidator.reset();
            }
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

             // Máscara telefone.
            var fone = $('#fone');
            var mascara = "(99) 9999-9999?9";
            if (fone.val().length > 10) {
                fone.mask("(99) 99999-999?9");
            }
            fone.mask(mascara);

            createAJAXPagination();
        });
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
                    data-target="#modal-search">
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
                                       id="senha" 
                                       value="12345"/>
                            </div>
                            <div class="form-group">
                                <div>
                                    <label for="tipo_usuario">Tipo</label>
                                </div>
                                <div class="form-group">
                                    <input type="radio" name="tipo_usuario" value="U"/>Usuário &nbsp;&nbsp;
                                    <input type="radio" name="tipo_usuario" value="A"/>Administrador
                                </div>
                            </div>                    
                            <div class="modal-footer">
                                <button type="submit" 
                                        class="btn btn-success">Salvar
                                        &nbsp;<span class="glyphicon glyphicon-floppy-save"></span>
                                </button>
                                <button type="reset" 
                                        class="btn btn-default" 
                                        onclick="resetForm()">Limpar</button>
                                <button type="button" 
                                        class="btn btn-primary" 
                                        data-dismiss="modal">Cancelar</button>
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
                            </div>
                            <div class="form-group">
                                <label for="lotacao">Lotação</label>
                                <select id="lotacao" name="lotacao" class="form-control">
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
                                <div>
                                    <label for="tipo_usuario">Tipo</label>
                                </div>
                                <div class="form-group">
                                    <input type="radio" 
                                           id="tipo_usuario_u"
                                           name="tipo_usuario"
                                           value="U"/>Usuário
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
                                           value="A" />Ativar &nbsp;
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" 
                                        class="btn btn-success">Salvar</button>
                                <button type="reset" 
                                        class="btn btn-default" 
                                        onclick="limpar()">Limpar</button>
                                <button type="button" 
                                        class="btn btn-primary" 
                                        data-dismiss="modal">Cancelar</button>
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
                            </button>
                            <button
                                type="button" 
                                class="btn btn-primary" 
                                data-dismiss="modal">Não
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div> <!-- Modais -->
    </div> <!-- Container -->
<?php    
    @include $_SERVER ['DOCUMENT_ROOT'] . '/sods/includes/rodape.php';
?>