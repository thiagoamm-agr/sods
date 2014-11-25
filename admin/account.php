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
            case 'edit':
                $controller->edit($usuario);
                $_SESSION['usuario'] = $controller->getUsuario('s.id', $_SESSION['usuario']['id']);
                exit();
                break;
            case 'list':
                echo $controller->getForm($_SESSION['usuario']['id']);
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
    <script type="text/javascript" src="/sods/static/js/validators/ContaFormValidator.js"></script>
    <script type="text/javascript" src="/sods/static/js/md5.js"></script>
    <script type="text/javascript" src="/sods/static/js/maskedInput.js"></script>
    <script type="text/javascript">
        var form = null;
        var formValidator = null;
        var resposta = null;
        var action = null;

        function list() {
            $.ajax({
                type: 'post',
                url: '/sods/admin/account.php',
                dataType: 'text',
                cache: false,
                timeout: 70000,
                async: true,
                data: {
                    action: 'list'
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
                        var usuario_nome = $(data).find('#nome').val();
                        $('#usuario_nome').text(usuario_nome);
                    }
                },
                error: function(xhr, status, error) {
                    console.log(error);
                },
                complete: function(xhr, status) {
                    //console.log('A requisição foi completada.');
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
                    url: '/sods/admin/account.php',
                    dataType: 'text',
                    cache: false,
                    timeout: 70000,
                    async: true,
                    data: {
                        'action': 'edit',
                        'json': json
                    },
                    success: function(data, status, xhr) {
                        console.log(data);
                        // Mostra mensagem de operação bem sucedida.
                        $('#alert-upd').modal('show');
                        window.setTimeout(function() {
                            $('#alert-upd').modal('hide');
                        }, 3000);
                        // Recarrega a grid.
                        list();
                    },
                    error: function(xhr, status, error) {
                        console.log(error);
                    },
                    complete: function(xhr, status) {
                        //console.log('A requisição foi completada.');
                    }
                });
            }
            return false;
        }

        function resetForm() {
            if (formValidator != null) {
                formValidator.reset();
            }
        }

        $(document).ready(function() {
            // Associando um validador ao formulário.
            form = $('#form-edit');
            formValidator = new ContaFormValidator(form);
            $('#form-edit').submit(function(event) {
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
        });
    </script>
        
    <div class="container">
        <form role="form" id="form-edit" method="post">
            <div id="grid" class="table-responsive">
                <div>
                    <h2>Informações Pessoais</h2>
                </div>
                <div class="row">
                    <div class="col-md-12">&nbsp;</div>
                </div>
<?php 
                $usuario = $controller->getUsuario('s.id', $_SESSION['usuario']['id']);
?>
                <div class="form-group">
                    <label for="nome">Nome</label>
                    <input type="text" id="nome" name="nome" class="form-control" 
                           value="<?php echo $usuario['nome'] ?>" />
                </div>
                <div class="form-group">
                    <label for="lotacao">Lotação</label>
                    <select id="lotacao_id" name="lotacao_id" class="form-control">
<?php
                        $lotacoesController = new LotacoesController();
                        $lotacoes = $lotacoesController->_list();
                        foreach ($lotacoes as $lotacao){
?>
                            <option value="<?php echo $lotacao['id'] ?>"><?php echo trim($lotacao['nome']) ?></option>
<?php
                        } 
?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="funcao">Função</label>
                    <input type="text" id="funcao" name="funcao" class="form-control" 
                           value="<?php echo $usuario['funcao']?>" />
                </div>
                <div class="form-group">
                       <label for="telefone">Telefone</label>
                       <input type="text" id="telefone" name="telefone" class="form-control" 
                              value="<?php echo $usuario['telefone']?>"/>
                </div>
                <div class="form-group">
                    <label for="email">E-mail</label>
                    <input type="email" id="email" name="email" class="form-control" 
                           value="<?php echo $usuario['email']?>"/>
                </div>
                <div class="form-group">
                    <label for="login">Login</label>
                    <input type="text" id="login" name="login" class="form-control" 
                           value="<?php echo $usuario['login']?>" readonly />
                </div>
                <div class="form-group">
                    <label for="senha">Senha</label>
                    <input type="password" id="senha" name="senha" class="form-control"
                           value="<?php echo $usuario['senha']?>" />
                </div>
                <div class="form-group">
                    <label for="confirma_senha"> Confirme a senha</label>
                    <input type="password" id="confirma_senha" name="confirma_senha" class="form-control"
                           value="<?php echo $usuario['senha']?>"/>
                </div>
                <div class="form-group">
                    <input type="hidden" 
                           id="id" 
                           name="id" value="<?php echo $usuario['id']?>"/>
                    <input type="hidden" 
                           id="status" 
                           name="status" value="<?php echo $usuario['status']?>"/>
                    <input type="hidden" 
                           id="data_criacao" 
                           name="data_criacao" value="<?php echo $usuario['data_criacao']?>"/>
                    <input type="hidden" 
                           id="data_alteracao" 
                           name="data_alteracao" value="<?php echo $usuario['data_alteracao']?>"/>
                    <input type="hidden" 
                           id="perfil" 
                           name="perfil" value="<?php echo $usuario['perfil']?>"/>
                </div>
                <div class="btn-toolbar pull-right form-footer">
                    <button type="submit" class="btn btn-success">
                        Salvar &nbsp;<span class="glyphicon glyphicon-floppy-save"></span>
                    </button>
                    <button type="reset" class="btn btn-default" onclick="resetForm()">
                        Resetar &nbsp;<span class="glyphicon glyphicon-refresh" style="color:black">
                    </button>
                </div>
                
            </div>
        </form>
        <!-- Alertas -->
        <div class="modal fade" id="alert-upd" tabindex="-1" role="dialog" aria-labelledby="modal-del" 
            aria-hidden="true">
            <div class="alert alert-success fade in" role="alert">
                <button type="button" class="close" onclick="$('#alert-upd').modal('toggle');">
                    <span aria-hidden="true">&times;</span><span class="sr-only">Fechar</span>
                </button><span id="alert-msg">Dados atualizados com sucesso.</span>
           </div>
        </div><!-- Alertas -->
    </div> <!-- Container -->
<?php
    @include $_SERVER ['DOCUMENT_ROOT'] . '/sods/includes/rodape.php';
?>