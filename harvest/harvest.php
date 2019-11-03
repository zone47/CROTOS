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

$matrice="select DISTINCT ?item
where {
		{?item wdt:P31/wdt:P279* wd:Q3305213}    # painting
	UNION {?item wdt:P31/wdt:P279* wd:Q860861}   # sculpture
	UNION {?item wdt:P31/wdt:P279* wd:Q48498}    # illuminated manuscript
	UNION {?item wdt:P31/wdt:P279* wd:Q93184}    # dessin
	UNION {?item wdt:P31/wdt:P279* wd:Q125191}   # photograph
	UNION {?item wdt:P31/wdt:P279* wd:Q133067}   # mosaic
 	UNION {?item wdt:P31/wdt:P279* wd:Q178659}   # illustration
    UNION {?item wdt:P31/wdt:P279* wd:Q429785}   # poster
	UNION {?item wdt:P31/wdt:P279* wd:Q1131329}  # grotto
	UNION {?item wdt:P31/wdt:P279* wd:Q1139104}  # computer wallpaper
	UNION {?item wdt:P31/wdt:P279* wd:Q1044853}  # pietra dura
    UNION {?item wdt:P31/wdt:P279* wd:Q2605386}  # tile tableau
	UNION {?item wdt:P31/wdt:P279* wd:Q1131329}  # grotto
	UNION {?item wdt:P31/wdt:P279* wd:Q11060274} # print
	UNION {?item wdt:P31/wdt:P279* wd:Q12043905} # pastel
	UNION {?item wdt:P31/wdt:P279* wd:Q22075301} # textile artwork
	UNION {?item wdt:P31/wdt:P279* wd:Q22669857} # collage
	UNION {?item wdt:P31/wdt:P279* wd:Q11060274} # print
	UNION {?item wdt:P31/wdt:P279* wd:Q1278452}  # polyptic
	
	UNION {?item wdt:P31/wdt:P279* wd:Q20437094} # installation art
	UNION {?item wdt:P31/wdt:P279* wd:Q326478}   # land art
	UNION {?item wdt:P31/wdt:P279* wd:Q184296}   # tapestry
	UNION {?item wdt:P31/wdt:P279* wd:Q28966302} # embroidery
	UNION {?item wdt:P31/wdt:P279* wd:Q572916}   # found object
	UNION {?item wdt:P31/wdt:P279* wd:Q1473346}  # stained glass
	UNION {?item wdt:P31/wdt:P279* wd:Q17514}    # graffiti
	UNION {?item wdt:P31/wdt:P279* wd:Q738680}   # pottery of ancient Greece
	UNION {?item wdt:P31/wdt:P279* wd:Q15727816} # painting series
	UNION {?item wdt:P31/wdt:P279* wd:Q18573970} # group of paintings
	UNION {?item wdt:P31/wdt:P279* wd:Q19479037} # sculpture series
	UNION {?item wdt:P31/wdt:P279* wd:Q19960510} # group of prints
	UNION {?item wdt:P31/wdt:P279* wd:Q16905563} # cycle of paintings
	UNION {?item wdt:P31/wdt:P279* wd:Q45791}    # geoglyph
	UNION {?item wdt:P31/wdt:P279* wd:Q1758043}  # tsuba
	UNION {?item wdt:P31/wdt:P279* wd:Q161439}   # jewellery
	UNION {?item wdt:P31 wd:Q220659}             # artifact
	UNION {?item wdt:P31 wd:Q221662}             # kudurru
	MINUS {?item wdt:P31 wd:Q125191.
	?item wdt:P195 wd:Q56677470}";
$sparql=$matrice."
	
	?item wdt:P18 [].                        # With image
}";
$sparqlurl=urlencode($sparql);
$req="https://query.wikidata.org/sparql?format=json&query=".$sparqlurl;
$res  = file_get_contents($req,true,$context);
$responseArray = json_decode($res,true);

$cpt=0;
foreach ($responseArray["results"]["bindings"] as $key => $value){
	$Qitem=$value["item"]["value"];
	$numQ=str_replace("http://www.wikidata.org/entity/Q","",$Qitem);
	copy("https://www.wikidata.org/w/api.php?action=wbgetentities&ids=q$numQ&format=json", $fold_crotos."harvest/items/$numQ.json");
 	$cpt++;
	if (($cpt % 1000)==0)
		echo "\n$cpt";
}

$sparql="
select DISTINCT ?item
where {
	{	?item wdt:P195/wdt:P361* wd:Q19675}          # in the collections of the Louvre
	UNION {                                      # OR
	?item wdt:P195 wd:Q1376}                     # in the collections of the musée Saint-Raymond
	MINUS {?item wdt:P31 wd:Q3561331.}
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

$sparql=$matrice."
	
	?article schema:about ?item .         
	FILTER regex(str(?article), \"wikipedia\")
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

$sparql=$matrice."
	
	?item wdt:P608 []
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