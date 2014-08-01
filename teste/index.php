<?php
    @session_start();
    
    require_once $_SERVER['DOCUMENT_ROOT'] . '/sods/lib/session.php';
    
    validar_acesso();
?>
<meta charset="utf-8" />
<?php
    echo $_SESSION['usuario']['nome'];
?>
