<?php 	
	
	$mode = $_GET["mode"];
	$title = 'Combo Vídeos - Administração de Pontos';
	include 'header.php'; 

	if(!empty($_POST['add'])) {	  			

		$client->setId($_POST['id_client']);

		$ponto->setNome($_POST['nome']);
		$ponto->setCliente($client);
		$ponto->setCep($_POST['cep']);
		$ponto->setEndereco($_POST['endereco']);
		$ponto->setNumero($_POST['numero']);
		$ponto->setBairro($_POST['bairro']);
		$ponto->setCidade($_POST['cidade']);
		$ponto->setUf($_POST['estado']);
		$ponto->setObservacao($_POST['observacao']);

		if ($ponto->checkExisting($ponto) > 0) {
			$error[] = 'Apelido informado já existe no sistema.';
		}
		
		if(!isset($error)) {
			$result = $ponto->save($ponto);			
			if ($result) {
				$success = 'Ponto cadastrado com sucesso.';
				$_SESSION['success'] = $success;
				header('Location: ponto.php?mode=list');
			}			
		}

	} // script Adicionar

	if(!empty($_POST['edit'])) {

		$client->setId($_POST['id_client']);

		$ponto->setId($_POST['id']);
		$ponto->setNome($_POST['nome']);
		$ponto->setCliente($client);
		$ponto->setCep($_POST['cep']);
		$ponto->setEndereco($_POST['endereco']);
		$ponto->setNumero($_POST['numero']);
		$ponto->setBairro($_POST['bairro']);
		$ponto->setCidade($_POST['cidade']);
		$ponto->setUf($_POST['estado']);
		$ponto->setObservacao($_POST['observacao']);

		if ($ponto->checkDuplicate($ponto) > 0) {
			$error[] = 'Apelido informado já existe no sistema.';
		}

		if(!isset($error)){
			$result = $ponto->update($ponto);
			if ($result) {
				$success = 'Alterações no ponto <strong>'. $ponto->getNome() .'</strong> gravadas com sucesso.';
				$_SESSION['success'] = $success;
				header('Location: ponto.php?mode=list');
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
							$pontos = $ponto->listar();
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
								<li><span>Administração de Pontos</span></li>								
							</ol>
							<a class="sidebar-right-toggle" data-open="sidebar-right"><i class="fa fa-chevron-left"></i></a>
						</div>									
					</header>
					<!-- start: page -->
					<section class="panel">
						<header class="panel-heading">
							<h2 class="panel-title">Pontos Cadastrados</h2>
						</header>

						<div class="panel-body">
							<table class="table table-bordered table-striped mb-none" id="datatable-tabletools" data-swf-path="assets/vendor/jquery-datatables/extras/TableTools/swf/copy_csv_xls_pdf.swf"s>
								<thead>
									<tr>
										<th>Nome</th>
										<th>Cliente</th>
										<th>Cidade</th>
										<th>Num. de equipamentos</th>
										<th>Status</th>
										<th>Ações</th>
									</tr>
								</thead>
								<tbody>
									<?php foreach ($pontos as $row) { ?>
									<tr>
										<td><?php echo $row['nome']; ?></td>
										<td><?php echo $row['cliente']; ?></td>
										<td><?php echo $row['cidade']; ?></td>
										<td><?php echo $row['equipamentos']; ?></td>
										<td><?php echo label_active($row['active']); ?></td>
										<td>
											<a href="./ponto.php?mode=edit&id=<?php echo $row['id']; ?>"><i class="fa fa-edit" aria-hidden="true" data-toggle="tooltip" title="Editar"></i></a>
											&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;
											<?php if ($row['active'] == 1) { ?>
												<a href="#modalIcon" class="mb-xs mt-xs mr-xs modal-basic open-modal"><i class="fa fa-trash" aria-hidden="true" data-toggle="tooltip" onclick="javaScript:save('<?php echo $row['id']; ?>')" title="Remover"></i></a>
											<?php } else { ?>													
												<a href="./ponto.php?mode=restore&id=<?php echo $row['id']; ?>" ><i class="fa fa-power-off" aria-hidden="true" data-toggle="tooltip" title="Restaurar"></i></a>
											<?php } ?>											
										</td>
									</tr>
									<?php } ?>
								</tbody>
							</table>
						</div>	
						<div class="panel-body">
							<a href="ponto.php?mode=add" class="mb-xs mt-xs mr-xs btn btn-default">Adicionar</a>
						</div>	
						<script type="text/javascript">
							function save(id) {
								document.getElementById('id_ponto').value = id; 								
							}

							function submitForm() {
								var client = document.getElementById('id_ponto').value;
								window.location.href = 'ponto.php?mode=delete&id=' + client;
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
										<p><strong>Atenção: </strong> ao desabilitar o ponto, você automaticamente disponibiliza seus equipamentos para uso em outro local. Quando reabilitar o ponto, uma nova associação equipamento-pontos deverá ser feita.</p>
										<p>Isto dito, deseja ainda assim continuar?</p>
									</div>
									<input type="hidden" name="id_ponto" id="id_ponto" value=""/>

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
								<li><span><a href="ponto.php?mode=list">Administração de Pontos</a></span></li>								
								<li><span>Cadastro de Pontos</span></li>	
							</ol>												
						</div>									
					</header>
					<?php 
						$comboClientes = $client->listar();
					 ?>
					<!-- start: page -->
					<section class="panel">
						<header class="panel-heading">
							<h2 class="panel-title">Cadastrar Ponto</h2>
						</header>
						<form class="form-horizontal form-bordered" method="post">
							<div class="panel-body">	
								<div class="form-group">
									<label class="col-md-3 control-label" for="inputDefault">Apelido do ponto</label>
									<div class="col-md-6">
										<input class="form-control" id="nome" type="text" name="nome" maxlength="100" required="true">
									</div>
								</div>								
								<div class="form-group">
									<label class="col-md-3 control-label" for="inputSuccess">Cliente</label>
									<div class="col-md-6">
										<select class="form-control mb-md" name="id_client">
											<?php foreach ($comboClientes as $r) { ?>
								            	<option value = "<?php echo $r['id'] ?>" ><?php echo $r['nome'] ?> </option>
								            <?php } ?>
										</select>		
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-3 control-label" for="cep">CEP</label>
									<div class="col-md-6">
										<input class="form-control" id="cep" type="number" name="cep" max="99999999" required="true" onChange="getEndereco()">
									</div>
								</div>					
								<div class="form-group">
									<label class="col-md-3 control-label" for="inputDefault">Endereço</label>
									<div class="col-md-6">
										<input class="form-control" id="endereco" type="text" name="endereco" maxlength="255" required="true">										
									</div>
								</div>											
								<div class="form-group">
									<label class="col-md-3 control-label" for="inputDefault">Número</label>
									<div class="col-md-6">
										<input class="form-control" id="numero" type="text" name="numero" maxlength="10" required="true">										
									</div>
								</div>											
								<div class="form-group">
									<label class="col-md-3 control-label" for="inputDefault">Bairro</label>
									<div class="col-md-6">
										<input class="form-control" id="bairro" type="text" name="bairro" maxlength="100" required="true">										
									</div>
								</div>											
								<div class="form-group">
									<label class="col-md-3 control-label" for="inputDefault">Cidade</label>
									<div class="col-md-6">
										<input class="form-control" id="cidade" type="text" name="cidade" maxlength="100" required="true">										
									</div>
								</div>											
								<div class="form-group">
									<label class="col-md-3 control-label" for="inputDefault">Estado</label>
									<div class="col-md-6">
										<input class="form-control" id="estado" type="text" name="estado" maxlength="2" required="true">										
									</div>
								</div>	
								<div class="form-group">
									<label class="col-md-3 control-label" for="observacao">Observações</label>
									<div class="col-md-6">
										<textarea class="form-control" rows="3" id="observacao" name="observacao"></textarea>
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
								<li><span><a href="ponto.php?mode=list">Administração de Pontos</a></span></li>								
								<li><span>Cadastro de Pontos</span></li>	
							</ol>												
						</div>									
					</header>
					<?php 
						$comboClientes = $client->listar();
						$record = $ponto->carregar($_GET['id']);
					 ?>
					<!-- start: page -->
					<section class="panel">
						<header class="panel-heading">
							<h2 class="panel-title">Editar Ponto</h2>
						</header>
						<form class="form-horizontal form-bordered" method="post">
							<div class="panel-body">	
								<div class="form-group">
									<label class="col-md-3 control-label" for="inputDefault">Apelido do ponto</label>
									<div class="col-md-6">
										<input class="form-control" id="nome" type="text" name="nome" maxlength="100" required="true" value="<?php echo $record['nome'] ?>">
									</div>
								</div>								
								<div class="form-group">
									<label class="col-md-3 control-label" for="inputSuccess">Cliente</label>
									<div class="col-md-6">
										<select class="form-control mb-md" name="id_client">
											<?php foreach ($comboClientes as $r) { ?>
								            	<option value = "<?php echo $r['id'] ?>" <?php if ($r['id'] == $record['id_client']) echo "selected"; ?> ><?php echo $r['nome'] ?> </option>
								            <?php } ?>
										</select>		
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-3 control-label" for="cep">CEP</label>
									<div class="col-md-6">
										<input class="form-control" id="cep" type="number" name="cep" max="99999999" required="true" onChange="getEndereco()" value="<?php echo $record['cep'] ?>">
									</div>
								</div>					
								<div class="form-group">
									<label class="col-md-3 control-label" for="inputDefault">Endereço</label>
									<div class="col-md-6">
										<input class="form-control" id="endereco" type="text" name="endereco" maxlength="255" required="true" value="<?php echo $record['endereco'] ?>">										
									</div>
								</div>											
								<div class="form-group">
									<label class="col-md-3 control-label" for="inputDefault">Número</label>
									<div class="col-md-6">
										<input class="form-control" id="numero" type="text" name="numero" maxlength="10" required="true" value="<?php echo $record['numero'] ?>">										
									</div>
								</div>											
								<div class="form-group">
									<label class="col-md-3 control-label" for="inputDefault">Bairro</label>
									<div class="col-md-6">
										<input class="form-control" id="bairro" type="text" name="bairro" maxlength="100" required="true" value="<?php echo $record['bairro'] ?>" >			
									</div>
								</div>											
								<div class="form-group">
									<label class="col-md-3 control-label" for="inputDefault">Cidade</label>
									<div class="col-md-6">
										<input class="form-control" id="cidade" type="text" name="cidade" maxlength="100" required="true" value="<?php echo $record['cidade'] ?>">			
									</div>
								</div>											
								<div class="form-group">
									<label class="col-md-3 control-label" for="inputDefault">Estado</label>
									<div class="col-md-6">
										<input class="form-control" id="estado" type="text" name="estado" maxlength="2" required="true" value="<?php echo $record['uf'] ?>">
									</div>
								</div>	
								<div class="form-group">
									<label class="col-md-3 control-label" for="observacao">Observações</label>
									<div class="col-md-6">
										<textarea class="form-control" rows="3" id="observacao" name="observacao"><?php echo $record['observacao'] ?></textarea>
									</div>
								</div>	
								<input type="hidden" name="id" value="<?php echo $record['id'] ?>" />					
							</div>							
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
							$ponto->setId($_GET['id']);	
							$result = $ponto->delete($ponto);	
							if ($result) {
								header('Location: ponto.php?mode=list');
							}			              
														
          				}
      				?>
      				<?php if ($mode == RESTORE) {    
						
      						$ponto->setId($_GET['id']);	

							$flagClienteAtivo = $ponto->checkClienteAtivo($ponto);
							
							if ($flagClienteAtivo == 0) {
								$error = 'Não é possível habilitar ponto de um cliente inativo.';
								$_SESSION['error'] = $error;
								header('Location: ponto.php?mode=list');
							} else {
								$result = $ponto->restore($ponto);
								if ($result) {
									header('Location: ponto.php?mode=list');
								}			              
							}							

							
							
          				}
      				?>
				</section>
			</div>
			
		</section>

		<script>
			$(document).ready(function() {
			    $('[data-toggle="tooltip"]').tooltip();			    
			});

			function getEndereco() {
				// Se o campo CEP não estiver vazio
				if($.trim($("#cep").val()) != ""){
					//document.getElementById("load").style.display = 'block';
						/* 
								Para conectar no serviço e executar o json, precisamos usar a função
								getScript do jQuery, o getScript e o dataType:"jsonp" conseguem fazer o cross-domain, os outros
								dataTypes não possibilitam esta interação entre domínios diferentes
								Estou chamando a url do serviço passando o parâmetro "formato=javascript" e o CEP digitado no formulário
								http://cep.republicavirtual.com.br/web_cep.php?formato=javascript&cep="+$("#cep").val()
						*/
						$.getScript("http://cep.republicavirtual.com.br/web_cep.php?formato=javascript&cep="+$("#cep").val(), function(){
								// o getScript dá um eval no script, então é só ler!
								//Se o resultado for igual a 1
								if(resultadoCEP["resultado"] && resultadoCEP["bairro"] != ""){
										// troca o valor dos elementos
										$("#endereco").val(unescape(resultadoCEP["tipo_logradouro"])+ " " +unescape(resultadoCEP["logradouro"]));
										$("#bairro").val(unescape(resultadoCEP["bairro"]));
										$("#cidade").val(unescape(resultadoCEP["cidade"]));
										$("#estado").val(unescape(resultadoCEP["uf"]));
										//$("#enderecoCompleto").show("slow");
										$("#num").focus();
										//document.getElementById("load").style.display = 'none';
										//validate()
								}else{
										alert(unescape("Endere%E7o n%E3o encontrado"));
										//$("#enderecoCompleto").show("slow");
										return false;
								}
						});                             
				}
			    else
			    {
			        alert('Antes, preencha o campo CEP!')
					//document.getElementById("load").style.display = 'none';
			    }
				
			}
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