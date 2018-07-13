<?php 	
	
	$mode = $_GET["mode"];
	$title = 'Combo Vídeos - Administração de Ocorrências';
	include 'header.php'; 

	$comboClientes  = $client->listarClientesComPontos();
	$comboProblemas = $problema->listar();

	if(!empty($_POST['add'])) {	  			
		
		$dataAbertura = formataDataDatePicker($_POST['data_abertura']) . " " . date('H:i:s');
		$ocorrencia->setDataAbertura($dataAbertura);
		$ocorrencia->setIdPonto($_POST['id_ponto']);
		$ocorrencia->setIdProblema($_POST['id_problema']);
		$ocorrencia->setDescricao($_POST['descr']);
		$ocorrencia->setIdUser($_SESSION['usuario_id']);
		$ocorrencia->setStatus("A");

		if(!isset($error)) {
			$result = $ocorrencia->save($ocorrencia);			
			if ($result) {
				$success = 'Ocorrência cadastrada com sucesso.';
				$_SESSION['success'] = $success;
				header('Location: ocorrencias.php?mode=list');
			}			
		}

	} // script Adicionar

	if(!empty($_POST['edit'])) {

		$ocorrencia->setIdPonto($_POST['id_ponto']);
		$ocorrencia->setIdProblema($_POST['id_problema']);
		$ocorrencia->setDescricao($_POST['descr']);
		$ocorrencia->setId($_POST['id']);
		$ocorrencia->setIdUser($_SESSION['usuario_id']);

		if(!isset($error)){
			$result = $ocorrencia->update($ocorrencia);
			if ($result) {
				$success = 'Alterações na ocorrência gravadas com sucesso.';
				$_SESSION['success'] = $success;
				header('Location: ocorrencias.php?mode=list');
			}
		}
	}  // script Editar

	if(!empty($_POST['close'])) {

		$ocorrencia->setId($_POST['id']);
		$ocorrencia->setStatus("F");
		$ocorrencia->setIdUser($_SESSION['usuario_id']);

		$result = $ocorrencia->encerrar($ocorrencia);
		
		if ($result) {
			$success = 'Ocorrência encerrada com sucesso.';
			$_SESSION['success'] = $success;
			header('Location: ocorrencias.php?mode=list');
		}
		
	}  // script Encerrar
	
?>


