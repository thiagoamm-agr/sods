<?php
@session_start ();

require_once $_SERVER ['DOCUMENT_ROOT'] . '/sods/lib/session.php';

validar_acesso ();
?>

<!DOCTYPE html>
	<html>
		<head>
			<meta charset="utf-8" />
			<link rel="stylesheet" type="text/css" href="../css/bootstrap/bootstrap.css" />
			
			<!-- estilo para esse template -->
			<link rel="stylesheet" type="text/css" href="../css/bootstrap/navbar.css"/>
			
			<script type="text/javascript">
				function logoutck() {
				    var r = confirm("Deseja sair?");
				    if (r) {
				       window.location.href = '../lib/logout.php'
				    }
				}
			</script>
			
			<title>SODS</title>
		</head>
		<body>
			
			<div class="container">

		      <!-- Static navbar -->
		      <div class="navbar navbar-default" role="navigation">
		        <div class="container-fluid">
		          <div class="navbar-header">
		            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
		              <span class="sr-only">Toggle navigation</span>
		              <span class="icon-bar"></span>
		              <span class="icon-bar"></span>
		              <span class="icon-bar"></span>
		            </button>
		            <a class="navbar-brand" href="#">SODS</a>
		          </div>
		          
		          <div class="navbar-collapse collapse">
		            <ul class="nav navbar-nav">
		              <li class="active"><a href="../admin">Página Inicial</a></li>
		              
		              <?php 
		              	if ($_SESSION['usuario']['tipo_usuario'] == 'A'){
							echo '<!-- Opção de Administrador -->
					              <li class="dropdown">
									<a href="#" class="dropdown-toggle" data-toggle="dropdown">Usuários<span class="caret"></span></a>
			                			<ul class="dropdown-menu" role="menu">
											<li><a href="../admin/account.php">Atualizar Dados</a></li>
			                  				<li><a href="../admin/addUser.php">Adicionar Usuários</a></li>
			                  				<li><a href="../admin/listUsers.php">Listar Usuários</a></li>
			                			</ul>
		              			  </li>';
						}else{
							echo '  <!-- Opção de Usuário -->
		              				<li><a href="../admin/account.php">Conta</a></li>';
						}
		              ?>		              
		              <li class="dropdown">
		                <a href="#" class="dropdown-toggle" data-toggle="dropdown">Solicitação <span class="caret"></span></a>
			                <ul class="dropdown-menu" role="menu">
			                  <li><a href="../admin/addRequest.php">Adicionar Solicitação</a></li>
			                  <li><a href="../admin/listRequests">Listar Solicitações</a></li>
			                </ul>
		              </li>
					          
		            </ul>
		            
		            
		            <ul class="nav navbar-nav navbar-right">
		              <li class="active"><a href="./">Usuário: <?php echo $_SESSION['usuario']['nome_sol'];?> </a></li>
		              <li><a href="#" onclick='logoutck();' value='LOGOUT'>Logout</a></li>
		            </ul>
		            
		          
		          </div><!--/.nav-collapse -->
		        
		        </div><!--/.container-fluid -->
		      
		      </div>
		   
		   </div> <!-- /container -->
		   
			<!-- Bootstrap core JavaScript
		    ================================================== -->
		    <!-- Placed at the end of the document so the pages load faster -->
		    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
		    <script src="../js/bootstrap/bootstrap.min.js"></script>
		</body>
	</html>