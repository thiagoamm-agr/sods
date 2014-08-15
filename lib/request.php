<?php
	@session_start();

	date_default_timezone_set("Brazil/East");

	@require_once $_SERVER['DOCUMENT_ROOT'] . '/sods/lib/session.php';
	@require_once $_SERVER['DOCUMENT_ROOT'] . '/sods/lib/db.php';

	validar_acesso();

	get_db_connection();

	$solicitante_id = $_SESSION['usuario']['id'];
	$desc = $_POST['desc'];
	$info_add = $_POST['info_add'];
	$obs = $_POST['obs'];
	$tipo_sol = $_POST['tipo_sol'];

	$query = mysql_query("insert into solicitacao (solicitante_id, detalhamento, info_adicionais, observacoes, tipo_solicitacao_id) values ($solicitante_id , '$desc', '$info_add', '$obs', $tipo_sol)") or die(mysql_error());

	header('location: /sods/admin/listRequests.php'); 
?>