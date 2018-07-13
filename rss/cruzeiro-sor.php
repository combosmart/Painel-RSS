<?php
error_reporting(E_ERROR | E_PARSE);

$mysql_host = 'combo_sistema.mysql.dbaas.com.br'; //host
$mysql_username = 'combo_sistema'; //username
$mysql_password = 'A0m@Lk8R'; //password
$mysql_database = 'combo_sistema'; //db

header('Content-Type: text/xml; charset=utf-8', true); //set document header content type to be XML

$rss = new SimpleXMLElement('<rss xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:content="http://purl.org/rss/1.0/modules/content/" xmlns:atom="http://www.w3.org/2005/Atom"></rss>');
$rss->addAttribute('version', '2.0');

$title = $rss->addChild('title','Combo_Videos'); //title of the feed
$description = $rss->addChild('description','RSS filtrada do Jornal Cruzeiro do Sul'); //feed description
$link = $rss->addChild('link','http://sistema.combovideos.com.br/rss/cruzeiro-sor.php'); //feed site
$language = $rss->addChild('language','pt-br'); //language

//Create RFC822 Date format to comply with RFC822
$date_f = date("D, d M Y H:i:s T", time());
$build_date = gmdate(DATE_RFC2822, strtotime($date_f)); 
$lastBuildDate = $rss->addChild('lastBuildDate',$date_f); //feed last build date

$generator = $rss->addChild('generator','Combo Videos'); //add generator node

$channel = $rss->addChild('channel'); //add channel node

//connect to MySQL - mysqli(HOST, USERNAME, PASSWORD, DATABASE);
$mysqli = new mysqli($mysql_host, $mysql_username, $mysql_password, $mysql_database);

//Output any connection error
if ($mysqli->connect_error) {
    die('Error : ('. $mysqli->connect_errno .') '. $mysqli->connect_error);
}
$results = $mysqli->query("SELECT id, title, link, destaque, data_item, arquivo_imagem  FROM rss_cruzeiro_sorocaba where exibir = 1");

if($results){ //we have records 
	while($row = $results->fetch_object()) //loop through each row
	{
		$item = $channel->addChild('item'); //add item node
		$title = $item->addChild('title', $row->title); //add title node under item
		$link = $item->addChild('link', $row->link); //add link node under item
		$guid = $item->addChild('destaque', $row->destaque); //add guid node under item
		$arquivo = $item->addChild('arquivo', $row->arquivo_imagem); //add arquivo_imagem node under item
		$date_rfc = gmdate(DATE_RFC2822, strtotime($row->data_item));
		$item = $item->addChild('pubDate', $date_rfc); //add pubDate node
	}
}

echo $rss->asXML(); //output XML