<?php
    /* CADASTRO DE SOLICITAÇÕES */
    
    @include $_SERVER['DOCUMENT_ROOT'] . '/sods/includes/topo.php';
    
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
?>
        <div class="container">
            <h2>Solicitações</h2>
            <div class="row">
                <div class="col-md-12">
                    <button class="btn btn-primary btn-sm pull-right" 
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
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-condensed tablesorter"
                       id="tablesorter">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Solicitante</th>
                            <th>Titulo</th>
                            <th>Status</th>
                            <th>Tipo</th>
                            <th>Data Abertura</th>
                            <th>Data Alteração</th>
                            <th class="nonSortable">Ação</th>
                        </tr>
                    </thead>
                    <tbody>
<?php
                    $controller = new SolicitacoesController();
                    
                    if (isset($_GET['p'])) {
                        $page = (int) $_GET['p'];
                    } else {
                        $page = '';
                    }
                    
                    if (!(empty($controller->getPage()))) {
                    
                    	foreach ($controller->getPage($page) as $solicitacao) {

?>
	                        <tr>
	                            <td><?php echo $solicitacao['id'] ?></td>
	                            <td><?php echo $solicitacao['nome'] ?></td>
	                            <td><?php echo $solicitacao['titulo'] ?></td>
	                            <td><?php echo $solicitacao['status'] ?></td>
	                            <td><?php echo $solicitacao['tipo_solicitacao'] ?></td>
	                            <td><?php echo date('d/m/Y H:i:s', strtotime ($solicitacao['data_abertura'])) ?></td>
	                            <td><?php if ($solicitacao['data_alteracao'] != null) {
	                            	 		echo date('d/m/Y H:i:s', strtotime ($solicitacao['data_alteracao']));
	                            	} ?></td>
	                            <td colspan="2">
	                                <button class='btn btn-warning btn-sm' 
	                                        data-toggle='modal' 
	                                        data-target='#modal-edit'
	                                        onclick='edit(<?php echo json_encode($solicitacao)?>)'>
	                                        <strong>Editar</strong>
	                                </button>
	                                <button class='btn btn-danger btn-sm' 
	                                        data-toggle='modal' 
	                                        data-target='#modal-del'
	                                        onclick='del(<?php echo json_encode($solicitacao)?>)'>
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
	                        <tr><td colspan="8"><?php echo $controller->paginator ?></td></tr>
	                    </tfoot>
<?php
						}

?>
	                </table>
<?php 
                    } else {
?>
                    	</tbody>
                    </table>
                    <div class='alert alert-danger' role='alert'>
                    	<center><b>Não há registros de solicitações.</b></center>
            		</div>                    	
<?php
                    }                
?>
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
                            <form id="form-edit" action="/sods/admin/solicitacoes" role="form" method="post">
                                  <div class="form-group">
                                      <label for="titulo">Título</label>
                                      <input type="text" class="form-control" id="titulo" name="titulo" maxlength="100">
                                  </div>
                                  <div class="form-group">
                                    <label for="detalhamento">Descrição do Sistema</label>
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
<?php 
                                                $tiposSolicitacoesController = new TiposSolicitacoesController();
                                                $tiposSolicitacoes = $tiposSolicitacoesController->_list();

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
                                            	<option value="CRIADA">Criada</option>
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
                                    <button type="submit" 
                                            class="btn btn-primary" 
                                            onclick="save()">Salvar</button>
                                    <button type="button"
                                            class="btn btn-default"
                                            onclick="reset()">Limpar</button>
                                    <button type="button" 
                                            class="btn btn-default" 
                                            data-dismiss="modal">
                                        Fechar
                                    </button>
                                </div>
                                
                            </form>
                        </div>
                    </div>
                </div>
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
                            <form id="form-add" action="/sods/admin/solicitacoes/" role="form" method="post">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <input type="hidden" 
                                                   class="form-control"
                                                   name="solicitante_id" 
                                                   id="solicitante_id" 
                                                   value="<?php echo $_SESSION['usuario']['id']?>"/>
                                            <label for="nome">Nome do Solicitante</label>
                                            <input type="text" 
                                                   class="form-control" 
                                                   name="nome" 
                                                   id="nome"
                                                   value= "<?php echo $_SESSION['usuario']['nome']; ?>"
                                                   readonly/>
                                        </div>
                                        <div class="col-sm-6">
                                            <label for="lotacao">Lotação</label>
                                            <select id="lotacao" name="lotacao" class="form-control">
<?php 
                                        $lotacoesController = new LotacoesController();
                                        
                                        $lotacoes = $lotacoesController->_list();
                                        
                                        foreach ($lotacoes as $lotacao){
?>
                                            <option value="<?php echo $lotacao['id'] ?>">
                                                <?php echo trim($lotacao['nome']) ?>
                                            </option>
<?php
                                        } 
