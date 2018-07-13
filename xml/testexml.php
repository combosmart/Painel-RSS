<?php

ini_set('memory_limit', '400M');

$doc = new DOMDocument();
$doc->loadXML(file_get_contents('megacurioso.xml'));

$xpath = new DOMXpath($doc);
$nodes = $xpath->query('//*');

$names = array();
foreach ($nodes as $node)
{
    $names[] = $node->nodeName;
}

echo join(PHP_EOL, array_unique($names));