<?php 	
	
	$mode = $_GET["mode"];
	$title = 'Combo Vídeos - Administração de Clientes';
	include 'header.php'; 

	if(!empty($_POST['add'])) {	  			

		$client->setNome($_POST['nome']);

		if ($client->checkExistingClient($client) > 0) {
			$error[] = 'Cliente informado já existe no sistema.';
		}
		
		if(!isset($error)) {
			$result = $client->save($client);
			if ($result) {
				$success = 'Cliente cadastrado com sucesso.';
				$_SESSION['success'] = $success;
				header('Location: client.php?mode=list');
			}			
		}

	} // script Adicionar

	if(!empty($_POST['edit'])) {

		$client->setId($_POST['id']);
		$client->setNome($_POST['nome']);

		if(!isset($error)){
			$result = $client->update($client);
			if ($result) {
				$success = 'Alterações no cliente '. $client->getNome() .' gravadas com sucesso.';
				$_SESSION['success'] = $success;
				header('Location: client.php?mode=list');
			}
		}
	}  // script Editar

	if(!empty($_POST['remove'])) {
		$client->setId($_POST['id_client']);
		$result = $client->delete($client);
		if ($result) {
			header('Location: client.php?mode=list');
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
							$clients = $client->listar();
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
								<li><span>Administração de Clientes</span></li>								
							</ol>
							<a class="sidebar-right-toggle" data-open="sidebar-right"><i class="fa fa-chevron-left"></i></a>
						</div>									
					</header>
					<!-- start: page -->
					<section class="panel">
						<header class="panel-heading">
							<h2 class="panel-title">Clientes Cadastrados</h2>
						</header>

						<div class="panel-body">
							<table class="table table-bordered table-striped mb-none" id="datatable-tabletools" data-swf-path="assets/vendor/jquery-datatables/extras/TableTools/swf/copy_csv_xls_pdf.swf"s>
								<thead>
									<tr>
										<th>Nome</th>
										<th>Num. de <br/>Pontos Ativos</th>
										<th>Status</th>
										<th>Ações</th>
									</tr>
								</thead>
								<tbody>
									<?php foreach ($clients as $row) { ?>
									<tr>
										<td><?php echo $row['nome']; ?></td>
										<td><?php echo $row['pontos']; ?></td>
										<td><?php echo label_active($row['active']); ?></td>
										<td>
											<a href="./client.php?mode=edit&id=<?php echo $row['id']; ?>"><i class="fa fa-edit" aria-hidden="true" data-toggle="tooltip" title="Editar"></i></a>
											&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;
											<?php if ($row['active'] == 1) { ?>
												<a href="#modalIcon" class="mb-xs mt-xs mr-xs modal-basic open-modal"><i class="fa fa-trash" aria-hidden="true" data-toggle="tooltip" onclick="javaScript:save('<?php echo $row['id']; ?>')" title="Remover"></i></a>
											<?php } else { ?>													
												<a href="./client.php?mode=restore&id=<?php echo $row['id']; ?>" ><i class="fa fa-power-off" aria-hidden="true" data-toggle="tooltip" title="Restaurar"></i></a>
											<?php } ?>											
										</td>
									</tr>
									<?php } ?>
								</tbody>
							</table>
						</div>		
						<div class="panel-body">
							<a href="client.php?mode=add" class="mb-xs mt-xs mr-xs btn btn-default">Adicionar</a>
						</div>	
						<script type="text/javascript">
							function save(id) {
								document.getElementById('id_client').value = id; 								
							}

							function submitForm() {
								var client = document.getElementById('id_client').value;
								window.location.href = 'client.php?mode=delete&id=' + client;
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
										<p><strong>Atenção: </strong> ao desabilitar o cliente, você automaticamente desabilita os pontos a ele relacionados e disponibiliza seus equipamentos para uso em outro local. Quando reabilitar o cliente, os pontos deverão ser reabilitados um a um e uma nova associação equipamento-pontos deverá ser feita.</p>
										<p>Isto dito, deseja ainda assim continuar?</p>
									</div>
									<input type="hidden" name="id_client" id="id_client" value=""/>

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
								<li><span><a href="client.php?mode=list">Administração de Clientes</a></span></li>								
								<li><span>Cadastro de Clientes</span></li>	
							</ol>												
						</div>									
					</header>
					<!-- start: page -->
					<section class="panel">
						<header class="panel-heading">
							<h2 class="panel-title">Cadastrar Cliente</h2>
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
          				$cliente = $client->carregar_cliente($_GET['id']);          
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
								<li><span><a href="client.php?mode=list">Administração de Clientes</a></span></li>								
								<li><span>Alteração de Dados de Clientes</span></li>	
							</ol>												
						</div>									
					</header>
					<!-- start: page -->
					<section class="panel">
						<header class="panel-heading">
							<h2 class="panel-title">Editar Clientes</h2>
						</header>
						<form class="form-horizontal form-bordered" method="post">
							<div class="panel-body">	
								<div class="form-group">
									<label class="col-md-3 control-label" for="inputDefault">Nome</label>
									<div class="col-md-6">
										<input class="form-control" id="nome" type="text" name="nome" maxlength="100" required="true" value="<?php echo $cliente['nome'] ?>">
									</div>
								</div>								
							</div>
							<input type="hidden" name="id" value="<?php echo $cliente['id'] ?>" />
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
							$client->setId($_GET['id']);
							$result = $client->delete($client);
							if ($result) {
								header('Location: client.php?mode=list');
							}			              
          				}
      				?>
      				<?php if ($mode == RESTORE) {    
							$client->setId($_GET['id']);
							$result = $client->restore($client);
							if ($result) {
								header('Location: client.php?mode=list');
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