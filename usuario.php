<?php 	
	
	$mode = $_GET["mode"];
	$title = 'Combo Vídeos - Administração de Usuários';
	include 'header.php'; 

	if(!empty($_POST['add'])) {	  			
		
		if(strlen($_POST['password']) < 5){
			$error[] = 'Password muito curta.';
		}

		if($_POST['password'] != $_POST['passwordConfirm']){
			$error[] = 'Senhas não conferem.';
		}

		//email validation
		if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
		    $error[] = 'Endereço de email em formato inválido';
		} else {
			$stmt = $db->prepare('SELECT email FROM users WHERE email = :email');
			$stmt->execute(array(':email' => $_POST['email']));
			$row = $stmt->fetch(PDO::FETCH_ASSOC);

			if(!empty($row['email'])){
				$error[] = 'Email informado já está em uso no sistema.';
			}

		}

		//if no errors have been created carry on
		if(!isset($error)){

			//hash the password
			$hashedpassword = $user->password_hash($_POST['password'], PASSWORD_BCRYPT);

			//create the activation code
			$activation = md5(uniqid(rand(),true));

			try {

				//insert into database with a prepared statement
				$stmt = $db->prepare('INSERT INTO users (username,password,email,active, role_id, nome, created) VALUES (:email, :password, :email, 1, :perfil, :nome, now())');
				$stmt->execute(array(
					':username' => $_POST['email'],
					':password' => $hashedpassword,
					':email' => $_POST['email'],
	        		':perfil' => $_POST['role_id'],
	        		':nome' => $_POST['nome']
				));
				
				$id = $db->lastInsertId('id');

				//send email
				
				$to = $_POST['email'];
				$subject = "Combo Vídeos - Confirmação de Cadastramento de Usuário";
				$body    = "<p>Foi criada uma conta associada a este email no painel administrativo Combo Vídeos</p>
						    <p>Seguem seus dados de acesso:</p>
	      				    <p>Usuário: ". $_POST['username'] . "</p>
	      				    <p>Senha: ". $_POST['password'] . "</p>
							<p>Recomenda-se enfaticamente que a senha seja alterada em seu primeiro acesso.</p>
						    <p>Guarde-a local seguro. Não teremos acesso a ela e, em caso de perda, o acesso só poderá ser feito gerando uma nova senha na tela de login</p>";

				$mail = new Mail();
				$mail->setFrom(SITEEMAIL);
				$mail->addAddress($to);
				$mail->subject(utf8_decode($subject));
				$mail->body(utf8_decode($body));
				$mail->send();
				
				$success = 'Usuário cadastrado com sucesso. Um email foi enviado para o endereço fornecido';
				$_SESSION['success'] = $success;

				header('Location: usuario.php?mode=list');

			//else catch the exception and show the error.
			} catch(PDOException $e) {
			    $error[] = $e->getMessage();
			}

		}

	} // script Adicionar

	if(!empty($_POST['edit'])) {
		if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
		    $error[] = 'Endereço de email em formato inválido';
		} else {
			$stmt = $db->prepare('SELECT email FROM users WHERE email = :email  AND id NOT IN (:id)');		
	    	$stmt->execute(array(
					':email' => $_POST['email'],				
					':id' => $_POST['id']
					));
			$row = $stmt->fetch(PDO::FETCH_ASSOC);

			if(!empty($row['email'])){
				$error[] = 'Email informado já está em uso no sistema.';
			}

		}

		if(!isset($error)){
			try {
	      			$sql = "UPDATE users SET nome = :nome, email = :email, role_id = :perfil, username = :email WHERE id = :id";      
	      			$stmt = $db->prepare($sql);		
					$stmt->execute(array(						
						':email' => $_POST['email'],
	        			':perfil' => $_POST['role_id'],
	        			':nome' => $_POST['nome'],
	        			':id' => $_POST['id']
					));			
					$success = 'Alterações no usuário '.$_POST['nome'].' gravadas com sucesso.';
					$_SESSION['success'] = $success;
					header('Location: usuario.php?mode=list');
			} catch(PDOException $e) {
			    $error[] = $e->getMessage();
			}

		}
	}  // script Editar

	if(!empty($_POST['changepass'])) {	  			
		
		if(strlen($_POST['passwordNew']) < 5) {
			$error[] = 'Password muito curta.';
		}

		if($_POST['passwordNew'] != $_POST['passwordConfirm']){
			$error[] = 'Password nova e confirmação não conferem.';
		}

		if (!($user->password_compare($_SESSION['username'],$_POST['password']))) {
			$error[] = 'Password atual não confere.';	
		}
		
		if(!isset($error)){
			try {
					$hashedpassword = $user->password_hash($_POST['passwordNew'], PASSWORD_BCRYPT);
	      			$sql = "UPDATE users SET password = :password WHERE id = :id";      
	      			$stmt = $db->prepare($sql);		
					$stmt->execute(array(						
						':password' => $hashedpassword,
	        			':id' => $_SESSION['usuario_id']
					));			
					
					$success = 'Senha alterada com sucesso.';
					$_SESSION['success'] = $success;
					

			} catch(PDOException $e) {
			    $error[] = $e->getMessage();
			}

		}		
	} // script Alterar Senha

