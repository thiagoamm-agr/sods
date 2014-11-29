<?php

    @require_once $_SERVER['DOCUMENT_ROOT'] . '/sods/app/lib/util.php';

    session_start();

    // Protege o script de acesso direto.
    script_guard();

    // Exclui os cookies.
    if (isset($_COOKIE['lembrar'])) {
        $duracao = time() - 3600;
        $path = '/sods';
        $domain = null;
        $secure = false;
        $http_only = true;
        // Arquivo de cookie do navegador.
        setcookie('lembrar', '', $duracao, $path, $domain, $secure, $http_only);
        setcookie('login', '', $duracao, $path, $domain, $secure, $http_only);
        setcookie('senha', '', $duracao, $path, $domain, $secure, $http_only);
        setcookie('duracao', '', $duracao, $path, $domain, $secure, $http_only);
        // Memória.
        unset($_COOKIE['lembrar']);
        unset($_COOKIE['login']);
        unset($_COOKIE['senha']);
        unset($_COOKIE['duracao']);
    }

    // Destrói a sessão.
    session_unset();
    session_destroy();

    header('location: /sods/');
?>