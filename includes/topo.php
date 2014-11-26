<?php
    @session_start();

    @require_once $_SERVER ['DOCUMENT_ROOT'] . '/sods/app/lib/session.php';

    validar_acesso();

    date_default_timezone_set("Brazil/East");
?>
<!DOCTYPE html>
<html lang="pt-BR">
    <head>
        <meta charset="UTF-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <meta name="description" content="" />
        <meta name="author" content="" />

        <link rel="icon" href="/sods/favicon.ico" />

        <link rel="stylesheet" type="text/css" href="/sods/static/bootstrap/css/bootstrap.min.css" />
        <link rel="stylesheet" type="text/css" href="/sods/static/bootstrap/css/bootstrapValidator.min.css" />
        <link rel="stylesheet" type="text/css" href="/sods/static/css/sods.css" />
        <link rel="stylesheet" type="text/css" href="/sods/static/css/theme.default.css" />

        <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>
              <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
              <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
        
        <script src="/sods/static/js/jquery.min.js"></script>
        <script src="/sods/static/js/jquery.tablesorter.min.js"></script>
        <script src="/sods/static/js/jquery.tablesorter.widgets.min.js"></script>
        
        <script src="/sods/static/bootstrap/js/bootstrap.min.js"></script>
        <script src="/sods/static/bootstrap/js/bootstrapValidator.min.js"></script>

        <script>
            $(document).ready(function() {
                // Table sorter.
                $("table thead .nonSortable").data("sorter", false);
                $("#tablesorter").tablesorter({
                    emptyTo: 'none',
                    theme : 'default',
                    headerTemplate : '{content}{icon}',
                    widgetOptions : {
                        columns : [ "primary", "secondary", "tertiary" ]
                    }
                });
                // Tooltip.
                $('[data-toggle="tooltip"]').tooltip({'placement': 'bottom'});
            });
        </script>

        <title>SODS</title>
    </head>
    <body>
        <div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
            <div class="container">
                <div class="navbar-header">
                    <button 
                        type="button" 
                        class="navbar-toggle" 
                        data-toggle="collapse" 
                        data-target=".navbar-collapse">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" 
                        style="font-size: 40px; color: white;" 
                        href="/sods/admin/" 
                        title="SISTEMA PARA SOLICITAÇÃO DE DESENVOLVIMENTO DE SOFTWARE"
                        data-toggle="tooltip" 
                        data-placement="bottom">SODS
                   </a>
                </div>
                <div class="collapse navbar-collapse">
                    <ul class="nav navbar-nav">
                        <li class="dropdown">
                            <a class="dropdown-toggle" href="/sods/admin/">
                                <span style="color:white" class="glyphicon glyphicon-home"></span>&nbsp;
                                <b>Início</b>
                            </a>
                        </li>
<?php 
                    if ($_SESSION['usuario']['perfil'] == 'A') {
                        // Menu do Administrador do Sistema.
?>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <span class="glyphicon glyphicon-file" style="color:white"></span>&nbsp;
                                <b>Cadastros</b> <span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu" role="menu">
                                <li>
                                    <a href="/sods/admin/lotacoes/">
                                        <span class="glyphicon glyphicon-file" style="color:black"></span>
                                        Cadastro de Lotações
                                    </a>
                                </li>
                                <li>
                                    <a href="/sods/admin/usuarios/">
                                        <span class="glyphicon glyphicon-file" style="color:black"></span>
                                        Cadastro de Usuários
                                    </a>
                                </li>
                                <li>
                                    <a href="/sods/admin/solicitacoes/">
                                        <span class="glyphicon glyphicon-file" style="color:black"></span>
                                        Cadastro de Solicitações
                                    </a>
                                </li>
                                <li>
                                    <a href="/sods/admin/tiposSolicitacao/">
                                        <span class="glyphicon glyphicon-file" style="color:black"></span>
                                        Cadastro de Tipos de Solicitação
                                    </a>
                                </li>
                            </ul>
                        </li>
<?php
                    } else {
                        // Menu do Usuário do Sistema.
?>
                        <li>
                            <a href="/sods/admin/solicitacoes/">
                                <span class="glyphicon glyphicon-file" style="color:white"></span>
                                <b>Solicitações</b>
                            </a>
                        </li>
<?php 
                    }
?>
                        <li class="dropdown">
                            <a class="dropdown-toggle" href="/sods/admin/sobre.php">
                                <span class="glyphicon glyphicon-info-sign" style="color:white;" ></span>&nbsp;
                                <b>Sobre</b>
                            </a>
                        </li>
                    </ul>
                    <span style="width: 100px"></span>
                    <ul class="nav navbar-nav navbar-right">
                        <li>
                            <a href="/sods/admin/informacoes_pessoais.php" title="Informações pessoais" data-toggle="tooltip" 
                                data-placement="bottom">
                                <strong>Bem-vindo(a),  &nbsp;</strong> 
                                <font color="white">
                                <span class="glyphicon glyphicon-user"></span>
                                    <b><span id="usuario_nome"><?php echo $_SESSION['usuario']['login'] ?></span></b>
                                </font>
                            </a>
                        </li>
                          <li>
                              <form 
                                  class="navbar-form navbar-right" 
                                  role="form" 
                                  action="/sods/app/lib/logout.php">
                                <button 
                                    id="btnSair" 
                                    type="submit" 
                                    class="btn btn-success btn-sm pull-right">
                                    <strong>Sair&nbsp; <span class="glyphicon glyphicon-log-out"></span></strong>
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
                <!--/.nav-collapse -->
            </div>
            <!--/.container-fluid -->
        </div>

