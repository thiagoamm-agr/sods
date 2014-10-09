<?php
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
            header('location: /sods/');
        }
    }

    script_guard();
?>