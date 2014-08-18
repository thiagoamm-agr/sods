<?php
	@include $_SERVER['DOCUMENT_ROOT'] . '/sods/includes/topo.php';
?>
		<div class="container">
			<h2 style="text-align: center; margin-bottom: 25px;">Conta</h2>
			<form role="form" style="width: 50%; margin: 0 auto;>
				<div class="col-xs-6">
					<div class="form-group">
						<label for="nome">Nome</label>
						<input type="text" id="nome" name="nome" class="form-control" 
						    value="<?php echo $_SESSION['usuario']['nome_sol'] ?>" />
					</div>		  
					<div class="form-group">
						<label for="lotacao">Lotação</label>
						<input type="text" id="lotacao" name="lotacao" class="form-control" 
						    value="<?php echo $_SESSION['usuario']['nome_sec'] ?>" />
					</div>
					<div class="form-group">
						<label for="cargo">Cargo</label>
						<input type="text" id="cargo" name="cargo" class="form-control" 
						    value="<?php echo $_SESSION['usuario']['cargo'] ?>" />
					</div>		  
					<div class="form-group">
				    	<label for="telefone_ramal">Telefone / Ramal</label>
				    	<input type="text" id="telefone_ramal" name="telefone_ramal" class="form-control" 
				    	    value="<?php echo $_SESSION['usuario']['fone_ramal'] ?>" />
				  	</div>		  
					<div class="form-group">
						<label for="email">E-mail</label>
						<input type="email" id="email" name="email" class="form-control" 
						    value="<?php echo $_SESSION['usuario']['email'] ?>" />
					</div>		  
					<div class="checkbox-disabled">
						<label>
							<input type="checkbox" id="tipo_usr" name="tipo_usr" 
							    <?php if ($_SESSION['usuario']['tipo_usuario'] == 'A') { echo 'checked="checked"' ;} ?> 
							    disabled="disabled" /> Administrador
						</label>		  
				  	</div>
					<button type="submit" class="btn btn-primary pull-right">Salvar</button>
				</div>
			</form>
		</div> <!-- container -->
<?php
	@include $_SERVER ['DOCUMENT_ROOT'] . '/sods/includes/rodape.php';
?>