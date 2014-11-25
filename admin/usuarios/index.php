<?php

    // Cadastro de Usuários.

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
        <script type="text/javascript" src="/sods/static/js/models/Usuario.js"></script>
        <script type="text/javascript" src="/sods/static/js/validators/UsuarioFormValidator.js"></script>
        <script type="text/javascript" src="/sods/static/js/validators/PesquisaFormValidator.js"></script>
        <script type="text/javascript" src="/sods/static/js/maskedInput.js"></script>

        <script type="text/javascript">
            // Variáveis globais.
            var usuario = null;
            var action = null;
            var form = null;
            var formValidator = null;
            var page = 1;
            var current_page = 1;
            var filter = null;
            var value = null;

            // Inicia a adição de usuário.
            function add() {
                action = 'add';
                usuario = new Usuario();
                usuario.id = null;
                form = $('#form-add');
                formValidator = new UsuarioFormValidator(form);
                filter = null;
                value = null;
                current_page = 1;
            }

            // Inicia a edição de usuário.
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
                        $('#funcao', form).val(usuario_json.funcao);
                        $('#fone', form).val(usuario_json.telefone);
                        $('#email', form).val(usuario_json.email);
                        $('#senha', form).val(usuario_json.senha);
                        $('#confirma_senha', form).val(usuario_json.senha);
                        if (usuario_json.status == 'A') {
                            $('#status', form).prop('checked', true);
                            $('#status', form).val('A');
                        } else {
                            $('#status', form).prop('checked', false);
                            $('#status', form).val('I');
                        }
                        if (usuario_json.perfil == 'A') {
                            $('#perfil', form).prop('checked', true);
                            $('#perfil', form).val('A');
                        } else {
                            $('#perfil', form).prop('checked', false);
                            $('#perfil', form).val('P');
                        }
                        current_page = page;
                    } else {
                        throw 'Não é possível editar uma alteração que não existe.';
                    }
                } catch(e) {
                    alert(e);
                }
            }

            // Inicia a exclusão de usuário.
            function del(usuario_json, page, total_records) {
                try {
                    if (usuario_json != null) {
                        action = 'delete';
                        usuario = new Usuario();
                        usuario.id = usuario_json.id;
                        usuario.nome = usuario_json.nome_sol;
                        usuario.lotacao_id = usuario_json.lotacao_id;
                        usuario.funcao = usuario_json.funcao;
                        usuario.telefone = usuario_json.telefone;
                        usuario.email = usuario_json.email;
                        usuario.login = usuario_json.login;
                        usuario.senha = usuario_json.senha;
                        usuario.perfil = usuario_json.perfil;
                        usuario.status = usuario_json.status;
                        usuario.data_criacao = usuario_json.data_criacao;
                        usuario.data_alteracao = usuario_json.data_alteracao;
                        form = $('#form-del');
                        formValidator = new UsuarioFormValidator(form);
                        total_records = total_records - 1;
                        if (total_records == 0) {
                            filter = null;
                            value = null;
                            current_page = 1;
                        }
                        var manipulated_page = Math.ceil(total_records / 10);
                        if (manipulated_page < page) {
                            current_page = manipulated_page;
                        } else {
                            current_page = page;
                        }
                    }
                } catch(e) {
                    alert(e);
                }
            }

            // Habilita a utilização de AJAX no paginador.
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

            // Lista os usuários cadastrados.
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
                        // Ordenação dos registros da Grid
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

            // Inicia a pesquisa de usuário.
            function search() {
                form = $('#form-search');
                formValidator = new PesquisaFormValidator(form);
            }

            /* Recarrega o formulário de cadastro com os valores padrão 
             * de acordo com a operação.
             */
            function clean() {
                if (formValidator != null) {
                    formValidator.reset();
                    if ($(form).attr('id') == 'form-search') {
                        html = '<input id="valor" name="valor" type="text" class="form-control" />';
                        $('#valor_filtro').html(html);
                    }
                }
            }

            /* Salva as alterações realizadas concluindo
             * a operação iniciada pelo usuário.
             */
            function save() {
                if (usuario !=  null) {
                    var json = null;
                    switch (action) {
                        case 'add':
                            usuario.perfil = "";
                            usuario.senha = ""; 
                        case 'edit':
                            usuario.nome = $('#nome', form).val();
                            usuario.lotacao_id = $('#lotacao', form).val();
                            usuario.funcao = $('#funcao', form).val();
                            usuario.telefone = $('#fone', form).val();
                            usuario.email = $('#email', form).val();
                            usuario.login = $('#login', form).val();
                            usuario.senha = $('#senha', form).val();
                            usuario.perfil = $('#perfil', form).val();
                            usuario.status = $('#status', form).val();
                            json = usuario.toJSON();
                            break;
                        case 'delete':
                            json = usuario.toJSON();
                            break;
                    } 
                    formValidator = new UsuarioFormValidator(form);
                    if (formValidator.validate()) {
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
                                    $(modal).modal('show');
                                } else {
                                    modal='#modal-success';
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

            // Carregamento da página.
            $(document).ready(function() {
                // Definindo tratadores de eventos.

                // Submissão de formulários.
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

                // Mudança do filtro de pesquisa.
                $('#filtro', '#form-search').change(function(e) {
                    var filtro = $(this).val();
                    if (filtro == 'status') {
                        var html = '' +
                            '<select id="valor" class="form-control">' +
                            '    <option value="A" selected="selected">Ativo</option>' +
                            '    <option value="I">Inativo</option>' +
                            '</select>';
                    } else if (filtro == 'perfil') {
                        var html = '' +
                            '<select id="valor" class="form-control">' +
                            '    <option value="A" selected="selected">Administrador</option>' +
                            '    <option value="P">Usuário padrão</option>' +
                            '</select>';
                    } else {
                        html = '<input id="valor" name="valor" type="text" class="form-control" />';
                    }
                    $('#valor_filtro').html(html);
                });

                // Definição de máscara para telefone.
                $('#fone', '#form-add').mask('(99) 9999-9999');
                $('#fone', '#form-edit').mask('(99) 9999-9999');

                $('#perfil', '#form-add').change(function(e) {
                    if ($(this).prop('checked')) {
                        $(this).val('A');
                    } else {
                        $(this).val('U');
                    }
                    console.log($(this).val());
                });

                $('#perfil', '#form-edit').change(function(e) {
                    if ($(this).prop('checked')) {
                        $(this).val('A');
                    } else {
                        $(this).val('U');
                    }
                    console.log($(this).val());
                });

                $('#status', '#form-add').change(function(e) {
                    if ($(this).prop('checked')) {
                        $(this).val('A');
                    } else {
                        $(this).val('I');
                    }
                    console.log($(this).val());
                });

                $('#status', '#form-edit').change(function(e) {
                    if ($(this).prop('checked')) {
                        $(this).val('A');
                    } else {
                        $(this).val('I');
                    }
                    console.log($(this).val());
                });

                // Habilita o AJAX no paginador da Grid.
                createAJAXPagination();
            });
        </script>

        <!-- Container -->
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

            <!-- Grid -->
            <div id="grid" class="table-responsive">
<?php 
            echo $controller->getGrid(1);
?>
            </div>
            <!-- /Grid -->

            <!--  Modais -->

            <!-- Adição -->
            <div 
                id="modal-add" 
                class="modal fade" 
                tabindex="-1" 
                role="dialog" 
                aria-labelledby="modal-add" 
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
                            <h3 class="modal-title" id="modal-add">Adicionar Usuário</h3>
                        </div>
                        <div class="modal-body">
                            <form id="form-add" action="" role="form" method="post">
                                <div class="form-group">
                                    <label for="nome">Nome</label>
                                    <input
                                        id="nome" 
                                        name="nome" 
                                        type="text" 
                                        class="form-control" 
                                        maxlength="255" />
                                </div>
                                <div class="form-group">
                                    <label for="lotacao">Lotação</label>
                                    <select id="lotacao" name="lotacao" class="form-control">
                                        <option value="">SELECIONE UMA LOTAÇÃO</option>
<?php
                                    $lotacoesController = new LotacoesController();
                                    $lotacoes = $lotacoesController->_list();
                                    foreach ($lotacoes as $lotacao) {
?>
                                        <option value="<?php echo $lotacao['id'] ?>"><?php 
                                            echo $lotacao['nome'] 
                                      ?></option>
<?php
                                    }
?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="funcao">Função</label>
                                    <input 
                                        id="funcao"
                                        name="funcao" 
                                        type="text"
                                        class="form-control" 
                                        maxlength="255" />
                                </div>
                                <div class="form-group">
                                    <label for="fone">Telefone</label>
                                    <input 
                                        id="fone" 
                                        name="fone" 
                                        type="text" 
                                        class="form-control" 
                                        maxlength="30" />
                                </div>
                                <div class="form-group">
                                    <label for="email">E-mail</label>
                                    <input
                                        id="email" 
                                        name="email" 
                                        type="text" 
                                        class="form-control" 
                                        maxlength="100"/>
                                </div>
                                <div class="form-group">
                                    <label for="login">Login</label>
                                    <input 
                                        id="login" 
                                        name="login" 
                                        type="text" 
                                        class="form-control" 
                                        maxlength="50" />
                                </div>
                                <div class="form-group">
                                    <input 
                                        id="senha"
                                        name="senha"
                                        type="hidden" 
                                        class="form-control" />
                                </div>
                                <div class="form-group">
                                    <div>
                                        <label for="perfil">Perfil</label>
                                    </div>
                                    <div class="form-group">
                                        <input 
                                            id="perfil"
                                            name="perfil"
                                            type="checkbox"
                                            value="U" />&nbsp;Admin
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button 
                                        type="submit" 
                                        class="btn btn-success">Salvar
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

            <!-- Edição -->
            <div 
                id="modal-edit"
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
                            <h3 class="modal-title" id="modal-edit">Editar Usuário</h3>
                        </div>
                        <div class="modal-body">
                            <form id="form-edit" role="form" action="" method="post">
                                <div class="form-group">
                                    <label for="nome">Nome</label>
                                    <input 
                                        id="nome" 
                                        name="nome" 
                                        type="text" 
                                        class="form-control" 
                                        maxlength="255" />
                                </div>
                                <div class="form-group">
                                    <label for="login">Login</label>
                                    <input 
                                        id="login" 
                                        name="login" 
                                        type="text" 
                                        class="form-control" 
                                        maxlength="50" />
                                    <input 
                                        id="login_antigo" 
                                        name="login_antigo" 
                                        type="hidden" 
                                        class="form-control" />
                                </div>
                                <div class="form-group">
                                    <label for="lotacao">Lotação</label>
                                    <select id="lotacao" name="lotacao" class="form-control">
                                        <option value="">SELECIONE UMA LOTAÇÃO</option>
<?php
                                    foreach ($lotacoes as $lotacao) {
?>
                                        <option value="<?php echo $lotacao['id'] ?>"><?php
                                            echo trim($lotacao['nome'])
                                      ?></option>
<?php
                                } 
?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="funcao">Função</label>
                                    <input 
                                        id="funcao" 
                                        name="funcao" 
                                        type="text" 
                                        class="form-control" 
                                        maxlength="255" />
                                </div>
                                <div class="form-group">
                                    <label for="fone">Telefone</label>
                                    <input 
                                        id="fone" 
                                        name="fone" 
                                        type="text" 
                                        class="form-control" 
                                        maxlength="30" />
                                </div>
                                <div class="form-group">
                                    <label for="email">E-mail</label>
                                    <input 
                                        id="email" 
                                        name="email" 
                                        type="email" 
                                        class="form-control" 
                                        maxlength="100" />
                                </div>
                                <div class="form-group">
                                    <label for="senha">Senha</label>
                                    <input 
                                        id="senha" 
                                        name="senha" 
                                        type="password" 
                                        class="form-control" />
                                </div>
                                <div class="form-group">
                                    <label for="confirma_senha">Confirme a senha</label>
                                    <input 
                                        id="confirma_senha" 
                                        name="confirma_senha" 
                                        type="password" 
                                        class="form-control" />
                                </div>
                                <div class="form-group">
                                    <div>
                                        <label for="perfil">Perfil</label>
                                    </div>
                                    <div class="form-group">
                                        <input type="checkbox"
                                            id="perfil"
                                            name="perfil" 
                                            value="U" />&nbsp;Admin
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div>
                                        <label for="status">Status</label>
                                    </div>
                                    <div class="form-group">
                                        <input 
                                            type="checkbox" 
                                            id="status" 
                                            name="status"
                                            value="I" />&nbsp;Ativo
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button 
                                        type="submit" 
                                        class="btn btn-success">Salvar
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

            <!-- Exclusão -->
            <div
                id="modal-del" 
                class="modal fade .bs-example-modal-sm" 
                tabindex="-1" 
                role="dialog" 
                aria-labelledby="modal-del" 
                aria-hidden="true">
                <div class="modal-dialog modal-sm">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button 
                                type="button" 
                                class="close" 
                                data-dismiss="modal" 
                                aria-hidden="true">&times;
                            </button>
                            <h4 id="modal-del" class="modal-title">Exclusão de Usuário</h4>
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

            <!--  Pesquisa -->
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
                                        <option value="funcao">Função</option>
                                        <option value="login">Login</option>
                                        <option value="status">Status</option>
                                        <option value="perfil">Perfil</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="valor">Valor:</label>
                                    <div id="valor_filtro">
                                        <input 
                                            id="valor" 
                                            name="valor" 
                                            type="text" 
                                            class="form-control" />
                                    </div>
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
                                    data-dismiss="modal"
                                    onclick="clean()">Cancelar
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- /Modais -->

            <!-- Alertas -->

            <!--  Sucesso -->
            <div 
                id="modal-success"
                class="modal fade" 
                tabindex="-1" 
                role="dialog" 
                aria-labelledby="modal-del" 
                aria-hidden="true">
                <div class="alert alert-success fade in" role="alert">
                    <button 
                        type="button" 
                        class="close" 
                        onclick="$('#modal-success').modal('toggle');">
                        <span aria-hidden="true">&times;</span>
                        <span class="sr-only">Fechar</span>
                    </button>
                    <strong>SUCESSO:</strong>
                    <span id="alert-msg">Dados atualizados.</span>
               </div>
            </div>

            <!-- Falha -->
            <div 
                id="modal-danger" 
                class="modal fade" 
                tabindex="-1" 
                role="dialog" 
                aria-labelledby="modal-del" 
                aria-hidden="true">
                <div class="alert alert-danger fade in" role="alert">
                    <button 
                        type="button" 
                        class="close" 
                        onclick="$('#modal-danger').modal('toggle');">
                        <span aria-hidden="true">&times;</span>
                        <span class="sr-only">Fechar</span>
                    </button>
                    <strong>FALHA:</strong>
                    <span id="alert-msg">Não é possível excluir um registro com referências.</span>
                </div>
            </div>
            <!-- /Alertas -->

        </div>
        <!-- /Container -->
<?php    
    @include $_SERVER ['DOCUMENT_ROOT'] . '/sods/includes/rodape.php';
?>