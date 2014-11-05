<?php
    // CADASTRO DE TIPO DE SOLICITAÇÕES

    // Model
    @include $_SERVER['DOCUMENT_ROOT'] . '/sods/app/models/TipoSolicitacao.php';

    // Controller
    @include $_SERVER['DOCUMENT_ROOT'] . '/sods/app/controllers/TiposSolicitacoesController.php';

    $controller = new TiposSolicitacoesController();

    // Identificação e atendimento das ações do usuário pelo controller.
    if (isset ($_POST['action'])) {
        // Recuperando os parâmetros da requisição.
        $action = $_POST['action'];
        if (isset ($_POST['json'])) {
            $json = $_POST['json'];
            if (!empty($json)) {
                $tipoSolicitacao = new TipoSolicitacao();
                $tipoSolicitacao->loadJSON($json);
            }
        }
        // Identificando a ação desempenhada pelo usuário.
        switch ($action) {
            case 'add':
                $controller->add($tipoSolicitacao);
                exit();
                break;
            case 'edit':
                $controller->edit($tipoSolicitacao);
                exit();
                break;
            case 'delete':
                $fail = $controller->delete($tipoSolicitacao);
                if ($fail) {
                    echo'ERRO';
                }
                exit();
                break;
            case 'list':
                echo $controller->getGrid();
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

    <script type="text/javascript">
        var tipoSolicitacao = null;
        var action = null;
        var form = null;
        var formValidator = null;
        var resposta = null;

        function add() {
            action = "add";
            tipoSolicitacao = new TipoSolicitacao();
            tipoSolicitacao.id = null;
            form = $('#form-add');
            formValidator = new TipoSolicitacaoFormValidator(form);
        }

        function edit(tipoSolicitacao_json) {
            try {
                if (tipoSolicitacao_json != null) {
                    action = 'edit';
                    modal = 'modal-edit';
                    form = $('#form-edit');
                    tipoSolicitacao = new TipoSolicitacao();
                    tipoSolicitacao.id = tipoSolicitacao_json.id;
                    $('#nome', form).val(tipoSolicitacao_json.nome);
                    if (tipoSolicitacao.status == 'A') {
                        $('#status', form).prop('checked', true);
                    } else {
                        $('#status', form).prop('checked', false);
                    }
                    formValidator = new TipoSolicitacaoFormValidator(form);
                } else {
                    throw 'Não é possível editar um tipo de lotação inexistente!';
                }
            } catch(e) {
                alert(e);
            }
        }

        function del(tipoSolicitacao_json) {
            try {
                 if (tipoSolicitacao_json != null) {
                     action = 'delete';
                     tipoSolicitacao = new TipoSolicitacao();
                     tipoSolicitacao.id = tipoSolicitacao_json.id;
                     tipoSolicitacao.nome = tipoSolicitacao_json.nome;
                     tipoSolicitacao.status = tipoSolicitacao_json.status;
                     form = $('#form-del');
                     formValidator = new TipoSolicitacaoFormValidator(form)
                }
            } catch(e) {
                alert(e);
            }
        }

        function list(){
            $.ajax({
                type: 'post',
                url: '/sods/admin/tiposSolicitacao/',
                dataType: 'text',
                cache: false,
                timeout: 70000,
                async: true,
                data: {
                    action: 'list'
                },
                success: function(data, status, xhr) {
                    console.log(data);
                    if (data == 'ERRO') {
                        $('#alert-del').modal('show');
                    } else {
                        $('#grid').html(data);
                    }
                },
                error: function(xhr, status, error) {
                    console.log(error);
                },
                complete: function(xhr, status) {
                    //console.log('A requisição foi completada.');
                }
            });
        }

        function save() {
            if (tipoSolicitacao != null) {
                var json = null;
                switch (action) {
                    case 'add':
                    case 'edit':
                        tipoSolicitacao.nome = $('#nome', form).val();
                        if ($('#status', form).prop('checked')) {
                            tipoSolicitacao.status = 'A';
                        } else {
                            tipoSolicitacao.status = 'I';
                        }
                        json = tipoSolicitacao.toJSON();
                        break;
                    case 'delete':
                        json = tipoSolicitacao.toJSON();
                        break;
                }
                // Requisição AJAX
                $.ajax({
                    type: 'post',
                    url: '/sods/admin/tiposSolicitacao/',
                    dataType: 'text',
                    cache: false,
                    timeout: 70000,
                    async: false,
                    data: {
                        'action': action,
                        'json': json
                    },
                    success: function(data, status, xhr) {
                        console.log(data);
                        if (data == 'ERRO') {
                            $('#alert-del').modal('show');
                        }
                        list();
                    },
                    error: function(xhr, status, error) {
                        console.log(error);
                    },
                    complete: function(xhr, status) {
                        //console.log('A requisição foi completada.');
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

            $('#form-del').submit(function(event) {
                event.preventDefault();
                save();
            });

            $('#status', '#form-edit').click(function(event) {
                if ($(this).prop('checked')) {
                    $(this).val('A');
                } else {
                    $(this).val('I');
                }
                alert($(this).val());
            });
        });
    </script>

        <!-- Container -->
        <div class="container">
            <h2>Tipos de Solicitação</h2>
            <div class="row">
                <div class="col-md-12">
                    <button class="btn btn-primary btn-sm pull-right" data-toggle="modal"
                        data-target="#modal-add" onclick="add()">
                        <b>Adicionar</b>
                    </button>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">&nbsp;</div>
            </div>
            <div id="grid" class="table-responsive">
<?php
                    if (isset($_GET['p'])) {
                        $page = (int) $_GET['p'];
                    } else {
                        $page = 1;
                    }
                    echo $controller->getGrid($page);
?>
            </div>

    <!-- Modais -->

    <!-- Adicionar Tipo de Solicitação -->
    <div id="modal-add" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-add" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button 
                        type="button" 
                        class="close" 
                        data-dismiss="modal"
                        aria-hidden="true">&times;
                    </button>
                    <h3 id="modal-add" class="modal-title">Adicionar Tipo de Solicitação</h3>
                </div>
                <div class="modal-body">
                    <form id="form-add" action="/sods/admin/tiposSolicitacao/" method="post">
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
                            <input 
                                type="checkbox" 
                                id=status 
                                name="status" 
                                value="A" 
                                checked="checked" 
                                disabled />&nbsp; Ativar
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success">Salvar</button>
                            <button type="reset" class="btn btn-default" onclick="resetForm()">Limpar</button>
                            <button type="button" class="btn btn-primary" data-dismiss="modal">Cancelar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Editar Tipo de Solicitação-->
    <div id="modal-edit" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-edit" 
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h3 id="modal-edit" class="modal-title">Editar Tipo de Solicitação</h3>
                </div>
                <div class="modal-body">
                    <form id="form-edit" action="/sods/admin/tiposSolicitacao/" method="post">
                        <div class="form-group">
                            <label for="nome">Nome</label>
                            <input 
                                type="text" 
                                id="nome" 
                                name="nome" 
                                class="form-control" 
                                maxlength="80" />
                        </div>
                        <div>
                            <label for="status">Status</label>
                        </div>
                        <div class="form-group">
                            <input 
                                type="checkbox"
                                id="status"
                                name="status" 
                                value="A" 
                                checked="checked" />&nbsp; Ativar
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success" >Salvar</button>
                            <button type="reset" class="btn btn-default" onclick="resetForm()">Limpar</button>
                            <button type="button" class="btn btn-primary" data-dismiss="modal">Cancelar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Excluir Tipo de Solicitação -->
    <div id="modal-del" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-del" 
        aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="modal-del">Exclusão de Tipo de Solicitação</h4>
                </div>
                <form id="form-del" action="/sods/admin/tiposSolicitacao/" method="post">
                    <div class="modal-body">
                        <h5>Confirma a exclusão?</h5>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-danger">Sim</button>
                        <button type="button" class="btn btn-primary" data-dismiss="modal">Não</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Alertas -->
    <div id="alert-del" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-del" aria-hidden="true">
        <div class="alert alert-danger fade in" role="alert">
            <button type="button" class="close" onclick="$('#alert-del').modal('toggle');">
                <span aria-hidden="true">&times;</span><span class="sr-only">Fechar</span>
            </button>
            <strong>ERRO:</strong> Não é possível excluir um registro com referências.
        </div>
    </div>
</div>
<!-- Container -->

<?php
    // Rodapé
    @include $_SERVER['DOCUMENT_ROOT'] . '/sods/includes/rodape.php';
?>