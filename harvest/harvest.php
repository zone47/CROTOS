<?php
/* / */
/* Harvest WD */

echo "\nHarvest";
include $file_timer_begin;
//$cmd="rm -Rf ".$fold_crotos."harvest/items";
$cmd="del /Q ".str_replace("/","\\",$fold_crotos)."harvest\\items\\*.*";
exec($cmd);
//$cmd="mkdir ".$fold_crotos."harvest/items";
//exec($cmd);
/* items with subclasses
3305213	 painting
11060274 print
133067   mosaic
277583   kakemono
12043905 pastel
132137   icon
5647631  handscroll
170593   collage
429785   poster
46686    altarpiece
5078274  sketch
17584242 creative drawing
42195 petroglypg
=>4502142 art image

860861   sculpture
1278452  polyptych
20437094   installation art
//48498    illuminated manuscript
125191   photograph
326478   land art
184296   tapestry
572916   found object
1473346  stained glass
860372   digital art
17514    graffiti


738680  pottery of ancient Greece


15727816 painting series
18573970 group of paintings
19479037 sculpture series
19960510 series of prints
16905563 cycle of paintings

//1640824  inscription
//178743   stele, subclass of artefact
//178659 illustration

items without subclasses
//93184    drawing
220659   artifact
//35140   performance
*/
// Types with subclasses
$types = array (4502142,860861,1278452,20437094,125191,326478,184296,572916,1473346,860372,17514,738680,15727816,18573970,19479037,19960510,16905563);

// institutions with too much items without image
$museum_min = "195:2983474,195:5476145,195:705551,195:671384,195:1464509,195:1192305,195:188740,195:1416890,195:49133,195:239303,195:160236,195:510324,195:430682,195:844926,195:2296362,195:526170,195:214867,195:1952033,195:842858,195:2817221,195:18572999,195:18573057,195:1471477,195:2365880,195:1948674,195:132783";

//Photographs of National Library of Wales excluded
$museum_excl = "%20and%20noclaim[195:666063]";

$cpt=0;
for ($i=0;$i<count($types);$i++){
	$type=$types[$i];
	
	for ($j=0;$j<2;$j++){
		$req="http://wdq.wmflabs.org/api?q=claim[31:%28tree[".$type."][][279]%29]";
		if ($j==0)
			$req.="%20and%20noclaim[".$museum_min."]";
		else
			$req.="%20and%20claim[".$museum_min."]%20and%20claim[18]";
		if ($type==125191)
			$req.=$museum_excl;
		$res = request($req);
		$responseArray = json_decode($res,true);
		foreach ($responseArray["items"] as $key => $value){
			copy("https://www.wikidata.org/w/api.php?action=wbgetentities&ids=q$value&format=json", $fold_crotos."harvest/items/$value.json");
			$cpt++;
			if (($cpt % 1000)==0)
				echo "\n$cpt";
		}
	}
}
// Types without subclasses
//$types2 = array (93184,220659,42195);
$types2 = array (220659);
for ($i=0;$i<count($types2);$i++){
	$type=$types2[$i];
	
	for ($j=0;$j<2;$j++){
		$req="http://wdq.wmflabs.org/api?q=claim[31:".$type."]";
		if ($j==0)
			$req.="%20and%20noclaim[".$museum_min."]";
		else
			$req.="%20and%20claim[".$museum_min."]%20and%20claim[18]";
		$res = request($req);
		$responseArray = json_decode($res,true);
		foreach ($responseArray["items"] as $key => $value){
			copy("https://www.wikidata.org/w/api.php?action=wbgetentities&ids=q$value&format=json", $fold_crotos."harvest/items/$value.json");
			$cpt++;
			if (($cpt % 500)==0)
				echo "\n$cpt";
		}
	}
}
echo "\nHarvest done";
include $file_timer_end;
?>