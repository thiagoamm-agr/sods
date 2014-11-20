<?php
    // Cadastro de Tipos de Solicitação.

    // Model
    @include $_SERVER['DOCUMENT_ROOT'] . '/sods/app/models/TipoSolicitacao.php';

    // Controller
    @include $_SERVER['DOCUMENT_ROOT'] . '/sods/app/controllers/TiposSolicitacoesController.php';

    $controller = new TiposSolicitacoesController();

    // Identificação e atendimento das ações do usuário pelo controller.
    if (isset($_POST['action'])) {
        // Recuperando os parâmetros da requisição.
        $action = $_POST['action'];
        if (isset($_POST['json'])) {
            $json = $_POST['json'];
            if (!empty($json)) {
                $tipoSolicitacao = new TipoSolicitacao();
                $tipoSolicitacao->loadJSON($json);
            }
        }
        // Identificando a ação desempenhada pelo usuário.
        switch ($action) {
            case 'add':
                $fail = $controller->add($tipoSolicitacao);
                if($fail){
                    echo 'NAOADD';
                }
                exit();
                break;
            case 'edit':
                $controller->edit($tipoSolicitacao);
                exit();
                break;
            case 'delete':
                $fail = $controller->delete($tipoSolicitacao);
                if ($fail) {
                    echo 'ERRO';
                }
                exit();
                break;
            case 'list':
                $filter = isset($_POST['filter']) ? $_POST['filter'] : '';
                $value = isset($_POST['value']) ? $_POST['value'] : '';
                $page = isset($_POST['p']) ? $_POST['p'] : 1;
                echo $controller->getGrid($page, $filter, $value);
                exit();
                break;
    }
}
?>
<?php
    // Topo
    @include $_SERVER['DOCUMENT_ROOT'] . '/sods/includes/topo.php';
