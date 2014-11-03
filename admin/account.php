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
		        exit();
		        break;
			case 'list':
				echo $controller->getForm();
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
                    if (data == 'ERRO') {
                        $('#alert-del').modal('show');
                    } else {
                        $('#grid').html(data);
                        console.log(data);
                    }
                },
                error: function(xhr, status, error) {
                    // console.log(error);
                },
                complete: function(xhr, status) {
                    //console.log('A requisição foi completada.');
                }
            });
        }

        function save() {
            //action = 'edit';
            //form = $('#form-edit');
            //var usuario = new Usuario();
            //usuario.id = $('#id', form).val();
            var id = $('#id', form).val();
            id = id == null ? '' : id;
            form = $('#form-edit');
            formValidator = new ContaFormValidator(form);
            if (id != '') {
                var usuario = new Usuario();
                var json = null;

                //variáveis escondidas no formulário
                usuario.id = id;
                usuario.status = $('#status', form).val();
                usuario.data_criacao = $('#data_criacao', form).val();
                usuario.data_alteracao = $('#data_alteracao', form).val();
                usuario.tipo_usuario = $('#tipo_usuario', form).val();

                //variáveis visíveis no formulário
                usuario.nome = $('#nome', form).val();
                usuario.lotacao_id = $('#lotacao_id', form).val();
                usuario.cargo = $('#cargo', form).val();
                usuario.telefone = $('#fone', form).val();
                usuario.email = $('#email', form).val();
                usuario.login = $('#login', form).val();
                usuario.senha = $('#senha', form).val();

                //se a senha foi editada, ela é criptografada.
                if (usuario.senha == '') {
                    usuario.senha = "<?php echo $_SESSION['usuario']['senha']?>";
                }else if (usuario.senha != "<?php echo $_SESSION['usuario']['senha']?>") {
                    var psw = usuario2.senha;
                    usuario2.senha = CryptoJS.MD5(psw);
                }
                
                json = usuario.toJSON();
                // Ajax
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
                        if (data == 'ERRO') {
                            $('#alert-del').modal('show');
                        }
                        //console.log(data);
                        // Recarrega a grid.
                        list();
                    },
                    error: function(xhr, status, error) {
                        // console.log(error);
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

        function limpar() {
            if (validator != null) {
                validator.resetForm();
            }
        }

        $(document).ready(function() {
            $('#form-edit').submit(function(event) {
                event.preventDefault();
                save();                
            });
            var fone = $('#fone');
            var mascara = "(99) 9999-9999?9";
            if (fone.val().length > 10) {
                fone.mask("(99) 99999-999?9");
            }
            fone.mask(mascara);
        });
    </script>
		
    <div class="container">
        <form role="form" id="form-edit" action="" method="post">
		    <div id="grid" class="table-responsive">
		        <div>
		        	<h2>Conta</h2>
		        </div>
                <div class="form-group">
					<label for="nome">Nome</label>
					<input type="text" id="nome" name="nome" class="form-control" value="<?php echo $_SESSION['usuario']['nome'] ?>" />
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
					<label for="cargo">Cargo</label>
					<input type="text" id="cargo" name="cargo" class="form-control" value="<?php echo $_SESSION['usuario']['cargo']?>" />
				</div>		  
				<div class="form-group">
				   	<label for="fone">Telefone / Ramal</label>
				   	<input type="text" id="fone" name="fone" class="form-control" value="<?php echo $_SESSION['usuario']['telefone']?>"/>
				</div>		  
				<div class="form-group">
					<label for="email">E-mail</label>
					<input type="email" id="email" name="email" class="form-control" value="<?php echo $_SESSION['usuario']['email']?>"/>
				</div>
				<div class="form-group">
					<label for="login">Login</label>
					<input type="text" id="login" name="login" class="form-control" value="<?php echo $_SESSION['usuario']['login']?>" readonly />
				</div>
				<div class="form-group">
				    <label for="senha">Senha</label>
				    <input type="password" id="senha" name="senha" class="form-control"/>
				</div>
				<div class="form-group">
				    <input type="hidden" 
				           id="id" 
				           name="id" value="<?php echo $_SESSION['usuario']['id']?>"/>
				    <input type="hidden" 
				           id="status" 
				           name="status" value="<?php echo $_SESSION['usuario']['status']?>"/>
				    <input type="hidden" 
				           id="data_criacao" 
				           name="data_criacao" value="<?php echo $_SESSION['usuario']['data_criacao']?>"/>
				    <input type="hidden" 
				           id="data_alteracao" 
				           name="data_alteracao" value="<?php echo $_SESSION['usuario']['data_alteracao']?>"/>
				    <input type="hidden" 
				           id="tipo_usuario" 
				           name="tipo_usuario" value="<?php echo $_SESSION['usuario']['tipo_usuario']?>"/>
				</div>
				<div class="btn-toolbar pull-right form-footer">
                    <button type="submit" class="btn btn-success">Salvar</button>
                    <button type="reset" class="btn btn-default" onclick="limpar()">Limpar</button>
                </div>
				
			</div>
		</form>
	</div> <!-- container -->
<?php
	@include $_SERVER ['DOCUMENT_ROOT'] . '/sods/includes/rodape.php';
?>