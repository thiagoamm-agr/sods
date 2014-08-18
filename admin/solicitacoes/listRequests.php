<?php
	@session_start ();
	
	@require_once $_SERVER ['DOCUMENT_ROOT'] . '/sods/app/lib/session.php';

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
				
			@require_once $_SERVER ['DOCUMENT_ROOT'] . '/sods/app/lib/db.php';
				
			get_db_connection();
				
			$user= $_SESSION['usuario']['login'];
				
			if ($_SESSION['usuario']['tipo_usuario'] == 'A') {
				$query= "select " . 
							"s.nome, so.detalhamento, so.status, " .
							"t.nome as nome_sol, so.data_abertura, so.data_alteracao " .
						"from " . 
						    "solicitante as s " . 
						"inner join solicitacao as so " .
						    "on s.id = so.solicitante_id " . 
				        "inner join tipo_solicitacao as t " .
	    		            "on t.id = so.tipo_solicitacao_id";
				$result = mysql_query($query);
			} else {
				$query= "select " . 
				            "s.nome, so.detalhamento, so.status, " . 
				            "t.nome as nome_sol, so.data_abertura, so.data_alteracao " . 
				        "from solicitante as s " . 
				        "inner join solicitacao as so " . 
				            "on s.id = so.solicitante_id and s.login = '$user' " . 
				        "inner join tipo_solicitacao as t " . 
				            "on t.id = so.tipo_solicitacao_id;";
				$result = mysql_query($query);
			}
?>
			<div class="table-responsive">
				<table class="table table-striped table-bordered table-condensed" style="width: 95%; margin: 0 auto;">
					<thead>
						<tr>
	            			<th>Solicitante</th>
	            			<th>Solicitação</th>
	            			<th>Status</th>
	            			<th>Tipo</th>
	            			<th>Data Abertura</th>
	            			<th>Data Alteração</th>
	            		</tr>
            		</thead>
            		<tbody>
<?php		
					while ($esc = mysql_fetch_array($result)) {
			        	echo "<tr>";
						echo   "<td>{$esc['nome']}</td>
	                        	<td id=\"detalhamento\">{$esc['detalhamento']}</td>
					        	<td>{$esc['status']}</td>
						    	<td>{$esc['nome_sol']}</td>";
						echo   "<td>". date('d/m/Y H:m:s', strtotime($esc['data_abertura'])) . "</td>";
						echo   "<td>" . @$esc['data_modificacao'] . "</td>";
				     	echo "</tr>";
					}
?>
					</tbody>
	    		</table>
    		</div>
    	</div>
	</body>
</html>