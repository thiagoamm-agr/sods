<?php 
    @session_start();
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link rel="stylesheet" type="text/css" media="screen" href="/sods/static/bootstrap/css/bootstrap.min.css" />
        <link href="/sods/static/bootstrap/css/signin.css" rel="stylesheet" />

        <style type="text/css">
            div.tooltip-inner {
                max-width: 500px;
            }
        </style>

        <script src="/sods/static/js/jquery.min.js"></script>
        <script type="text/javascript" src="/sods/static/bootstrap/js/bootstrap.min.js"></script>
        
        <title>Login</title>

        <script type="text/javascript">
            $(document).ready(function() {
                $('[data-toggle="tooltip"]').tooltip({'placement': 'bottom'});
            });
        </script>
    </head>
        <body>
            <center><h2><b>SODS</b> - Sistema de Solicitação de Desenvolvimento de Software</h2></center>
                <div class="container">
                    <form class="form-signin" role="form" method="post" id="form-signin" name="form-signin">
                        <h3 class="form-signin-heading">Login</h3>
                        <input type="text" id="login" name="login" 
                            onblur="if(this.value == '<?php echo isset($_COOKIE['login'])? $_COOKIE['login']: '' ?>')
                            senha.value = '<?php echo isset($_COOKIE['senha'])? $_COOKIE['senha']: '' ?>'"  
                            class="form-control" placeholder="Usuário" required autofocus />
                        <input type="password" id="senha" name="senha" class="form-control" 
                            placeholder="Senha" required />
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" id="lembrar" name="lembrar">
                                    <span 
                                        data-toggle="tooltip" 
                                        data-placement="bottom" 
                                        title="Habilita o login automático durante uma semana.">
                                        Mantenha-me conectado.
                                    </span>
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

    if (!isset($_COOKIE['lembrar'])) {
        if (!empty($lembrar)) {
            // Define que os cookies irão durar uma semana a partir de agora.
            $duracao = time() + 3600 * 24 * 7;
            $path = '/sods';
            $domain = null;
            $secure = false;
            $http_only = true;
            setcookie('lembrar', true, $duracao, $path, $domain, $secure, $http_only);
            setcookie('login', $login, $duracao, $path, $domain, $secure, $http_only);
            setcookie('senha', $senha, $duracao, $path, $domain, $secure, $http_only);
            setcookie('duracao', $duracao, $duracao, $path, $domain, $secure, $http_only);
            $_COOKIE['lembrar'] = true;
            $_COOKIE['login'] = $login;
            $_COOKIE['senha'] = $senha;
            $_COOKIE['duracao'] = $duracao;
        }
    }
    if (isset($_COOKIE['lembrar'])) {
        $login = $_COOKIE['login'];
        $senha = $_COOKIE['senha'];
        if (autenticar_usuario($login, $senha)) {
            header('location: /sods/admin');
        }
    } else {
        if (isset($_SESSION['usuario'])) {
            header('location: /sods/admin');
        } else if (autenticar_usuario($login, $senha)) {
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