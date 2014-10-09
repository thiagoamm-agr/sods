<?php 

    @require_once $_SERVER['DOCUMENT_ROOT'] . '/sods/app/lib/util.php';

    script_guard();

    function get_db_connection($host='10.243.1.14', $user='sods', $password='dev123') {
        $conexao = null;
        if (isset($host) && isset($user) && isset($password)) {
            # Criando a conexão
            $conexao = mysql_connect($host, $user, $password, true);
            if (!isset($conexao)) {
                die("Não foi possível conectar ao servidor de banco de dados:" . mysql_error());
            }
            # Selecionando o banco de dados
            mysql_select_db('sods_development');
            # Informando o charset utilizado.
            mysql_set_charset('UTF8', $conexao);
        }
        return $conexao;
    }
?>