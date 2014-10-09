<?php

    @require_once $_SERVER['DOCUMENT_ROOT'] . '/sods/app/lib/util.php';

    // Protege o script de acesso direto.
    script_guard();

    session_start();
    session_unset();
    session_destroy();

    header('location: /sods/index.php');
?>