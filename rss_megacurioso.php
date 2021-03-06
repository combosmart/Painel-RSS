<?php 	
	error_reporting(E_ERROR | E_PARSE);	
	$mode = $_GET["mode"];
	$title = 'Combo Vídeos - Administração de Feeds: Megacurioso';
	include 'header.php'; 
	
	// ao entrar na página, verifica o feed do dia. se já tiver atualizado no banco
	// não faz nada; caso contrário, baixa.
	
	if ($mode == LISTAR) { 
		$listaOk = $megacurioso->saveXML();
		if (!listaOk) {
			$_SESSION['error'] = "Erro ao baixar feed de notícias do dia";
		}
	}

	if(!empty($_POST['clear'])) {
		$result = $megacurioso->clearAll();
		if ($result) {
			$message = 'Playlist esvaziada com sucesso.';
			$_SESSION['success'] = $message;
			header('Location: rss_megacurioso.php?mode=list');
		} else {
			$message = 'Erro ao esvaziar playlist.';
			$_SESSION['error'] = $message;
			header('Location: rss_megacurioso.php?mode=list');
		}
	}

	if(!empty($_POST['edit'])) {

		$megacurioso->setId($_POST['id']);
		$megacurioso->setDestaque($_POST['destaque']);
		$megacurioso->setExibir($_POST['exibir']);
		$megacurioso->setTitle($_POST['title']);

		if (is_uploaded_file($_FILES["post_img"]["tmp_name"])) {
			$target_dir = "uploads/";
			$target_file = $target_dir . basename($_FILES["post_img"]["name"]);
			$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
			$check = getimagesize($_FILES["post_img"]["tmp_name"]);

			if (!$check) { $error[] = 'Arquivo não é uma imagem.'; }
			if (file_exists($target_file)) { $error[] = 'Já existe um arquivo no servidor com este nome.'; }
			//if ($_FILES["post_img"]["size"] > 500000) { $error[] = 'Arquivo grande demais.'; }

			if(!isset($error)) {
				if (move_uploaded_file($_FILES["post_img"]["tmp_name"], $target_file)) {
					$link = DIR . $target_dir . $_FILES["post_img"]["name"];
					$megacurioso->setLink($link);
				} else {
					$error[] = 'Ocorreu um erro no upload do aquivo.';	
				}
			}

		} else {
			$megacurioso->setLink($_POST['link']);			
		}
		
		if(!isset($error)){
			$result = $megacurioso->update($megacurioso);
			if ($result) {
				$success = 'Alterações no item gravadas com sucesso.';
				$_SESSION['success'] = $success;
				header('Location: rss_megacurioso.php?mode=list');
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
							if (!(in_array($_SESSION['role_id'], array(ADMINISTRADOR, USUARIO)))) { header('Location: main.php'); }
							$feed = $megacurioso->listar();
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
								<li><span>Administração de Feeds: Megacurioso</span></li>								
							</ol>							
						</div>									
					</header>
					<!-- start: page -->
					<section class="panel">
						<header class="panel-heading">
							<h2 class="panel-title">Feed de publicações do Megacurioso <a class="mb-1 mt-1 mr-1 btn btn-default" data-toggle="modal" data-target="#modalBootstrap" href="#">Limpar Feed</a></h2>
						</header>
						<!-- MODAL LIMPAR FEED -->
						<form method="post">
						<div class="modal" id="modalBootstrap" tabindex="-1" role="dialog">
							<div class="modal-dialog" role="document">
								<div class="modal-content">
									<div class="modal-header">
										<h5 class="modal-title">Atenção!</h5>
										<button type="button" class="close" data-dismiss="modal" aria-label="Close">
											<span aria-hidden="true">&times;</span>
										</button>
									</div>
									<div class="modal-body">
										<p>Você está prestes a remover todos os itens da playlist. Deseja continuar?</p>
									</div>
									<div class="modal-footer">
										<button type="submit" name="clear" value="clear" class="btn btn-primary">Sim</button>
										<button type="button" class="btn btn-default" data-dismiss="modal">Não</button>
									</div>
								</div>
							</div>
						</div>		
						</form>			
						<!-- /MODAL LIMPAR FEED -->
						<div class="panel-body">
							<table class="table table-bordered table-striped mb-none" id="datatable-default" data-swf-path="assets/vendor/jquery-datatables/extras/TableTools/swf/copy_csv_xls_pdf.swf">
								<thead>
									<tr>
										<th>&nbsp;</th>
										<th>Destaque</th>
										<th>Data do Feed</th>
										<th>Título</th>
										<th>Exibir</th>
										<th>Ações</th>
									</tr>
								</thead>
								<tbody>
									<?php foreach ($feed as $row) { ?>
									<tr>
										<td><?php echo $row['id']; ?></td>
										<td><?php echo $row['destaque']; ?></td>
										<td><?php echo formataData($row['data_item']); ?></td>
										<td><?php echo $row['title']; ?></td>
										<td><?php echo label_active($row['exibir']); ?></td>
										<td>
											<a href="./rss_megacurioso.php?mode=edit&id=<?php echo $row['id']; ?>"><i class="fa fa-edit" aria-hidden="true" data-toggle="tooltip" title="Editar"></i></a>
											&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;
											<?php if ($row['exibir'] == 1) { ?>
												<a href="./rss_megacurioso.php?mode=ocultar&id=<?php echo $row['id']; ?>" ><i class="fa fa-power-off" aria-hidden="true" data-toggle="tooltip" title="Ocultar"></i></a>
											<?php } else { ?>													
												<a href="./rss_megacurioso.php?mode=exibir&id=<?php echo $row['id']; ?>" ><i class="fa fa-power-off" aria-hidden="true" data-toggle="tooltip" title="Exibir"></i></a>
											<?php } ?>											
										</td>
									</tr>
									<?php } ?>
								</tbody>
							</table>
						</div>							
					</section>													
					</div>															
					<!-- end: page -->
					<?php 
						} // LISTAR 
					?>
					<?php 
						if ($mode == EDITAR) { 
						if (!(in_array($_SESSION['role_id'], array(ADMINISTRADOR, USUARIO)))) { header('Location: main.php'); }
          				$record = $megacurioso->carregar($_GET["id"]);        
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
								<li><span><a href="rss_megacurioso.php?mode=list">Administração de Feeds: Megacurioso</a></span></li>								
								<li><span>Alteração de Texto do Feed</span></li>	
							</ol>												
						</div>									
					</header>
					<!-- start: page -->
					<section class="panel">
						<header class="panel-heading">
							<h2 class="panel-title">Editar Conteúdo</h2>
						</header>
						<form class="form-horizontal form-bordered" method="post" enctype="multipart/form-data">
							<div class="panel-body">	
								<div class="form-group">
									<label class="col-md-3 control-label" for="inputDefault">Destaque</label>
									<div class="col-md-6">
										<input class="form-control" id="nome" type="text" name="destaque" maxlength="100" required="true" value="<?php echo $record['destaque']; ?>">
									</div>
								</div>								
								<div class="form-group">
									<label class="col-md-3 control-label" for="inputDefault">Título</label>
									<div class="col-md-6">
										<input class="form-control" id="title" type="text" name="title" value="<?php echo $record['title']; ?>" required="true">
									</div>
								</div>	
								<div class="form-group">
									<label class="col-md-3 control-label" for="inputSuccess">Exibir</label>
									<div class="col-md-6">
										<select class="form-control mb-md" name="exibir">
											<option value = "1" <?php if ($record['exibir'] == 1) echo "selected"; ?> >Sim</option>
											<option value = "0" <?php if ($record['exibir'] == 0) echo "selected"; ?> >Não</option>
										</select>		
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-3 control-label" for="inputSuccess">Imagem</label>
									<div class="col-md-6">
										<span id="img_post"><img src="<?php echo $record['link']; ?> " height="200" width="300"></span>								
									</div>
									<input type="hidden" name="link" value="<?=$record['link']?>">									
								</div>
								<div class="form-group row">
									<label class="col-lg-3 control-label text-lg-right pt-2">Trocar Imagem</label>
									<div class="col-lg-6">
										<div class="fileupload fileupload-new" data-provides="fileupload">
											<div class="input-append">
												<div class="uneditable-input">
													<i class="fa fa-file fileupload-exists"></i>
													<span class="fileupload-preview"></span>
												</div>
												<span class="btn btn-default btn-file">
													<span class="fileupload-exists">Alterar</span>
													<span class="fileupload-new">Selecionar</span>
													<input type="file" name="post_img" />
												</span>
												<a href="#" class="btn btn-default fileupload-exists" data-dismiss="fileupload">Remover</a>
											</div>
										</div>
									</div>
								</div>
							</div>
							<input type="hidden" name="id" value="<?php echo $record['id'] ?>" />
							<footer class="panel-footer">
								<div class="row">
									<div class="col-sm-9 col-sm-offset-3">
										<button class="btn btn-primary" name="edit" value="edit">Enviar</button>
										<button type="button" class="btn btn-default" onclick="window.location='rss_megacurioso.php?mode=list'">Voltar</button>
									</div>
								</div>
							</footer>
						</form>
					</section>
					<!-- end: page -->
					<?php 
						} // EDITAR
					?>
					<?php if ($mode == OCULTAR) {    
							$result = $megacurioso->ocultar($_GET['id']);
							if ($result) {
								header('Location: rss_megacurioso.php?mode=list');
							}			              
          				}
      				?>
      				<?php if ($mode == EXIBIR) {    
							$result = $megacurioso->exibir($_GET['id']);
							if ($result) {
								header('Location: rss_megacurioso.php?mode=list');
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