<?php
    /* Cadastro de Lotações */

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
                $controller->add($lotacao);
                exit(); // finaliza a stream (a resposta termina aqui).
                break;
            case 'edit':
                $controller->edit($lotacao);
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
        <script type="text/javascript" src="/sods/static/js/models/Lotacao.js"></script>
        <script type="text/javascript" src="/sods/static/js/validators/LotacaoFormValidator.js"></script>

        <script type="text/javascript">
            var lotacao = null;
            var action = null;
            var form = null;
            var formValidator = null;
            var resposta = null;

            function add() {
                action = 'add';
                lotacao = new Lotacao();
                lotacao.id = null;
                form = $('#form-add');
                formValidator = new LotacaoFormValidator(form);
            }

            function edit(lotacao_json) {
                try {
                    if (lotacao_json != null) {
                        action = 'edit';
                        modal = 'modal-edit';
                        form = $('#form-' + action);
                        formValidator = new LotacaoFormValidator(form);
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
                    } else {
                        throw 'Não é possível editar uma alteração que não existe.';
                    }
                } catch(e) {
                    alert(e);
                }
            }

            function del(lotacao_json) {
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
                    }
                } catch(e) {
                    alert(e);
                }
            }

            function list() {
                $.ajax({
                    type: 'post',
                    url: '/sods/admin/lotacoes/',
                    dataType: 'text',
                    cache: false,
                    timeout: 70000,
                    async: true,
                    data: {
                        action: 'list'
                    },
                    success: function(data, status, xhr) {
                        if (data == 'ERRO') {
                            $('#alert-del').modal('show');
                        } else {
                            $('#grid').html(data);
                            //console.log(data);
                        }
                    },
                    error: function(xhr, status, error) {
                        // console.log(error);
                    },
                    complete: function(xhr, status) {
                        //console.log('A requisição foi completada.');
                    }
                });
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
                            if (data == 'ERRO') {
                                $('#alert-del').modal('show');
                            }
                            //console.log(data);
                            // Recarrega a grid.
                            list();
                        },
                        error: function(xhr, status, error) {
                            // console.log(error);
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

                $('#form-edit').submit(function(event) {
                    event.preventDefault();
                    save();
                });

                $('#form-del').submit(function(event) {
                    event.preventDefault();
                    save();
                });
            });
        </script>
        <div class="container">
            <h2>Lotações</h2>
            <div class="row">
                <div class="col-md-12">
                    <button
                        id="btn-add"
                        class="btn btn-primary btn-sm pull-right" 
                        data-toggle="modal" 
                        data-target="#modal-add"
                        onclick="add()">
                        <b>Adicionar</b>
                    </button>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">&nbsp;</div>
            </div>
            <div id="grid" class="table-responsive">
                <table class="table table-striped table-bordered table-condensed tablesorter"
                       id="tablesorter">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nome</th>
                            <th>Sigla</th>
                            <th>Gerência</th>
                            <th class="nonSortable">Ação</th>
                        </tr>
                    </thead>
                    <tbody>
<?php
                    if (isset($_GET['p'])) {
                        $page = (int) $_GET['p'];
                    } else {
                        $page = "";
                    }

                    foreach ($controller->getRows($page) as $lotacao) {
?>
                        <tr>
                            <td><?php echo $lotacao['id'] ?></td>
                            <td><?php echo $lotacao['nome'] ?></td>
                            <td><?php echo $lotacao['sigla'] ?></td>
                            <td><?php echo isset($lotacao['gerencia']) ? 
                                $lotacao['gerencia']->sigla : '' ?></td>
                            <td colspan="2">
                                <button 
                                    class="btn btn-warning btn-sm" 
                                    data-toggle="modal" 
                                    data-target="#modal-edit"
                                    onclick='edit(<?php echo json_encode($lotacao) ?>)'>
                                    <strong>Editar</strong>
                                </button>
                                <button 
                                    class="delete-type btn btn-danger btn-sm" 
                                    data-toggle="modal" 
                                    data-target="#modal-del"
                                    onclick='del(<?php echo json_encode($lotacao) ?>)'>
                                    <strong>Excluir</strong>
                                </button>
                            </td>
                        </tr>
<?php
                    }
?>
                    </tbody>
<?php
                if ($controller->count() > 10) { 
?>
                    <tfoot>
                        <tr><td colspan="5"><?php echo $controller->paginator ?></td></tr>
                    </tfoot>
<?php
                }
?>
                </table>
            </div>

            <!--  Modais -->

            <!-- Modal de Adição de Lotação -->
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
                            <form id="form-add" role="form" action="/sods/admin/lotacoes" method="post">
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
                                    <select id="gerencia" name="gerencia" class="form-control">
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
                                    <button type="submit" class="btn btn-success">
                                        Salvar
                                    </button>
                                    <button type="reset" class="btn btn-default" onclick="resetForm()">
                                        Limpar
                                    </button>
                                    <button type="button" class="btn btn-default" data-dismiss="modal">
                                        Fechar
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Editar lotação -->
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
                                    <select id="gerencia" name="gerencia" class="form-control">
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
                                    </button>
                                    <button 
                                        type="reset" 
                                        class="btn btn-default" 
                                        onclick="resetForm()">Limpar
                                    </button>
                                    <button 
                                        type="button" 
                                        class="btn btn-default" 
                                        data-dismiss="modal">Fechar
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Excluir Lotação -->
            <div class="modal fade" id="modal-del" tabindex="-1" role="dialog" 
                aria-labelledby="modal-del" aria-hidden="true">
                <div class="modal-dialog modal-sm">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button 
                                type="button" 
                                class="close" 
                                data-dismiss="modal" 
                                aria-hidden="true">
                                &times;
                            </button>
                            <h4 class="modal-title">Exclusão de Lotação</h4>
                        </div>
                        <form id="form-del" action="" method="post">
                            <div class="modal-body">
                                <h5>Confirma exclusão da lotação?</h5>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-danger">Sim</button>
                                <button type="button" class="btn btn-primary" data-dismiss="modal">Não</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div><!-- Modais -->
             
            <!-- Alerta -->
            <div class="modal fade" id="alert-del" tabindex="-1" role="dialog" aria-labelledby="modal-del" 
                aria-hidden="true">
                <div class="alert alert-danger fade in" role="alert">
                    <button type="button" class="close" onclick="$('#alert-del').modal('toggle');">
                        <span aria-hidden="true">&times;</span><span class="sr-only">Fechar</span>
                    </button>
                    <strong>ERRO:</strong> Não é possível excluir um registro com referências.
               </div>
            </div>
        </div><!-- Container -->
<?php
    // Rodapé
    @include $_SERVER['DOCUMENT_ROOT'] . '/sods/includes/rodape.php';
?>