<?php
/* / */
/* Harvest WD */
$cmd="rm -f /***/crotos/harvest/items/*";
exec($cmd);
$types = array (860861,3305213,1278452,11060274,212431,48498,125191,326478,133067,184296,572916,1473346,860372,213156,277583,12043905,132137,17514,5647631);
$cpt=0;
for ($i=0;$i<count($types);$i++){
	$type=$types[$i];
	$req="http://wdq.wmflabs.org/api?q=claim[31:%28tree[".$type."][][279]%29]";
	$res = request($req);
	$responseArray = json_decode($res,true);
	foreach ($responseArray["items"] as $key => $value){
		copy("https://www.wikidata.org/w/api.php?action=wbgetentities&ids=q$value&format=json", "/var/www/crotos/harvest/items/$value.json");
		$cpt++;
		if (($cpt % 500)==0)
			echo "\n$cpt";
	}
}
// Types without subclasses
$types2 = array (93184,2916094,7845071);
for ($i=0;$i<count($types2);$i++){
	$type=$types2[$i];
	$req="http://wdq.wmflabs.org/api?q=claim[31:".$type."]";
	$res = request($req);
	$responseArray = json_decode($res,true);
	foreach ($responseArray["items"] as $key => $value){
		copy("https://www.wikidata.org/w/api.php?action=wbgetentities&ids=q$value&format=json", "/var/www/crotos/harvest/items/$value.json");
		$cpt++;
		if (($cpt % 500)==0)
			echo "\n$cpt";
	}
}
echo "\nharvest done";

?>