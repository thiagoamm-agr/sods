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
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		
		<style type="text/css">
  			table {
			    padding-left: 15%;
			    padding-right: 20%;
			    font-size: 
		    }
  		</style>
		
		<title>SODS</title>
	</head>
    <body>
    <?php
		include '../admin/index.php';
		
		require_once $_SERVER ['DOCUMENT_ROOT'] . '/sods/lib/db.php';
		get_db_connection();
		
		$user= $_SESSION['usuario']['login'];
		
		if ($_SESSION['usuario']['tipo_usuario'] == 'A'){
			$query= "select s.nome, so.detalhamento, so.status, t.nome as nome_sol, so.data_abertura, so.data_alteracao from solicitante as s inner join 
						solicitacao as so on s.id = so.solicitante_id inner join tipo_solicitacao as t on t.id = so.tipo_solicitacao_id";
			$result = mysql_query($query);
		}else{
			$query= "select s.nome, so.detalhamento, so.status, t.nome as nome_sol, so.data_abertura, so.data_alteracao from solicitante as s inner join 
						solicitacao as so on s.id = so.solicitante_id and s.login = '$user' inner join tipo_solicitacao as t on t.id = so.tipo_solicitacao_id;";
			$result = mysql_query($query);
		}
		
		echo   '<table class="table table-striped">
			    		<tr>
							<td>Nome do Solicitante</td>
							<td>Detalhamento</td>
							<td>Status</td>
							<td>Tipo de Solicitação</td>
							<td>Abertura</td>
							<td>Ultima Alteração</td>
						</tr>';
		while ($esc = mysql_fetch_array($result)){
			echo '<tr>
					<td>' .$esc['nome']. ' </td>
					<td>' .$esc['detalhamento']. ' </td>
					<td>' .$esc['status']. ' </td>
					<td>' .$esc['nome_sol']. ' </td>
					<td>' .$esc['data_abertura']. ' </td>
					<td>' .@$esc['data_modificacao']. ' </td>
				  </tr>';
		}
		echo '</table>';
	?>
	
	</body>
</html>