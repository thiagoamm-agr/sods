<?php 
@session_start();

date_default_timezone_set("Brazil/East");

@require_once $_SERVER['DOCUMENT_ROOT'] . '/sods/lib/session.php';
@require_once $_SERVER['DOCUMENT_ROOT'] . '/sods/lib/db.php';

validar_acesso();

get_db_connection();

$nome = $_POST['nome'];
$lotacao = $_POST['lotacao'];
$cargo = $_POST['cargo'];
$fone_ramal = $_POST['fone_ramal'];
$email = $_POST['email'];
$login = $_POST['login'];
$tipo_usr = $_POST['tipo_usr'];

$query= "insert into solicitante (nome, secao_id, cargo, fone_ramal, email, login, tipo_usuario) " .
		"values ('$nome', '$lotacao', '$cargo', '$fone_ramal', '$email', '$login', '$tipo_usr')";

$result= mysql_query($query) or die(mysql_error());;

header('location: /sods/admin/listUsers.php');


?>