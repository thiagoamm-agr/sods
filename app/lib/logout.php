<?php

    @require_once $_SERVER['DOCUMENT_ROOT'] . '/sods/app/lib/util.php';

    // Protege o script de acesso direto.
    script_guard();

    session_start();
    session_unset();
    session_destroy();

    // Removendo cookie.
    setcookie('login', '', time() - 3600);
    setcookie('senha', '', time() - 3600);

    unset($_COOKIE['login']);
    unset($_COOKIE['senha']);

    header('location: /sods/index.php');
?>