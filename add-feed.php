<?php 	
	
	$mode = $_GET["mode"];
	$title = 'Combo Vídeos - Administração de Feeds';
	include 'header.php'; 

	if(!empty($_POST['add'])) {	  			
		
		if(!filter_var($_POST['url'], FILTER_VALIDATE_URL)){
		    $error[] = 'Endereço em formato inválido';
		} else {
			libxml_use_internal_errors(true);
			$doc = new DOMDocument();
			if ($doc->loadXML(file_get_contents($_POST['url']))){
				$xpath = new DOMXpath($doc);
				$nodes = $xpath->query('//*');
				$tags = array();
				foreach ($nodes as $node) {
					$tags[] = $node->nodeName;
				}
			} else {
				$error[] = 'Endereço fornecido não monta um XML válido';				
			}
			
		}

	} 


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
								<li><span>Administração de Feeds</span></li>								
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
								<li><span><a href="usuario.php?mode=list">Administração de Feeds</a></span></li>								
								<li><span>Cadastro de Feeds</span></li>	
							</ol>												
						</div>									
					</header>
					<!-- start: page -->
					<section class="panel">
						<header class="panel-heading">
							<h2 class="panel-title">Cadastrar URL Feed</h2>
						</header>
						<form class="form-horizontal form-bordered" method="post">
							<div class="panel-body">	
								<div class="form-group">
									<label class="col-md-3 control-label" for="inputDefault">Endereço (adicionar http ou https)</label>
									<div class="col-md-6">
										<input class="form-control" id="url" type="text" name="url" required="true">
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
					<?php if(isset($tags)) { ?>
					<section class="panel">
						<div class="panel-body">
							<div class="row">
								<div class="col-lg-12">
									<section class="panel">
										<header class="panel-heading">
											<h2 class="panel-title">Tags disponíveis</h2>
										</header>
										<div class="panel-body">
											<div class="row">
												<div class="col-md-12">
													<form class="form-horizontal form-bordered" method="get">
														<div class="form-group">
															<?php foreach (array_unique($tags) as $t) { ?>
															<div class="col-sm-8">
																<div class="checkbox-custom checkbox-default">
																	<input type="checkbox" name="tags[]" id="checkboxExample1" value="<?php echo $t; ?>">
																	<label for="checkboxExample1"><?php echo $t; ?></label>
																</div>
															</div>
															<?php } ?>
														</div>
													</form>
												</div>
											</div>
										</div>
									</section>
								</div>
							</div>
						</div>
					</section>
					<?php } ?>
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