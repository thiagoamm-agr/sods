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
                    <form class="form-signin" role="form" method="post" id="form-signin" name="form-signin">
                        <h3 class="form-signin-heading">Login</h3>
                        <input type="text" id="login" name="login" 
                            onblur="if(this.value == '<?php echo isset($_COOKIE['login'])? $_COOKIE['login']: '' ?>')
                            senha.value = '<?php echo isset($_COOKIE['senha'])? $_COOKIE['senha']: '' ?>'"  
                            class="form-control" placeholder="Login" required autofocus />
                        <input type="password" id="senha" name="senha" class="form-control" 
                            placeholder="Senha" required />
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" id="lembrar" name="lembrar"> Continue conectado por uma semana
                            </label>
                        </div>
                        <button class="btn btn-lg btn-primary btn-block" type="submit">
                            Entrar&nbsp;   <span class="glyphicon glyphicon-log-in"></span>
                        </button>
                    </form>
                </div> 
<?php
    @require_once $_SERVER['DOCUMENT_ROOT'] . '/sods/app/lib/auth.php';

    $login = isset($_POST['login']) ? $_POST['login'] : '';
    $senha = isset($_POST['senha']) ? $_POST['senha'] : '';
    $lembrar = isset($_POST['lembrar']) ? $_POST['lembrar'] : '';

    // Calcula tempo de armazenamento do cookie.
    $duracao_cookie = time() + 3600 * 24 * 7; // 1 semana.
    $_SESSION['duracao_cookie'] = $duracao_cookie;

    // Lembra dos dados de acesso.
    if (!empty($lembrar)) {
        // Configura os valores do cookie.
        setcookie('login', $login, $duracao_cookie);
        setcookie('senha', $senha, $duracao_cookie);
        $_COOKIE['login'] = $login;
        $_COOKIE['senha'] = $senha;
        $_SESSION['remember_me'] = true;
    } else {
        // Limpa resíduos de cookie.
        setcookie('login', '', $duracao_cookie);
        setcookie('senha', '', $duracao_cookie);
        $_COOKIE['login'] = '';
        $_COOKIE['senha'] = '';
        $_SESSION['remember_me'] = false;
    }
    if (isset($_COOKIE['login']) && isset($_COOKIE['senha'])) {
        $login = $_COOKIE['login'];
        $senha = $_COOKIE['senha'];
        if (autenticar_usuario($login, $senha)) {
            // Redireciona para a área administrativa.
            header('location: /sods/admin');
        }
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