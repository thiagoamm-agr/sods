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
					<button class="btn btn-warning btn-sm pull-right" 
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
				<table class="table table-striped table-bordered table-condensed">
					<thead>
						<tr>
							<th>ID</th>
	            			<th>Solicitante</th>
	            			<th>Solicitação</th>
	            			<th>Status</th>
	            			<th>Tipo</th>
	            			<th>Data Abertura</th>
	            			<th>Data Alteração</th>
	            			<th>Ação</th>
	            		</tr>
            		</thead>
            		<tbody>
<?php
					$controller = new SolicitacoesController();
					
					foreach ($controller->_list() as $solicitacao) {

?>
			        	<tr>
			        		<td><?php echo $solicitacao['id'] ?></td>
			        		<td><?php echo $solicitacao['nome'] ?></td>
			        		<td width="350px"><?php echo $solicitacao['titulo'] ?></td>
							<td><?php echo $solicitacao['status'] ?></td>
							<td><?php echo $solicitacao['nome_sol'] ?></td>
							<td><?php echo date('d/m/Y H:m:s', strtotime ($solicitacao['data_abertura'])) ?></td>
							<td><?php echo @$solicitacao['data_alteracao'] ?></td>
							<td colspan="2">							
								<button class='btn btn-primary btn-sm' 
								    	data-toggle='modal' 
								    	data-target='#modalEdit'
								    	onclick="edit(<?php json_encode($solicitacao)?>)">
										<strong>Editar</strong>
								</button>
								<button class='btn btn-danger btn-sm' 
								    	data-toggle='modal' 
								    	data-target='#modalDel'
								    	onclick="del(<?php json_encode($solicitacao)?>)">
								    <strong>Excluir</strong>
							    </button>							
							</td>
				     	</tr>
<?php
					}
