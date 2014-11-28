<?php

    @require_once $_SERVER['DOCUMENT_ROOT'] . '/sods/app/lib/util.php';

    // Protege o script de acesso direto.
    script_guard();

    function validar_acesso() {
        // Validação do login.
        if (!isset($_SESSION['usuario'])) {
            session_destroy();
            header('location: /sods');
        }
        // Validação da duração da sessão.
        if (!isset($_SESSION['end_time']) || time() >= $_SESSION['end_time']) {
            if ($_SESSION['remember_me']) {
                setcookie('login', '', $_SESSION['end_time']);
                setcookie('senha', '', $_SESSION['end_time']);
            }
            session_destroy();
            header('location: /sods');
        } else {
            if (!$_SESSION['remember_me']) {
                $_SESSION['end_time'] = time() + $_SESSION['timeout'];
            }
        }
    }
?>