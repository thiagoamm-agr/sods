<?php
    function validar_acesso() {
        if (!isset($_SESSION['usuario'])) {
            session_destroy();
            header('location: /sods');
        }        
        if (!isset($_SESSION['end_time']) || time() >= $_SESSION['end_time']) {
            session_destroy();
            header('location: /sods');
        } else {
            $_SESSION['end_time'] = time() + $_SESSION['timeout'];
        }
    }
?>