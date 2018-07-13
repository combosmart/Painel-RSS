<?php require('includes/config.php');

if(!empty($_POST)){

	$cssInfo    = "<div class='alert alert-info'>";
	$cssError   = "<div class='alert alert-danger'>";
	$cssSuccess = "<div class='alert alert-success'>";

	
	if (empty($_POST['email'])) {
		$error[] = $cssError . 'Preencha o campo de e-mail</div>';
	} else {
		if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
		    $error[] = $cssError . 'Formato inválido de e-mail</div>';
		} else {
			$stmt = $db->prepare('SELECT email FROM users WHERE email = :email');
			$stmt->execute(array(':email' => $_POST['email']));
			$row = $stmt->fetch(PDO::FETCH_ASSOC);

			if(empty($row['email'])){
				$error[] = $cssError . 'E-mail não consta em nosso sistema.</div>';
			}

		}
	}
	

	//if no errors have been created carry on
	if(!isset($error)){

		//create the activation code
		$token = md5(uniqid(rand(),true));

		try {

			$stmt = $db->prepare("UPDATE users SET resetToken = :token, resetComplete='No' WHERE email = :email");
			$stmt->execute(array(
				':email' => $row['email'],
				':token' => $token
			));

			//send email
			$to = $row['email'];
			$subject = "Combo Vídeos - Redefinição de senha";
			$body = "<p>Alguém solicitou que a senha associada a esse email fosse reiniciada.</p>
			<p>Se não foi você, desconsidere esse e-mail e nada será feito.</p>
			<p>Para redefinir sua senha, clique <a href='". DIR . "resetPassword.php?key=$token'>aqui</a></p>";

			$mail = new Mail();
			$mail->setFrom(SITEEMAIL);
			$mail->addAddress($to);
			$mail->subject(utf8_decode($subject));
			$mail->body(utf8_decode($body));
			$mail->send();

			$error[] = $cssSuccess . 'Verifique seu email para prosseguir com a recuperação.</div>';

		//else catch the exception and show the error.
		} catch(PDOException $e) {
		    $error[] = $e->getMessage();
		}

	}

}

?>
<!doctype html>
<html class="fixed">
	<head>

		<!-- Basic -->
		<meta charset="UTF-8">

		<meta name="keywords" content="HTML5 Admin Template" />
		<meta name="description" content="Porto Admin - Responsive HTML5 Template">
		<meta name="author" content="okler.net">

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
					<img src="assets/images/combo-logo.png" height="54" alt="Combo Vídeos" />
				</a>

				<div class="panel panel-sign">
					<div class="panel-title-sign mt-xl text-right">
						<h2 class="title text-uppercase text-weight-bold m-none"><i class="fa fa-user mr-xs"></i> Recuperar Senha</h2>
					</div>
					<div class="panel-body">
						<?php 

							$loginBoxMsg = "<div class='alert alert-info'>
												<p class='m-none text-weight-semibold h6'>Preencha seu email no campo abaixo para receber instruções de recuperação</p>
											</div>";

							if(isset($error)){
								$loginBoxMsg = "";
								foreach($error as $error){
									$loginBoxMsg = $error;
								}					                					            
					        }
					        echo $loginBoxMsg;    
					    ?>							
						
						<form action="" method="post">
							<div class="form-group mb-none">
								<div class="input-group">
									<input name="email" type="email" placeholder="E-mail" class="form-control input-lg" required="true" />
									<span class="input-group-btn">
										<button class="btn btn-primary btn-lg" type="submit">Recuperar</button>
									</span>
								</div>
							</div>

							<p class="text-center mt-lg">Lembrou? <a href="index.php">Volte para o login</a></p>
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