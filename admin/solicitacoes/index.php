<?php
    /* CADASTRO DE SOLICITAÇÕES */

    // Models
    @include $_SERVER['DOCUMENT_ROOT'] . '/sods/app/models/Solicitacao.php';
    @include $_SERVER['DOCUMENT_ROOT'] . '/sods/app/models/Lotacao.php';
    @include $_SERVER['DOCUMENT_ROOT'] . '/sods/app/models/Usuario.php';
    @include $_SERVER['DOCUMENT_ROOT'] . '/sods/app/models/TipoSolicitacao.php';

    // Controllers
    @include $_SERVER['DOCUMENT_ROOT'] . '/sods/app/controllers/SolicitacoesController.php';
    @include $_SERVER['DOCUMENT_ROOT'] . '/sods/app/controllers/LotacoesController.php';
    @include $_SERVER['DOCUMENT_ROOT'] . '/sods/app/controllers/UsuariosController.php';
    @include $_SERVER['DOCUMENT_ROOT'] . '/sods/app/controllers/TiposSolicitacoesController.php';

    $controller = new SolicitacoesController();
    $tipos_solicitacoes_controller = new TiposSolicitacoesController();

    $tipos_solicitacoes = $tipos_solicitacoes_controller->activeElements();
    $tipos_solicitacao_json = json_encode($tipos_solicitacoes_controller->_list());
    $perfil_usuario = $_SESSION['usuario']['perfil'];

    //Indentificando ações e parâmetros do post
    if (isset($_POST['action'])) {
        // Recuperando os parâmetros da requisição.
        $action = $_POST['action'];
        if (isset($_POST['json'])) {
            $json = $_POST['json'];
            if (!empty($json)) {
                $solicitacao = new Solicitacao();
                $solicitacao->loadJSON($json);
            }
        }
        switch ($action) {
            case 'add':
                $controller->add($solicitacao);
                exit();
                break;
            case 'edit':
                $controller->edit($solicitacao);
                exit();
                break;
            case 'delete':
                $fail = $controller->delete($solicitacao);
                if ($fail) {
                    echo 'ERRO';
                }
                exit();
                break;
            case 'list':
                $filter = isset($_POST['filter']) ? $_POST['filter'] : '';
                $value = isset($_POST['value']) ? $_POST['value'] : '';
                $page = isset($_POST['p']) ? $_POST['p'] : 1;
                date_default_timezone_set("Brazil/East");
                echo $controller->getGrid($page, $filter, $value);
                exit();
                break;
        }
    }
?>
<?php
    //Topo
    @include $_SERVER['DOCUMENT_ROOT'] . '/sods/includes/topo.php'; 
