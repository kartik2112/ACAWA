<?php

/*Kartik's Code*/

$docRoot = new DOMDocument('1.0');
$docRoot -> load("https://timesofindia.indiatimes.com/rssfeeds/913168846.cms");
echo $docRoot -> saveXML();

/* My Code
$html = "";
$url = "https://timesofindia.indiatimes.com/rssfeeds/913168846.cms";
//$url = "https://news.google.com/news/rss/?ned=us&gl=US&hl=en";
$xml = simplexml_load_file($url);

foreach($xml->channel->item as $item){
    $title = $item->title;
    $description = $item->description;
    $html .= "<p>$title</p>";
    $html .= "<p>$description</p>";
}

echo $html;
*/
?>