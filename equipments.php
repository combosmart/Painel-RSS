<?php 	
	
	$mode = $_GET["mode"];
	$title = 'Combo Vídeos - Administração de Equipamentos';
	include 'header.php'; 

	if(!empty($_POST['add'])) {	  			

		$equipment->setNome($_POST['nome']);
		$equipment->setSerialNum($_POST['serial_num']);
		$equipment->setSpecs($_POST['specs']);
		$equipment->setAno($_POST['ano']);

		$equip_type->setId($_POST['equip_type_id']);

		$equipment->setTipo($equip_type);	

		if ($equipment->checkExisting($equipment) > 0) {
			$error[] = 'Número de série informado já existe associado a outro equipamento.';
		}

		if (empty($_POST['equip_type_id'])) {
			$error[] = 'Tipo de equipamento deverá ser informado';
		}	


		if(!isset($error)) {
			$result = $equipment->save($equipment);
			if ($result) {
				$success = 'Equipamento cadastrado com sucesso.';
				$_SESSION['success'] = $success;
				header('Location: equipments.php?mode=list');
			}			
		}

	} // script Adicionar

	if(!empty($_POST['edit'])) {

		$equipment->setId($_POST['id']);
		$equipment->setNome($_POST['nome']);
		$equipment->setSerialNum($_POST['serial_num']);
		$equipment->setSpecs($_POST['specs']);
		$equipment->setAno($_POST['ano']);

		$equip_type->setId($_POST['equip_type_id']);
		$equipment->setTipo($equip_type);
		
		if ($equipment->checkDuplicate($equipment) > 0) {
			$error[] = 'Número de série informado já existe associado a outro equipamento.';
		}
		
		if(!isset($error)){
			$result = $equipment->update($equipment);
			if ($result) {
				$success = 'Alterações no equipamento '. $equipment->getNome() .' gravadas com sucesso.';
				$_SESSION['success'] = $success;
				header('Location: equipments.php?mode=list');
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
							$equipments = $equipment->listar();
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
							<h2 class="panel-title">Equipamentos Cadastrados</h2>
						</header>

						<div class="panel-body">
							<table class="table table-bordered table-striped mb-none" id="datatable-tabletools" data-swf-path="assets/vendor/jquery-datatables/extras/TableTools/swf/copy_csv_xls_pdf.swf">
								<thead>
									<tr>
										<th>Tipo</th>
										<th>Marca</th>
										<th>Descrição</th>
										<th>Alocado em</th>
										<th>Status</th>
										<th>Ações</th>
									</tr>
								</thead>
								<tbody>
									<?php foreach ($equipments as $row) { ?>
									<tr>
										<td><?php echo $row['tipo']; ?></td>
										<td><?php echo $row['nome']; ?></td>
										<td><?php echo limit_text($row['specs'],5); ?></td>
										<td><?php echo $row['cliente']; ?></td>
										<td><?php echo label_active($row['active']); ?></td>
										<td>
											<a href="./equipments.php?mode=edit&id=<?php echo $row['id']; ?>"><i class="fa fa-edit" aria-hidden="true" data-toggle="tooltip" title="Editar"></i></a>
											&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;
											<?php if ($row['active'] == 1) { ?>
												<a href="#modalIcon" class="mb-xs mt-xs mr-xs modal-basic open-modal"><i class="fa fa-trash" aria-hidden="true" data-toggle="tooltip" onclick="javaScript:save('<?php echo $row['id']; ?>')" title="Remover"></i></a>
											<?php } else { ?>													
												<a href="./equipments.php?mode=restore&id=<?php echo $row['id']; ?>" ><i class="fa fa-power-off" aria-hidden="true" data-toggle="tooltip" title="Restaurar"></i></a>
											<?php } ?>											
										</td>
									</tr>
									<?php } ?>
								</tbody>
							</table>
						</div>	
						<div class="panel-body">
							<a href="equipments.php?mode=add" class="mb-xs mt-xs mr-xs btn btn-default">Adicionar</a>
						</div>		
						<script type="text/javascript">
							function save(id) {
								document.getElementById('id_equipment').value = id; 								
							}

							function submitForm() {
								var id = document.getElementById('id_equipment').value;
								window.location.href = 'equipments.php?mode=delete&id=' + id;
							}
						</script>			
					</section>		
					<div id="modalIcon" class="modal-block modal-block-primary mfp-hide">
						<section class="panel">
							<header class="panel-heading">
								<h2 class="panel-title">Tem certeza?</h2>
							</header>
							<div class="panel-body">
								<div class="modal-wrapper">
									<div class="modal-icon">
										<i class="fa fa-warning"></i>
									</div>
									<div class="modal-text">
										<p><strong>Atenção: </strong> ao desabilitar o equipamento, você automaticamente desfaz a associação que ele possa ter com um ponto.</p>
										<p>Deseja continuar?</p>
									</div>
									<input type="hidden" name="id_equipment" id="id_equipment" value=""/>

								</div>
							</div>
							<footer class="panel-footer">
								<div class="row">
									<div class="col-md-12 text-right">
										<a href="#" class="btn btn-primary" onclick="submitForm()">Confirmar</a>
										<button class="btn btn-default modal-dismiss">Cancelar</button>
									</div>
								</div>
							</footer>							
						</section>
					</div>															
					<!-- end: page -->
					<?php 
						} // LISTAR 
					?>
					<?php 
						if ($mode == ADICIONAR) { 
						if ($_SESSION['role_id'] != ADMINISTRADOR) { header('Location: main.php'); }	
						$equip_types = $equip_type->listar();						
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
								<li><span><a href="equipments.php?mode=list">Administração de Equipamentos</a></span></li>								
								<li><span>Cadastro de Equipamento</span></li>	
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
									<label class="col-md-3 control-label" for="inputSuccess">Tipo de Equipamento</label>
									<div class="col-md-6">
										<select class="form-control mb-md" name="equip_type_id">
											<?php foreach ($equip_types as $row) { ?>}
												<?php if ($row['active'] == 1) { ?>
						                    		<option value = "<?php echo $row['id'] ?>"><?php echo $row['nome'] ?> </option>
						                    	<?php } ?>
						                    <?php } ?>
										</select>		
									</div>
								</div>	
								<div class="form-group">
									<label class="col-md-3 control-label" for="inputDefault">Marca</label>
									<div class="col-md-6">
										<input class="form-control" id="nome" type="text" name="nome" maxlength="100" required="true">
									</div>
								</div>								
								<div class="form-group">
									<label class="col-md-3 control-label" for="inputDefault">Número de série</label>
									<div class="col-md-6">
										<input class="form-control" id="serial_num" type="text" name="serial_num" maxlength="100" required="true">
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-3 control-label" for="inputSuccess">Ano</label>
									<div class="col-md-6">
										<select class="form-control mb-md" name="ano">
											<?php for ($i = 1995; $i <= 2030; $i++) { ?>
												<option value = "<?php echo $i ?>"><?php echo $i ?> </option>						                    	
						                    <?php } ?>
										</select>		
									</div>
								</div>		
								<div class="form-group">
									<label class="col-md-3 control-label" for="specs">Especificações</label>
									<div class="col-md-6">
										<textarea class="form-control" rows="3" id="specs" name="specs"></textarea>
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
          				$equip_type = $equip_type->listar();  
          				$record = $equipment->carregar($_GET["id"]);        
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
								<li><span><a href="equipments.php?mode=list">Administração de Equipamentos</a></span></li>								
								<li><span>Alteração de Dados do Equipamento</span></li>	
							</ol>												
						</div>									
					</header>
					<!-- start: page -->
					<section class="panel">
						<header class="panel-heading">
							<h2 class="panel-title">Editar de Equipamento</h2>
						</header>
						<form class="form-horizontal form-bordered" method="post">
							<div class="panel-body">	
								<div class="form-group">
									<label class="col-md-3 control-label" for="inputSuccess">Tipo de Equipamento</label>
									<div class="col-md-6">
										<select class="form-control mb-md" name="equip_type_id">
											<?php foreach ($equip_type as $row) { ?>}
												<?php if ($row['active'] == 1) { ?>
						                    		<option value = "<?php echo $row['id'] ?>" <?php if ($record['equip_type_id'] == $row['id']) echo "selected"; ?> ><?php echo $row['nome'] ?> </option>
						                    	<?php } ?>
						                    <?php } ?>
										</select>		
									</div>
								</div>	
								<div class="form-group">
									<label class="col-md-3 control-label" for="inputDefault">Marca</label>
									<div class="col-md-6">
										<input class="form-control" id="nome" type="text" name="nome" maxlength="100" required="true" value="<?php echo $record['nome']; ?>">
									</div>
								</div>								
								<div class="form-group">
									<label class="col-md-3 control-label" for="inputDefault">Número de série</label>
									<div class="col-md-6">
										<input class="form-control" id="serial_num" type="text" name="serial_num" maxlength="100" required="true" value="<?php echo $record['serial_num']; ?>">
									</div>
								</div>	
								<div class="form-group">
									<label class="col-md-3 control-label" for="inputSuccess">Ano</label>
									<div class="col-md-6">
										<select class="form-control mb-md" name="ano">
											<?php for ($i = 1995; $i <= 2030; $i++) { ?>
												<option value = "<?php echo $i ?>" <?php if ($record['ano'] == $i) echo "selected"; ?> ><?php echo $i ?> </option>						                    	
						                    <?php } ?>
										</select>		
									</div>
								</div>		
								<div class="form-group">
									<label class="col-md-3 control-label" for="specs">Especificações</label>
									<div class="col-md-6">
										<textarea class="form-control" rows="3" id="specs" name="specs"><?php echo $record['specs']; ?>"</textarea>
									</div>
								</div>		
							</div>
							<input type="hidden" name="id" value="<?php echo $record['id'] ?>" />
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
							$equipment->setId($_GET['id']);
							$result = $equipment->delete($equipment);
							if ($result) {
								header('Location: equipments.php?mode=list');
							}			              
          				}
      				?>
      				<?php if ($mode == RESTORE) {    
							$equipment->setId($_GET['id']);
							$flgTipoDesabilitado = $equipment->checkTipoEquipamentoAtivo($equipment);
							if ($flgTipoDesabilitado > 0) {
								$error = 'Não é possível habilitar equipamento de um tipo inativo.';
								$_SESSION['error'] = $error;
								header('Location: equipments.php?mode=list');
							} else {
								$result = $equipment->restore($equipment);
								if ($result) {
									header('Location: equipments.php?mode=list');
								}			              	
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
		<script src="assets/vendor/pnotify/pnotify.custom.js"></script>


		
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
		<script src="assets/javascripts/ui-elements/examples.modals.js"></script>

	</body>
</html>