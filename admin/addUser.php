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
		
		require_once $_SERVER ['DOCUMENT_ROOT'] . '/sods/lib/db.php';
		get_db_connection();
		
		$query= "select * from secao";
		$result = mysql_query($query);

		
?>
	<form role="form" action="../lib/saveUser.php" method="post">
		  <div class="col-xs-6">
		  <div class="form-group">
		    <label for="nome">Nome</label>
		    <input type="text" class="form-control" name="nome" id="nome">
		  </div>
		  
		  <div class="form-group">
		  		<select class="form-control" name=lotacao id="lotacao">
<?php 
		  				while($resultFim = mysql_fetch_assoc($result)){
?>
						<option value="<?=$resultFim["id"]?>"> <?=$resultFim["nome"]?></option>
<?php 
		  				}
?>
				</select>			  		  	
		  </div>
		  
		  <div class="form-group">
		    <label for="cargo">Cargo</label>
		    <input type="text" class="form-control" name="cargo" id="cargo">
		  </div>
		  
		  <div class="form-group">
		    <label for="fone_ramal">Telefone/Ramal</label>
		    <input type="text" class="form-control" name="fone_ramal" id="fone_ramal">
		  </div>
		  
		  <div class="form-group">
		    <label for="email">e-mail</label>
		    <input type="email" class="form-control" name="email" id="email">
		  </div>
		  
		  <div class="form-group">
		  	<label for="login">login</label>
		  	<input type="text" class="form-control" name="login" id="login">	  
		  </div>
		  
		  <div class="checkbox">
		    <label>
		      <input type="checkbox" id="tipo_usr" name="tipo_usr" value="A"> Administrador
		    </label>
		  
		  </div>
		  <button type="submit" class="btn btn-primary pull-right">Salvar Usuário</button>
		</div>
</form>
		
    </body>
</html>