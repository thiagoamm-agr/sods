<?php
	/* CADASTRO DE SOLICITAÇÕES */
	
	@include $_SERVER['DOCUMENT_ROOT'] . '/sods/includes/topo.php';
	
	@include $_SERVER['DOCUMENT_ROOT'] . '/sods/app/controllers/SolicitacoesController.php';
?>
		<div class="container">
			<h2>Solicitações</h2>
			<div class="row">
                <div class="col-md-12">
					<button class="btn btn-warning btn-sm pull-right" 
					    data-toggle="modal" data-target="#modalAdd">
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
					
					// Obtém a lista de todas as solicitações.					
					if($_SESSION['usuario']['tipo_usuario'] == 'A') {
						$solicitacoes = $controller->allAdmin();
					} else {
						$solicitacoes = $controller->allUser($_SESSION['usuario']['login']);
					}
					
					
					foreach ($controller->allAdmin() as $solicitacao) {

?>
			        	<tr>
			        		<td>
			        			<?php echo $solicitacao['id'] ?>
		        			</td>
			        		<td>
			        			<?php echo $solicitacao['nome'] ?>
		        			</td>
			        		<td width="350px">
			        			<?php echo $solicitacao['titulo'] ?>
		        			</td>
							<td>
								<?php echo $solicitacao['status'] ?>
							</td>
							<td>
								<?php echo $solicitacao['nome_sol'] ?>
							</td>
							<td>
								<?php 
									echo date('d/m/Y H:m:s', strtotime ($solicitacao['data_abertura'])) 
								?>
							</td>
							<td>
								<?php echo @$solicitacao['data_alteracao'] ?>
							</td>
							<td colspan="2">							
								<button class='btn btn-primary btn-sm' 
								    data-toggle='modal' data-target='#modalEdit'>
									<strong>Editar</strong>
								</button>
								<button class='btn btn-danger btn-sm' 
								    data-toggle='modal' data-target='#modalDel'>
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
							<form role="form">								
		  						<div class="form-group">
		    						<label for="descricao" class="col-sm-2 control-label">Descrição</label>
		    						<textarea class="form-control" id="desc" name="desc" 
		    						    rows="6" style="width: 80%;"></textarea>
		  						</div>
		  						<div class="form-group">
		    						<label for="info_adc" class="col-sm-2 control-label">Inf. Adicionais</label>
		    						<textarea class="form-control" id="info_adc" name="info_adc" 
		    						    rows="4" style="width: 80%;"></textarea>
		  						</div>
		  						<div class="form-group">
		    						<label for="obs" class="col-sm-2 control-label">Observações</label>
		    						<textarea class="form-control" id="obs" name="obs" 
		    						    rows="2" style="width: 80%;"></textarea>
		  						</div>
		  						<div class="form-group">
		  							<div class="row">
		  								<div class="col-sm-4">
											<label for="tp_sol">Tipo de Solicitação</label>
		  									<select id="tp_sol" name="tp_sol" class="form-control">
<?php 
												$sql = "select * from tipo_solicitacao";
												$secoes = mysql_query($sql);
																		
		  										while ($secao = mysql_fetch_assoc($secoes)) {
?>
												<option value="<?php echo $secao['id'] ?>">
															   <?php echo $secao['nome']?>
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
											<label for="ult_alteracao">Ultima Alteração</label>
											<input type="text" class="form-control" name="ult_alteracao" 
											    id="ult_alteracao" readonly>
										</div>									
									</div>
  								</div>
  								<div class="modal-footer">
									<button type="button" class="btn btn-primary">Salvar</button>
									<button type="button" class="btn btn-default" data-dismiss="modal">
								    Fechar
								</button>
							</div>
							</form>
						</div>
					</div>
				</div>
			</div>			
			
			<!-- Excluir Solicitação -->
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
			    			<form id="form-adicionar" role="form" action="#" method="post">
			    				<div class="form-group">
				    				<div class="row">
				    					<div class="col-sm-6">
				    						<label for="nome">Nome</label>
		    								<input type="text" class="form-control" name="nome" id="nome"/>
				    					</div>
				    					<div class="col-sm-6">
				    						<label for="lotacao">Lotação</label>
	    									<select id="lotacao" name="lotacao" class="form-control">
<?php 
											$sql = "select * from secao";
																	
											$secoes = mysql_query($sql);
							
			  								while ($secao = mysql_fetch_assoc($secoes)) {
?>
												<option value="<?php echo $secao['id'] ?>">
															   <?php echo $secao['nome']?>
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
	    							<label for="desc">Descrição do Sistema</label>
	    							<textarea class="form-control" id="desc" name="desc" 
		    						    rows="6" style="width: 100%;"></textarea>
	    						
	    						</div>
	    						<div class="form-group">
	    							<label for="desc">Inf. Adicionais</label>
	    							<textarea class="form-control" id="info_adc" name=info_adc" 
		    						    rows="4" style="width: 100%;"></textarea>
	    						</div>
	    						
	    						<div class="form-group">
	    							<div class="row">
	    								<div class="col-sm-6">
	    									<label for="obs">Observações</label>
	    									<textarea class="form-control" id="obs" name="obs"
	    										rows="4" style="width: 100%"></textarea>
	    								</div>
	    								<div class="col-sm-4">
	    									<div class="form-group">
	    										<label for="tp_sol">Tipo de Solicitação</label>
	    										<select id="tp_sol" name="tp_sol" class="form-control">
<?php 
												$sql = "select * from tipo_solicitacao";
												$secoes = mysql_query($sql);
																		
		  										while ($secao = mysql_fetch_assoc($secoes)) {
?>
												<option value="<?php echo $secao['id'] ?>">
															   <?php echo $secao['nome']?>
												</option>
<?php
												}
?>
											</select>	    									
	    									</div>
	    									<div class="form-group">
												<label for="data">Data de Criação</label>
												<input type="text" class="form-control" name="data" 
											    	id="data" readonly>
											</div>
	    								</div>
	    							</div>
	    						</div>
	    						<div class="modal-footer">
									<button type="button" class="btn btn-primary">Salvar</button>
									<button type="button" class="btn btn-default" data-dismiss="modal">
								    	Fechar
									</button>
								</div>
			    			</form>	
			    		</div>
			    	</div>
			    </div>
    		</div>
    		
    		
    		
    		<!--  modais -->    		
    	</div> <!-- container -->
<?php 	
	@include $_SERVER['DOCUMENT_ROOT'] . '/sods/includes/rodape.php';
?>