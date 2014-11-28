<?php 
    @session_start();
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <link rel="stylesheet" type="text/css" media="screen" href="/sods/static/bootstrap/css/bootstrap.min.css" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- Estilo para esse template -->
        <link href="/sods/static/bootstrap/css/signin.css" rel="stylesheet" />

        <title>Login</title>
    </head>
        <body>
            <center><h2><b>SODS</b> - Sistema de Solicitação de Desenvolvimento de Software</h2></center>
                <div class="container">
                  <form class="form-signin" role="form" method="post" id="formlogin" name="formlogin">
                    <h3 class="form-signin-heading">Login</h3>
                    <input type="text" name="login" 
                        onblur="if(this.value == '<?php echo isset($_COOKIE['login'])? $_COOKIE['login']:"" ?>')
                        senha.value = '<?php echo isset($_COOKIE['senha'])? $_COOKIE['senha']:"" ?>'"  
                        class="form-control" placeholder="Login" required autofocus />  
                    <input type="password" id="senha" name="senha" class="form-control" 
                        placeholder="Senha" required />
                    <div class="checkbox">
                      <label>
                        <input type="checkbox" id="lembrar" name="lembrar"> Lembrar por 1 semana
                      </label>
                    </div>
                    <button class="btn btn-lg btn-primary btn-block" type="submit">
                    Entrar&nbsp;   <span class="glyphicon glyphicon-log-in"></span></button>
                  </form>
                </div> 
<?php
    @require_once $_SERVER['DOCUMENT_ROOT'] . '/sods/app/lib/auth.php';

    @$login = $_POST['login'];
    @$senha = $_POST['senha'];

    if (isset($_COOKIE['login']) && isset($_COOKIE['senha'])) {
        header('location: /sods/admin');
        @$login = $_COOKIE['login'];
        @$senha = $_COOKIE['senha'];
        autenticar_usuario($login, $senha);
    } else {
        if (autenticar_usuario($login, $senha)) {
            header('location: /sods/admin');
        } else if (isset($_POST['login']) && isset($_POST['senha'])) {
?>
            <div class='alert alert-danger' role='alert'>
                <center><b>Login inválido&nbsp;<span class="glyphicon glyphicon-remove"></span></b></center>
            </div>
<?php
        }
    }
?>
    </body>
</html>