?>
        <!--  Javascript -->
        <script type="text/javascript" src="/sods/static/js/models/Solicitacao.js"></script>
        <script type="text/javascript" src="/sods/static/js/validators/SolicitacaoFormValidator.js"></script>
        <script type="text/javascript" src="/sods/static/js/validators/PesquisaFormValidator.js"></script>
        <script type="text/javascript">
            var solicitacao = null;
            var action = null;
            var form = null;
            var formValidator = null;
            var current_page = 1;
            var filter = null;
            var value = null;
            var tipos_solicitacao_json = null;

            function clean() {
                if (formValidator != null) {
                    formValidator.reset();
                    if ($(form).attr('id') == 'form-search') {
                        html = '<input id="valor" name="valor" type="text" class="form-control" />';
                        $('#valor_filtro').html(html);
                    }
                }
            }
    
            function add() {
                action = "add";
                solicitacao = new Solicitacao();
                solicitacao.id = null;
                form = $('#form-add');
                formValidator = new SolicitacaoFormValidator(form);
                filter = null;
                value = null;
                current_page = 1;
            }

            function edit(solicitacao_json, page) {
                try {
                    if (solicitacao_json != null) {
                        if ((solicitacao_json.status == 'CANCELADA' && '<?php echo $perfil_usuario ?>' == 'P') || 
                            (solicitacao_json.status == 'INDEFERIDA' && '<?php echo $perfil_usuario ?>' == 'P') || 
                            (solicitacao_json.status == 'ATENDIDA' && '<?php echo $perfil_usuario ?>' == 'P')) {
                            modal='#modal-danger';
                            $('#alert-msg').text('Não é possivel editar uma solicitação: ' + solicitacao_json.status);
                            $(modal).modal('show');
                            window.setTimeout(function() {
                                $(modal).modal('hide');
                            }, 3000);
                            return false;
                        }
                        action = 'edit';
                        form = $('#form-edit');
                        $('#solicitante_id', form).val(solicitacao_json.solicitante_id);
                        $('#titulo', form).val(solicitacao_json.titulo);
                        $('#detalhamento', form).val(solicitacao_json.detalhamento);
                        $('#info_adicionais', form).val(solicitacao_json.info_adicionais);
                        $('#observacoes', form).val(solicitacao_json.observacoes);
                        $('#status', form).val(solicitacao_json.status);
                        $('#observacoes_status', form).val(solicitacao_json.observacoes_status);
                        $('#tipo_solicitacao_id', form).val(solicitacao_json.tipo_solicitacao_id);
                        $('#data_criacao', form).val(formataData(solicitacao_json.data_criacao));
                        $('#data_alteracao', form).val(formataData(solicitacao_json.data_alteracao));
                        solicitacao = new Solicitacao();
                        solicitacao.id = solicitacao_json.id;
                        // Validador
                        formValidator = new SolicitacaoFormValidator(form);
                        current_page = page;
                        // Desabilita os campos.
                        if ('<?php echo $perfil_usuario ?>' == 'P') {
                            if (solicitacao_json.status != 'CRIADA') {
                                $('#titulo', form).prop('readonly', true);
                                $('#detalhamento', form).prop('readonly', true);
                                $('#info_adicionais', form).prop('readonly', true);
                                $('#observacoes', form).prop('readonly', true);
                                $('#salvar', form).prop('disabled', true);
                                $('#limpar', form).prop('disabled', true);
                            } else {
                                $('#titulo', form).prop('readonly', false);
                                $('#detalhamento', form).prop('readonly', false);
                                $('#info_adicionais', form).prop('readonly', false);
                                $('#observacoes', form).prop('readonly', false);
                                $('#salvar', form).prop('disabled', false);
                                $('#limpar', form).prop('disabled', false);
                            }
                        }
                        // Retorna a lista de tipos de solicitação.
                        tipos_solicitacao_json = <?php echo $tipos_solicitacao_json ?>;
                        // Popula a combobox de tipos de solicitação.
                        $(tipos_solicitacao_json).each(function(i, e) {
                            $('#tipo_solicitacao_id', form).append(new Option(e.nome, e.id));
                        });
                        // Seleciona o tipo de solicitação do registro a ser editado.
                        $("#tipo_solicitacao_id option[value=\"" + solicitacao_json.tipo_solicitacao_id + "\"]", form)
                            .prop('selected', true);
                        if (solicitacao_json.status != 'CRIADA' && '<?php echo $perfil_usuario ?>' == 'P') {
                            $('#tipo_solicitacao_id', form).prop('disabled', true);
                        } else {
                            $('#tipo_solicitacao_id', form).prop('disabled', false);
                        } 
                    } else {
                        throw 'Não é possível editar uma alteração que não existe.';
                    }
                } catch(e) {
                    alert(e);
                }
            }

            function del(solicitacao_json, page, total_records) {
                 try {
                        if (solicitacao_json != null) {
                            action = 'delete';
                            form = $('#form-del');
                            solicitacao = new Solicitacao();
                            solicitacao.id = solicitacao_json.id;
                            solicitacao.solicitante_id = solicitacao_json.solicitante_id;
                            solicitacao.titulo = solicitacao_json.titulo;
                            solicitacao.detalhamento = solicitacao_json.detalhamento;
                            solicitacao.info_adicionais = solicitacao_json.info_adicionais;
                            solicitacao.observacoes = solicitacao_json.observacoes;
                            solicitacao.status = solicitacao_json.status;
                            solicitacao.tipo_solicitacao_id = solicitacao_json.tipo_solicitacao_id;
                            solicitacao.data_criacao = solicitacao_json.data_criacao;
                            solicitacao.data_alteracao = solicitacao_json.data_alteracao;
                            form = $('#form-del');
                            formValidator = new SolicitacaoFormValidator(form);
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
                    url: '/sods/admin/solicitacoes/',
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
                            $('#alert-del').modal('show');
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
                            // Mostra saída no console do Firebug.
                            console.log(data);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.log(error);
                    },
                    complete: function(xhr, status) {
                        console.log('A requisição list foi completada.');
                        if ($(form).attr('id') == 'form-search') {
                            clean();
                        }
                    }
                });
                return false;
            }

            function save() {
                var json = null;
                if (solicitacao != null) {
                    switch (action) {
                        case 'add':
                        case 'edit':
                            solicitacao.solicitante_id = $('#solicitante_id', form).val();
                            solicitacao.titulo = $('#titulo', form).val();
                            solicitacao.detalhamento = $('#detalhamento', form).val();
                            solicitacao.info_adicionais = $('#info_adicionais', form).val();
                            solicitacao.observacoes = $('#observacoes', form).val();
                            solicitacao.tipo_solicitacao_id = $('#tipo_solicitacao_id', form).val();
                            // Se a ação for adição, os campos de status não devem ser setados.
                            if (action == 'edit') {
                               solicitacao.status = $('#status', form).val();
                               solicitacao.observacoes_status = $('#observacoes_status', form).val();
                            }
                            json = solicitacao.toJSON();
                            break;
                        case 'delete':
                            json = solicitacao.toJSON();
                            break;
                    }
                    // Valida o formulário.
                    if (formValidator.validate()) {
                        // Ajax
                        $.ajax({
                           type: 'post',
                           url: '/sods/admin/solicitacoes/',
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
                                if (data == 'ERRO') {
                                    modal='#modal-danger';
                                    $('#alert-msg').text('Não é possivel cancelar uma solicitação: ' 
                                        + solicitacao.status);
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
                                console.log('A requisição foi completada.');
                                clean();
                            }
                        });
                    }
                }
                return false;
            }

            function formataData(data) {
                if (data == null) {
                	data =  '';
                } else {
                    data = data.replace(' ', 'T');
                    data = new Date(data);
                    var browser = window.navigator.userAgent.toLocaleLowerCase();
                    if (browser.indexOf('firefox') >= 0) {
                        data = data.toLocaleFormat('%d/%m/%Y %H:%M:%S');
                    } else {
                        var options = {
                            timeZone: 'America/Sao_Paulo',
                            year: '2-digit', 
                            month: '2-digit', 
                            day: '2-digit',
                            hour: 'numeric',
                            minute: 'numeric',
                            second: 'numeric'
                        };
                        data = data.toLocaleString('pt-BR', options);
                    }
                }
                return data;
            }

            // Tratamento de eventos no carregamento da página.
            $(document).ready(function() {
                // Adição
                $('#form-add').submit(function(event) {
                    event.preventDefault();
                    save();
                });

                // Edição
                $('#form-edit').submit(function(event) {
                    event.preventDefault();
                    save();
                });

                // Exclusão
                $('#form-del').submit(function(event) {
                    event.preventDefault();
                    save();
                });

                // Pesquisa
                $('#form-search').submit(function(event) {
                    event.preventDefault();
                    filter = $('#filtro', this).val();
                    if (filter == 'data_criacao' || filter == 'data_alteracao') {
                        var data_inicio = $('#data_inicio', this).val(); 
                        var data_fim =  $('#data_fim', this).val();
                        value = {
                            data_inicio: '',
                            data_fim: ''
                        };
                        if (data_inicio != '') {
                            value.data_inicio = data_inicio;
                        }
                        if (data_fim != '') {
                            value.data_fim = data_fim;
                        }
                    } else {
                        value = $('#valor', this).val();
                    }
                    page = 1;
                    list(page);
                });

                // Mudança do filtro de pesquisa.
                $('#filtro', '#form-search').change(function(e) {
                    var filtro = $(this).val();
                    var html = '';
                    if (filtro == 'status') {
                        var html = '' +
                            '<select id="valor" class="form-control">' +
                            '    <option value="CRIADA" selected="selected">Criada</option>' +
                            '    <option value="EM ANÁLISE" selected="selected">Em Análise</option>' +
                            '    <option value="DEFERIDA" selected="selected">Deferida</option>' +
                            '    <option value="INDEFERIDA" selected="selected">Indeferida</option>' +
                            '    <option value="ATENDIDA" selected="selected">Atendida</option>' +
                            '    <option value="CANCELADA" selected="selected">Cancelada</option>' +
                            '</select>';
                    } else if (filtro == 'tipo') {
                        // Cria a caixa de seleção.
                        html = '' +
                            '<select id="valor" name="valor" class="form-control">' +  
                            '    <option value="">SELECIONE UM TIPO DE SOLICITAÇÃO</option>' +
                            '</select>';
                    } else if (filtro == 'data_criacao' || filtro == 'data_alteracao') {
                        html = '' + 
                            '<div class="input-daterange input-group" id="datepicker">' + 
                            '    <span class="input-group-addon">de</span>' + 
                            '    <input type="text" id="data_inicio" name="data_inicio" class="input-sm form-control" />' +
                            '    <span class="input-group-addon">até</span>' +
                            '    <input type="text" id="data_fim" name="data_fim" class="input-sm form-control" />' +
                            '</div>';
                    } else {
                        html = '<input id="valor" name="valor" type="text" class="form-control" />';
                    }
                    $('#valor_filtro').html(html);
                    // Cria as opções da caixa de seleção.
                    if (filtro == 'tipo') {
                        tipos_solicitacao_json = <?php echo $tipos_solicitacao_json ?>;
                        $(tipos_solicitacao_json).each(function(i, e) {
                            $('#valor', form).append(new Option(e.nome, e.id));
                        });
                    } else if (filtro == 'data_criacao' || filtro == 'data_alteracao') {
                        $('#form-search .input-daterange').datepicker({
                            language: 'pt-BR', 
                            format: 'dd/mm/yyyy'
                        });
                    } else {
                        
                    }
                });
                // Cria paginação AJAX na Grid.
                createAJAXPagination();
            });

            function search() {
                form = $('#form-search');
                formValidator = new PesquisaFormValidator(form);
            }
        </script>

        <div class="container">
            <h2>Solicitações</h2>
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

            <!-- Modais -->

            <!-- Adicionar -->
            <div 
                id="modal-add" 
                class="modal fade" 
                tabindex="-1" 
                role="dialog" 
                aria-labelledby="modal-add" 
                aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button 
                                type="button" 
                                class="close" 
                                data-dismiss="modal" 
                                aria-hidden="true">&times;
                            </button>
                            <h3 class="modal-title" id="modal-add">Adicionar Solicitação</h3>
                        </div>
                        <div class="modal-body">
                            <form id="form-add" action="" method="post" role="form">
                                <div class="form-group">
                                 <input type="hidden" 
                                    class="form-control"
                                    name="solicitante_id" 
                                    id="solicitante_id" 
                                    value="<?php echo $_SESSION['usuario']['id'] ?>"/>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label for="nome">Solicitante</label>
                                            <input type="text" 
                                                   class="form-control" 
                                                   name="nome" 
                                                   id="nome"
                                                   value= "<?php echo $_SESSION['usuario']['nome'] ?>"
                                                   disabled />
                                        </div>
                                        <div class="col-sm-6">
                                            <label for="lotacao">Lotação</label>
                                            <input type="text" 
                                                   class="form-control" 
                                                   name="lotacao" 
                                                   id="lotacao"
                                                   value= "<?php echo $_SESSION['usuario']['nome_lotacao'] ?>"
                                                   disabled />
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="titulo">Título</label>
                                    <input 
                                        type="text" 
                                        id="titulo" 
                                        name="titulo" 
                                        class="form-control" 
                                        maxlength="100" />
                                </div>
                                <div class="form-group">
                                    <label for="detalhamento">Descrição</label>
                                    <textarea 
                                        id="detalhamento" 
                                        name="detalhamento" 
                                        class="form-control" 
                                        rows="6" 
                                        style="width: 100%;"></textarea>
                                
                                </div>
                                <div class="form-group">
                                    <label for="info_adicionais">Informações adicionais</label>
                                    <textarea 
                                        id="info_adicionais" 
                                        name="info_adicionais" 
                                        class="form-control" 
                                        rows="4" 
                                        style="width: 100%;"></textarea>
                                </div>
                                
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label for="observacoes">Observações</label>
                                            <textarea
                                                id="observacoes" 
                                                name="observacoes" 
                                                class="form-control" 
                                                rows="4" 
                                                style="width: 100%"></textarea>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="tipo_solicitacao_id">Tipo</label>
                                                <select 
                                                    id="tipo_solicitacao_id" 
                                                    name="tipo_solicitacao_id" 
                                                    class="form-control">
                                                    <option value="">SELECIONE UM TIPO DE SOLICITAÇÃO</option>
