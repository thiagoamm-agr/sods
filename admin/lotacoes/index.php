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
	$lotacao->nome = 'Coordenação de Tecnologia da Informação';
	$lotacao->sigla = 'COTI';
	$lotacao->gerencia_id = 1;
	$json = $lotacao->toJSON();
	$obj = json_decode($json);
?>
		<p><?php echo $json ?></p>
		<p><?php echo $obj->id ?></p>
		<p><?php echo $obj->nome ?></p>
		<p><?php echo $obj->sigla ?></p>
		<p><?php echo $obj->usuario->nome ?></p>
<?php	
	@include $_SERVER['DOCUMENT_ROOT'] . '/sods/includes/rodape.php'; 
?>