?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="titulo">Titulo da Solicitação</label>
                                    <input type="text" class="form-control" name="titulo" id="titulo" maxlength="100"/>
                                </div>
                                <div class="form-group">
                                    <label for="detalhamento">Detalhamento do Sistema</label>
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
                                            class="btn btn-primary" 
                                            onclick="save()">Salvar</button>
                                    <button type="reset"
                                            class="btn btn-default"
                                            onclick="reset()">Limpar</button>
                                    <button type="button" 
                                            class="btn btn-default" 
                                            data-dismiss="modal">Fechar</button>
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
                        <form id="modal-del" action="/sods/admin/solicitacoes/" role="form" method="post">
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-danger" onclick="save()">Sim</button>
                                <button type="button" class="btn btn-primary" data-dismiss="modal">Não</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div> <!--  modais -->
        </div> <!-- container -->
        
         <script type="text/javascript" src="/sods/static/js/models/Solicitacao.js"></script>
        
        <script type="text/javascript" src="/sods/static/js/validators/SolicitacaoFormValidator.js"></script>
        
        <script type="text/javascript">
            var solicitacao = null;
            var action = null;
            var form = null;
            var formValidator = null;
    
            function add() {
                action = "add";
                solicitacao = new Solicitacao();
                solicitacao.id = null;
                form= $('#form-add');
                formValidator = new SolicitacaoFormValidator(form);
            }

            function edit(solicitacao_json) {
                try {
                    if (solicitacao_json != null) {
                        action = 'edit';
                        form = $('#form-edit');
                        formValidator = new SolicitacaoFormValidator(form);
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
                    } else {
                        throw 'Não é possível editar uma alteração que não existe.';
                    }
                } catch(e) {
                    alert(e);
                }
            }

            function del(solicitacao_json) {
                 try {
                        if (solicitacao_json != null) {
                            action = 'delete';
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
                        }
                    } catch(e) {
                        alert(e);
                    }
            }

            function save() {
                if (solicitacao != null) {
                    if (action == 'add' || action == 'edit'){
                        solicitacao.solicitante_id = 
                            $('#form-' + action + ' input[name="solicitante_id"]').val();
                        solicitacao.titulo = 
                            $('#form-' + action  + ' input[name="titulo"]').val();
                        solicitacao.detalhamento = 
                            $('#form-' + action  + ' textarea[name="detalhamento"]').val();
                        solicitacao.info_adicionais = 
                            $('#form-' + action  + ' textarea[name="info_adicionais"]').val();
                        solicitacao.observacoes = 
                            $('#form-' + action  + ' textarea[name="observacoes"]').val();
                        solicitacao.tipo_solicitacao_id = 
                                $('#form-' + action  + ' select[name="tipo_solicitacao_id"]').val();
                        //Se a ação for adição, os campos de status não devem ser setados.
                        if (action != 'add') {
                            solicitacao.status = 
                                $('#form-' + action + ' select[name="status"]').val();
                            solicitacao.observacoes_status = 
                                $('#form-' + action  + ' input[name="observacoes_status"]').val();
                        }                    
                    }
                    // Requisição AJAX
                    $.ajax({
                        type: 'post',
                        url: '/sods/admin/solicitacoes/',
                        dataType: 'json',
                        contentType: 'application/x-www-form-urlencoded',
                        cache: false,
                        timeout: 7000,
                        async: false,
                        data: {
                            'action': action,
                            'json': solicitacao.toJSON()
                        }
                    }); 
                }
            }

            function limpar() {
                if (validator != null) {
                    validator.resetForm();
                }
            }

            function formataData(data_antiga) {
                if(data_antiga != null) {
                    var data = data_antiga.split("-");
                    var jsDate = new Date(data[0], data[1]-1, data[2].substr(0,2), data[2].substr(3,2), 
                                          data[2].substr(6,2), data[2].substr(9,2));
                    var dia = jsDate.getUTCDate();
                    var mes = jsDate.getUTCMonth()+1;
                    var ano = jsDate.getUTCFullYear();
                    var hora = jsDate.getHours();
                    var min = jsDate.getMinutes();
                    var seg = jsDate.getSeconds();
                    return (dia + "/" + mes + "/" + ano + " " + hora + ":" + min + ":" + seg);
                } else {
                    return " ";
                }
            }



        </script>
<?php
    //Indentificando ações e parâmetros do post
    if (isset($_POST['action']) && isset($_POST['json'])) {
        //Recuperando dados do post
        $action = $_POST['action'];
        $json = $_POST['json'];
        
        $solicitacao = new Solicitacao();
        $solicitacao->loadJSON($json);

        switch ($action){
            case 'add':
                $controller->add($solicitacao);
                break;
            case 'edit':
                $controller->edit($solicitacao);
                break;
            case 'delete':
                $controller->delete($solicitacao);
                break;
        }
    }
    @include $_SERVER['DOCUMENT_ROOT'] . '/sods/includes/rodape.php'; 
?>