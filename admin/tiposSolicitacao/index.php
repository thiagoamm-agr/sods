<?php
	/* CADASTRO DE TIPO DE SOLICITAÇÕES */

	@include $_SERVER['DOCUMENT_ROOT'] . '/sods/includes/topo.php';
	
	@include $_SERVER['DOCUMENT_ROOT'] . '/sods/app/models/TipoSolicitacao.php';
	
	@include $_SERVER['DOCUMENT_ROOT'] . '/sods/app/controllers/TiposSolicitacoesController.php';
?>
		<div class="container">
			<h2>Tipos de Solicitação</h2>
			<div class="row" style="width: 50%; margin: 0 auto";>
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
			<div class="table-responsive" style="width: 50%; margin: 0 auto";>
				<table class="table table-striped table-bordered table-condensed">
					<thead>
						<tr>
							<th>ID</th>
							<th>Nome do Tipo de Solicitação</th>
							<th>Ação</th>
						</tr>
					</thead>
					<tbody>
<?php 
					$controller = new TiposSolicitacoesController();

					//Obtém a lista de todos os tipos de solicitação
					foreach ($tipos = $controller->getTipos() as $tipo) {
?>
							<tr>
								<td><?php echo $tipo['id'] ?></td>
								<td><?php echo $tipo['nome'] ?></td>
								<td colspan="2">
									<button class="btn btn-primary btn-sm" data-toggle="modal" 
									    data-target="#modalEdit">
										<strong>Editar</strong>
									</button>
									<button class="btn btn-danger btn-sm" data-toggle="modal" 
									    data-target="#modalDel">
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
			<!-- Modais -->
			
			<!-- Adicionar Tipo de Solicitação -->
			<div class="modal fade" id="modalAdd" tabindex="-1" role="dialog" 
			    aria-labelledby="modalAdd" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
							    &times;
							</button>
							<h3 class="modal-title" id="modalAdd">Adicionar novo tipo</h3>
						</div>
						<div class="modal-body">    						
    						 <form id="form-add" role="from" action="#" method="post">
    							<div class="form-group">
    								<label for="nome">Nome do Tipo de Solicitação</label>
    								<input type="text" class="form-control" name="nome" id="nome" maxlength="50"/>    								
    							</div>					
								<div class="modal-footer">
									<button type="submit" class="btn btn-success" onclick="tipo_sol('#form-add')">Salvar</button>
		    						<button type="button" class="btn btn-primary" data-dismiss="modal">Cancelar</button>    						
								</div>
						    </form>
						</div>
    				</div>
    			</div>
    		</div>
			
			<!-- Editar Tipo de Solicitação-->
			<div class="modal fade" id="modalEdit" tabindex="-1" role="dialog" 
			    aria-labelledby="modalEdit" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
							    &times;
							</button>
							<h3 class="modal-title" id="modalEdit">Editar Tipo</h3>
						</div>
						<div class="modal-body">    						
    						 <form id="form-edit" method="post">
    							<div class="form-group">
    								<label for="nome">Digite o novo nome</label>
    								<input type="text" class="form-control" name="nome" id="nome" maxlength="50"/>    								
    							</div> 				
								<div class="modal-footer">
									<button type="submit" class="btn btn-success" onclick="tipo_sol('#form-edit')">Salvar</button>
		    						<button type="button" class="btn btn-primary" data-dismiss="modal">Cancelar</button>    						
								</div>
							</form>
						</div>
    				</div>
    			</div>
    		</div>
    		
			<!-- Excluir Tipo de Solicitação -->
			<div class="modal fade" id="modalDel" tabindex="-1" role="dialog" 
			    aria-labelledby="modalDel" aria-hidden="true">
				<div class="modal-dialog modal-sm">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
							    &times;
							</button>
							<h4 class="modal-title" id="modalDel">Exclusão de Tipo</h4>
						</div>
						<div class="modal-body">
    						<h5>Confirma exclusão do tipo de solicitação?</h5>
    					</div>
						<form action="#">
	    					<div class="modal-footer">
	    						<button type="submit" class="btn btn-danger">Sim</button>
	    						<button type="button" class="btn btn-primary" data-dismiss="modal">Não</button>	    						
	    					</div>
    					</form>
    				</div>
    			</div>
    		</div><!-- Modais -->	
			
		</div><!-- container -->
		
		<script type="text/javascript" src="/sods/js/validators/tipoSolicitacao.js"></script>
		
<?php
	@include $_SERVER['DOCUMENT_ROOT'] . '/sods/includes/rodape.php'; 
?>