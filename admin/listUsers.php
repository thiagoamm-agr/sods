<?php
	@session_start ();
	
	require_once $_SERVER ['DOCUMENT_ROOT'] . '/sods/lib/session.php';

	validar_acesso ();
	
	date_default_timezone_set("Brazil/East");
	$data = date("d/m/y " . " H:i");
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN">
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		
		<style>
			.table th {
				text-align: center;
				font-size: 14px;				
			}
			
			#detalhamento {
				text-align: justify;
				text-justify: inter-word;
				font-size: 14px;
			}
			
			.table td {
				text-align: center;
				font-size: 14px;
			}
		</style>				
		
		<title>SODS</title>
	</head>
	<body>
		<div class="container">    
<?php
			include '../admin/index.php';
				
			require_once $_SERVER ['DOCUMENT_ROOT'] . '/sods/lib/db.php';
				
			get_db_connection();
			
			$query = "select " . 
						"s.id, s.nome, sec.nome as nome_sec, s.cargo," .
						" s.fone_ramal, s.login, s.tipo_usuario, s.status " . 
					"from " . 
						"solicitante as s " . 
					"inner join secao as sec " . 
						"on s.secao_id = sec.id;";
			$result = mysql_query($query);
?>
		<div class="table-responsive">
				<table class="table table-striped table-bordered table-condensed" style="width: 95%; margin: 0 auto;">
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
					while ($esc = mysql_fetch_array($result)) {
			        	echo "<tr>";
						echo   "<td>{$esc['id']}</td>
								<td>{$esc['nome']}</td>
	                        	<td id=\"detalhamento\">{$esc['nome_sec']}</td>
					        	<td>{$esc['cargo']}</td>
						    	<td>{$esc['fone_ramal']}</td>
								<td>{$esc['tipo_usuario']}</td>
								<td>{$esc['status']}</td>
								<td>{$esc['login']}</td> 
								<td colspan=2> 
              							<button class='btn btn-primary btn-sm' data-toggle='modal' data-target='#modalEdit'>
              								Editar
										</button>
										<button class='btn btn-danger btn-sm' data-toggle='modal' data-target='#modalDel'>
              								Excluir
										</button>
									</form>
								</td>";
				     	echo "</tr>";
					}
?>

				
    		
    		<div class="modal fade" id="modalEdit" tabindex="-1" role="dialog" aria-labelledby="modalEdit" aria-hidden="true">
    			<div class="modal-dialog">
    				<div class="modal-content">
    					<div class="modal-header">
    						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    						<h3 class="modal-title" id="modalEdit">Editar Usuário</h3>
    					</div>
    					<div class="modal-body">
    						<h5>Digite os dados do usuário</h5>
    					</div>
    					<div class="modal-footer">
    						<button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
    						<button type="button" class="btn btn-primary">Salvar Modificações</button>
    					</div>
    				</div>
    			</div>
    		</div>
    		
    		
    		<div class="modal fade .bs-example-modal-sm" id="modalDel" tabindex="-1" role="dialog" aria-labelledby="modalDel" aria-hidden="true">
    			<div class="modal-dialog modal-sm">
    				<div class="modal-content">
    					<div class="modal-header">
    						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    						<h4 class="modal-title" id="modalDel">Exclusão de Usuário</h4>
    					</div>
    					<div class="modal-body">
    						<h5>Confirma exclusão de usuário?</h5>
    					</div>
    					<div class="modal-footer">
    						<button type="button" class="btn btn-primary" data-dismiss="modal">Não</button>
    						<form class='navbar-form navbar-left' role='form' action="#">
              							<button type='submit' class='btn btn-danger' href='#'>
              								Sim
										</button>
							</form>
    					</div>
    				</div>
    			</div>
    		</div>
    		
    		
    		
					</tbody>
	    		</table>
    		</div>
    		
	</body>
</html>