<!-- camada visual -->

			<div class="inner-wrapper">

			<?php include 'sidebar.php'; ?>

				<section role="main" class="content-body">
					<?php 						
						if ($mode == LISTAR) { 

							$ocorrencias = $ocorrencia->listar();
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
								<li><span>Administração de Ocorrências</span></li>								
							</ol>
							<a class="sidebar-right-toggle" data-open="sidebar-right"><i class="fa fa-chevron-left"></i></a>
						</div>									
					</header>
					<!-- start: page -->
					<section class="panel">
						<header class="panel-heading">
							<h2 class="panel-title">Ocorrências Registradas</h2>
						</header>

						<div class="panel-body">
							<table class="table table-bordered table-striped mb-none" id="datatable-tabletools" data-swf-path="assets/vendor/jquery-datatables/extras/TableTools/swf/copy_csv_xls_pdf.swf"s>
								<thead>
									<tr>
										<th>Data</th>
										<th>Cliente</th>
										<th>Ponto</th>
										<th>Problema</th>
										<th>Status</th>
										<th>Ações</th>
									</tr>
								</thead>
								<tbody>
									<?php foreach ($ocorrencias as $row) { ?>
									<tr>
										<td data-order="[unixTimestamp]"><?php echo formataData($row['dt']) . " (" . $row['tm'] . ")" ; ?></td>
										<td><?php echo $row['cliente']; ?></td>
										<td><?php echo $row['ponto']; ?></td>
										<td><?php echo $row['problema']; ?></td>
										<td><?php echo aberto_fechado($row['status']); ?></td>
										<?php if (in_array($_SESSION['role_id'], array(ADMINISTRADOR, TECNICO))) { ?>
											<td><a href="./ocorrencias.php?mode=edit&id=<?php echo $row['id']; ?>"><i class="fa fa-edit" aria-hidden="true" data-toggle="tooltip" title="Editar"></i></a></td>
										<?php } else { ?>										
											<td><a href="./ocorrencias.php?mode=edit&id=<?php echo $row['id']; ?>"><i class="fa fa-eye" aria-hidden="true" data-toggle="tooltip" title="Ver detalhes"></i></a></td>
										<?php } ?>
									</tr>
									<?php } ?>
								</tbody>
							</table>
						</div>	
						<?php if (in_array($_SESSION['role_id'], array(ADMINISTRADOR, TECNICO))) { ?>
						<div class="panel-body">
							<a href="ocorrencias.php?mode=add" class="mb-xs mt-xs mr-xs btn btn-default">Adicionar</a>
						</div>					
						<?php } ?>
					</section>								
					<!-- end: page -->
					<?php 
						} // LISTAR 
					?>
					
					<?php 
						if ($mode == ADICIONAR) { 
					
							if (!in_array($_SESSION['role_id'], array(ADMINISTRADOR, TECNICO))) { header('Location: main.php'); }

							if(isset($error)) { 
								echo "<div class='alert alert-danger'>";
								echo "<strong>Erro: </strong>";
								foreach ($error as $e) {
									echo $e;
								}
								echo "</div>";
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
								<li><span><a href="ocorrencias.php?mode=list">Administração de Ocorrências</a></span></li>								
								<li><span>Cadastro de Ocorrências</span></li>	
							</ol>												
						</div>									
					</header>
					<!-- start: page -->
					<section class="panel">
						<header class="panel-heading">
							<h2 class="panel-title">Cadastrar Ocorrência</h2>
						</header>
						<form class="form-horizontal form-bordered" method="post">
							<div class="panel-body">	
								<div class="form-group">
									<label class=" col-md-3 control-label">Aberto por</label>
									<div class="col-lg-6">
										<p class="form-control-static"><?php echo $_SESSION['nome_completo']; ?></p>
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-3 control-label">Data de abertura</label>
									<div class="col-md-6">
										<div class="input-group">
											<span class="input-group-addon">
												<i class="fa fa-calendar"></i>
											</span>
											<input type="text" data-plugin-datepicker class="form-control" name="data_abertura" id = "data_abertura" required="true">
										</div>
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-3 control-label">Ponto</label>
									<div class="col-md-6">
										<select data-plugin-selectTwo class="form-control populate pontos" name="id_ponto">
											<?php foreach ($comboClientes as $r) { ?>
												<?php $pontosClientes = $ponto->listarPorCliente($r['id']) ?>				
												<optgroup label="<?php echo $r['nome'] ?>">
													<?php foreach ($pontosClientes as $p) { ?>
														<option value="<?php echo $p['id'] ?>"><?php echo $p['nome'] ?></option>
													<?php } ?>
												</optgroup>	
											<?php } ?>
										</select>
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-3 control-label" for="inputSuccess">Natureza do Problema</label>
									<div class="col-md-6">
										<select class="form-control mb-md" name="id_problema">
											<?php foreach ($comboProblemas as $row) { ?>}
												<?php if ($row['active'] == 1) { ?>				
						                    	<option value = "<?php echo $row['id'] ?>" ><?php echo $row['nome'] ?> </option>
						                    	<?php } ?>
						                    <?php } ?>
										</select>		
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-3 control-label" for="observacao">Descrição</label>
									<div class="col-md-6">
										<textarea class="form-control" rows="3" id="descr" name="descr" required="true"></textarea>
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
							
							$record = $ocorrencia->carregar($_GET['id']);          
							if(isset($error)) { 
								echo "<div class='alert alert-danger'>";
								echo "<strong>Erro: </strong>";
								foreach ($error as $e) {
									echo $e;
								}
								echo "</div>";
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
								<li><span><a href="ocorrencias.php?mode=list">Administração de Ocorrências</a></span></li>								
								<li><span>Alteração de Dados da Ocorrência</span></li>	
							</ol>												
						</div>									
					</header>
					<!-- start: page -->
					<section class="panel">
						<header class="panel-heading">
							<h2 class="panel-title">Editar Ocorrência</h2>
						</header>
						<form class="form-horizontal form-bordered" name = "encerrarOcorrencia" method="post">
							<div class="panel-body">	
								<div class="form-group">
									<label class=" col-md-3 control-label">Aberto por</label>
									<div class="col-lg-6">
										<p class="form-control-static"><?php echo $record['nome']; ?></p>
									</div>
								</div>
								<div class="form-group">
									<label class=" col-md-3 control-label">Data de abertura</label>
									<div class="col-lg-6">
										<p class="form-control-static"><?php echo formataData($record['dt']) . " - " . $record['tm']; ?></p>
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-3 control-label">Ponto</label>
									<div class="col-md-6">
										<select data-plugin-selectTwo class="form-control populate pontos" name="id_ponto" <?php if ((in_array($_SESSION['role_id'], array(USUARIO))) || ($record['status'] == OCORRENCIA_FECHADA)) { echo "disabled" ; }  ?> >
											<?php foreach ($comboClientes as $r) { ?>
												<?php $pontosClientes = $ponto->listarPorCliente($r['id']) ?>				
												<optgroup label="<?php echo $r['nome'] ?>">
													<?php foreach ($pontosClientes as $p) { ?>
														<option value="<?php echo $p['id'] ?>" <?php if ($p['id'] == $record['id_ponto']) echo "selected"; ?> ><?php echo $p['nome'] ?></option>
													<?php } ?>
												</optgroup>	
											<?php } ?>
										</select>
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-3 control-label" for="inputSuccess">Natureza do Problema</label>
									<div class="col-md-6">
										<select class="form-control mb-md" name="id_problema" <?php if ((in_array($_SESSION['role_id'], array(USUARIO))) || ($record['status'] == OCORRENCIA_FECHADA)) { echo "disabled" ; }  ?> >
											<?php foreach ($comboProblemas as $row) { ?>}
												<?php if ($row['active'] == 1) { ?>				
						                    	<option value = "<?php echo $row['id'] ?>" <?php if ($row['id'] == $record['id_problema']) echo "selected"; ?> ><?php echo $row['nome'] ?> </option>
						                    	<?php } ?>
						                    <?php } ?>
										</select>		
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-3 control-label" for="observacao">Descrição</label>
									<div class="col-md-6">
										<textarea class="form-control" rows="3" id="descr" name="descr" required="true" <?php if ((in_array($_SESSION['role_id'], array(USUARIO))) || ($record['status'] == OCORRENCIA_FECHADA)) { echo "disabled" ; }  ?> ><?php echo $record['descr'] ?></textarea>
									</div>
								</div>
							</div>
							<input type="hidden" name="id" value="<?php echo $record['id'] ?>">
							<input type="hidden" name="close">
							<?php if (!in_array($_SESSION['role_id'], array(USUARIO))) {  ?>
								<?php if ($record['status'] != "F") { ?>
								<footer class="panel-footer">
									<div class="row">
										<div class="col-sm-9 col-sm-offset-3">
											<button class="btn btn-primary" name="edit" value="edit">Atualizar</button>
											<a href="#modalIcon" class="mb-xs mt-xs mr-xs modal-basic open-modal btn btn-primary">Encerrar Ocorrência</a>
										</div>
									</div>
								</footer>							
								<?php } ?>
							<?php } ?>
						</form>
					</section>
					<script type="text/javascript">
						function submitForm() {
							var frm = document.encerrarOcorrencia;
							frm.close.value = "1";
							frm.submit();
						}
					</script>
					<?php 
						$listaHistorico = $ocorrencia->listarHistorico($record['id']);
					 ?>
					<section class="panel">
						<header class="panel-heading">
							<h2 class="panel-title">Histórico de Alterações na Ocorrência</h2>
						</header>
						<div class="panel-body">
							<table class='table table-bordered mb-none' id='associados'>
								<thead>
									<tr>
										<th>Usuário</th>
										<th>Campo</th>
										<th>Valor Anterior</th>
										<th>Valor Posterior</th>
										<th>Data da alteração</th>
									</tr>
								</thead>
								<tbody>
									<?php foreach ($listaHistorico as $row) { ?>
									<tr>
										<td><?php echo $row['usuario']; ?></td>
										<td><?php echo utf8_encode($row['campo']); ?></td>
										<td><?php echo $row['old_value']; ?></td>
										<td><?php echo $row['new_value']; ?></td>										
										<td><?php echo formataData($row['dt']) . " (" . $row['tm'] . ")"; ?></td>
									</tr>
									<?php } ?>
								</tbody>
							</table>
						</div>	
						<?php if (in_array($_SESSION['role_id'], array(USUARIO)) || $record['status'] == "F") { ?>
						<div class="panel-body">
							<a href="ocorrencias.php?mode=list" class="mb-xs mt-xs mr-xs btn btn-default">Voltar</a>
						</div>					
						<?php } ?>	
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
										<p><strong>Atenção: </strong> A operação de encerramento de ocorrência não pode ser desfeita.</p>
										<p>Deseja ainda assim continuar?</p>
									</div>											
								</div>
							</div>
							<footer class="panel-footer">
								<div class="row">
									<div class="col-md-12 text-right">
										<a href="#" class="btn btn-primary" onclick="submitForm()">Encerrar</a>
										<button class="btn btn-default modal-dismiss">Cancelar</button>
									</div>
								</div>
							</footer>							
						</section>														
					</div>					
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
		<script src="assets/vendor/bootstrap-datepicker/locales/bootstrap-datepicker.pt-BR.min.js"></script>


		
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
		<script src="assets/javascripts/forms/examples.advanced.form.js"></script>
		<script src="assets/javascripts/ui-elements/examples.modals.js"></script>

		<script>
			$(document).ready(function(){
			    $('[data-toggle="tooltip"]').tooltip();
			    //$(".pontos").select2();
			});

			$('#data_abertura').datepicker({
		    	format: 'dd/mm/yyyy',
		    	language: 'pt-BR',
		    	weekStart: 0,
		    	startDate:'0d',
		    	todayHighlight: true
		    });
		</script>

	</body>
</html>