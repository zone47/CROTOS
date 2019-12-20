<?php
/* / */
/* Harvest WD */
echo "\nHarvest";
$opts = [
	'http' => [
		'method' => 'GET',
		'user_agent' => 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13',
		'header' => ['Accept: application/sparql-results+json'],
	],
];
$context = stream_context_create($opts);

include $file_timer_begin;
//$cmd="rm -Rf ".$fold_crotos."harvest/items";
$cmd="del /Q ".str_replace("/","\\",$fold_crotos)."harvest\\items\\*.*";
exec($cmd);

$sparql="
select DISTINCT ?item
where {
	?item wdt:P195/wdt:P361* wd:Q666063
}";
$sparqlurl=urlencode($sparql);
$req="https://query.wikidata.org/sparql?format=json&query=".$sparqlurl;
$res  = file_get_contents($req,true,$context);
$responseArray = json_decode($res,true);

foreach ($responseArray["results"]["bindings"] as $key => $value){
	$Qitem=$value["item"]["value"];
	$numQ=str_replace("http://www.wikidata.org/entity/Q","",$Qitem);
	copy("https://www.wikidata.org/w/api.php?action=wbgetentities&ids=q$numQ&format=json", $fold_crotos."harvest/items/$numQ.json");
 	$cpt++;
	if (($cpt % 1000)==0)
		echo "\n$cpt";
}

echo "\nHarvest done";
include $file_timer_end;
?>