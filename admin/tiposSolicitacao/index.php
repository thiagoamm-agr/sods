<?php
	/* CADASTRO DE TIPO DE SOLICITAÇÕES */

	@include $_SERVER['DOCUMENT_ROOT'] . '/sods/includes/topo.php';
	
	@include $_SERVER['DOCUMENT_ROOT'] . '/sods/app/models/TipoSolicitacao.php';
	
	@include $_SERVER['DOCUMENT_ROOT'] . '/sods/app/controllers/TiposSolicitacoesController.php';
?>
		<div class="container">
			<h2>Tipos de Solicitação</h2>
			<div class="row">
                <div class="col-md-12">
                	<button 
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
			<div class="table-responsive">
				<table class="table table-striped table-bordered table-condensed" 
					   class="tablesorter"
					   id="tablesorter">
					<thead>
						<tr>
							<th>ID</th>
							<th>Nome do Tipo de Solicitação</th>
							<th>Status</th>
							<th class="nonSortable">Ação</th>
						</tr>
					</thead>
					<tbody>
<?php 
                    $controller = new TiposSolicitacoesController();

                    if (isset($_GET['p'])) {
                        $page = (int) $_GET['p'];
                    } else {
                    	$page = '';
                    }

					//Obtém a lista de todos os tipos de solicitação
					foreach ($controller->getRows($page) as $tipo) {
?>
							<tr>
								<td><?php echo $tipo['id'] ?></td>
								<td><?php echo $tipo['nome'] ?></td>
								<td><?php echo $tipo['status'] ?></td>
								<td colspan="2">
									<button 
										class="edit-type btn btn-warning btn-sm" 
										data-toggle="modal" 
									    data-target="#modal-edit"
									    onclick='edit(<?php echo json_encode($tipo)?>)'>
										<strong>Editar</strong>
									</button>
									<button class="delete-type btn btn-danger btn-sm" 
											data-toggle="modal" 
									    	data-target="#modal-del" 
									    	onclick='del(<?php echo json_encode($tipo)?>)'>
								    <strong>Excluir</strong>
							    </button>						
								</td>
							</tr>
<?php 
					}
