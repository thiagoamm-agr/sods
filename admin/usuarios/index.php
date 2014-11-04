<?php
    /* CADASTRO DE USUÁRIOS */

    @include $_SERVER['DOCUMENT_ROOT'] . '/sods/includes/topo.php';

    // Models
    @include $_SERVER['DOCUMENT_ROOT'] . '/sods/app/models/Lotacao.php';
    @include $_SERVER['DOCUMENT_ROOT'] . '/sods/app/models/Usuario.php';

    // Controllers
    @include $_SERVER['DOCUMENT_ROOT'] . '/sods/app/controllers/LotacoesController.php';
    @include $_SERVER['DOCUMENT_ROOT'] . '/sods/app/controllers/UsuariosController.php';
?>
        <div class="container">
            <h2>Usuários</h2>
            <div class="row">
                <div class="col-md-12">
                    <button class="btn btn-primary btn-sm pull-right" 
                        data-toggle="modal" 
                        data-target="#modal-add"
                        onclick="add()">
                        <b>Adicionar</b>
                    </button>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">&nbsp;</div>
            </div>
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-condensed tablesorter"
                       id="tablesorter">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nome</th>
                            <th>Lotação</th>
                            <th>Cargo</th>
                            <th>Telefone/Ramal</th>
                            <th>Tipo de Usuário</th>
                            <th>Status</th>
                            <th>Login</th>
                            <th class="nonSortable">Ação</th>
                        </tr>
                    </thead>
                    <tbody>
<?php
                    $controller = new UsuariosController();
                    
                    if (isset($_GET['p'])) {
                    	$page = (int) $_GET['p'];
                    } else {
                    	$page = '';
                    }
                    
                    if (!(empty($controller->getPage()))) {

                    	foreach ($controller->getPage($page) as $usuario) {
?>
	                        <tr>
	                            <td><?php echo $usuario['id'] ?></td>
	                            <td><?php echo $usuario['nome_sol'] ?></td>
	                            <td><?php echo $usuario['lotacao'] ?></td>
	                            <td><?php echo $usuario['cargo'] ?></td>
	                            <td><?php echo $usuario['telefone'] ?></td>
	                            <td>
<?php
                                     if ($usuario['tipo_usuario'] == "A") {
                                     	echo "Administrador";
                                     } else {
                                     	echo "Usuário";
                                     }
?>	                            
	                            </td>
	                            <td>
<?php
                                     if ($usuario['status'] == "A") {
                                     	echo "Ativo";
                                     } else {
                                     	echo "<font color='#FF0000'>Inativo</font>";
                                     }

?>
                                </td>
	                            <td><?php echo $usuario['login'] ?></td>
	                            <td colspan="2">
	                                <button class="btn btn-warning btn-sm" 
	                                    data-toggle="modal" data-target="#modal-edit" 
	                                    onclick='edit(<?php echo json_encode($usuario)?>)'>
	                                    <strong>Editar</strong>
	                                </button>
	                                <button class="btn btn-danger btn-sm" 
	                                        data-toggle="modal" 
	                                        data-target="#modal-del"
	                                        onclick='del(<?php echo json_encode($usuario)?>)'>
	                                    <strong>Excluir</strong>
	                                </button>
	                            </td>
	                        </tr>
<?php
                    	}
?>
                    </tbody>
<?php 
                        if ($controller->count() > 10) {
?>                    
                    <tfoot>
						<tr><td colspan="10"><?php echo $controller->paginator ?></td></tr>
                    </tfoot>
<?php 
                        }
?>
                </table>
<?php 
                    } else {
?> 
                  	</tbody>
                </table>
                <div class='alert alert-danger' role='alert'>
                	<center><b>Não há registros de solicitações.</b></center>
                </div>
<?php 
                    }              
