<?php
	@session_start();

	@require_once $_SERVER ['DOCUMENT_ROOT'] . '/sods/app/lib/session.php';

	validar_acesso();
	
	date_default_timezone_set("Brazil/East");
	$data = date("d/m/y " . " H:i");
?>
<!DOCTYPE html>
<html lang="pt-BR">
    <head>
        <meta charset="UTF-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <meta name="description" content="" />
        <meta name="author" content="" />
 
        <link rel="icon" href="/sods/favicon.ico" />
 
        <!--  Bootstrap core CSS -->
        <link rel="stylesheet" type="text/css" href="/sods/css/bootstrap/bootstrap.css" />

        <!-- Custom styles for this template -->
        <link rel="stylesheet" href="/sods/css/navbar-fixed-top.css" />
        
        <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    	<!--[if lt IE 9]>
      		<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->
		
		<link rel="stylesheet" href="/sods/css/sods.css" />
		
        <script src="/sods/js/jquery.min.js"></script>
        <script src="/sods/js/bootstrap/bootstrap.min.js"></script>
        				
		<title>SODS</title>
    </head>
	<body>
		<div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
		    <div class="container">
				<div class="navbar-header">
            		<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
              			<span class="sr-only">Toggle navigation</span>
              			<span class="icon-bar"></span>
              			<span class="icon-bar"></span>
						<span class="icon-bar"></span>
            		</button>
					<a class="navbar-brand" style="font-size: 40px; color: white;" 
				   	   href="/sods/admin/" title="Sistema para Solicitação de Desenvolvimento de Software">SODS</a>
          		</div>
				<div class="collapse navbar-collapse">
					<ul class="nav navbar-nav">
						<li class="dropdown">
							<a class="dropdown-toggle" href="/sods/admin/">
								Início
							</a>
						</li>
<?php 
					if ($_SESSION['usuario']['tipo_usuario'] == 'A') {
						// Menu do Administrador do Sistema.
?>							
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown">
								Cadastros <span class="caret"></span>
							</a>
							<ul class="dropdown-menu" role="menu">
								<li><a href="/sods/admin/lotacoes/">Cadastro de Lotações</a></li>
								<li><a href="/sods/admin/usuarios/">Cadastro de Usuários</a></li>
								<li><a href="/sods/admin/solicitacoes/">Cadastro de Solicitações</a></li>
								<li><a href="/sods/admin/tiposSolicitacao/">Cadastro de Tipos de Solicitação</a></li>
							</ul>
						</li>
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown">
								Relatórios <span class="caret"></span>
							</a>
							<ul class="dropdown-menu" role="menu">
								<li><a href="#">Relatório de Usuários</a></li>
								<li><a href="#">Relatório de Solicitações</a></li>
							</ul>
						</li>
<?php
					} else {
						// Menu do Usuário do Sistema.
?>
						<li><a href="/sods/admin/account.php">Conta</a></li>
						<li><a href="/sods/admin/solicitacoes/">Solicitações</a></li>
<?php 
					}
?>
						<li class="dropdown"><a class="dropdown-toggle" href="#">Sobre</a></li>
					</ul>
            		<span style="width: 100px"></span>
            		<ul class="nav navbar-nav navbar-right">
						<li>
							<a href="/sods/admin/account.php">
							    Bem-vindo(a), 
							    <font color="white">
							        <b><?php echo $_SESSION['usuario']['nome'];?></b>
							    </font>.
						    </a>
						</li>
              			<li>
              				<form class="navbar-form navbar-right" role="form" action="/sods/app/lib/logout.php">
								<button id="btnSair" type="submit" class="btn btn-success btn-sm pull-right">
									<strong>Sair</strong>
								</button>
							</form>
						</li>
            		</ul>            		
				</div> <!--/.nav-collapse -->		        
			</div> <!--/.container-fluid -->
		</div>
