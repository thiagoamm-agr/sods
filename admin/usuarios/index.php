<?php
	/* CADASTRO DE USUÁRIOS */	

	@include $_SERVER['DOCUMENT_ROOT'] . '/sods/includes/topo.php';
	
	// Models
	@include $_SERVER['DOCUMENT_ROOT'] . '/sods/app/models/Lotacao.php';	
	@include $_SERVER['DOCUMENT_ROOT'] . '/sods/app/models/Usuario.php';
	
	// Controllers
	@include $_SERVER['DOCUMENT_ROOT'] . '/sods/app/controllers/LotacoesController.php';	
	@include $_SERVER['DOCUMENT_ROOT'] . '/sods/app/controllers/UsuariosController.php';
?>
		<div class="container">
			<h2>Usuários</h2>
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
					$controller = new UsuariosController(); 

					foreach ($controller->getUsuarios() as $usuario) {
?>
						<tr>
							<td><?php echo $usuario['id'] ?></td>
							<td><?php echo $usuario['nome_sol'] ?></td>
							<td><?php echo $usuario['lotacao'] ?></td>
							<td><?php echo $usuario['cargo'] ?></td>
							<td><?php echo $usuario['telefone'] ?></td>
							<td><?php echo $usuario['tipo_usuario'] ?></td>
							<td><?php echo $usuario['status'] ?></td>
							<td><?php echo $usuario['login'] ?></td>
							<td colspan="2">
								<button class="btn btn-primary btn-sm" 
								    data-toggle="modal" data-target="#modalEdit" 
								    onclick='edit(<?php echo json_encode($usuario) ?>)'>
									<strong>Editar</strong>
								</button>
								<button class="btn btn-danger btn-sm" 
								    	data-toggle="modal" 
								    	data-target="#modalDel"
								    	onclick="del(<?php echo $usuario['id']?>)">
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
			
			<!-- Editar Usuário -->
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
							<form role="form" id="form-edit">
		  						<div class="form-group">
		    						<label for="nome">Nome</label>
		    						<input id="nome" name="nome" type="text" class="form-control" />
		  						</div>
		  						<div class="form-group">
		  							<label for="login">Login</label>
		  							<input id="login" name="login" type="text" class="form-control" />
		  						</div>
								<div class="form-group">
									<label for="lotacao">Lotação</label>
		  							<select id="lotacao" name="lotacao" class="form-control">
<?php 
										$lotacoesController = new LotacoesController();
										
										$lotacoes = $lotacoesController->getLotacoes();
										
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
		  						<div class="form-group">
		    						<label for="cargo">Cargo</label>
		    						<input id="cargo" name="cargo" type="text" class="form-control" />
		  						</div>		  
								<div class="form-group">
		    						<label for="fone">Telefone / Ramal</label>
		    						<input id="fone" name="fone"
		    						    type="text" class="form-control" />
		  						</div>
								<div class="form-group">
		    						<label for="email">E-mail</label>
		    						<input id="email" name="email" type="email" class="form-control" />
		  						</div>
								<div class="form-group">
	    							<div>
	    								<label for="tipoUsuario">Tipo</label>
	    							</div>
    								<div class="form-group">
    									<input type="radio" name="tipoUsuario" value="U"/>Usuário &nbsp;&nbsp;
    									<input type="radio" name="tipoUsuario" value="A"/>Administrador
    									</label>
    								</div>
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
			
			<!-- Excluir Usuário -->
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
						<form id="form-del" action="#" method="post">
	    					<div class="modal-footer">
	    						<button type="submit" class="btn btn-danger" onclick="save()">Sim</button>
	    						<button type="button" class="btn btn-primary" data-dismiss="modal">Não</button>	    						
	    					</div>
    					</form>
    				</div>
    			</div>
    		</div> 
    		
    		<!-- Adicionar Usuário -->
    		<div class="modal fade" id="modalAdd" tabindex="-1" role="dialog" 
			    aria-labelledby="modalAdd" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
							    &times;
							</button>
							<h3 class="modal-title" id="modalAdd">Adicionar Usuário</h3>
						</div>
						<div class="modal-body">    						
    						 <form id="form-add" action="#" method="post">
    							<div class="form-group">
    								<label for="nome">Nome</label>
    								<input type="text" class="form-control" name="nome" id="nome"/>    								
    							</div>
    							<div class="form-group">
    								<label for="lotacao">Lotação</label>
    								<select id="lotacao" name="lotacao" class="form-control">
<?php
										foreach ($lotacoes as $lotacao){
?>
											<option value="<?php echo $lotacao['id'] ?>">
												<?php echo $lotacao['nome']?>
											</option>
<?php
										} 
?>
    								</select>
    							</div>
    							<div class="form-group">
    								<label for="cargo">Cargo</label>
    								<input type="text" class="form-control" name="cargo" id="cargo"/>
    							</div>
    							<div class="form-group">
    								<label for="fone_ramal">Telefone / Ramal</label>
    								<input type="text" class="form-control" name="fone" id="fone"/>
    							</div>
    							<div class="form-group">
    								<label for="email">E-mail</label>
    								<input type="text" class="form-control" name="email" id="email"/>
    							</div>
    							<div class="form-group">
    								<label for="login">Login</label>
    								<input type="text" class="form-control" name="login" id="login"/>
    							</div>
    							<div class="form-group">
    								<input type="hidden" 
    									class="form-control" 
    									name="senha" 
    									id="senha" 
    									value="<?php echo md5(12345)?>"/>
    							</div>
    							<div class="form-group">
	    							<div>
	    								<label for="tipoUsuario">Tipo</label>
	    							</div>
    								<div class="form-group">
    									<input type="radio" name="tipoUsuario" value="U"/>Usuário &nbsp;&nbsp;
    									<input type="radio" name="tipoUsuario" value="A"/>Administrador
    									</label>
    								</div>
    							</div>					
								<div class="modal-footer">
									<button type="submit" class="btn btn-success" onclick="save()">Salvar</button>
									<button type="reset" class="btn btn-default">Limpar</button>
		    						<button type="button" class="btn btn-primary" data-dismiss="modal">Cancelar</button>    						
								</div>
							</form>
						</div>
    				</div>
    			</div>
    		</div> <!-- modais -->		
		</div> <!-- container -->
		
		<!-- Javascript -->
        <script type="text/javascript" src="/sods/static/js/models/Usuario.js"></script>
        
        <script type="text/javascript" src="/sods/static/js/validators/UsuarioValidator.js"></script>
		
		<script type="text/javascript">
			var usuario = null;
			var action = null;

			function add() {
				action = "add";
				usuario = new Usuario();
			}
			
			function edit(usuario_json) {
                try {
                    if (usuario_json != null) {
                        action = 'edit';
                        var form = $('#form-' + action);

                        $('#nome', form).val(usuario_json.nome_sol);
                        $('#login', form).val(usuario_json.login);
                        $('#lotacao', form).val("" + usuario_json.lotacao_id);
                        $('#cargo', form).val(usuario_json.cargo);
                        $('#fone', form).val(usuario_json.telefone);
                        $('#email', form).val(usuario_json.email);
                        $('#tipo_usuario', form).val(usuario_json.tipo_usuario);

                        usuario = new Usuario();
                        usuario.id = usuario_json.id;
                    } else {
                        throw 'Não é possível editar uma alteração que não existe.';
                    }
                } catch(e) {
                    alert(e);
                }
            }

			function del(id) {
				try {
					id = id == null ? "" : id;
					if (id !== "") {
						action = "del";
						usuario = new Usuario();
						usuario.id = id;
					} else {
						throw "Não foi possivel obter o id do tipo de solicitacao";
					}
				} catch (e) {
					alert(e);
				}
			}

			function save() {
				if (usuario !=  null) {
					UsuarioValidator.validate($('#form-' + action));
					usuario.nome = $('#form-' + action  + ' input[name="nome"]').val();
				    usuario.lotacao_id = $('#form-' + action  + ' select[name="lotacao"]').val();
				    usuario.cargo = $('#form-' + action  + ' input[name="cargo"]').val();
				    usuario.telefone = $('#form-' + action  + ' input[name="fone"]').val();
				    usuario.email = $('#form-' + action  + ' input[name="email"]').val();
				    usuario.login = $('#form-' + action  + ' input[name="login"]').val();
				    usuario.senha = $('#form-' + action  + ' input[name="senha"]').val();
				    usuario.tipo_usuario = $('#form-' + action  + ' input:radio[name="tipoUsuario"]:checked').val();

				    if(action == 'del'){
						usuario.lotacao_id = '""';
				    }

				
					// Requisição AJAX
				    $.ajax({
					    type: 'POST',
					    url: '',
					    data: 'action=' + action + '&' + 'usuario=' + usuario.toJSON(),
					    success: function(data) {
						    					    
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
	if (isset($_POST['action']) && isset($_POST['usuario'])) {
		//Recuperando dados do post
		$action = $_POST['action'];
		$json = $_POST['usuario'];
		
		$usuario = new Usuario();
		$usuario->loadJSON($json);
		
		switch ($action) {
			case 'add':
				$controller->insert($usuario);
				break;
			case 'edit':
				$controller->edit($usuario);
				break;
			case 'del':
				$controller->delete($usuario);
				break;
		}
	}
	@include $_SERVER ['DOCUMENT_ROOT'] . '/sods/includes/rodape.php';
?>