<?php 
                                                foreach ($tipos_solicitacoes as $tipos) {
?>
                                                    <option value="<?php echo $tipos['id'] ?>">
                                                        <?php echo $tipos['nome']?>
                                                    </option>
<?php
                                                }
?>
                                                </select>
                                            </div>
                                        </div>
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
                                        onclick="clean();">Limpar
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
            <!-- /Adicionar -->

            <!-- Editar -->
            <div 
                id="modal-edit" 
                class="modal fade" 
                tabindex="-1" 
                role="dialog" 
                aria-labelledby="modal-edit" 
                aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content modal-scrollbar">
                        <div class="modal-header">
                            <button 
                                type="button" 
                                class="close" 
                                data-dismiss="modal" 
                                aria-hidden="true">&times;
                            </button>
                            <h3 class="modal-title" id="modal-edit">Editar Solicitação</h3>
                        </div>
                        <div class="modal-body">
                            <form id="form-edit" action="" role="form" method="post">
                                <div class="form-group">
                                    <label for="titulo">Título</label>
                                    <input 
                                        type="text" 
                                        id="titulo" 
                                        name="titulo" 
                                        class="form-control" 
                                        maxlength="100" />
                                </div>
                                <div class="form-group">
                                    <label for="detalhamento">Descrição</label>
                                    <textarea 
                                        id="detalhamento" 
                                        name="detalhamento" 
                                        class="form-control" 
                                        rows="6" 
                                        style="width: 100%;"></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="info_adicionais">Informações adicionais</label>
                                    <textarea
                                        id="info_adicionais" 
                                        name="info_adicionais" 
                                        class="form-control" 
                                        rows="2" 
                                        style="width: 100%;"></textarea>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label for="observacoes">Observações</label>
                                            <textarea 
                                                id="observacoes" 
                                                name="observacoes"
                                                class="form-control" 
                                                rows="6" 
                                                style="width: 100%"></textarea>
                                        </div>
                                        <div class="col-sm-6">
                                            <label for="tipo_solicitacao_id">Tipo</label>
                                            <select 
                                                id="tipo_solicitacao_id" 
                                                name="tipo_solicitacao_id" 
                                                class="form-control">
                                                <option value="">SELECIONE UM TIPO DE SOLICITAÇÃO</option>
                                            </select>
                                        </div>
                                        <div class="col-sm-6">
                                            <label for="data_criacao">Criação</label>
                                            <input 
                                                type="text" 
                                                id="data_criacao" 
                                                name="data_criacao" 
                                                class="form-control" 
                                                readonly />
                                        </div>
                                        <div class="col-sm-6">
                                            <label for="data_alteracao">Alteração</label>
                                            <input 
                                                type="text" 
                                                id="data_alteracao" 
                                                name="data_alteracao" 
                                                class="form-control" 
                                                readonly />
                                        </div>
                                    </div>