?>
    <!--  Javascript -->
    <script type="text/javascript" src="/sods/static/js/models/TipoSolicitacao.js"></script>
    <script type="text/javascript" src="/sods/static/js/validators/TipoSolicitacaoFormValidator.js"></script>
    <script type="text/javascript" src="/sods/static/js/validators/PesquisaFormValidator.js"></script>

    <script type="text/javascript">
        var tipoSolicitacao = null;
        var action = null;
        var form = null;
        var formValidator = null;
        var resposta = null;
        var page = 1;
        var current_page = null;
        var filter = null;
        var value = null;

        function add() {
            action = "add";
            tipoSolicitacao = new TipoSolicitacao();
            tipoSolicitacao.id = null;
            form = $('#form-add');
            formValidator = new TipoSolicitacaoFormValidator(form);
            current_page = 1;
        }

        function edit(tipoSolicitacao_json, page) {
            try {
                if (tipoSolicitacao_json != null) {
                    action = 'edit';
                    form = $('#form-edit');
                    tipoSolicitacao = new TipoSolicitacao();
                    formValidator = new TipoSolicitacaoFormValidator(form);
                    tipoSolicitacao.id = tipoSolicitacao_json.id;
                    $('#nome', form).val(tipoSolicitacao_json.nome);
                    if (tipoSolicitacao_json.status == 'A') {
                        $('#status', form).prop('checked', true);
                        $('#status', form).val('A');
                    } else {
                        $('#status', form).prop('checked', false);
                        $('#status', form).val('I');
                    }
                    current_page = page;
                } else {
                    throw 'Não é possível editar um tipo de lotação inexistente!';
                }
            } catch(e) {
                alert(e);
            }
        }

        function del(tipoSolicitacao_json, page, total_records) {
            try {
                if (tipoSolicitacao_json != null) {
                    action = 'delete';
                    tipoSolicitacao = new TipoSolicitacao();
                    tipoSolicitacao.id = tipoSolicitacao_json.id;
                    tipoSolicitacao.nome = tipoSolicitacao_json.nome;
                    tipoSolicitacao.status = tipoSolicitacao_json.status;
                    form = $('#form-del');
                    formValidator = new TipoSolicitacaoFormValidator(form);
                    total_records = total_records - 1;
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
                url: '/sods/admin/tiposSolicitacao/',
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
                success: function(data, status, xhr) {
                    if (data == 'ERRO') {
                        $('#modal-danger').modal('show');
                        window.setTimeout(function() {
                            $('#modal-danger').modal('hide');
                        }, 3000);
                    } else {
                        // Carrega o HTML da Grid.
                        $('#grid').html(data);
                        // Paginação AJAX na Grid.
                        createAJAXPagination();
                        // Ordenação dos resultados da Grid.
                        $("table thead .nonSortable").data("sorter", false);
                        $("#tablesorter").tablesorter({
                            emptyTo: 'none',
                            theme : 'default',
                            headerTemplate : '{content}{icon}',
                            widgetOptions : {
                              columns : [ "primary", "secondary", "tertiary" ]
                            }
                        });
                        // Tooltip.
                        $('[data-toggle="tooltip"]').tooltip({'placement': 'bottom'});
                        createAJAXPagination();
                    }
                    // Mostra a saída no console do Firebug.
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

        function save() {
            // Verifica se o objeto a ser manipulado existe.
            if (tipoSolicitacao != null) {
                var json = null;
                switch (action) {
                    case 'add':
                    case 'edit':
                        // Obtém os dados do formulário.
                        tipoSolicitacao.nome = $('#nome', form).val();
                        tipoSolicitacao.status = $('#status', form).val();
                        // Serializa o objeto no formato JSON.
                        json = tipoSolicitacao.toJSON();
                        break;
                    case 'delete':
                        json = tipoSolicitacao.toJSON();
                        break;
                }
                // Instancia um validador de formulário.
                formValidator = new TipoSolicitacaoFormValidator(form);
                // Valida o formulário.
                //validation=formValidator.validate();
                if (formValidator.validate()) {
                    // Efetua uma requisição AJAX para essa página.
                    $.ajax({
                        type: 'post',
                        url: '/sods/admin/tiposSolicitacao/',
                        dataType: 'text',
                        cache: false,
                        timeout: 70000,
                        async: true,
                        data: {
                           'action': action,
                            'json': json
                        },
                        success: function(data, status, xhr) {
                            modal='';
                            if (data == 'ERRO') {
                                modal='#modal-danger';
                                $('#alert-msg').text('Não é possivel excluir um tipo de solicitação referenciado');
                                $(modal).modal('show');
                            }
                            else if (data == 'NAOADD'){
                                modal='#modal-danger';
                                $('#alert-msg').text('Não é possivel adicionar um tipo de solicitação repetido.');
                                $(modal).modal('show');
                            }
                            else {
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
                            clean();
                        }
                    });
                }
            }
            return false;
        }

        function search() {
            form = $('#form-search');
            formValidator = new PesquisaFormValidator(form);
        }

        function clean() {
            if (formValidator != null) {
                formValidator.reset();
            }
        }

        /* Definindo a manipulação de alguns eventos após o 
         * carregamento da página.
         */
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

            $('#status', '#form-edit').click(function(event) {
                if ($(this).prop('checked')) {
                    $(this).val('A');
                } else {
                    $(this).val('I');
                }
            });

            createAJAXPagination();
        });
    </script>

    <!-- Container -->
    <div class="container">
        <h2>Tipos de Solicitação</h2>
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

        <!-- Modais -->

        <!-- Modal de adição -->
        <div id="modal-add" class="modal fade" tabindex="-1" role="dialog" 
            aria-labelledby="modal-add" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button 
                            type="button" 
                            class="close" 
                            data-dismiss="modal"
                            aria-hidden="true">&times;
                        </button>
                        <h3 class="modal-title">Adicionar Tipo de Solicitação</h3>
                    </div>
                    <div class="modal-body">
                        <form id="form-add" action="" method="post">
                            <div class="form-group">
                                <label for="nome">Nome</label>
                                <input 
                                    type="text" 
                                    id="nome" 
                                    name="nome" 
                                    class="form-control" 
                                    maxlength="80" />
                            </div>
                            <div class="form-group">
                                 <div>
                                    <label for="status">Status</label>
                                </div>
                                <input 
                                    type="checkbox" 
                                    id="status" 
                                    name="status" 
                                    value="A" 
                                    checked="checked" 
                                    disabled />&nbsp;Ativo
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

        <!-- Modal de edição -->
        <div class="modal fade" id="modal-edit" tabindex="-1" role="dialog" 
            aria-labelledby="modal-edit" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h3 class="modal-title">Editar Tipo de Solicitação</h3>
                    </div>
                    <div class="modal-body">
                        <form role="form" id="form-edit" action="" method="post">
                            <div class="form-group">
                                <label for="nome">Nome</label>
                                <input 
                                    type="text" 
                                    id="nome" 
                                    name="nome" 
                                    class="form-control" 
                                    maxlength="80" />
                            </div>
                            <div class="form-group">
                                <div>
                                    <label for="status">Status</label>
                                </div>
                                <input 
                                    type="checkbox"
                                    id="status"
                                    name="status" 
                                    value="A" 
                                    checked="checked" />&nbsp;Ativo
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

        <!-- Modal de exclusão -->
        <div id="modal-del" class="modal fade" tabindex="-1" role="dialog" 
            aria-labelledby="modal-del" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">Exclusão de Tipo de Solicitação</h4>
                    </div>
                    <form id="form-del" action="" method="post">
                        <div class="modal-body">
                            <h5>Confirma a exclusão?</h5>
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

        <!--  Modal de pesquisa -->
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
                        <h3 class="modal-title">Pesquisar Lotação</h3>
                    </div>
                    <form id="form-search" role="form" method="post">
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="nome">Filtro:</label>
                                <select 
                                    id="filtro" 
                                    name="filtro"
                                    class="form-control">
                                    <option value="">SELECIONE UM FILTRO</option>
                                    <option value="id">ID</option>
                                    <option value="nome">Nome</option>
                                    <option value="status">Status</option>
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
        <div id="modal-danger" class="modal fade" tabindex="-1" role="dialog" 
            aria-labelledby="modal-del" aria-hidden="true">
            <div class="alert alert-danger fade in" role="alert">
                <button type="button" class="close" onclick="$('#modal-danger').modal('toggle');">
                    <span aria-hidden="true">&times;</span><span class="sr-only">Fechar</span>
                </button>
                <strong>FALHA:</strong>
                <span id="alert-msg">Não é possível excluir um registro com referências.</span>
            </div>
        </div>
        
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
        <!-- Alertas -->
    </div>
    <!-- /Container -->
<?php
    // Rodapé
    @include $_SERVER['DOCUMENT_ROOT'] . '/sods/includes/rodape.php';
?>