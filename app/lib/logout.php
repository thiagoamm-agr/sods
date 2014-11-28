<?php

    @require_once $_SERVER['DOCUMENT_ROOT'] . '/sods/app/lib/util.php';

    session_start();

    // Protege o script de acesso direto.
    script_guard();

    // Destruindo a sessão e os cookies.
    if (isset($_COOKIE[session_name()])) {
        setcookie(session_name(), '');
    }

    if (isset($_COOKIE['login'])) {
        setcookie('login', '');
    }

    if (isset($_COOKIE['senha'])) {
        setcookie('senha', '');
    }

    session_unset();
    session_destroy();

    header('location: /sods/index.php');
?>