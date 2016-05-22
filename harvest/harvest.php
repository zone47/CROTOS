<?php
/* / */
/* Harvest WD */
echo "\nHarvest";

include $file_timer_begin;
//$cmd="rm -Rf ".$fold_crotos."harvest/items";
$cmd="del /Q ".str_replace("/","\\",$fold_crotos)."harvest\\items\\*.*";
exec($cmd);

$sparql="select DISTINCT ?item
where {
	{?item wdt:P31/wdt:P279* wd:Q4502142}        # art image
	UNION {?item wdt:P31/wdt:P279* wd:Q860861}   # sculpture
	UNION {?item wdt:P31/wdt:P279* wd:Q1278452}  # polyptych
	UNION {?item wdt:P31/wdt:P279* wd:Q20437094} # installation art
	UNION {?item wdt:P31/wdt:P279* wd:Q326478}   # land art
	UNION {?item wdt:P31/wdt:P279* wd:Q184296}   # tapestry
	UNION {?item wdt:P31/wdt:P279* wd:Q572916}   # found object
	UNION {?item wdt:P31/wdt:P279* wd:Q1473346}  # stained glass
	UNION {?item wdt:P31/wdt:P279* wd:Q860372}   # digital art
	UNION {?item wdt:P31/wdt:P279* wd:Q17514}    # graffiti
	UNION {?item wdt:P31/wdt:P279* wd:Q738680}   # pottery of ancient Greece
	UNION {?item wdt:P31/wdt:P279* wd:Q15727816} # painting series
	UNION {?item wdt:P31/wdt:P279* wd:Q18573970} # group of paintings
	UNION {?item wdt:P31/wdt:P279* wd:Q19479037} # sculpture series
	UNION {?item wdt:P31/wdt:P279* wd:Q19960510} # group of paintings
	UNION {?item wdt:P31/wdt:P279* wd:Q16905563} # cycle of paintings
	UNION {?item wdt:P31/wdt:P279* wd:Q125191.}  # photograph
	UNION {?item wdt:P31 wd:Q220659}             # artifact

	{?item wdt:P18 ?img.}                        # With image
	UNION {                                      # OR
	?article schema:about ?item .         
	FILTER regex(str(?article), \"wikipedia\")}  # With Wikipedia article
	UNION {                                      # OR
	?item wdt:P195/wdt:P361* wd:Q19675}          # in the collections of the Louvre
}";
$sparqlurl=urlencode($sparql);
$req="https://query.wikidata.org/sparql?format=json&query=".$sparqlurl;
$res  = file_get_contents($req);
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

echo "\nHarvest done";
include $file_timer_end;
?>