?>
					</tbody>
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
		  							<label for="nome">Nome</label>
		  							<input type="text" class="form-control" id="nome" name="nome">
		  						</div>
		  						
		  						<div class="form-group">
	    							<label for="desc">Descrição do Sistema</label>
	    							<textarea class="form-control" id="desc" name="desc" 
		    						    rows="6" style="width: 100%;"></textarea>
	    						
	    						</div>
	    						
			    				<div class="form-group">
	    							<label for="desc">Inf. Adicionais</label>
	    							<textarea class="form-control" id="infoAdc" name="infoAdc" 
		    						    rows="4" style="width: 100%;"></textarea>
	    						</div>
	    						
		  						
		  						<div class="form-group">
		  							<div class="row">
		  							
		  								<div class="col-sm-6">
	    									<label for="obs">Observações</label>
	    									<textarea class="form-control" id="obs" name="obs"
	    										rows="6" style="width: 100%"></textarea>
	    								</div>
		  							
		  								<div class="col-sm-4">
											<label for="tp_sol">Tipo de Solicitação</label>
		  									<select id="tp_sol" name="tp_sol" class="form-control">
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
											<label for="data">Data de Criação</label>
											<input type="text" class="form-control" name="data" 
											    id="data" readonly>
										</div>
										
										<div class="col-sm-4">
											<label for="ultAlter">Ultima Alteração</label>
											<input type="text" class="form-control" name="ultAlter" 
											    id="ult_alteracao" readonly>
										</div>
									
									
									</div>
  								</div>
								
								<div class="modal-footer">
									<button type="submit" class="btn btn-primary" onclick="solicitacao('#form-edit')">Salvar</button>
									<button type="button" class="btn btn-default" data-dismiss="modal">
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
				    							   name="solicitanteId" 
				    							   id="solicitanteId" 
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
	    							<label for="desc">Titulo da Solicitação</label>
	    							<input type="text" class="form-control" name="titulo" id="titulo"/>	    						
	    						</div>
	    						<div class="form-group">
	    							<label for="desc">Detalhamento do Sistema</label>
	    							<textarea class="form-control" id="detalhamento" name="detalhamento" 
		    						    rows="6" style="width: 100%;"></textarea>
	    						
	    						</div>
	    						<div class="form-group">
	    							<label for="desc">Inf. Adicionais</label>
	    							<textarea class="form-control" id="infoAdicionais" name="infoAdicionais" 
		    						    rows="4" style="width: 100%;"></textarea>
	    						</div>
	    						
	    						<div class="form-group">
	    							<div class="row">
	    								<div class="col-sm-6">
	    									<label for="obs">Observações</label>
	    									<textarea class="form-control" id="observacoes" name="observacoes"
	    										rows="4" style="width: 100%"></textarea>
	    								</div>
	    								<div class="col-sm-4">
	    									<div class="form-group">
	    										<label for="tipoSolicitacaoId">Tipo de Solicitação</label>
	    										<select id="tipoSolicitacaoId" 
	    												name="tipoSolicitacaoId" 
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
	    									<div class="form-group">
												<label for="data">Data de Criação</label>
												<input type="text" class="form-control" name="data" 
											    	id="data" value="<?php echo $data ?>" readonly>
											</div>
	    								</div>
	    							</div>
	    						</div>
	    						<div class="modal-footer">
									<button type="submit" 
											class="btn btn-primary" 
											onclick="save()">Salvar</button>
									<button type="reset"
											class="btn btn-primary"
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
	    						<button type="submit" class="btn btn-danger">Sim</button>
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
	        var Solicitacao = null;
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
                        formValidator = new SolicitacaoFormValidator();
						$('#solicitanteId', form).val(solicitacao_json.solicitanteId);
						$('#titulo', form).val(solicitacao_json.titulo);
						$('#detalhamento', form).val(solicitacao_json.detalhamento);
						$('#infoAdicionais', form).val(solicitacao_json.infoAdicionais);
						$('#observacoes', form).val(solicitacao_json.observacoes);
						$('#status', form).val(solicitacao_json.status);
						$('#observacoesStatus', form).val(solicitacao_json.observacoesStatus);
						$('#tipoSolicitacaoId', form).val(solicitacao_json.tipoSolicitacaoId);
                        solicitacao = new solicitacao();
                        solicitacao.id = tipoSolicitacao_json.id;
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
	                        tipoSolicitacao = new Solicitacao();
	                        solicitacao.id = solicitacao_json.id;
	                        solicitacao.solicitanteId = solicitacao_json.solicitanteId;
	                        solicitacao.titulo = solicitacao_json.titulo;
	                        solicitacao.detalhamento = solicitacao_json.detalhamento;
	                        solicitacao.infoAdicionais = solicitacao_json.infoAdicionais;
	                        solicitacao.observacoes = solicitacao_json.observacoes;
	                        solicitacao.status = solicitacao_json.status;
	                        solicitacao.tipoSolicitacaoId = solicitacao_json.tipoSolicitacaoId;	                        
	                    }
	                } catch(e) {
	                    alert(e);
	                }
			}

			function save() {
				if (solicitacao != null) {
					if (action == 'add' || action == 'edit'){
						solicitacao.solicitanteId = $('#form-' + action + ' input[name="solicitanteId"]').val();
						solicitacao.titulo = $('#form-' + action  + ' input[name="titulo"]').val();
						solicitacao.detalhamento = $('#form-' + action  + ' textarea[name="detalhamento"]').val();
						solicitacao.infoAdicionais = $('#form-' + action  + ' textarea[name="infoAdicionais"]').val();
						solicitacao.observacoes = $('#form-' + action  + ' textarea[name="observacoes"]').val();
						solicitacao.tipoSolicitacaoId = $('#form-' + action  + ' select[name="tipoSolicitacaoId"]').val();
						//Se a ação for adição, os campos de status não devem ser setados.
						if (action != 'add') {
							solicitacao.status = $('#form-' + action  + ' input:radio[name="status"]:checked').val();
							solicitacao.observacoesStatus = $('#form-' + action  + ' input[name="observacoesStatus"]').val();
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