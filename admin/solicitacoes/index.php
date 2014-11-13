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
    $lotacoesController = new LotacoesController();
    $lotacoes = $lotacoesController->_list();
    $tiposSolicitacoesController = new TiposSolicitacoesController();
    $tiposSolicitacoes = $tiposSolicitacoesController->activeElements();

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
                $page = isset($_POST['p']) ? $_POST['p'] : 1;
                echo $controller->getGrid($page);
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
        <script type="text/javascript">
            var solicitacao = null;
            var action = null;
            var form = null;
            var formValidator = null;
            var current_page = null;

            function clean() {
                if (formValidator != null) {
                    formValidator.reset();
                }
            }
    
            function add() {
                action = "add";
                solicitacao = new Solicitacao();
                form = $('#form-add');
                formValidator = new SolicitacaoFormValidator(form);
                current_page = 1;
            }

            function edit(solicitacao_json, page) {
                current_page = page;
                try {
                    if (solicitacao_json != null) {
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
                        $('#data_abertura', form).val(formataData(solicitacao_json.data_abertura));
                        $('#data_alteracao', form).val(formataData(solicitacao_json.data_alteracao));
                        solicitacao = new Solicitacao();
                        solicitacao.id = solicitacao_json.id;
                        formValidator = new SolicitacaoFormValidator(form);
                    } else {
                        throw 'Não é possível editar uma alteração que não existe.';
                    }
                } catch(e) {
                    alert(e);
                }
            }

            function del(solicitacao_json, page, totalRecords) {
                 totalRecords = totalRecords - 1;
                 var manipulatedPage = Math.ceil(totalRecords / 10);
                 if(manipulatedPage < page) {
                	 current_page = manipulatedPage;
                 }else{
                	 current_page = page;
                 }
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
                            solicitacao.data_abertura = solicitacao_json.data_abertura;
                            solicitacao.data_alteracao = solicitacao_json.data_alteracao;
                            formValidator = new SolicitacaoFormValidator(form);
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
                    url: '/sods/admin/solicitacoes/',
                    dataType: 'text',
                    cache: false,
                    timeout: 70000,
                    async: true,
                    data: {
                        action: 'list',
                        p: page
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
                                if (data == 'ERRO') {
                                    $('#alert-del').modal('show');
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

            function formataData(data_antiga) {
                if(data_antiga != null) {
                    var data = data_antiga.split("-");
                    var jsDate = new Date(
                        data[0], 
                        data[1] - 1, 
                        data[2].substr(0,2),
                        data[2].substr(3,2),
                        data[2].substr(6,2),
                        data[2].substr(9,2)
                    );
                    var dia = jsDate.getUTCDate();
                    var mes = jsDate.getUTCMonth() + 1;
                    var ano = jsDate.getUTCFullYear();
                    var hora = jsDate.getHours();
                    var min = jsDate.getMinutes();
                    var seg = jsDate.getSeconds();
                    return (dia + "/" + mes + "/" + ano + " " + hora + ":" + min + ":" + seg);
                } else {
                    return " ";
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

                createAJAXPagination();
            });
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
/*
                    Lista os registros sem usar AJAX.
                    if (isset($_GET['p'])) {
                        $page = (int) $_GET['p'];
                    } else {
                        $page = 1;
                    }
*/
                    echo $controller->getGrid(1);
?>
            </div>

            <!-- Adicionar Solicitação -->
            <div class="modal fade" id="modal-add" tabindex="-1" role="dialog" 
                aria-labelledby="modal-add" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                                &times;
                            </button>
                            <h4 class="modal-title" id="modal-add">Adicionar Solicitação</h4>
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
                                            <label for="nome">Nome do Solicitante</label>
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
                                    <label for="titulo">Titulo da Solicitação</label>
                                    <input type="text" class="form-control" name="titulo" id="titulo" maxlength="100" />
                                </div>
                                <div class="form-group">
                                    <label for="detalhamento">Descrição da Solicitação</label>
                                    <textarea class="form-control" id="detalhamento" name="detalhamento" 
                                        rows="6" style="width: 100%;"></textarea>
                                
                                </div>
                                <div class="form-group">
                                    <label for="info_adicionais">Inf. Adicionais</label>
                                    <textarea class="form-control" id="info_adicionais" name="info_adicionais" 
                                        rows="4" style="width: 100%;"></textarea>
                                </div>
                                
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label for="observacoes">Observações</label>
                                            <textarea class="form-control" id="observacoes" name="observacoes"
                                                rows="4" style="width: 100%"></textarea>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="tipo_solicitacao_id">Tipo de Solicitação</label>
                                                <select id="tipo_solicitacao_id" 
                                                        name="tipo_solicitacao_id" 
                                                        class="form-control">
                                                        <option value="">SELECIONE UM TIPO DE SOLICITAÇÃO</option>
<?php 
                                                        foreach ($tiposSolicitacoes as $tipos){
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
                                    <button type="submit" 
                                            class="btn btn-success" >Salvar
                                            <span class="glyphicon glyphicon-floppy-save"></span>
                                    </button>
                                    <button type="reset"
                                            class="btn btn-default" onclick="clean();">Limpar
                                            </button>
                                    <button type="button" 
                                            class="btn btn-default"
                                            data-dismiss="modal">Fechar</button>
                                </div>
                            </form>    
                        </div>
                    </div>
                </div>
            </div>
            
             <!--  Modais -->
            
            <!-- Editar Solicitação -->
            <div class="modal fade" id="modal-edit" tabindex="-1" role="dialog" 
                aria-labelledby="modal-edit" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                                &times;
                            </button>
                            <h3 class="modal-title" id="modal-edit">Editar Solicitação</h3>
                        </div>
                        <div class="modal-body">
                            <form id="form-edit" action="" role="form" method="post">
                                  <div class="form-group">
                                      <label for="titulo">Título</label>
                                      <input type="text" class="form-control" id="titulo" name="titulo" maxlength="100">
                                  </div>
                                  <div class="form-group">
                                    <label for="detalhamento">Descrição da Solicitação</label>
                                    <textarea class="form-control" id="detalhamento" name="detalhamento" 
                                        rows="6" style="width: 100%;"></textarea>
                                
                                </div>
                                <div class="form-group">
                                    <label for="info_adicionais">Inf. Adicionais</label>
                                    <textarea class="form-control" id="info_adicionais" name="info_adicionais"
                                        rows="2" style="width: 100%;"></textarea>
                                </div>
                                  <div class="form-group">
                                      <div class="row">
                                      
                                          <div class="col-sm-6">
                                            <label for="observacoes">Observações</label>
                                            <textarea class="form-control" id="observacoes" name="observacoes"
                                                rows="6" style="width: 100%"></textarea>
                                        </div>
                                      
                                          <div class="col-sm-6">
                                            <label for="tipo_solicitacao_id">Tipo de Solicitação</label>
                                              <select id="tipo_solicitacao_id" name="tipo_solicitacao_id" 
                                                      class="form-control">
                                                <option value="">SELECIONE UM TIPO DE SOLICITAÇÃO</option>
<?php 
                                                foreach ($tiposSolicitacoes as $tipos){
?>
                                                <option value="<?php echo $tipos['id'] ?>">
                                                               <?php echo $tipos['nome']?>
                                                </option>
<?php
                                                }
?>
                                            </select>
                                        </div>
                                    
                                        <div class="col-sm-6">
                                            <label for="data_abertura">Data de Criação</label>
                                            <input type="text" class="form-control" name="data_abertura" 
                                                id="data_abertura" readonly>
                                        </div>
                                        
                                        <div class="col-sm-6">
                                            <label for="data_alteracao">Ultima Alteração</label>
                                            <input type="text" class="form-control" name="data_alteracao" 
                                                id="data_alteracao" readonly/>
                                        </div>
                                    </div>
<?php 
                                    if ($_SESSION['usuario']['tipo_usuario'] == "A") {
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
                                            <label for="observacoes_status">Obs. Status</label>
                                            <input type="text" class="form-control" name="observacoes_status"
                                                id="observacoes_status"/>
                                        </div>
                                    
                                    </div>
<?php 
                                    }
?>
                                  </div>
                                <input type="hidden" id="solicitante_id" name="solicitante_id">
                                <div class="modal-footer">
                                    <button 
                                        type="submit" 
                                        class="btn btn-primary">
                                        Salvar
                                    </button>
                                    <button 
                                        type="reset" 
                                        class="btn btn-default" 
                                        onclick="clean();">Limpar
                                    </button>
                                    <button type="button" 
                                            class="btn btn-default"
                                            data-dismiss="modal">Fechar
                                    </button>
                                </div>
                                
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Excluir solicitação -->
            <div class="modal fade .bs-example-modal-sm" id="modal-del" tabindex="-1" role="dialog" 
                aria-labelledby="modal-del" aria-hidden="true">
                <div class="modal-dialog modal-sm">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                                &times;
                            </button>
                            <h4 class="modal-title" id="modal-del">Exclusão de Solicitação</h4>
                        </div>
                        <div class="modal-body">
                            <h5>Confirma exclusão de solicitação?</h5>
                        </div>
                        <form id="form-del" action="" role="form" method="post">
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-danger">Sim</button>
                                <button type="button" class="btn btn-primary" data-dismiss="modal">Não</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div> <!--  modais -->
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
         <!-- Alertas -->
        <div class="modal fade" id="modal-success" tabindex="-1" role="dialog" aria-labelledby="modal-del" 
            aria-hidden="true">
            <div class="alert alert-success fade in" role="alert">
                <button type="button" class="close" onclick="$('#modal-success').modal('toggle');">
                    <span aria-hidden="true">&times;</span><span class="sr-only">Fechar</span>
                </button>
                <strong>SUCESSO:</strong>
                <span id="alert-msg">Dados atualizados</span>
           </div>
        </div><!-- Alertas -->
  </div> <!-- container -->
<?php
    @include $_SERVER['DOCUMENT_ROOT'] . '/sods/includes/rodape.php'; 
?>