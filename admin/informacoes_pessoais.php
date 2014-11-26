<?php

    // Models
    @include $_SERVER['DOCUMENT_ROOT'] . '/sods/app/models/Usuario.php';
    @include $_SERVER['DOCUMENT_ROOT'] . '/sods/app/models/Lotacao.php';

    // Controllers
    @include $_SERVER['DOCUMENT_ROOT'] . '/sods/app/controllers/UsuariosController.php';
    @include $_SERVER['DOCUMENT_ROOT'] . '/sods/app/controllers/LotacoesController.php';

    $controller = new UsuariosController();

    //Identificação da resposta do usuario
    if (isset($_POST['action'])) {
        //Recuperação dos parâmetros
        $action = $_POST['action'];
        if (isset($_POST['json'])) {
            $json = $_POST['json'];
            if (!empty($json)){
                $usuario = new Usuario();
                $usuario->loadJSON($json);
            }
        }
        switch ($action) {
            case 'save':
                $controller->edit($usuario);
                $_SESSION['usuario'] = $controller->getUsuario('s.id', $_SESSION['usuario']['id']);
                exit();
                break;
            case 'load':
                echo $controller->getInformacoesPessoais($_SESSION['usuario']['id']);
                exit();
                break;
        }
    }
?>
<?php
    @include $_SERVER['DOCUMENT_ROOT'] . '/sods/includes/topo.php';
?>
    <!-- Javascript -->
    <script type="text/javascript" src="/sods/static/js/models/Usuario.js"></script>
    <script type="text/javascript" src="/sods/static/js/validators/InformacoesPessoaisFormValidator.js"></script>
    <script type="text/javascript" src="/sods/static/js/maskedInput.js"></script>

    <script type="text/javascript">
        var form = null;
        var formValidator = null;
        var action = null;

        function load() {
            $.ajax({
                type: 'post',
                url: '/sods/admin/informacoes_pessoais.php',
                dataType: 'text',
                cache: false,
                timeout: 70000,
                async: true,
                data: {
                    action: 'load'
                },
                success: function(data, status, xhr) {
                    console.log(data);
                    if (data == 'ERRO') {
                        $('#alert-upd').modal('show');
                        window.setTimeout(function() {
                            $('#alert-upd').modal('hide');
                        }, 3000);
                    } else {
                        $('#grid').html(data);
                    }
                },
                error: function(xhr, status, error) {
                    console.log(error);
                },
                complete: function(xhr, status) {
                    console.log('A requisição foi completada.');
                    config();
                }
            });
            return false;
        }

        function save() {
            var id = $('#id', form).val();
            id = id == null ? '' : id;
            if (id != '') {
                var usuario = new Usuario();
                var json = null;
                // Variáveis escondidas no formulário
                usuario.id = id;
                usuario.status = $('#status', form).val();
                usuario.data_criacao = $('#data_criacao', form).val();
                usuario.data_alteracao = $('#data_alteracao', form).val();
                usuario.perfil = $('#perfil', form).val();
                // Variáveis visíveis no formulário
                usuario.nome = $('#nome', form).val();
                usuario.lotacao_id = $('#lotacao_id', form).val();
                usuario.funcao = $('#funcao', form).val();
                usuario.telefone = $('#telefone', form).val();
                usuario.email = $('#email', form).val();
                usuario.login = $('#login', form).val();
                usuario.senha = $('#senha', form).val();

                json = usuario.toJSON();
                // Requisição AJAX
                $.ajax({
                    type: 'post',
                    url: '/sods/admin/informacoes_pessoais.php',
                    dataType: 'text',
                    cache: false,
                    timeout: 70000,
                    async: true,
                    data: {
                        'action': 'save',
                        'json': json
                    },
                    success: function(data, status, xhr) {
                        console.log(data);
                        // Mostra mensagem de operação bem sucedida.
                        $('#alert-upd').modal('show');
                        window.setTimeout(function() {
                            $('#alert-upd').modal('hide');
                        }, 3000);
                        // Carrega o formulário.
                        load();
                    },
                    error: function(xhr, status, error) {
                        console.log(error);
                    },
                    complete: function(xhr, status) {
                        console.log('A requisição foi completada.');
                    }
                });
            }
            return false;
        }

        function reload() {
            if (formValidator != null) {
                formValidator.reset();
            }
        }

        // Configura o formulário.
        function config() {
            // Associando um validador ao formulário.
            form = $('#form');
            formValidator = new InformacoesPessoaisFormValidator(form);
            $('#form').submit(function(event) {
                event.preventDefault();
                save();
            });
            // Máscara telefone.
            var telefone = $('#telefone');
            var mascara = "(99) 9999-9999?9";
            if (telefone.val().length > 10) {
                telefone.mask("(99) 99999-999?9");
            }
            telefone.mask(mascara);
        }

        $(document).ready(function() {
            config();
        });
    </script>

    <!-- Container -->
    <div class="container">
        <!-- Formulário -->
<?php
    echo $controller->getInformacoesPessoais($_SESSION['usuario']['id']);
?>
        <!-- /Formulário -->

        <!-- Alertas -->
        <div class="modal fade" id="alert-upd" tabindex="-1" role="dialog" aria-labelledby="modal-del" 
            aria-hidden="true">
            <div class="alert alert-success fade in" role="alert">
                <button type="button" class="close" onclick="$('#alert-upd').modal('toggle');">
                    <span aria-hidden="true">&times;</span><span class="sr-only">Fechar</span>
                </button><span id="alert-msg">Dados atualizados com sucesso.</span>
           </div>
        </div>
        <!-- /Alertas -->

    </div>
    <!-- /Container -->
<?php
    @include $_SERVER ['DOCUMENT_ROOT'] . '/sods/includes/rodape.php';
?>