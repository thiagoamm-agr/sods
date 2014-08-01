<?php
    @session_start();
    
    require_once $_SERVER['DOCUMENT_ROOT'] . '/sods/lib/session.php';
    
    validar_acesso();
?>
<meta charset="utf-8" />
<h1>Área Administrativa</h1>
<?php
    echo '<h4>Login: ' . $_SESSION['usuario']['login'] . '</h4>';    
?>