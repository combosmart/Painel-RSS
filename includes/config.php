<?php
ob_start();
session_start();

//set timezone
setlocale(LC_ALL, 'pt_BR', 'portuguese');
date_default_timezone_set('America/Sao_Paulo');
setlocale(LC_MONETARY,"pt_BR", "ptb");

//database credentials
define('DBHOST','combo_sistema.mysql.dbaas.com.br');
define('DBUSER','combo_sistema');
define('DBPASS','A0m@Lk8R');
define('DBNAME','combo_sistema');

//application address
define('DIR','http://sistema.combovideos.com.br/');
define('SITEEMAIL','monitoramento@combovideos.com.br');
define('SITETITLE','Combo Vídeos');

//perfis de acesso
define('ADMINISTRADOR','1');
define('USUARIO','2');
define('TECNICO','3');

// modos de acesso
define('LISTAR','list');
define('ADICIONAR','add');
define('EDITAR','edit');
define('REMOVER','delete');
define('RESTORE','restore');
define('ALTERAR_SENHA','changepass');
define('OCULTAR','ocultar');
define('EXIBIR','exibir');

//status das ocorrencias
define('OCORRENCIA_ABERTA','A');
define('OCORRENCIA_FECHADA','F');

//destinatários de job
$jobToEmails = array('flavio.vecina@combovideos.com.br',
					 'felipe.maynard@combovideos.com.br',
					 'carolina.moreira@combovideos.com.br');

try {

	//create PDO connection
	$db = new PDO("mysql:host=".DBHOST.";dbname=".DBNAME, DBUSER, DBPASS);
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch(PDOException $e) {
	//show error
    echo '<p class="bg-danger">'.$e->getMessage().'</p>';
    exit;
}

//include the user class, pass in the database connection
include('classes/user.php');
include('classes/client.php');
include('classes/equip_types.php');
include('classes/equipments.php');
include('classes/ponto.php');
include('classes/problema.php');
include('classes/ocorrencias.php');
include('classes/utils.php');
include('classes/rss_megacurioso.php');
include('classes/rss_tecmundo.php');
include('classes/rss_thebrief.php');
include('classes/rss_minhaserie.php');
include('classes/rss_cruzeiro_sorocaba.php');
include('classes/rss_cruzeiro_geral.php');
include('classes/rss_voxel.php');
include('classes/rss_curitibacult.php');
include('classes/rss_curitibalocal.php');
include('classes/phpmailer/mail.php');

$user   	    = new User($db);
$client 	    = new Cliente($db);
$equip_type     = new TipoEquipamento($db);
$equipment      = new Equipamento($db);
$ponto 		    = new Ponto($db);
$problema       = new Problema($db);
$ocorrencia     = new Ocorrencia($db);
$megacurioso    = new RSS_Megacurioso($db);
$tecmundo       = new RSS_Tecmundo($db);
$thebrief       = new RSS_Thebrief($db);
$minhaserie     = new RSS_Minhaserie($db);
$jCruzeiroSor   = new RSS_JCruzSor($db);
$jCruzeiroGer   = new RSS_JCruzGer($db);
$curitibacult   = new RSS_CuritibaCult($db);
$curitibalocal  = new RSS_CuritibaLocal($db);
$voxel          = new RSS_Voxel($db);