<?php

@include $_SERVER['DOCUMENT_ROOT'] . '/sods/app/models/TipoSolicitacao.php';

@include $_SERVER['DOCUMENT_ROOT'] . '/sods/app/controllers/TiposSolicitacoesController.php';

try{
	$id = $_POST['bookName'];
	$nome = $_POST['nome'];
	$controller = new TiposSolicitacoesController();
	$controller->update($id, $nome);
}catch(Exception $e){
	echo "Não foi possivel editar o registro" . $e;
}finally{
	header('Location: index.php');
}

?>