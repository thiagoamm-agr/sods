<?php
	/* Cadastro de Lotações */

	@include $_SERVER['DOCUMENT_ROOT'] . '/sods/includes/topo.php';
	
	@include $_SERVER['DOCUMENT_ROOT'] . '/sods/app/models/Lotacao.php';
	
	@include $_SERVER['DOCUMENT_ROOT'] . '/sods/app/controllers/LotacoesController.php';
?>
		<div class="container">
			<h2>Lotações</h2>
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
	            			<th>Nome</th>
	            			<th>Sigla</th>
	            			<th>Gerência</th>
	            			<th>Ação</th>
	            		</tr>
					</thead>
					<tbody>
<?php
					$controller = new LotacoesController();

					foreach ($controller->getLotacoes() as $lotacao) {
?>
						<tr>
							<td><?php echo $lotacao['id'] ?></td>
							<td><?php echo $lotacao['nome'] ?></td>
							<td><?php echo $lotacao['sigla'] ?></td>
							<td><?php echo isset($lotacao['gerencia']) ? $lotacao['gerencia']->sigla : ''?></td>
							<td colspan="2">							
								<button class="btn btn-primary btn-sm" 
								    data-toggle="modal" data-target="#modalEdit" 
								    onclick="editar(<?php echo $lotacao['id'] ?>)">
									<strong>Editar</strong>
								</button>
								<button class="btn btn-danger btn-sm" 
								    data-toggle="modal" data-target="#modalDel">
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
			<!-- Adicionar Usuário -->
    		<div class="modal fade" id="modalAdd" tabindex="-1" role="dialog" 
			    aria-labelledby="modalAdd" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
							    &times;
							</button>
							<h3 class="modal-title" id="modalEdit">Adicionar Lotação</h3>
						</div>
						<div class="modal-body">    						
    						 <form id="form-adicionar" role="form" action="#" method="post">
    							<div class="form-group">
    								<label for="nome">Nome</label>
    								<input type="text" class="form-control" id="nome" name="nome" />    								
    							</div>
    							<div class="form-group">
    								<label for="sigla">Sigla</label>
    								<input type="text" class="form-control" id="sigla" name="sigla" />    								
    							</div>
    							<div class="form-group">
    								<label for="gerencia">Gerência</label>
    								<select id="gerencia" name="gerencia" class="form-control">
<?php 
									foreach ($controller->getGerencias() as $gerencia) { 
?>
										<option value="<?php echo $gerencia['id'] ?>"><?php echo $gerencia['nome'] . 
										' - ' . $gerencia['sigla'] ?></option>
<?php 
									}
?>
									</select>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
<?php
	@include $_SERVER['DOCUMENT_ROOT'] . '/sods/includes/rodape.php'; 
?>