?>
            </div>

            <!--  Modais -->
            
            <!-- Editar Usuário -->
            <div class="modal fade" id="modal-edit" tabindex="-1" role="dialog" aria-labelledby="modal-edit" 
                aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                                &times;
                            </button>
                            <h3 class="modal-title" id="modal-edit">Editar Usuário</h3>
                        </div>
                        <div class="modal-body">
                            <form id="form-edit" role="form" action="/sods/admin/usuarios/" method="post">
                                  <div class="form-group">
                                    <label for="nome">Nome</label>
                                    <input id="nome" name="nome" type="text" class="form-control" maxlength="255" />
                                  </div>
                                  <div class="form-group">
                                      <label for="login">Login</label>
                                      <input id="login" name="login" type="text" class="form-control" maxlength="50"/>
                                  </div>
                                <div class="form-group">
                                    <label for="lotacao">Lotação</label>
                                      <select id="lotacao" name="lotacao" class="form-control">
<?php 
                                        $lotacoesController = new LotacoesController();
                                        
                                        $lotacoes = $lotacoesController->_list();
                                        
                                        foreach ($lotacoes as $lotacao){
?>
                                            <option value="<?php echo $lotacao['id'] ?>">
                                                <?php echo trim($lotacao['nome']) ?>
                                            </option>
<?php
                                        } 
?>
                                    </select>
                                  </div>
                                  <div class="form-group">
                                    <label for="cargo">Cargo</label>
                                    <input id="cargo" name="cargo" type="text" class="form-control" maxlength="255"/>
                                  </div>          
                                <div class="form-group">
                                    <label for="fone">Telefone / Ramal</label>
                                    <input id="fone" name="fone" type="text" class="form-control" maxlength="30" />
                                  </div>
                                <div class="form-group">
                                    <label for="email">E-mail</label>
                                    <input id="email" name="email" type="email" class="form-control" maxlength="100"/>
                                  </div>
                                <div class="form-group">
                                    <div>
                                        <label for="tipo_usuario">Tipo</label>
                                    </div>
                                    <div class="form-group">
                                        <input type="radio" 
                                               id="tipo_usuario_u"
                                               name="tipo_usuario"
                                               value="U"/>Usuário
                                        &nbsp;&nbsp;
                                        <input type="radio"
                                               id="tipo_usuario_a"
                                               name="tipo_usuario" 
                                               value="A"/>Administrador
                                    </div>
                                </div>
                                 <div class="form-group">
                                    <div>
                                        <label for="status">Status</label>
                                    </div>
                                    <div class="form-group">
                                        <input type="checkbox" 
                                        	   id="statusEdit" 
                                        	   name="statusEdit" 
                                        	   value="A" 
                                        	   checked="checked"/>Ativar &nbsp;
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" 
                                            class="btn btn-success" 
                                            onclick="save()">Salvar</button>
                                    <button type="reset" 
                                            class="btn btn-default" 
                                            onclick="limpar()">Limpar</button>
                                    <button type="button" class="btn btn-primary" data-dismiss="modal">Cancelar</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>            
            
            <!-- Excluir Usuário -->
            <div class="modal fade .bs-example-modal-sm" id="modal-del" tabindex="-1" role="dialog" 
                aria-labelledby="modal-del" aria-hidden="true">
                <div class="modal-dialog modal-sm">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                                &times;
                            </button>
                            <h4 class="modal-title" id="modal-del">Exclusão de Usuário</h4>
                        </div>
                        <div class="modal-body">
                            <h5>Confirma exclusão de usuário?</h5>
                        </div>
                        <form id="form-del" action="/sods/admin/usuarios" role="form" method="post">
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-danger" onclick="save()">Sim</button>
                                <button type="button" class="btn btn-primary" data-dismiss="modal">Não</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div> 
            
            <!-- Adicionar Usuário -->
            <div class="modal fade" id="modal-add" tabindex="-1" role="dialog" 
                aria-labelledby="modal-add" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                                &times;
                            </button>
                            <h3 class="modal-title" id="modal-add">Adicionar Usuário</h3>
                        </div>
                        <div class="modal-body">
                             <form id="form-add" action="/sods/admin/usuarios/" role="form" method="post">
                                <div class="form-group">
                                    <label for="nome">Nome</label>
                                    <input type="text" class="form-control" name="nome" id="nome" maxlength="255"/>
                                </div>
                                <div class="form-group">
                                    <label for="lotacao">Lotação</label>
                                    <select id="lotacao" name="lotacao" class="form-control">
