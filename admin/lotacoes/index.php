<?php
	/* Cadastro de Lotações */

	@include $_SERVER['DOCUMENT_ROOT'] . '/sods/includes/topo.php';
	
	@include $_SERVER['DOCUMENT_ROOT'] . '/sods/app/models/Lotacao.php';
?>
		<div class="container">
			<h2>Lotações</h2>
		</div>
<?php
	$lotacao = new Lotacao();
	$lotacao->id = 2;
	$lotacao->nome = 'Coordenação de Tecnologia da Informação';
	$lotacao->sigla = 'COTI';
	$lotacao->gerencia_id = 1;
	$json = json_encode($lotacao->toArray());
	var_dump($lotacao->toArray());
	
	@include $_SERVER['DOCUMENT_ROOT'] . '/sods/includes/rodape.php'; 
?>