?>				
					</tbody>
					<tfoot>
                        <tr><td colspan="5"><?php echo $controller->paginator ?></td></tr>
                    </tfoot>
				</table>
			</div>
			<!-- Modais -->
			
			<!-- Adicionar Tipo de Solicitação -->
			<div class="modal fade" id="modal-add" tabindex="-1" role="dialog" 
			    aria-labelledby="modal-add" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
							    &times;
							</button>
							<h3 class="modal-title" id="modal-add">Adicionar novo tipo</h3>
						</div>
						<div class="modal-body">    						
    						 <form id="form-add" action="/sods/admin/tiposSolicitacao/" method="post">
    							<div class="form-group">
    								<label for="nome">Nome do Tipo de Solicitação</label>
    								<input type="text" class="form-control" name="nome" id="nome" maxlength="80"/>    								
    							</div>		
    							<div class="form-group">
    									<input type="checkbox" 
    										   name="status" 
    										   value="A" 
    										   checked="checked" 
    										   readonly/>&nbsp; Ativar
    							</div>				
								<div class="modal-footer">
									<button type="submit" 
											class="btn btn-success" 
											onclick="save()">Salvar</button>
									<button type="reset" 
											class="btn btn-default" 
											onclick="limpar()">Limpar</button>
		    						<button type="button" class="btn btn-primary" data-dismiss="modal">Cancelar</button>    						
								</div>
						    </form>
						</div>
    				</div>
    			</div>
    		</div>
			
			<!-- Editar Tipo de Solicitação-->
			<div class="modal fade" id="modal-edit" tabindex="-1" role="dialog" 
			    aria-labelledby="modal-edit" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
							    &times;
							</button>
							<h3 class="modal-title" id="modal-edit">Editar Tipo</h3>
						</div>
						<div class="modal-body">    						
    						 <form id="form-edit" action="/sods/admin/tiposSolicitacao/" method="post">
    							<div class="form-group">
    								<label for="nome">Digite o novo nome</label>
    								<input type="text" id="nome" name="nome" class="form-control" maxlength="80"/>    								
    							</div>
    							<div>
	    								<label for="status">Status</label>
	    							</div>
    							<div class="form-group">
    									<input type="checkbox" name="status" value="A" id="statusEdit" checked="checked"/>&nbsp; Ativar
    							</div>			
								<div class="modal-footer">
									<button type="submit" 
											class="btn btn-success" 
											onclick="save()">Salvar</button>
									<button type="reset" 
											class="btn btn-default" 
											onclick="limpar()">Limpar</button>
		    						<button type="button" class="btn btn-primary" data-dismiss="modal">Cancelar</button>    						
								</div>
							</form>
						</div>
    				</div>
    			</div>
    		</div>
    		
			<!-- Excluir Tipo de Solicitação -->
			<div class="modal fade" id="modal-del" tabindex="-1" role="dialog" 
			    aria-labelledby="modal-del" aria-hidden="true">
				<div class="modal-dialog modal-sm">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
							    &times;
							</button>
							<h4 class="modal-title" id="modal-del">Exclusão de Tipo</h4>
						</div>
						<form id="form-del" action="/sods/admin/tiposSolicitacao/" method="post">
							<div class="modal-body">
	    						<h5>Confirma exclusão do tipo de solicitação?</h5>
	    					</div>
	    					<div class="modal-footer">
	    						<button type="submit" class="btn btn-danger" onclick="save()">Sim</button>
	    						<button type="button" class="btn btn-primary" data-dismiss="modal">Não</button>	    						
	    					</div>
    					</form>
    				</div>
    			</div>
    		</div><!-- Modais -->	
			
		</div><!-- container -->
		
     	<!--  Javascript -->
        <script type="text/javascript" src="/sods/static/js/models/TipoSolicitacao.js"></script>
        
        <script type="text/javascript" src="/sods/static/js/validators/TipoSolicitacaoFormValidator.js"></script>
		
		<script type="text/javascript">		
			 var tipoSolicitacao = null;
	         var action = null;
	         var form = null;
	         var formValidator = null;

			function add() {
				action = "add";
				tipoSolicitacao = new TipoSolicitacao();
				tipoSolicitacao.id = null;
				form= $('#form-add');
				formValidator = new TipoSolicitacaoFormValidator(form);
			}

			function edit(tipoSolicitacao_json) {
                try {
                	if(tipoSolicitacao_json.status=='A'){
    					document.getElementById('statusEdit').setAttribute("checked","checked");
    				}else{
    					document.getElementById('statusEdit').removeAttribute("checked");
    				}
                    if (tipoSolicitacao_json != null) {
                        action = 'edit';
                        form = $('#form-edit');
                        formValidator = new TipoSolicitacaoFormValidator(form);
                        $('#nome', form).val(tipoSolicitacao_json.nome);                        
                        tipoSolicitacao = new TipoSolicitacao();
                        tipoSolicitacao.id = tipoSolicitacao_json.id;
                    } else {
                        throw 'Não é possível editar uma alteração que não existe.';
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
	                    }
	                } catch(e) {
	                    alert(e);
	                }
			}

			function save() {
				if (tipoSolicitacao != null) {
					if (action == 'add' || action == 'edit') {
						tipoSolicitacao.nome = $('#form-' + action  + ' input[name="nome"]').val();
						tipoSolicitacao.status = $('#form-' + action  + ' input:checkbox[name="status"]:checked').val();
						if (tipoSolicitacao.status != "A") {
							tipoSolicitacao.status = "I";
						}
					}
					
					// Requisição AJAX
					$.ajax({
						type: 'post',
						url: '/sods/admin/tiposSolicitacao/',
						dataType: 'json',
						contentType: 'application/x-www-form-urlencoded',
						cache: false,
						timeout: 70000,
						async: false,
						data: {
							'action': action,
							'tipoSolicitacao': tipoSolicitacao.toJSON()
						}
						
					});			 
				}
			}

			function limpar() {
                if (validator != null) {
                    validator.resetForm();
                }
            }

		</script>
		
<?php
	//Indentificando ações e parâmetros do post
	if (isset($_POST['action']) && isset($_POST['tipoSolicitacao'])) {
		//Recuperando dados do post
		$action = $_POST['action'];
		$json = $_POST['tipoSolicitacao'];
		
		$tipoSolicitacao = new TipoSolicitacao();
		$tipoSolicitacao->loadJSON($json);
		
		
		switch ($action){
			case 'add':
				$controller->add($tipoSolicitacao);
				break;
			case 'edit':
				$controller->edit($tipoSolicitacao);
				break;
			case 'delete':
				$controller->delete($tipoSolicitacao);
				break;
		}
	}
	@include $_SERVER['DOCUMENT_ROOT'] . '/sods/includes/rodape.php'; 
?>