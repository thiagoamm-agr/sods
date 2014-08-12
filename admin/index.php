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
    	
    	<link rel="icon" href="../favicon.ico" />
    	
    	<!--  Bootstrap core CSS -->
		<link rel="stylesheet" type="text/css" href="../css/bootstrap/bootstrap.css" />

    	<!-- Custom styles for this template -->
    	<link rel="stylesheet" href="../css/navbar-fixed-top.css" />

		</script>
		
		<title>SODS</title>
	</head>
	<body>		
		<div class="navbar navbar-default navbar-fixed-top" role="navigation">
			<div class="container">
				<div class="navbar-header">
	            	<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
	              		<span class="sr-only">Toggle navigation</span>
	              		<span class="icon-bar"></span>
	              		<span class="icon-bar"></span>
              			<span class="icon-bar"></span>
	            	</button>
	            	<a class="navbar-brand" style="font-size: 40px;" href="#" title="Sistema para Solicitação de Desenvolvimento de Software">
	            		SODS
            		</a>
	          	</div>		          
				<div class="collapse navbar-collapse">
					<ul class="nav navbar-nav">
<?php 
		              	if ($_SESSION['usuario']['tipo_usuario'] == 'A') {
							// Menu do Administrador do Sistema.
							echo '<li class="dropdown">
								      <a href="#" class="dropdown-toggle" data-toggle="dropdown">Usuários<span class="caret"></span></a>
			                		  <ul class="dropdown-menu" role="menu">
                                          <li><a href="../admin/account.php">Atualizar Dados</a></li>
                                          <li><a href="../admin/addUser.php">Adicionar Usuários</a></li>
                                          <li><a href="../admin/listUsers.php">Listar Usuários</a></li>
                                      </ul>
		              			 </li>';
						} else {
                            // Menu do Usuário do Sistema.
							echo '<li><a href="../admin/account.php">Conta</a></li>';
						}
?>
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown">
								Solicitação <span class="caret"></span>
							</a>
							<ul class="dropdown-menu" role="menu">
								<li>
									<a href="../admin/addRequest.php">Adicionar Solicitação</a>
								</li>
		                  		<li>
		                  			<a href="../admin/listRequests.php">Listar Solicitações</a>
	                  			</li>
		                	</ul>
	              		</li>					          
	            	</ul>
	            	<span width="100px" />
	            	<ul class="nav navbar-nav navbar-right">
						<li>
							<a href="#">Usuário: <b><?php echo $_SESSION['usuario']['nome_sol'];?></b></a>
						</li>
	              		<li>
	              			<form class="navbar-form navbar-right" role="form" action="../lib/logout.php">
              					<button type="submit" class="btn btn-success" href="#">
              						Sair
								</button>
							</form>              				
              			</li>
	            	</ul>
	          	</div><!--/.nav-collapse -->		        
	        	</div><!--/.container-fluid -->		      
	      	</div>		   
		</div> <!-- /container -->
					   
		<!-- Bootstrap core JavaScript
	    ================================================== -->
	    <!-- Placed at the end of the document so the pages load faster -->
	    <script src="../js/jquery.min.js"></script>
	    <script src="../js/bootstrap/bootstrap.min.js"></script>
	</body>
</html>