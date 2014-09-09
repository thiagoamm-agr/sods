<?php

@include $_SERVER['DOCUMENT_ROOT'] . '/sods/app/models/TipoSolicitacao.php';

@include $_SERVER['DOCUMENT_ROOT'] . '/sods/app/controllers/TiposSolicitacoesController.php';

	try{
		$id = $_POST['bookId'];
		$controller = new TiposSolicitacoesController();
		$controller->delete($id);
	}catch(Exception $e){
		echo "Não foi possivel deletar o registro" . $e;
	}finally{
		header('Location: index.php');
	}
	

?>