<?php 
                                    if ($perfil_usuario == "A") {
?>
                                    <div class="row">
                                        
                                        <div class="col-sm-6">
                                            <label for="status">Status</label>
                                            <select id="status" name="status" class="form-control">
                                                <option value="CRIADA" selected="selected">Criada</option>
                                                <option value="EM ANÁLISE">Em análise</option>
                                                <option value="DEFERIDA">Deferida</option>
                                                <option value="INDEFERIDA">Indeferida</option>
                                                <option value="ATENDIDA">Atendida</option>
                                                <option value="CANCELADA">Cancelada</option>
                                            </select>
                                        </div>
                                        
                                        <div class="col-sm-6">
                                            <label for="observacoes_status">Observações do status</label>
                                            <textarea 
                                                id="observacoes_status" 
                                                name="observacoes_status"
                                                class="form-control" 
                                                rows="6" 
                                                style="width: 100%"></textarea>
                                        </div>
                                    </div>
<?php 
                                    } else {
?>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label for="status">Status</label>
                                            <input 
                                                id="status" 
                                                name="status" 
                                                class="form-control" 
                                                readonly="readonly" />
                                        </div>
                                        <div class="col-sm-6">
                                            <label for="observacoes_status">Observações do status</label>
                                            <input 
                                                type="text" 
                                                id="observacoes_status" 
                                                name="observacoes_status" 
                                                class="form-control" 
                                                readonly="readonly" />
                                        </div>
                                    </div>
<?php
                                    } 
