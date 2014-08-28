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
					$usuariosController = new UsuariosController(); 

					foreach ($usuariosController->getUsuarios() as $usuario) {
?>
						<tr>
							<td><?php echo $usuario['id'] ?></td>
							<td><?php echo $usuario['nome'] ?></td>
							<td><?php echo $usuario['lotacao'] ?></td>
							<td><?php echo $usuario['cargo'] ?></td>
							<td><?php echo $usuario['telefone'] ?></td>
							<td><?php echo $usuario['tipo_usuario'] ?></td>
							<td><?php echo $usuario['status'] ?></td>
							<td><?php echo $usuario['login'] ?></td>
							<td colspan="2">
								<button class="btn btn-primary btn-sm" 
								    data-toggle="modal" data-target="#modalEdit" 
								    onclick="editar(<?php echo $usuario['id'] ?>)">
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
							<form role="form">
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
							<button type="button" class="btn btn-primary" onclick="salvar()">Salvar</button>
							<button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>							
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
						<form action="#">
	    					<div class="modal-footer">
	    						<button type="submit" class="btn btn-danger">Sim</button>
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
							<h3 class="modal-title" id="modalEdit">Adicionar Usuário</h3>
						</div>
						<div class="modal-body">    						
    						 <form id="form-add" role="form" action="#" method="post">
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
    								<input type="text" class="form-control" name="fone-ramal" id="fone-ramal"/>
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
    								<input type="checkbox" id="tipo_usr" name="tipo_usr" value="A"> Administrador
    							</div>
    						</form>
    					</div>						
						<div class="modal-footer">
							<button type="submit" class="btn btn-success" onclick="salvar()">Salvar</button>
    						<button type="button" class="btn btn-primary" data-dismiss="modal">Cancelar</button>    						
						</div>
    				</div>
    			</div>
    		</div> <!-- modais -->		
		</div> <!-- container -->

		<script type="text/javascript" src="/sods/js/models/Usuario.js"></script>
		
		<script type="text/javascript">

			$(document).ready(function() {
			    $('#form-add').bootstrapValidator({
			        feedbackIcons: {
			            valid: 'glyphicon glyphicon-ok',
			            invalid: 'glyphicon glyphicon-remove',
			            validating: 'glyphicon glyphicon-refresh'
			        },
			        fields: {
			            email: {
			                validators: {
			                    emailAddress: {
			                        message: 'Digite um endereço de e-mail válido'
			                    }
			                }
			            }
			        }
			    });
			});



			function editar(id) {
				$.ajax({
				    type: 'POST',
				    url: '',
				    data: 'acao=editar&id=' + id,
				    success: function(data) {
					    					    
				    }
                });
			}

			function salvar() {
			    // 1 - JSON do usuário.
			    var usuario = new Usuario();
			    usuario.nome = $('#nome').val();
			    usuario.secaoId = $('#lotacao').val();
			    usuario.cargo = $('#cargo').val();
			    usuario.telefoneRamal = $('#telefone_ramal').val();
			    usuario.email = $('#email').val();			    		    
			    // 2 - Requisição Ajax
			    $.ajax({
				    type: 'POST',
				    url: '',
				    data: 'acao=' + $('#acao').val() + '&' + 
				          'json=' + usuario.toJSON(),
				    success: function(data) {
					    					    
				    }
                });
		    }
 		</script>
<?php	
	if (isset($_POST['acao'])) {
		$acao = $_POST['acao'];
		if ($acao == 'salvar') {
			if (isset($_POST['json'])) {
				$json = $_POST['json'];
			}
// 		$obj = json_decode($json);
// 		$usuario = new Usuario();
// 		$usuario->nome = $obj->nome;
// 		$usuario->secao_id = $obj->secaoId;
// 		$usuario->cargo = $obj->cargo;
// 		$usuario->fone_ramal = $obj->telefoneRamal;
// 		$usuario->email = $obj->email;
// 		$controller->insert($usuario);	
		} else if ($acao == 'editar') {
			$usuario = $usuariosController->getUsuario($_POST['id']);
		} else if ($acao == 'excluir') {
			
		}		
	}
	@include $_SERVER ['DOCUMENT_ROOT'] . '/sods/includes/rodape.php';
?>