?>


<!-- camada visual -->

			<div class="inner-wrapper">

			<?php include 'sidebar.php'; ?>

				<section role="main" class="content-body">
					<?php 						
						if ($mode == LISTAR) { 
							if ($_SESSION['role_id'] != ADMINISTRADOR) { header('Location: main.php'); }							
	          				$users = $user->listar_users();
	          				if (isset($_SESSION['success'])) {
	          					echo "<div class='alert alert-success'>" . $_SESSION['success'] . "</div>";
	          					unset($_SESSION['success']);
	          				}
					?>	
					<header class="page-header page-header-left-breadcrumb">
						<div class="right-wrapper">
							<ol class="breadcrumbs">
								<li>
									<a href="./main.php">
										<i class="fa fa-home"></i>
									</a>
								</li>
								<li><span>Início</span></li>								
								<li><span>Administração de Usuários</span></li>								
							</ol>
							<a class="sidebar-right-toggle" data-open="sidebar-right"><i class="fa fa-chevron-left"></i></a>
						</div>									
					</header>
					<!-- start: page -->
					<section class="panel">
						<header class="panel-heading">
							<h2 class="panel-title">Usuários do Sistema</h2>
						</header>

						<div class="panel-body">
							<table class="table table-bordered table-striped mb-none" id="datatable-tabletools" data-swf-path="assets/vendor/jquery-datatables/extras/TableTools/swf/copy_csv_xls_pdf.swf"s>
								<thead>
									<tr>
										<th>Nome</th>
										<th>Email</th>
										<th>Perfil</th>
										<th>Status</th>
										<th>Ações</th>
									</tr>
								</thead>
								<tbody>
									<?php foreach ($users as $row) { ?>
									<tr>
										<td><?php echo $row['nome']; ?></td>
										<td><?php echo $row['username']; ?></td>
										<td><?php echo utf8_encode($row['descr']); ?></td>
										<td><?php echo label_active($row['active']); ?></td>
										<td>
											<a href="./usuario.php?mode=edit&id=<?php echo $row['id']; ?>"><i class="fa fa-edit" aria-hidden="true" data-toggle="tooltip" title="Editar"></i></a>
											&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;
											<?php if ($_SESSION['usuario_id'] != $row['id']) { ?>
												<?php if ($row['active'] == 1) { ?>
													<a href="./usuario.php?mode=delete&id=<?php echo $row['id']; ?>" ><i class="fa fa-trash" aria-hidden="true" data-toggle="tooltip" title="Remover"></i></a>
												<?php } else { ?>													
													<a href="./usuario.php?mode=restore&id=<?php echo $row['id']; ?>" ><i class="fa fa-power-off" aria-hidden="true" data-toggle="tooltip" title="Restaurar"></i></a>
												<?php } ?>
											<?php } ?>
										</td>
									</tr>
									<?php } ?>
								</tbody>
							</table>
						</div>
						<div class="panel-body">
							<a href="usuario.php?mode=add" class="mb-xs mt-xs mr-xs btn btn-default">Adicionar</a>
						</div>							
					</section>								
					<!-- end: page -->
					<?php 
						} // LISTAR 
					?>
					<?php 
						if ($mode == ADICIONAR) { 
						if ($_SESSION['role_id'] != ADMINISTRADOR) { header('Location: main.php'); }	
          				$perfis = $user->list_roles();          
					?>
					<?php if(isset($error)) { 
						echo "<div class='alert alert-danger'>";
						echo "<strong>Erro: </strong>";
						foreach ($error as $e) {
							echo $e;
						}
						echo "</div>";
	                } ?>
					<header class="page-header page-header-left-breadcrumb">
						<div class="right-wrapper">
							<ol class="breadcrumbs">
								<li>
									<a href="./main.php">
										<i class="fa fa-home"></i>
									</a>
								</li>
								<li><span>Início</span></li>								
								<li><span><a href="usuario.php?mode=list">Administração de Usuários</a></span></li>								
								<li><span>Cadastro de Usuários</span></li>	
							</ol>												
						</div>									
					</header>
					<!-- start: page -->
					<section class="panel">
						<header class="panel-heading">
							<h2 class="panel-title">Cadastrar Usuário</h2>
						</header>
						<form class="form-horizontal form-bordered" method="post">
							<div class="panel-body">	
								<div class="form-group">
									<label class="col-md-3 control-label" for="inputDefault">Nome</label>
									<div class="col-md-6">
										<input class="form-control" id="nome" type="text" name="nome" maxlength="100" required="true">
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-3 control-label" for="inputDefault">Email</label>
									<div class="col-md-6">
										<input class="form-control" id="email" type="email" name="email" maxlength="100" required="true">
									</div>
								</div>	
								<div class="form-group">
									<label class="col-md-3 control-label" for="inputDefault">Senha</label>
									<div class="col-md-6">
										<input class="form-control" name="password" maxlength="10" id="password" type="password" required="true">
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-3 control-label" for="inputDefault">Confirme a senha</label>
									<div class="col-md-6">
										<input class="form-control" name="passwordConfirm" maxlength="10" id="passwordConfirm" type="password" required="true">
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-3 control-label" for="inputSuccess">Perfil de acesso</label>
									<div class="col-md-6">
										<select class="form-control mb-md" name="role_id">
											<?php foreach ($perfis as $perfil) { ?>}
						                    	<option value = "<?php echo $perfil['id'] ?>" ><?php echo utf8_encode($perfil['descr']) ?> </option>
						                    <?php } ?>
										</select>		
									</div>
								</div>
							</div>
							<footer class="panel-footer">
								<div class="row">
									<div class="col-sm-9 col-sm-offset-3">
										<button class="btn btn-primary" name="add" value="add">Enviar</button>
										<button type="reset" class="btn btn-default">Reset</button>
									</div>
								</div>
							</footer>
						</form>
					</section>
					<!-- end: page -->
					<?php 
						} // ADICIONAR
					?>
					<?php 
						if ($mode == EDITAR) { 
						if ($_SESSION['role_id'] != ADMINISTRADOR) { header('Location: main.php'); }	
          				$perfis = $user->list_roles();
          				$usuario = $user->carregar_usuario($_GET['id']);          
					?>
					<?php if(isset($error)) { 
						echo "<div class='alert alert-danger'>";
						echo "<strong>Erro: </strong>";
						foreach ($error as $e) {
							echo $e;
						}
						echo "</div>";
	                } ?>
					<header class="page-header page-header-left-breadcrumb">
						<div class="right-wrapper">
							<ol class="breadcrumbs">
								<li>
									<a href="./main.php">
										<i class="fa fa-home"></i>
									</a>
								</li>
								<li><span>Início</span></li>								
								<li><span><a href="usuario.php?mode=list">Administração de Usuários</a></span></li>								
								<li><span>Alteração de Dados de Usuários</span></li>	
							</ol>												
						</div>									
					</header>
					<!-- start: page -->
					<section class="panel">
						<header class="panel-heading">
							<h2 class="panel-title">Editar Usuário</h2>
						</header>
						<form class="form-horizontal form-bordered" method="post">
							<div class="panel-body">	
								<div class="form-group">
									<label class="col-md-3 control-label" for="inputDefault">Nome</label>
									<div class="col-md-6">
										<input class="form-control" id="nome" type="text" name="nome" maxlength="100" required="true" value="<?php echo $usuario['nome'] ?>">
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-3 control-label" for="inputDefault">Email</label>
									<div class="col-md-6">
										<input class="form-control" id="email" type="email" name="email" maxlength="100" required="true" value="<?php echo $usuario['email'] ?>">
									</div>
								</div>	
								<div class="form-group">
									<label class="col-md-3 control-label" for="inputSuccess">Perfil de acesso</label>
									<div class="col-md-6">
										<select class="form-control mb-md" name="role_id">
											<?php foreach ($perfis as $perfil) { ?>}
						                    	<option value = "<?php echo $perfil['id'] ?>" <?php if ($perfil['id'] == $usuario['role_id']) echo "selected"; ?> ><?php echo utf8_encode($perfil['descr']) ?> </option>
						                    <?php } ?>
										</select>		
									</div>
								</div>
							</div>
							<input type="hidden" name="id" value="<?php echo $usuario['id'] ?>" />
							<footer class="panel-footer">
								<div class="row">
									<div class="col-sm-9 col-sm-offset-3">
										<button class="btn btn-primary" name="edit" value="edit">Enviar</button>
										<button type="reset" class="btn btn-default">Reset</button>
									</div>
								</div>
							</footer>
						</form>
					</section>
					<!-- end: page -->
					<?php 
						} // EDITAR
					?>
					<?php 
						if ($mode == ALTERAR_SENHA) { 						
					?>
					<?php if(isset($error)) { 
						echo "<div class='alert alert-danger'>";
						echo "<strong>Erro: </strong>";
						foreach ($error as $e) {
							echo $e;
						}
						echo "</div>";
	                } ?>
	                <?php 
	                if (isset($_SESSION['success'])) {
						echo "<div class='alert alert-success'>" . $_SESSION['success'] . "</div>";
						unset($_SESSION['success']);
	          		}	
	                ?>
					<header class="page-header page-header-left-breadcrumb">
						<div class="right-wrapper">
							<ol class="breadcrumbs">
								<li>
									<a href="./main.php">
										<i class="fa fa-home"></i>
									</a>
								</li>
								<li><span>Início</span></li>								
								<li><span><a href="#">Administração de Usuários</a></span></li>								
								<li><span>Alterar Minha Senha</span></li>	
							</ol>												
						</div>									
					</header>
					<!-- start: page -->
					<section class="panel">
						<header class="panel-heading">
							<h2 class="panel-title">Alterar Minha Senha</h2>
						</header>
						<form class="form-horizontal form-bordered" method="post">
							<div class="panel-body">	
								<div class="form-group">
									<label class="col-md-3 control-label" for="inputDefault">Senha Atual</label>
									<div class="col-md-6">
										<input class="form-control" name="password" id="password" type="password" required="true">
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-3 control-label" for="inputDefault">Nova Senha</label>
									<div class="col-md-6">
										<input class="form-control" name="passwordNew"  id="passwordConfirm" type="password" required="true">
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-3 control-label" for="inputDefault">Confirme a senha</label>
									<div class="col-md-6">
										<input class="form-control" name="passwordConfirm" id="passwordConfirm" type="password" required="true">
									</div>
								</div>
								<input type="hidden" name="id" value="<?php echo $_SESSION['usuario_id']; ?>">								
							</div>
							<footer class="panel-footer">
								<div class="row">
									<div class="col-sm-9 col-sm-offset-3">
										<button class="btn btn-primary" name="changepass" value="changepass">Alterar</button>
										<button type="reset" class="btn btn-default">Reset</button>
									</div>
								</div>
							</footer>
						</form>
					</section>
					<!-- end: page -->
					<?php 
						} // ADICIONAR
					?>
					<?php if ($mode == REMOVER) {     
			              try {
			                    $sql = "UPDATE users SET active = 0 WHERE id = :id";      
			                    $stmt = $db->prepare($sql);		
			                    $stmt->execute(array(
			                      ':id' => $_GET['id']
			                    ));			
			                    header('Location: usuario.php?mode=list');
			              } catch(PDOException $e) {
			                  $error[] = $e->getMessage();
			              }
          				}
      				?>
      				<?php if ($mode == RESTORE) {     
			              try {
			                    $sql = "UPDATE users SET active = 1 WHERE id = :id";      
			                    $stmt = $db->prepare($sql);		
			                    $stmt->execute(array(
			                      ':id' => $_GET['id']
			                    ));			
			                    header('Location: usuario.php?mode=list');
			              } catch(PDOException $e) {
			                  $error[] = $e->getMessage();
			              }
          				}
      				?>
				</section>
			</div>
			
		</section>

		<script>
			$(document).ready(function(){
			    $('[data-toggle="tooltip"]').tooltip();
			});
		</script>

		<!-- Vendor -->
		<script src="assets/vendor/jquery/jquery.js"></script>
		<script src="assets/vendor/jquery-browser-mobile/jquery.browser.mobile.js"></script>
		<script src="assets/vendor/bootstrap/js/bootstrap.js"></script>
		<script src="assets/vendor/nanoscroller/nanoscroller.js"></script>
		<script src="assets/vendor/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
		<script src="assets/vendor/magnific-popup/jquery.magnific-popup.js"></script>
		<script src="assets/vendor/jquery-placeholder/jquery-placeholder.js"></script>
		
		<!-- Specific Page Vendor -->
		<script src="assets/vendor/jquery-ui/jquery-ui.js"></script>
		<script src="assets/vendor/jqueryui-touch-punch/jqueryui-touch-punch.js"></script>
		<script src="assets/vendor/jquery-appear/jquery-appear.js"></script>
		<script src="assets/vendor/bootstrap-multiselect/bootstrap-multiselect.js"></script>
		<script src="assets/vendor/jquery.easy-pie-chart/jquery.easy-pie-chart.js"></script>
		<script src="assets/vendor/flot/jquery.flot.js"></script>
		<script src="assets/vendor/flot.tooltip/flot.tooltip.js"></script>
		<script src="assets/vendor/flot/jquery.flot.pie.js"></script>
		<script src="assets/vendor/flot/jquery.flot.categories.js"></script>
		<script src="assets/vendor/flot/jquery.flot.resize.js"></script>
		<script src="assets/vendor/jquery-sparkline/jquery-sparkline.js"></script>
		<script src="assets/vendor/raphael/raphael.js"></script>
		<script src="assets/vendor/morris.js/morris.js"></script>
		<script src="assets/vendor/gauge/gauge.js"></script>
		<script src="assets/vendor/snap.svg/snap.svg.js"></script>
		<script src="assets/vendor/liquid-meter/liquid.meter.js"></script>
		<script src="assets/vendor/jqvmap/jquery.vmap.js"></script>
		<script src="assets/vendor/jqvmap/data/jquery.vmap.sampledata.js"></script>
		<script src="assets/vendor/jqvmap/maps/jquery.vmap.world.js"></script>
		<script src="assets/vendor/jqvmap/maps/continents/jquery.vmap.africa.js"></script>
		<script src="assets/vendor/jqvmap/maps/continents/jquery.vmap.asia.js"></script>
		<script src="assets/vendor/jqvmap/maps/continents/jquery.vmap.australia.js"></script>
		<script src="assets/vendor/jqvmap/maps/continents/jquery.vmap.europe.js"></script>
		<script src="assets/vendor/jqvmap/maps/continents/jquery.vmap.north-america.js"></script>
		<script src="assets/vendor/jqvmap/maps/continents/jquery.vmap.south-america.js"></script>
		<script src="assets/vendor/select2/js/select2.js"></script>
		<script src="assets/vendor/jquery-datatables/media/js/jquery.dataTables.js"></script>
		<script src="assets/vendor/jquery-datatables/extras/TableTools/js/dataTables.tableTools.min.js"></script>
		<script src="assets/vendor/jquery-datatables-bs3/assets/js/datatables.js"></script>
		<script src="assets/vendor/autosize/autosize.js"></script>
		<script src="assets/vendor/bootstrap-fileupload/bootstrap-fileupload.min.js"></script>


		
		<!-- Theme Base, Components and Settings -->
		<script src="assets/javascripts/theme.js"></script>
		
		<!-- Theme Custom -->
		<script src="assets/javascripts/theme.custom.js"></script>
		
		<!-- Theme Initialization Files -->
		<script src="assets/javascripts/theme.init.js"></script>

		<!-- Examples -->
		<script src="assets/javascripts/dashboard/examples.dashboard.js"></script>
		<script src="assets/javascripts/tables/examples.datatables.default.js"></script>
		<script src="assets/javascripts/tables/examples.datatables.row.with.details.js"></script>
		<script src="assets/javascripts/tables/examples.datatables.tabletools.js"></script>

	</body>
</html>