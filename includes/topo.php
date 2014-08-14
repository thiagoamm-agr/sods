<?php
	@session_start();

	require_once $_SERVER ['DOCUMENT_ROOT'] . '/sods/lib/session.php';

	validar_acesso();
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
				   	   href="index.php" title="Sistema para Solicitação de Desenvolvimento de Software">SODS</a>
          		</div>
				<div class="collapse navbar-collapse">
					<ul class="nav navbar-nav">						
<?php 
						if ($_SESSION['usuario']['tipo_usuario'] == 'A') {
							// Menu do Administrador do Sistema.
							echo '<li class="dropdown">
							      	<a href="#" class="dropdown-toggle" data-toggle="dropdown">
		                              	Cadastro <span class="caret"></span>
		                          	</a>
		                		  	<ul class="dropdown-menu" role="menu">
                                      	<li><a href="../admin/listUsers.php">Usuário</a></li>
                                      	<li><a href="#">Solicitação</a></li>
                                  	</ul>
		              	      	 </li>';
							echo '<li class="dropdown">
				                  	<a href="#" class="dropdown-toggle" data-toggle="dropdown">
				                      	Relatório <span class="caret"></span>
				                  	</a>
				                  	<ul class="dropdown-menu" role="menu">
				                      	<li><a href="#">Usuário</a></li>
				                      	<li><a href="#">Solicitação</a></li>
				                  	</ul>
				              	 </li>';
							} else {
                            	// Menu do Usuário do Sistema.
								echo '<li><a href="../admin/account.php">Conta</a></li>';
							}
?>
					</ul>
            		<span width="100px" />
            		<ul class="nav navbar-nav navbar-right">
						<li>
							<a href="#">
							    Bem-vindo(a), 
							    <font color="white">
							        <b><?php echo $_SESSION['usuario']['nome_sol'];?></b>
							    </font>.
						    </a>
						</li>
              			<li>
              				<form class="navbar-form navbar-right" role="form" action="/sods/lib/logout.php">
								<button type="submit" class="btn btn-success" href="#">Sair</button>
							</form>
						</li>
            		</ul>            		
				</div><!--/.nav-collapse -->		        
			</div><!--/.container-fluid -->
		</div>