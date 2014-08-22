<?php
/* Cadastro de Tipos de Solicitação */

@include $_SERVER['DOCUMENT_ROOT'] . '/sods/includes/topo.php';

@include $_SERVER['DOCUMENT_ROOT'] . '/sods/app/models/TipoSolicitacao.php';

@include $_SERVER['DOCUMENT_ROOT'] . '/sods/app/controllers/TiposSolicitacaoController.php';
?>
		<div class="container">
			<h2>Tipos de Solicitação</h2>
		</div>
<?php
	@include $_SERVER['DOCUMENT_ROOT'] . '/sods/includes/rodape.php'; 
?>