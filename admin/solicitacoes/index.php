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
					    	data-target="#modalAdd"
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
	            			<th>Solicitante</th>
	            			<th>Solicitação</th>
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
					
					foreach ($controller->getRows($page) as $solicitacao) {

?>
			        	<tr>
			        		<td><?php echo $solicitacao['id'] ?></td>
			        		<td><?php echo $solicitacao['nome'] ?></td>
			        		<td width="350px"><?php echo $solicitacao['titulo'] ?></td>
							<td><?php echo $solicitacao['status'] ?></td>
							<td><?php echo $solicitacao['tipo_solicitacao'] ?></td>
							<td><?php echo date('d/m/Y H:m:s', strtotime ($solicitacao['data_abertura'])) ?></td>
							<td><?php echo @$solicitacao['data_alteracao'] ?></td>
							<td colspan="2">							
								<button class='btn btn-warning btn-sm' 
								    	data-toggle='modal' 
								    	data-target='#modalEdit'
								    	onclick='edit(<?php echo json_encode($solicitacao)?>)'>
										<strong>Editar</strong>
								</button>
								<button class='btn btn-danger btn-sm' 
								    	data-toggle='modal' 
								    	data-target='#modalDel'
								    	onclick='del(<?php echo json_encode($solicitacao)?>)'>
								    <strong>Excluir</strong>
							    </button>							
							</td>
				     	</tr>
<?php
					}
?>
					</tbody>
					<tfoot>
						<tr><td colspan="8"><?php echo $controller->paginator ?></td></tr>
					</tfoot>
	    		</table>
    		</div>
    		
    		<!--  Modais -->
    		
    		<!-- Editar Solicitação -->
			<div class="modal fade" id="modalEdit" tabindex="-1" role="dialog" 
			    aria-labelledby="modalEdit" aria-hidden="true">
				<div class="modal-dialog modal-lg">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
							    &times;
							</button>
							<h3 class="modal-title" id="modalEdit">Editar Solicitação</h3>
						</div>
						<div class="modal-body">
							<form id="form-edit" role="form" action="#" method="post">								
		  						<div class="form-group">
		  							<label for="titulo">Título</label>
		  							<input type="text" class="form-control" id="titulo" name="titulo">
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
		  							
		  								<div class="col-sm-4">
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
									
										<div class="col-sm-4">
											<label for="data_abertura">Data de Criação</label>
											<input type="text" class="form-control" name="data_abertura" 
											    id="data_abertura" readonly>
										</div>
										
										<div class="col-sm-4">
											<label for="data_alteracao">Ultima Alteração</label>
											<input type="text" class="form-control" name="data_alteracao" 
												id="data_alteracao" readonly/>
										</div>
									</div>
									
									<div class="row">
										
										<div class="col-sm-6">
											<label for="status">Status</label>
											<select id="status" name="status" class="form-control">
												<option  value="EM ANÁLISE">Em análise</option>
												<option  value="DEFERIDA">Deferida</option>
												<option  value="INDEFERIDA">Indeferida</option>
												<option  value="ATENDIDA">Atendida</option>
												<option  value="CANCELADA">Cancelada</option>
											</select>
										</div>
										
										<div class="col-sm-6">
											<label for="observacoes_status">Obs. Status</label>
											<input type="text" class="form-control" name="observacoes_status"
												id="observacoes_status"/>
										</div>
									
									</div>
									
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
    		<div class="modal fade" id="modalAdd" tabindex="-1" role="dialog" 
			    aria-labelledby="modalAdd" aria-hidden="true">
			    <div class="modal-dialog modal-lg">
			    	<div class="modal-content">
			    		<div class="modal-header">
			    			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
			    				&times;
			    			</button>
			    			<h4 class="modal-title" id="modalAdd">Adicionar Solicitação</h4>
			    		</div>
			    		<div class="modal-body">
			    			<form id="form-add" action="#" method="post">
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
		    									   value= "<?php echo $_SESSION['usuario']['nome']; ?>"/>
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
	    							<input type="text" class="form-control" name="titulo" id="titulo"/>	    						
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
			<div class="modal fade .bs-example-modal-sm" id="modalDel" tabindex="-1" role="dialog" 
			    aria-labelledby="modalDel" aria-hidden="true">
				<div class="modal-dialog modal-sm">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
							    &times;
							</button>
							<h4 class="modal-title" id="modalDel">Exclusão de Solicitação</h4>
						</div>
						<div class="modal-body">
    						<h5>Confirma exclusão de solicitação?</h5>
    					</div>
						<form action="#">
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
						$('#data_abertura', form).val(solicitacao_json.data_abertura);
						$('#data_alteracao', form).val(solicitacao_json.data_alteracao);
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
						solicitacao.solicitante_id = $('#form-' + action + ' input[name="solicitante_id"]').val();
						solicitacao.titulo = $('#form-' + action  + ' input[name="titulo"]').val();
						solicitacao.detalhamento = $('#form-' + action  + ' textarea[name="detalhamento"]').val();
						solicitacao.info_adicionais = $('#form-' + action  + ' textarea[name="info_adicionais"]').val();
						solicitacao.observacoes = $('#form-' + action  + ' textarea[name="observacoes"]').val();
						solicitacao.tipo_solicitacao_id = 
								$('#form-' + action  + ' select[name="tipo_solicitacao_id"]').val();
						//Se a ação for adição, os campos de status não devem ser setados.
						if (action != 'add') {
							solicitacao.status = $('#form-' + action + ' select[name="status"]').val();
							solicitacao.observacoes_status = 
									$('#form-' + action  + ' input[name="observacoes_status"]').val();
						}					
					}
					// Requisição AJAX
					$.ajax({
						type: 'POST',
						url: '',
						data: 'action=' + action + '&' + 'solicitacao=' + solicitacao.toJSON(),
						success: function(data){}
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
	if (isset($_POST['action']) && isset($_POST['solicitacao'])) {
		//Recuperando dados do post
		$action = $_POST['action'];
		$json = $_POST['solicitacao'];
		
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