<?php 
    @session_start();
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <link rel="stylesheet" type="text/css" media="screen" href="css/bootstrap/bootstrap.css" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
    	<meta name="viewport" content="width=device-width, initial-scale=1">	
	    
	    <!-- Estilo para esse template -->
	    <link href="css/bootstrap/signin.css" rel="stylesheet" />
	    
	    <title>Login</title>
    </head>
    	<body>
        	<center><h2><b>SODS</b> - Sistema de Solicitação de Desenvolvimento de Software</h2></center>
        		<div class="container">
			      <form class="form-signin" role="form" method="post" id="formlogin" name="formlogin">
			        <h3 class="form-signin-heading">Login</h3>
			        <input type="login" id="login" name="login" class="form-control" placeholder="Login" 
			            required autofocus />
			        <input type="password" id="senha" name="senha" class="form-control" placeholder="Senha" 
			            required />
			        <div class="checkbox">
			          <label>
			            <input type="checkbox" value="remember-me"> Lembre-me
			          </label>
			        </div>
			        <button class="btn btn-lg btn-primary btn-block" type="submit">Entrar</button>
			      </form>
    			</div> 
<?php
    @require_once $_SERVER['DOCUMENT_ROOT'] . '/sods/app/lib/auth.php';     
            
    @$login = $_POST['login'];
    @$senha = $_POST['senha'];

    if (autenticar_usuario($login, $senha)) {        
        header('location: /sods/admin');
    }
?>
    </body>
</html>