<?php
                                        foreach ($lotacoes as $lotacao){
?>
                                            <option value="<?php echo $lotacao['id'] ?>">
                                                <?php echo $lotacao['nome']?>
                                            </option>
<?php
                                        } 
?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="cargo">Cargo</label>
                                    <input type="text" class="form-control" name="cargo" id="cargo" maxlength="255"/>
                                </div>
                                <div class="form-group">
                                    <label for="fone_ramal">Telefone / Ramal</label>
                                    <input type="text" class="form-control" name="fone" id="fone"/>
                                </div>
                                <div class="form-group">
                                    <label for="email">E-mail</label>
                                    <input type="text" class="form-control" name="email" id="email" maxlength="100"/>
                                </div>
                                <div class="form-group">
                                    <label for="login">Login</label>
                                    <input type="text" class="form-control" name="login" id="login" maxlength="50"/>
                                </div>
                                <div class="form-group">
                                    <input type="hidden" 
                                        class="form-control" 
                                        name="senha" 
                                        id="senha" 
                                        value="<?php echo md5(12345)?>"/>
                                </div>
                                <div class="form-group">
                                    <div>
                                        <label for="tipo_usuario">Tipo</label>
                                    </div>
                                    <div class="form-group">
                                        <input type="radio" name="tipo_usuario" value="U"/>Usuário &nbsp;&nbsp;
                                        <input type="radio" name="tipo_usuario" value="A"/>Administrador
                                    </div>
                                </div>                    
                                <div class="modal-footer">
                                    <button type="submit" 
                                            class="btn btn-success" 
                                            onclick="save()">Salvar</button>
                                    <button type="reset" 
                                            class="btn btn-default" 
                                            onclick="limpar()">Limpar</button>
                                    <button type="button" class="btn btn-primary" data-dismiss="modal">Cancelar</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div> <!-- modais -->        
        </div> <!-- container -->
        
        <!-- Javascript -->
        <script type="text/javascript" src="/sods/static/js/models/Usuario.js"></script>
        
        <script type="text/javascript" src="/sods/static/js/validators/UsuarioFormValidator.js"></script>
        
        <script type="text/javascript" src="/sods/static/js/maskedInput.js"></script>
        
        <script type="text/javascript">
            var tipoSolicitacao = null;
            var action = null;
            var form = null;
            var formValidator = null;
            
            function add() {
                action = "add";
                usuario = new Usuario();
                usuario.id = null;
                usuario.status = null;
                form= $('#form-add');
                formValidator = new UsuarioFormValidator(form);
            }
            
            function edit(usuario_json) {
                try {
                    if (usuario_json.status == 'A') {
                        document.getElementById('statusEdit').setAttribute("checked", "checked");
                    } else {
                    	document.getElementById('statusEdit').removeAttribute("checked");
                    }
                    if (usuario_json.tipo_usuario == 'A') {
                        document.getElementById('tipo_usuario_a').setAttribute("checked", "checked");
                    } else {
                        document.getElementById('tipo_usuario_u').setAttribute("checked", "checked");
                    }
                    if (usuario_json != null) {
                        action = 'edit';
                        form = $('#form-edit');
                        formValidator = new UsuarioFormValidator(form);
                        $('#nome', form).val(usuario_json.nome_sol);
                        $('#login', form).val(usuario_json.login);
                        $('#lotacao', form).val("" + usuario_json.lotacao_id);
                        $('#cargo', form).val(usuario_json.cargo);
                        $('#fone', form).val(usuario_json.telefone);
                        $('#email', form).val(usuario_json.email);
                        usuario = new Usuario();
                        usuario.id = usuario_json.id;
                    } else {
                        throw 'Não é possível editar uma alteração que não existe.';
                    }
                } catch(e) {
                    alert(e);
                }
            }

            function del(usuario_json) {
                 try {
                        if (usuario_json != null) {
                            action = 'delete';
                            usuario = new Usuario();
                            usuario.id = usuario_json.id;
                            usuario.nome = usuario_json.nome_sol;
                            usuario.lotacao_id = usuario_json.lotacao_id;
                            usuario.cargo = usuario_json.cargo;
                            usuario.telefone = usuario_json.telefone;
                            usuario.email = usuario_json.email;
                            usuario.login = usuario_json.login;
                            usuario.senha = usuario_json.senha;
                            usuario.tipo_usuario = usuario_json.tipo_usuario;
                            usuario.status = usuario_json.status;
                            usuario.data_criacao = usuario_json.data_criacao;
                            usuario.data_alteracao = usuario_json.data_alteracao;
                        }
                    } catch(e) {
                        alert(e);
                    }
            }

            function save() {
                if (usuario !=  null) {
                    if (action == 'add' || action == 'edit') {
                        usuario.nome = $('#form-' + action  + ' input[name="nome"]').val();
                        usuario.lotacao_id = $('#form-' + action  + ' select[name="lotacao"]').val();
                        usuario.cargo = $('#form-' + action  + ' input[name="cargo"]').val();
                        usuario.telefone = $('#form-' + action  + ' input[name="fone"]').val();
                        usuario.email = $('#form-' + action  + ' input[name="email"]').val();
                        usuario.login = $('#form-' + action  + ' input[name="login"]').val();
                        usuario.senha = $('#form-' + action  + ' input[name="senha"]').val();
                        usuario.tipo_usuario = 
                            $('#form-' + action  + ' input:radio[name="tipo_usuario"]:checked').val();
                        if (action == 'edit'){
                            usuario.status = 
                                $('#form-' + action  + ' input:checkbox[name="statusEdit"]:checked').val();
                        }
                    }
                
                    // Requisição AJAX
                    $.ajax({
                        type: 'post',
                        url: '/sods/admin/usuarios/',
                        dataType: 'json',
                        contentType: 'application/x-www-form-urlencoded',
                        cache: false,
                        timeout: 7000,
                        async: false,
                        data: {
                            'action': action,
                            'json': usuario.toJSON()
                        }
                    });
                }
            }

            function limpar() {
                if (validator != null) {
                    validator.resetForm();
                }
            }

            //Máscara para campo Telefone
            jQuery(document).ready(function() {
                //Inicio Mascara Telefone
                jQuery('input[id=fone]').mask("(99) 9999-9999?9").ready(function(event) {
                    var target, phone, element;
                    target = (event.currentTarget) ? event.currentTarget : event.srcElement;
                    phone = target.value.replace(/\D/g, '');
                    element = $(target);
                    element.unmask();
                    if(phone.length > 10) {
                        element.mask("(99) 99999-999?9");
                    } else {
                        element.mask("(99) 9999-9999?9");
                    }
                });
            });
            (jQuery);
            
         </script>
<?php    
    if (isset($_POST['action']) && isset($_POST['json'])) {
        //Recuperando dados do post
        $action = $_POST['action'];
        $json = $_POST['json'];
        
        $usuario = new Usuario();
        $usuario->loadJSON($json);
        
        switch ($action) {
            case 'add':
                $controller->add($usuario);
                break;
            case 'edit':
                $controller->edit($usuario);
                break;
            case 'delete':
                $controller->delete($usuario);
                break;
        }
    }
    @include $_SERVER ['DOCUMENT_ROOT'] . '/sods/includes/rodape.php';
?>