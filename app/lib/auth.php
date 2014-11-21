<?php

    @require_once $_SERVER['DOCUMENT_ROOT'] . '/sods/app/lib/db.php';

    function autenticar_usuario($login, $senha) {
        $login = isset($login) ? trim($login) : '';
        $senha = isset($senha) ? trim($senha) : '';
        if ($login !== '' && $senha !== '') {
            $conexao = get_db_connection("10.243.1.14", "sods", "dev123");
            $query = "" . 
                "select\n" .
                    "\ts.id, s.nome, l.nome as nome_lotacao,\n" . 
                    "\tl.id as id_lotacao, s.cargo, s.telefone, s.email,\n" . 
                    "\ts.login, s.senha, s.tipo_usuario, s.status,\n" . 
                    "\ts.data_criacao, s.data_alteracao\n" . 
                "from\n" .
                    "\tsolicitante as s\n" . 
                "inner join lotacao as l\n" . 
                    "\ton s.lotacao_id = l.id and\n" . 
                    "\ts.login = '$login' and\n" . 
                    "\ts.senha = md5('$senha') and \n" . 
                    "\ts.status <> 'I';";
            $result = mysql_query($query);
            if (mysql_num_rows($result) > 0) {
                $_SESSION['usuario'] = mysql_fetch_array($result);
                $_SESSION['conexao'] = $conexao;
                $_SESSION['start_time'] = time();
                // 900 segundos ou 15 minutos.
                $_SESSION['timeout'] = 900; 
                $_SESSION['end_time'] = $_SESSION['start_time'] + $_SESSION['timeout'];
                return true;
            }
        }
        return false;
    }
?>