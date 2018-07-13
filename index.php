<?php
	require_once('includes/config.php');
	if($user->is_logged_in()){ header('Location: main.php'); } 

	if (!empty($_POST)) {
	    
	    $username = $_POST['username'];
		$password = $_POST['password'];
		
		if (empty($username) || empty($password)) 
		{
		    $error[] = 'Preencha os campos corretamente';
		} else {
		    if($user->login($username,$password)) { 
			    $_SESSION['username'] = $username;
			    header('Location: main.php');
			    exit;
	        } else {
	    		$error[] = 'Login ou senha incorretos';
	    	}  
		    
		}
	} 
?>
<!doctype html>
<html class="fixed">
	<head>

		<title>Combo Vídeos</title>

		<!-- Basic -->
		<meta charset="UTF-8">

		<meta name="keywords" content="combo vídeos elemidia administração" />
		<meta name="description" content="Combo Vídeos - Área Administrativa">
		<meta name="author" content="smartcombo.com.br">

		<!-- Mobile Metas -->
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

		<!-- Web Fonts  -->
		<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800|Shadows+Into+Light" rel="stylesheet" type="text/css">

		<!-- Vendor CSS -->
		<link rel="stylesheet" href="assets/vendor/bootstrap/css/bootstrap.css" />

		<link rel="stylesheet" href="assets/vendor/font-awesome/css/font-awesome.css" />
		<link rel="stylesheet" href="assets/vendor/magnific-popup/magnific-popup.css" />
		<link rel="stylesheet" href="assets/vendor/bootstrap-datepicker/css/bootstrap-datepicker3.css" />

		<!-- Theme CSS -->
		<link rel="stylesheet" href="assets/stylesheets/theme.css" />

		<!-- Skin CSS -->
		<link rel="stylesheet" href="assets/stylesheets/skins/default.css" />

		<!-- Theme Custom CSS -->
		<link rel="stylesheet" href="assets/stylesheets/theme-custom.css">

		<!-- Head Libs -->
		<script src="assets/vendor/modernizr/modernizr.js"></script>

	</head>
	<body>
		<!-- start: page -->
		<section class="body-sign">
			<div class="center-sign">
				<a href="/" class="logo pull-left">
					<img src="assets/images/combo-logo.png" height="54" alt="Combo Videos" />
				</a>

				<div class="panel panel-sign">
					<div class="panel-title-sign mt-xl text-right">
						<h2 class="title text-uppercase text-weight-bold m-none"><i class="fa fa-user mr-xs"></i> Administração</h2>
					</div>
					<div class="panel-body">
						<form id="login-form" method="post" role="form">
							<?php 
					      	    if(isset($error)){
					                foreach($error as $error){
									    $loginBoxMsg = "<div class='alert alert-danger'>
									    					<strong>Erro: </strong>" . $error .
									    			   "</div>";
					                }					                
					            echo $loginBoxMsg;    
					            }
					      	?>
							<div class="form-group mb-lg">
								<label>Username</label>
								<div class="input-group input-group-icon">
									<input name="username" id="username" type="text" class="form-control input-lg" placeholder="Email" value="" />
									<span class="input-group-addon">
										<span class="icon icon-lg">
											<i class="fa fa-user"></i>
										</span>
									</span>
								</div>
							</div>

							<div class="form-group mb-lg">
								<div class="clearfix">
									<label class="pull-left">Senha</label>
									<a href="lost-pass.php" class="pull-right">Esqueci a senha.</a>
								</div>
								<div class="input-group input-group-icon">
									<input name="password" type="password" class="form-control input-lg" />
									<span class="input-group-addon">
										<span class="icon icon-lg">
											<i class="fa fa-lock"></i>
										</span>
									</span>
								</div>
							</div>

							<div class="row">								
								<div class="col-sm-12 text-center">
									<button type="submit" class="btn btn-primary hidden-xs" name="login-submit" id="login-submit">Acessar</button>
									<button type="submit" class="btn btn-primary btn-block btn-lg visible-xs mt-lg" name="login-submit" id="login-submit">Acessar</button>
								</div>
							</div>														
						</form>
					</div>
				</div>

				<p class="text-center text-muted mt-md mb-md">&copy; Copyright <?php echo date("Y"); ?> Combo Vídeos.</p>
			</div>
		</section>
		<!-- end: page -->

		<!-- Vendor -->
		<script src="assets/vendor/jquery/jquery.js"></script>
		<script src="assets/vendor/jquery-browser-mobile/jquery.browser.mobile.js"></script>
		<script src="assets/vendor/bootstrap/js/bootstrap.js"></script>
		<script src="assets/vendor/nanoscroller/nanoscroller.js"></script>
		<script src="assets/vendor/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
		<script src="assets/vendor/magnific-popup/jquery.magnific-popup.js"></script>
		<script src="assets/vendor/jquery-placeholder/jquery-placeholder.js"></script>
		
		<!-- Theme Base, Components and Settings -->
		<script src="assets/javascripts/theme.js"></script>
		
		<!-- Theme Custom -->
		<script src="assets/javascripts/theme.custom.js"></script>
		
		<!-- Theme Initialization Files -->
		<script src="assets/javascripts/theme.init.js"></script>

	</body>
</html>