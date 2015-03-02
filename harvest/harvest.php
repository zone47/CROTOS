<?php
/* / */
/* Harvest WD */
//$cmd="rm -f ".$fold_crotos."harvest/items/*";
echo "\nHarvest";
include $file_timer_begin;

$cmd="del /Q ".str_replace("/","\\",$fold_crotos)."harvest\\items\\*.*";
exec($cmd);
/* items with subclasses
3305213	 painting
860861   sculpture
1278452  polyptych
11060274 print
212431   installation art
48498    illuminated manuscript
125191   photograph
326478   land art
133067   mosaic
184296   tapestry
572916   found object
1473346  stained glass
860372   digital art
213156   performance art
277583   kakemono
12043905 pastel
132137   icon
17514    graffiti
5647631  handscroll
220659   artifact
170593   collage
429785   poster
46686    altarpiece
1277842  panathenaic amphora
15123870 lithograph
//1640824  inscription
//178743   stele, subclass of artefact

items without subclasses
93184    drawing
5078274  sketch
17584242 creative drawing
*/
// Types with subclasses
$types = array (3305213,860861,1278452,11060274,212431,48498,125191,326478,133067,
184296,572916,1473346,860372,213156,277583,12043905,132137,17514,5647631,
220659,170593,429785,46686,1277842,15123870);
$cpt=0;
for ($i=0;$i<count($types);$i++){
	$type=$types[$i];
	$req="http://wdq.wmflabs.org/api?q=claim[31:%28tree[".$type."][][279]%29]";
	$res = request($req);
	$responseArray = json_decode($res,true);
	foreach ($responseArray["items"] as $key => $value){
		copy("https://www.wikidata.org/w/api.php?action=wbgetentities&ids=q$value&format=json", $fold_crotos."harvest/items/$value.json");
		$cpt++;
		if (($cpt % 500)==0)
			echo "\n$cpt";
	}
}
// Types without subclasses
$types2 = array (93184,5078274,17584242);
for ($i=0;$i<count($types2);$i++){
	$type=$types2[$i];
	$req="http://wdq.wmflabs.org/api?q=claim[31:".$type."]";
	$res = request($req);
	$responseArray = json_decode($res,true);
	foreach ($responseArray["items"] as $key => $value){
		copy("https://www.wikidata.org/w/api.php?action=wbgetentities&ids=q$value&format=json", $fold_crotos."harvest/items/$value.json");
		$cpt++;
		if (($cpt % 500)==0)
			echo "\n$cpt";
	}
}
echo "\nHarvest done";
include $file_timer_end;
?>