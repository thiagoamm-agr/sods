<?php
    // Cadastro de Lotações

    // Models
    @include $_SERVER['DOCUMENT_ROOT'] . '/sods/app/models/Lotacao.php';

    // Controllers
    @include $_SERVER['DOCUMENT_ROOT'] . '/sods/app/controllers/LotacoesController.php';

    $controller = new LotacoesController();

    // Identificação e atendimento das ações do usuário pelo controller.
    if (isset($_POST['action'])) {
        // Recuperando os parâmetros da requisição.
        $action = $_POST['action'];
        if (isset($_POST['json'])) {
            $json = $_POST['json'];
            if (!empty($json)) {
                $lotacao = new Lotacao();
                $lotacao->loadJSON($json);
            }
        }
        // Identificando a ação desempenhada pelo usuário.
        switch ($action) {
            case 'add':
                $fail=$controller->add($lotacao);
                if($fail){
                    echo 'NOUPDATE';
                }
                exit(); // finaliza a stream (a resposta termina aqui).
                break;
            case 'edit':
                $fail=$controller->edit($lotacao);
                if($fail){
                    echo 'NOUPDATE';
                }
                exit();
                break;
            case 'delete':
                $fail = $controller->delete($lotacao->id);
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
        <script type="text/javascript" src="/sods/static/js/models/Lotacao.js"></script>
        <script type="text/javascript" src="/sods/static/js/validators/LotacaoFormValidator.js"></script>
        <script type="text/javascript" src="/sods/static/js/validators/PesquisaFormValidator.js"></script>

        <script type="text/javascript">
            var lotacao = null;
            var action = null;
            var form = null;
            var formValidator = null;
            var page = 1;
            var current_page = 1;
            var filter = null;
            var value = null;

            function add() {
                action = 'add';
                lotacao = new Lotacao();
                lotacao.id = null;
                form = $('#form-add');
                formValidator = new LotacaoFormValidator(form);
                current_page = 1;
                filter = null;
                value = null; 
            }

            function edit(lotacao_json, page) {
                try {
                    if (lotacao_json != null) {
                        action = 'edit';
                        modal = 'modal-edit';
                        form = $('#form-' + action);
                        $('#nome', form).val(lotacao_json.nome);
                        $('#sigla', form).val(lotacao_json.sigla);
                        // Caixa de seleção de gerências.
                        var select = $('#gerencia option', form);
                        // Itera sobre a lista de opções (caixa de seleção).
                        select.each(function(i, e) {
                            var t1 = $(e).text();
                            var n = $('#nome', '#form-edit').val();
                            var s = $('#sigla', '#form-edit').val();
                            // Texto vísivel para o usuário.
                            var t2 = n + ' - ' + s;
                            $(this).attr('disabled', false); 
                            if (t1 == t2) {
                                /* Desabilita a gerência que for homônima a lotação 
                                 * que está sendo editada.
                                 */
                                $(this).attr('disabled', true);
                            }
                        });
                        $('#gerencia', form).val("" + lotacao_json.gerencia_id);
                        lotacao = new Lotacao();
                        lotacao.id = lotacao_json.id;
                        formValidator = new LotacaoFormValidator(form);
                        current_page = page; 
                    } else {
                        throw 'Não é possível editar uma alteração que não existe.';
                    }
                } catch(e) {
                    alert(e);
                }
            }

            function del(lotacao_json, page, total_records) {
                try {
                    if (lotacao_json != null) {
                        action = 'delete'; 
                        lotacao = new Lotacao();
                        lotacao.id = lotacao_json.id;
                        lotacao.nome = lotacao_json.nome;
                        lotacao.sigla = lotacao_json.sigla;
                        lotacao.gerencia_id = lotacao_json.gerencia_id;
                        form = $('#form-del');
                        formValidator = new LotacaoFormValidator(form);
                        total_records = total_records - 1;
                        if (total_records == 0) {
                            filter = null;
                            value = null;
                            current_page = 1;
                        }
                        var manipulated_page = Math.ceil(total_records / 10);
                        if (manipulated_page < page) {
                            current_page = manipulated_page;
                        } else{
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
                        page = $(this).attr('id');
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
                    url: '/sods/admin/lotacoes/',
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
                        }
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
                return false;
            }

            function save() {
                if (lotacao != null) {
                    var json = null;
                    switch (action) {
                        case 'add':
                        case 'edit':
                            lotacao.nome = $('#nome', form).val();
                            lotacao.sigla = $('#sigla', form).val();
                            lotacao.gerencia_id = $('#gerencia', form).val();
                            json = lotacao.toJSON();
                            break;
                        case 'delete':
                            json = lotacao.toJSON();
                            break;
                    }
                    if (formValidator.validate()) {
                        // Ajax
                        $.ajax({
                            type: 'post',
                            url: '/sods/admin/lotacoes/',
                            dataType: 'text',
                            cache: false,
                            timeout: 70000,
                            async: true,
                            data: {
                                'action': action,
                                'json': json
                            },
                            success: function(data, status, xhr) {
                                var modal = null;
                                if (data == 'ERRO') {
                                    modal = $('#modal-danger');
                                    $('#danger-msg').text('Não é possível excluir um registro com referências.');
                                    $(modal).modal('show');
                                } else if (data == 'NOUPDATE'){
                                    modal = $('#modal-danger');
                                    $('#danger-msg').text('Não é possível inserir ou editar uma lotação com nome ou sigla repetidos.');
                                    $(modal).modal('show');
                                } 
                                else {
                                    modal = $('#modal-success');
                                    $(modal).modal('show');
                                    list(current_page);
                                }
                                window.setTimeout(function() {
                                    $(modal).modal('hide');
                                }, 4000);
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

                createAJAXPagination();
            });
        </script>

        <!--  Container -->
        <div class="container">
            <h2>Lotações</h2>
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

            <!-- Modal de adição -->
            <div id="modal-add" class="modal fade" tabindex="-1" role="dialog" 
                aria-labelledby="modal-add" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                                &times;
                            </button>
                            <h3 class="modal-title" id="modal-add">Adicionar Lotação</h3>
                        </div>
                        <div class="modal-body">
                            <form id="form-add" role="form" action="" method="post">
                                <div class="form-group">
                                    <label for="nome">Nome</label>
                                    <input type="text" class="form-control" id="nome" name="nome" maxlength="67" />
                                </div>
                                <div class="form-group">
                                    <label for="sigla">Sigla</label>
                                    <input type="text" class="form-control" id="sigla" name="sigla" maxlength="10" />
                                </div>
                                <div class="form-group">
                                    <label for="gerencia">Gerência</label>
                                    <select 
                                        id="gerencia" 
                                        name="gerencia" 
                                        class="form-control">
                                        <option value="">SELECIONE UMA GERÊNCIA</option>
                                        <option value="null">Nenhuma</option>
<?php 
                                    foreach ($controller->getGerencias() as $gerencia) {
?>
                                        <option value="<?php echo $gerencia['id'] ?>"><?php 
                                        echo $gerencia['nome'] . ' - ' . $gerencia['sigla'] ?></option>
<?php 
                                    }
?>
                                    </select>
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
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                                &times;
                            </button>
                            <h3 class="modal-title">Editar Lotação</h3>
                        </div>
                        <div class="modal-body">
                            <form id="form-edit" role="form" action="" method="post">
                                <div class="form-group">
                                    <label for="nome">Nome</label>
                                    <input type="text" class="form-control" id="nome" name="nome" maxlength="67" />
                                </div>
                                <div class="form-group">
                                    <label for="sigla">Sigla</label>
                                    <input type="text" class="form-control" id="sigla" name="sigla" maxlength="10" />
                                </div>
                                <div class="form-group">
                                    <label for="gerencia">Gerência</label>
                                    <select 
                                        id="gerencia" 
                                        name="gerencia" 
                                        class="form-control">
                                        <option value="">SELECIONE UMA GERÊNCIA</option>
                                        <option value="null">Nenhuma</option>
<?php 
                                    foreach ($controller->getGerencias() as $gerencia) {
?>
                                        <option value="<?php echo $gerencia['id'] ?>"><?php 
                                        echo $gerencia['nome'] . ' - ' . $gerencia['sigla'] ?></option>
<?php 
                                    }
?>
                                    </select>
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
            <div class="modal fade" id="modal-del" tabindex="-1" role="dialog" 
            aria-labelledby="modal-del" aria-hidden="true">
                <div class="modal-dialog modal-sm">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button 
                                type="button" 
                                class="close" 
                                data-dismiss="modal" 
                                aria-hidden="true">&times;
                            </button>
                            <h4 class="modal-title">Excluir Lotação</h4>
                        </div>
                        <form id="form-del" action="" method="post">
                            <div class="modal-body">
                                <h5>Confirma exclusão da lotação?</h5>
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
                                        <option value="sigla">Sigla</option>
                                        <option value="gerencia_id">Gerência (ID)</option>
                                        <option value="gerencia_nome">Gerência (Nome)</option>
                                        <option value="gerencia_sigla">Gerência (Sigla)</option>
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
            <div class="modal fade" id="modal-danger" tabindex="-1" role="dialog" 
                aria-labelledby="modal-del" aria-hidden="true">
                <div class="alert alert-danger fade in" role="alert">
                    <button type="button" class="close" onclick="$('#modal-danger').modal('toggle');">
                        <span aria-hidden="true">&times;</span><span class="sr-only">Fechar</span>
                    </button>
                    <strong>FALHA:</strong>
                    <span id="danger-msg">Não é possível excluir um registro com referências.</span>
               </div>
            </div>
            <div class="modal fade" id="modal-success" tabindex="-1" role="dialog" 
                aria-labelledby="modal-success" aria-hidden="true">
                <div class="alert alert-success fade in" role="alert">
                    <button type="button" class="close" onclick="$('#modal-success').modal('toggle');">
                        <span aria-hidden="true">&times;</span><span class="sr-only">Fechar</span>
                    </button>
                    <strong>SUCESSO:</strong>
                    <span id="success-msg">Dados atualizados.</span>
               </div>
            </div>
            <!-- Alertas -->
        </div>
        <!-- Container -->
<?php
    // Rodapé
    @include $_SERVER['DOCUMENT_ROOT'] . '/sods/includes/rodape.php';
?>