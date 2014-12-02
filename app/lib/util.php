<?php

    @session_start();

    // returns true if $str begins with $sub
    function startsWith($str, $sub) {
        return (substr($str, 0, strlen($sub)) == $sub);
    }

    // return tru if $str ends with $sub
    function endsWith($str, $sub) {
        return (substr($str, strlen($str) - strlen($sub)) == $sub);
    }

    // Restringe o acesso direto (via url) aos arquivos nos diretórios app e includes.
    function script_guard() {
        $uri = $_SERVER['REQUEST_URI'];
        if (startsWith($uri, '/sods/app') || startswith($uri, '/sods/includes')) {
            header('location: /sods/404.html');
        }
        $tipo_usuario = $_SESSION['usuario']['tipo_usuario'];
        if ($tipo_usuario == 'U') {
            switch ($uri) {
                case '/sods/admin/lotacoes/':
                case '/sods/admin/lotacoes/index.php':
                case '/sods/admin/usuarios/':
                case '/sods/admin/usuarios/index.php':
                case '/sods/admin/tipos_solicitacao/':
                case '/sods/admin/tipos_solicitacao/index.php':
                    header('location: /sods/404.html');
                    break;
            }
        }
    }

    /**
     * jsonpp - Pretty print JSON data
     *
     * In versions of PHP < 5.4.x, the json_encode() function does not yet provide a
     * pretty-print option. In lieu of forgoing the feature, an additional call can
     * be made to this function, passing in JSON text, and (optionally) a string to
     * be used for indentation.
     *
     * @param string $json The JSON data, pre-encoded
     * @param string $istr The indentation string
     *
     * @link https://github.com/ryanuber/projects/blob/master/PHP/JSON/jsonpp.php
     *
     * @return string
     */
     function jsonpp($json, $istr=' ') {
        $result = '';
        for ($p = $q = $i = 0; isset($json[$p]); $p++) {
            $json[$p] == '"' && ($p > 0 ? $json[$p - 1]: '') != '\\' && $q = !$q;
            if (!$q && strchr(" \t\n\r", $json[$p])) { 
                continue; 
            }
            if (strchr('}]', $json[$p]) && !$q && $i--) {
                strchr('{[', $json[$p - 1]) || $result .= "\n".str_repeat($istr, $i);
            }
            $result .= $json[$p];
            if (strchr(',{[', $json[$p]) && !$q) {
                $i += strchr('{[', $json[$p]) === FALSE ? 0 : 1;
                strchr('}]', $json[$p + 1]) || $result .= "\n".str_repeat($istr, $i);
            }
        }
        return $result;
    }

    script_guard();
?>