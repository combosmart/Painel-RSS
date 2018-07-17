<?php
/**
 * Job de obtenção de feed do Voxel
 *
 * O funcionamento do job dá-se da seguinte forma: de hora em hora ele verifica se existem
 * novas notícias que não foram importadas ainda para as tabelas locais. Usará como chave única
 * a tag <pubDate> da notícia, convertida para unix timestamp, para ser usada à guisa de chave primária.
 * Se não existir na tabela, importa. Caso contrário, não faz nada.
 * 
 * PHP version 5.6.31
 * 
 *
 * @category   CronJobs
 * @author     Felipe Maynard <felipe@combovideos.com.br>
 * @copyright  2018 Combo Smart Solutions
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 */	

require('../includes/config.php');

$feed = "https://rss.voxel.com.br/feed/15/";
$isValidURL = is_valid_url($feed);

if ($isValidURL) {
	$xml = simplexml_load_file($feed);
	$lastBuildDate = date_format(date_create(strval($xml->channel->lastBuildDate)),"Y-m-d");
	
	foreach($xml->xpath("//item") as $item) {
		// Recupero a data de publicação de um item e converto para unix timestamp para virar chave
		$uid = strtotime(strval($item->pubDate));
		
		// Verifico se esta chave @uid já existe na tabela para evitar duplicidade
		$sql = "SELECT id from rss_voxel WHERE uid = :uid";			    
		$stmt = $db->prepare($sql);
		$stmt->execute(array(						
			':uid' => $uid
		));			
		$checkUid = $stmt->rowCount();
		
		// Se for uma notícia nova, importa
		if ($checkUid == 0) {
			$sql = "INSERT INTO rss_voxel 
			        (title, link, data_item, arquivo_imagem, uid, data_import) 
			        VALUES (:title, :link, :data_item, :arquivo_imagem, :uid, NOW())";
			$stmt = $db->prepare($sql);
			$result = $stmt->execute(array(
								 ':title'          => strval($item->title), 
								 ':link'           => strval($item->enclosure["url"]), 						
								 ':data_item'      => strval($lastBuildDate),
								 ':arquivo_imagem' => hash('md5',strval($item->title)),
								 ':uid'            => $uid
						     ));			
		}
	}	

	if ($result) {

		// Em caso de sucesso, manda email informando a execução correta do job, para os destinatários
		// cadastrados no array $jobToEmails, que é declarado em /includes/config.php
				
		$subject = "Combo Vídeos - Execução de Job - Feed Voxel";
		$body    = "<p>O job de obtenção de feeds do parceiro Voxel foi executado com sucesso em " . date("d/m/y G:i:s") . "</p>";				
		$mail = new Mail();
		$mail->setFrom(SITEEMAIL);
		
		while (list ($key, $val) = each($jobToEmails)) {
			$mail->addAddress($val);
		}
		
		$mail->subject(utf8_decode($subject));
		$mail->body(utf8_decode($body));
		$mail->send();		
	}
} else {
	return false;
}
