<?php
	include $_SERVER ['DOCUMENT_ROOT'] . '/sods/includes/topo.php';
			
	require_once $_SERVER ['DOCUMENT_ROOT'] . '/sods/lib/db.php';
			
	get_db_connection();
		
	$query = "select " . 
				"s.id, s.nome, sec.nome as secao, s.cargo, " .
				"s.fone_ramal, s.login, s.tipo_usuario, s.status " . 
			"from " . 
				"solicitante as s " . 
			"inner join secao as sec " . 
				"on s.secao_id = sec.id;";
	
	$result = mysql_query($query);
?>		
			<h2>Usuários</h2>
			<div class="table-responsive">				
				<table class="table table-striped table-bordered table-condensed">
					<thead>
						<tr>
							<th>ID</th>
	            			<th>Nome</th>
	            			<th>Lotação</th>
	            			<th>Cargo</th>
	            			<th>Telefone/Ramal</th>
	            			<th>Tipo de Usuário</th>
	            			<th>Status</th>
	            			<th>Login</th>
	            			<th>Ação</th>
	            		</tr>
					</thead>
					<tbody>
<?php		
					while ($usuario = mysql_fetch_assoc($result)) {
?>
						<tr>
							<td><?php echo $usuario['id']?></td>
							<td><?php echo $usuario['nome']?></td>
							<td><?php echo $usuario['secao']?></td>
							<td><?php echo $usuario['cargo']?></td>
							<td><?php echo $usuario['fone_ramal']?></td>
							<td><?php echo $usuario['tipo_usuario']?></td>
							<td><?php echo $usuario['status']?></td>
							<td><?php echo $usuario['login']?></td>
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
			<div class="modal fade" id="modalEdit" tabindex="-1" role="dialog" aria-labelledby="modalEdit" 
			    aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
							    &times;
							</button>
							<h3 class="modal-title" id="modalEdit">Editar Usuário</h3>
						</div>
						<div class="modal-body">
							<h5>Digite os dados do usuário</h5>
							<form role="form">								
		  						<div class="form-group">
		    						<label for="nome">Nome</label>
		    						<input id="nome" name="nome" type="text" class="form-control" />
		  						</div>
								<div class="form-group">
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
		  						<div class="form-group">
		    						<label for="cargo">Cargo</label>
		    						<input id="cargo" name="cargo" type="text" class="form-control" />
		  						</div>		  
								<div class="form-group">
		    						<label for="telefone_ramal">Telefone / Ramal</label>
		    						<input id="telefone_ramal" name="telefone_ramal"
		    						    type="text" class="form-control" />
		  						</div>
								<div class="form-group">
		    						<label for="email">E-mail</label>
		    						<input id="email" name="email" type="email" class="form-control" />
		  						</div>
								<div class="form-group">
									<input type="checkbox" id="tipo_usuario" name="tipo_usuario" />
									<label for="tipo_usuario"> Administrador</label>									                				
								</div>
							</form>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-primary">Salvar</button>
							<button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
						</div>
					</div>
				</div>
			</div>			

			<div class="modal fade .bs-example-modal-sm" id="modalDel" tabindex="-1" role="dialog" 
			    aria-labelledby="modalDel" aria-hidden="true">
				<div class="modal-dialog modal-sm">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
							    &times;
							</button>
							<h4 class="modal-title" id="modalDel">Exclusão de Usuário</h4>
						</div>
						<div class="modal-body">
    						<h5>Confirma exclusão de usuário?</h5>
    					</div>
						<form action="#">
	    					<div class="modal-footer">
	    						<button type="submit" class="btn btn-danger">Sim</button>
	    						<button type="button" class="btn btn-primary" data-dismiss="modal">Não</button>	    						
	    					</div>
    					</form>
    				</div>
    			</div>
    		</div> <!--  Fim Modais -->
    		
<?php
	include $_SERVER ['DOCUMENT_ROOT'] . '/sods/includes/rodape.php';
?>