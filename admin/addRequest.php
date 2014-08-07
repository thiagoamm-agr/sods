<?php
@session_start ();

require_once $_SERVER ['DOCUMENT_ROOT'] . '/sods/lib/session.php';

validar_acesso ();

date_default_timezone_set("Brazil/East");
$data = date("d/m/y " . " H:i");
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN">
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		
		<style type="text/css">
  			form {
			    padding-left: 15%;
			    padding-right: 20%;
		    }
  		</style>
		
		<title>SODS</title>
	</head>
    <body>
    <?php
		include '../admin/index.php';
	?>
		<form class="form-horizontal" role="form">
		  
			  <div class="form-group">
			    <label for="nome_sol" class="col-sm-2 control-label">Nome</label>
			    <div class="col-sm-10">
			      <input type="text" class="form-control" id="nome_sol" placeholder="<?php echo $_SESSION['usuario']['nome_sol'];?>" readonly>
			    </div>
			  </div>
			  
			  <div class="form-group">
			    <label for="nome_sec" class="col-sm-2 control-label">Lotação</label>
			    <div class="col-sm-10">
			      <input type="text" class="form-control" id="nome_sec" placeholder="<?php echo $_SESSION['usuario']['nome_sec'];?>" readonly>
			    </div>
			  </div>
			  
			  <div class="form-group">
			  	<label for="descricao" class="col-sm-2 control-label">Descrição</label>
			  	<div class="col-sm-10">
			  		<textarea class="form-control" rows="6" placeholder="Digite aqui a descrição detalhada do sistema"></textarea>
			  	</div>
			  </div>
			  
			  <div class="form-group">
			  	<label for="info_adc" class="col-sm-2 control-label">Inf. Adicionais</label>
			  	<div class="col-sm-10">
			  		<textarea class="form-control" rows="4" placeholder="Digite aqui alguma informação que julgue importante"></textarea>
			  		<span class="help-block">Ex.: No desenvolvimento do sistema deveremos contar com um profissional de Direito.</span>
			  	</div>
			  </div>
			  
			  <div class="form-group">
			  	<label for="obs" class="col-sm-2 control-label">Observações</label>
			  	<div class="col-sm-10">
			  		<textarea class="form-control" rows="2" placeholder="Observações relevantes para o desenvolvimento"></textarea>
			  		<span class="help-block">Ex.: Torna-se importante o desenvolvimento por ...</span>
			  	</div>
			  </div>
			  
			  <div class="form-group">
			  	<label for="tipo_sol" class="col-sm-2 control label">Tipo de Solicitação</label>
			  	<div class="col-sm-10">
			  		<select class="form-control">
			  		  <option id=" "> </option>
					  <option id="desenvolvimento">Desenvolvimento</option>
					  <option id="adaptativa">Correção Adaptativa</option>
					  <option id="evolutiva">Correção Evolutiva</option>
					  <option id="preventiva">Correção Preventiva</option>
					</select>
			  	</div>
			  
			  </div>
			  
			  <div class="form-group">
			    <label for="data" class="col-sm-2 control-label">Data</label>
			    <div class="col-sm-10">
			      <input type="text" class="form-control" id="data" placeholder=" <?php echo $data?>" readonly>
			    </div>
			  </div>
			  
			  <div class="form-group">
			    <label for="ult_alteracao" class="col-sm-2 control-label">Ultima Alteração</label>
			    <div class="col-sm-10">
			      <input type="text" class="form-control" id="ult_ateracao" placeholder="00/00/00 00:00" readonly>
			    </div>
			  </div>
		  <button type="submit" class="btn btn-primary" >Enviar Solicitação</button>
		</form>
	
    </body>
</html>