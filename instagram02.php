<?php
error_reporting(E_ERROR | E_PARSE);

require __DIR__ . '/instagram/vendor/autoload.php';

$baseUrl = 'https://www.instagram.com/explore/tags/instafood/?__a=1';
$url = $baseUrl;

//$instagram = \InstagramScraper\Instagram::withCredentials('fmaynard1974', 'q3mxyfh6');
//$instagram->login();


/*
while(1) {
    $json = json_decode(file_get_contents($url));
    print_r($json->tag->media->nodes);
    if(!$json->tag->media->page_info->has_next_page) break;
    $url = $baseUrl.'&max_id='.$json->tag->media->page_info->end_cursor;
}
*/

$json = json_decode(file_get_contents($url));
$arrPosts = $json->tag->media->nodes;
print_r($arrPosts); exit;

/* XML montagem */
header('Content-Type: text/xml; charset=utf-8', true); //set document header content type to be XML
$rss = new SimpleXMLElement('<rss xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:content="http://purl.org/rss/1.0/modules/content/" xmlns:atom="http://www.w3.org/2005/Atom"></rss>');
$rss->addAttribute('version', '2.0');
$title = $rss->addChild('title','Combo_Videos'); //title of the feed
$description = $rss->addChild('description','RSS filtrada do Instagram'); //feed description
$link = $rss->addChild('link','http://sistema.combovideos.com.br/rss/instagram.php'); //feed site
$language = $rss->addChild('language','pt-br'); //language
//Create RFC822 Date format to comply with RFC822
$date_f = date("D, d M Y H:i:s T", time());
$build_date = gmdate(DATE_RFC2822, strtotime($date_f)); 
$lastBuildDate = $rss->addChild('lastBuildDate',$date_f); //feed last build date
$generator = $rss->addChild('generator','Combo Videos'); //add generator node
$channel = $rss->addChild('channel'); //add channel node
/* XML montagem */

foreach ($arrPosts as $obj) {
	$account = $instagram->getAccountById($obj->owner->id);
	$item = $channel->addChild('item'); //add item node
	$username  = $item->addChild('username', $account->getUsername()); //add link node under item
	$link      = $item->addChild('link', $obj->display_src); //add link node under item
	$descr     = $item->addChild('texto', $obj->caption); //add guid node under item	
}

echo $rss->asXML(); //output XML