?>
                                  </div>
                                <input type="hidden" id="solicitante_id" name="solicitante_id" />
                                <div class="modal-footer">
                                    <button 
                                        id="salvar"
                                        type="submit" 
                                        name="salvar"
                                        class="btn btn-success">Salvar
                                        <span class="glyphicon glyphicon-floppy-disk"></span>
                                    </button>
                                    <button
                                        id="limpar" 
                                        name="limpar" 
                                        type="reset"
                                        class="btn btn-primary" 
                                        onclick="clean();">Limpar
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
            <!-- /Editar -->

            <!-- Excluir -->
            <div 
                id="modal-del" 
                class="modal fade .bs-example-modal-sm" 
                tabindex="-1" 
                role="dialog" 
                aria-labelledby="modal-del" 
                aria-hidden="true">
                <div class="modal-dialog modal-sm">
                    <div class="modal-content">
<?php
                        if ($perfil_usuario == 'P') {
?>
                        <div class="modal-header">
                            <button 
                                type="button" 
                                class="close" 
                                data-dismiss="modal" 
                                aria-hidden="true">&times;
                            </button>
                            <h4 class="modal-title" id="modal-del">Cancelar Solicitação</h4>
                        </div>
                        <div class="modal-body">
                            <h5>Confirma cancelamento da solicitação?</h5>
                        </div>
<?php                         
                        } else {
?>
                        <div class="modal-header">
                            <button 
                                type="button" 
                                class="close" 
                                data-dismiss="modal" 
                                aria-hidden="true">&times;
                            </button>
                            <h4 class="modal-title" id="modal-del">Indeferir Solicitação</h4>
                        </div>
                        <div class="modal-body">
                            <h5>Confirma indeferimento da solicitação?</h5>
                        </div>
<?php 
                        }
