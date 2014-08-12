<?php
@session_start ();

require_once $_SERVER ['DOCUMENT_ROOT'] . '/sods/lib/session.php';

validar_acesso ();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN">
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		
		<style type="text/css">
  			form {
			    padding-left: 15%;
			    padding-right: 15%;
		    }
  		</style>
		
		<title>SODS</title>
	</head>
    <body>
    <?php
		include '../admin/index.php';
	?>
	<!-- formulário para contas de usuário (upd) -->
		<form role="form">
		  <div class="col-xs-6">
		  <div class="form-group">
		    <label for="nome">Nome</label>
		    <input type="text" class="form-control" id="nome" value='<?php echo $_SESSION['usuario']['nome_sol'];?>'>
		  </div>
		  
		  <div class="form-group">
		    <label for="lotacao">Lotação</label>
		    <input type="text" class="form-control" id="lotacao" value='<?php echo $_SESSION['usuario']['nome_sec'];?>'>
		  </div>
		  
		  <div class="form-group">
		    <label for="cargo">Cargo</label>
		    <input type="text" class="form-control" id="cargo" value='<?php echo $_SESSION['usuario']['cargo'];?>'>
		  </div>
		  
		  <div class="form-group">
		    <label for="telefone_ramal">Telefone/Ramal</label>
		    <input type="text" class="form-control" id="telefone_ramal" value='<?php echo $_SESSION['usuario']['fone_ramal'];?>'>
		  </div>
		  
		  <div class="form-group">
		    <label for="email">e-mail</label>
		    <input type="email" class="form-control" id="email" value='<?php echo $_SESSION['usuario']['email'];?>'>
		  </div>
		  
		  <div class="checkbox-disabled">
		    <label>
		      <input type="checkbox" id="tipo_usr" <?php if ($_SESSION['usuario']['tipo_usuario'] == 'A') { echo 'checked' ;}?> disabled="disabled"> Administrador
		    </label>
		  
		  </div>
		  <button type="submit" class="btn btn-primary pull-right">Atualizar Dados</button>
		</div>
		</form>
	
    </body>
</html>