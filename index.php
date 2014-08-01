<?php 
    @session_start();
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <link rel="stylesheet" type="text/css" media="screen" href="css/normalize.css" />
        <style type="text/css">
            h1 {
                text-align: center;
            }

            body {
                background-color: #DDD;
                font-size: 12pt;
                font-family: Arial;
            }

            form {
                margin: 8% auto 8%;
                width: 13%;
            }

            legend {
                font-weight: bold;
                font-size: 14pt;
            }

            fieldset {
                padding-left: 15px;
            }

            input[type="text"] {
                width: auto;
            }

            p > input[type="submit"] {
                float: right;
                /*margin-right: 4px;*/
            }
        </style>
        <title>SODS - Sistema de Solicitação de Desenvolvimento de Software</title>
    </head>
    <body>
        <h1>SODS - Sistema de Solicitação de Desenvolvimento de Software</h1>
        <form id="form_login" name="form_login" method="POST">
            <fieldset>
                <legend>SODS</legend>
                <p>
                    <label for="login">Login:</label>
                    <input id="login" name="login" type="text" />
                </p>
                <p>
                    <label for="senha">Senha:</label>
                    <input id="senha" name="senha" type="password" />
                </p>
                <p>
                    <input type="submit" value="Entrar" />
                </p>
            </fieldset>
        </form>
<?php
    require_once $_SERVER['DOCUMENT_ROOT'] . '/sods/lib/user.php';
            
    $login = $_POST['login'];
    $senha = $_POST['senha'];

    if (autenticar_usuario($login, $senha)) {        
        header('location: /sods/admin');
    }
?>
    </body>
</html>