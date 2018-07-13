<?php 	
	
	$mode = $_GET["mode"];
	$title = 'Combo Vídeos - Administração de Pontos';
	include 'header.php'; 

	$id_ponto = $_SESSION['id_ponto'];
	$ponto = $ponto->carregar($id_ponto);
	$equipAssociados  = $equipment->listarEquipamentosAssociados($id_ponto);
	$equipDisponiveis = $equipment->listarEquipamentosDisponiveis();

	if(!empty($_POST['remover'])) {
		$equipamentos = $_POST["id_equipment"];
		foreach ($equipamentos as $e) {
			$result = $equipment->removerEquipamentos($e, $id_ponto);
		}

		if ($result) {
			$success = "Operação de <strong>remoção de equipamentos</strong> efetuada com sucesso";
			$_SESSION['success'] = $success;		
			header('Location: associar_equipamentos.php');	
		}
	}

	if(!empty($_POST['adicionar'])) {
		$equipamentos = $_POST["id_equipment"];
		foreach ($equipamentos as $e) {
			$result = $equipment->adicionarEquipamentos($e, $id_ponto);
		}

		if ($result) {
			$success = "Operação de <strong>associação de equipamentos</strong> efetuada com sucesso";
			$_SESSION['success'] = $success;		
			header('Location: associar_equipamentos.php');	
		}
	}

?>


<!-- camada visual -->

			<div class="inner-wrapper">

			<?php include 'sidebar.php'; ?>

				<section role="main" class="content-body">
					<?php 						
							if ($_SESSION['role_id'] != ADMINISTRADOR) { header('Location: main.php'); }
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
								<li><span>Administração de Pontos</span></li>								
								<li><span><a href="selecionar_ponto.php">Associar Equipamentos aos Pontos</a></span></li>
							</ol>
							<a class="sidebar-right-toggle" data-open="sidebar-right"><i class="fa fa-chevron-left"></i></a>
						</div>									
					</header>
					<section class="panel">
						<header class="panel-heading">
							<h2 class="panel-title">Ponto: <?php echo $ponto['nome']; ?></h2>
						</header>
						<div class="panel-body">
							Utilize o painel abaixo para administrar os equipamentos relacionados ao ponto. Caso o ponto seja desabilitado na tela de administração, os equipamentos a ele relacionados estarão liberados para uso em outras unidades.
						</div>
					</section>

					<!-- start: page -->
					<section class="panel">
						<header class="panel-heading">
							<h2 class="panel-title">Equipamentos Associados ao Ponto</h2>
						</header>
						<form method="post">
						<div class="panel-body">
							<table class='table table-bordered mb-none' id='associados'>
								<thead>
									<tr>
										<th>&nbsp;</th>
										<th>Tipo</th>
										<th>Marca</th>
										<th>Descrição</th>
									</tr>
								</thead>
								<tbody>
									<?php foreach ($equipAssociados as $row) { ?>
									<tr>
										<td><input type="checkbox" name="id_equipment[]" value="<?php echo $row['id'] ?>"></td>
										<td><?php echo $row['tipo']; ?></td>
										<td><?php echo $row['nome']; ?></td>
										<td><?php echo $row['specs']; ?></td>										
									</tr>
									<?php } ?>
								</tbody>
							</table>
						</div>	
						<div class="panel-body">
							<button class="mb-xs mt-xs mr-xs btn btn-default" name="remover" value="remover" <?php if (count($equipAssociados) == 0) { echo "disabled='true'"; } ?>>Remover Selecionados</button>
						</div>	
						</form>						
					</section>								
					<!-- end: page -->

					<!-- start: page -->
					<section class="panel">
						<header class="panel-heading">
							<h2 class="panel-title">Equipamentos Disponíveis</h2>
						</header>
						<form method="post">
						<div class="panel-body">
							<table class='table table-bordered mb-none' id='associados'>
								<thead>
									<tr>
										<th>&nbsp;</th>
										<th>Tipo</th>
										<th>Marca</th>
										<th>Descrição</th>
									</tr>
								</thead>
								<tbody>
									<?php foreach ($equipDisponiveis as $row) { ?>
									<tr>
										<td><input type="checkbox" name="id_equipment[]" value="<?php echo $row['id'] ?>"></td>
										<td><?php echo $row['tipo']; ?></td>
										<td><?php echo $row['nome']; ?></td>
										<td><?php echo $row['specs']; ?></td>										
									</tr>
									<?php } ?>
								</tbody>
							</table>
						</div>	
						<div class="panel-body">
							<button class="mb-xs mt-xs mr-xs btn btn-default" name="adicionar" value="adicionar" <?php if (count($equipDisponiveis) == 0) { echo "disabled='true'"; } ?>>Adicionar Selecionados</button>
						</div>	
						</form>						
					</section>								
					<!-- end: page -->
				</section>
			</div>
			
		</section>

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