?>
                        <form id="form-del" action="" role="form" method="post">
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-danger">Sim</button>
                                <button type="button" class="btn btn-primary" data-dismiss="modal">Não</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- /Excluir -->

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
                            <h3 class="modal-title">Pesquisar Solicitação</h3>
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
                                        <option value="nome">Solicitante</option>
                                        <option value="titulo">Título</option>
                                        <option value="tipo">Tipo Solicitação</option>
                                        <option value="status">Status</option>
                                        <option value="data_criacao">Data de Criação</option>
                                        <option value="data_alteracao">Data de Alteração</option>
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
            <!-- /Pesquisar -->

            <!-- /Modais -->

            <!-- Alertas -->
            <div 
                id="alert-del" 
                class="modal fade" 
                tabindex="-1" 
                role="dialog" 
                aria-labelledby="modal-del" 
                aria-hidden="true">
                <div class="alert alert-danger fade in" role="alert">
                    <button type="button" class="close" onclick="$('#alert-del').modal('toggle');">
                        <span aria-hidden="true">&times;</span><span class="sr-only">Fechar</span>
                    </button>
                    <strong>ERRO:</strong> Não é possível excluir um registro com referências.
               </div>
            </div>

            <div 
                id="modal-danger" 
                class="modal fade" 
                tabindex="-1" 
                role="dialog" 
                aria-labelledby="modal-del" 
                aria-hidden="true">
                <div class="alert alert-danger fade in" role="alert">
                <button type="button" class="close" onclick="$('#modal-danger').modal('toggle');">
                    <span aria-hidden="true">&times;</span><span class="sr-only">Fechar</span>
                </button>
                <strong>FALHA:</strong>
                <span id="alert-msg"></span>
            </div>
        </div>

        <div 
            id="modal-success" 
            class="modal fade" 
            tabindex="-1" 
            role="dialog" 
            aria-labelledby="modal-del" 
            aria-hidden="true">
            <div class="alert alert-success fade in" role="alert">
                <button type="button" class="close" onclick="$('#modal-success').modal('toggle');">
                    <span aria-hidden="true">&times;</span><span class="sr-only">Fechar</span>
                </button>
                <strong>SUCESSO:</strong>
                <span id="alert-msg">Dados atualizados</span>
           </div>
        </div>
        <!-- /Alertas -->
  </div>
  <!-- /Container -->
<?php
    @include $_SERVER['DOCUMENT_ROOT'] . '/sods/includes/rodape.php'; 
?>