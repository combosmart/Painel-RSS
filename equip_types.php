<?php 	
	
	$mode = $_GET["mode"];
	$title = 'Combo Vídeos - Administração de Equipamentos';
	include 'header.php'; 

	if(!empty($_POST['add'])) {	  			

		$equip_type->setNome($_POST['nome']);

		if ($equip_type->checkExisting($equip_type) > 0) {
			$error[] = 'Tipo de equipamento informado já existe no sistema.';
		}
		
		if(!isset($error)) {
			$result = $equip_type->save($equip_type);			
			if ($result) {
				$success = 'Tipo de equipamento cadastrado com sucesso.';
				$_SESSION['success'] = $success;
				header('Location: equip_types.php?mode=list');
			}			
		}

	} // script Adicionar

	if(!empty($_POST['edit'])) {

		$equip_type->setId($_POST['id']);
		$equip_type->setNome($_POST['nome']);

		if ($equip_type->checkDuplicate($equip_type) > 0) {
			$error[] = 'Tipo de equipamento informado já existe no sistema.';
		}

		if(!isset($error)){
			$result = $equip_type->update($equip_type);
			if ($result) {
				$success = 'Alterações no tipo de equipamento '. $equip_type->getNome() .' gravadas com sucesso.';
				$_SESSION['success'] = $success;
				header('Location: equip_types.php?mode=list');
			}
		}
	}  // script Editar
	
?>


<!-- camada visual -->

			<div class="inner-wrapper">

			<?php include 'sidebar.php'; ?>

				<section role="main" class="content-body">
					<?php 						
						if ($mode == LISTAR) { 
							if ($_SESSION['role_id'] != ADMINISTRADOR) { header('Location: main.php'); }
							$equip_types = $equip_type->listar();
	          				if (isset($_SESSION['success'])) {
	          					echo "<div class='alert alert-success'>" . $_SESSION['success'] . "</div>";
	          					unset($_SESSION['success']);
	          				}
	          				if (isset($_SESSION['error'])) {
	          					echo "<div class='alert alert-danger'>" . $_SESSION['error'] . "</div>";
	          					unset($_SESSION['error']);
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
								<li><span>Administração de Equipamentos</span></li>								
							</ol>
							<a class="sidebar-right-toggle" data-open="sidebar-right"><i class="fa fa-chevron-left"></i></a>
						</div>									
					</header>
					<!-- start: page -->
					<section class="panel">
						<header class="panel-heading">
							<h2 class="panel-title">Tipos de Equipamentos Cadastrados</h2>
						</header>

						<div class="panel-body">
							<table class="table table-bordered table-striped mb-none" id="datatable-tabletools" data-swf-path="assets/vendor/jquery-datatables/extras/TableTools/swf/copy_csv_xls_pdf.swf"s>
								<thead>
									<tr>
										<th>Nome</th>
										<th>Status</th>
										<th>Ações</th>
									</tr>
								</thead>
								<tbody>
									<?php foreach ($equip_types as $row) { ?>
									<tr>
										<td><?php echo $row['nome']; ?></td>
										<td><?php echo label_active($row['active']); ?></td>
										<td>
											<a href="./equip_types.php?mode=edit&id=<?php echo $row['id']; ?>"><i class="fa fa-edit" aria-hidden="true" data-toggle="tooltip" title="Editar"></i></a>
											&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;
											<?php if ($row['active'] == 1) { ?>
												<a href="./equip_types.php?mode=delete&id=<?php echo $row['id']; ?>" ><i class="fa fa-trash" aria-hidden="true" data-toggle="tooltip" title="Remover"></i></a>
											<?php } else { ?>													
												<a href="./equip_types.php?mode=restore&id=<?php echo $row['id']; ?>" ><i class="fa fa-power-off" aria-hidden="true" data-toggle="tooltip" title="Restaurar"></i></a>
											<?php } ?>											
										</td>
									</tr>
									<?php } ?>
								</tbody>
							</table>
						</div>	
						<div class="panel-body">
							<a href="equip_types.php?mode=add" class="mb-xs mt-xs mr-xs btn btn-default">Adicionar</a>
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
								<li><span><a href="equip_types.php?mode=list">Administração de Equipamentos</a></span></li>								
								<li><span>Cadastro de Tipo de Equipamento</span></li>	
							</ol>												
						</div>									
					</header>
					<!-- start: page -->
					<section class="panel">
						<header class="panel-heading">
							<h2 class="panel-title">Cadastrar Tipo de Equipamento</h2>
						</header>
						<form class="form-horizontal form-bordered" method="post">
							<div class="panel-body">	
								<div class="form-group">
									<label class="col-md-3 control-label" for="inputDefault">Nome</label>
									<div class="col-md-6">
										<input class="form-control" id="nome" type="text" name="nome" maxlength="100" required="true">
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
          				$equip_type = $equip_type->carregar($_GET['id']);          
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
								<li><span><a href="equip_types.php?mode=list">Administração de Equipamentos</a></span></li>								
								<li><span>Alteração de Dados de Tipo de Equipamento</span></li>	
							</ol>												
						</div>									
					</header>
					<!-- start: page -->
					<section class="panel">
						<header class="panel-heading">
							<h2 class="panel-title">Editar Tipo de Equipamento</h2>
						</header>
						<form class="form-horizontal form-bordered" method="post">
							<div class="panel-body">	
								<div class="form-group">
									<label class="col-md-3 control-label" for="inputDefault">Nome</label>
									<div class="col-md-6">
										<input class="form-control" id="nome" type="text" name="nome" maxlength="100" required="true" value="<?php echo $equip_type['nome'] ?>">
									</div>
								</div>								
							</div>
							<input type="hidden" name="id" value="<?php echo $equip_type['id'] ?>" />
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
					<?php if ($mode == REMOVER) {    
							$equip_type->setId($_GET['id']);
							$flagEquipamentoAtivo = $equip_type->checkEquipamentoAtivo($equip_type);

							if ($flagEquipamentoAtivo > 0) {
								$error = "Não é possível desabilitar este item porque há equipamentos ativos deste tipo.";
								$_SESSION['error'] = $error;
								header('Location: equip_types.php?mode=list');
							} else {
								$result = $equip_type->delete($equip_type);
								if ($result) {
									header('Location: equip_types.php?mode=list');
								}
							}

							
          				}
      				?>
      				<?php if ($mode == RESTORE) {    
							$equip_type->setId($_GET['id']);
							$result = $equip_type->restore($equip_type);
							if ($result) {
								header('Location: equip_types.php?mode=list');
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