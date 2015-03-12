<?php
/* / */
/* Harvest qwd items of qwd values for artworks propeties */
echo "\nHarvest props";
include $file_timer_begin;

$link = mysqli_connect ($host,$user,$pass,$db) or die ('Erreur : '.mysqli_error());
mysqli_query($link,"SET NAMES 'utf8'");
//$cmd="rm -f ".$fold_crotos."harvest/items/*";
$cmd="del /Q ".str_replace("/","\\",$fold_crotos)."harvest\\items_props\\*.*";
exec($cmd);
$props = array (31,135,136,144,170,179,180,186,195,276,361,921,941,1693);
$cpt=0;
for ($i=0;$i<count($props);$i++){
	$sql="select qwd from p".$props[$i];
	$rep=mysqli_query($link,$sql);
	while ($data = mysqli_fetch_assoc($rep)){
		copy("https://www.wikidata.org/w/api.php?action=wbgetentities&ids=q".$data["qwd"]."&format=json", $fold_crotos."harvest/items_props/".$data["qwd"].".json");
		$cpt++;
		if (($cpt % 500)==0)
			echo "\n$cpt";
	}
}
mysqli_close($link);
echo "\nHarvest props done";
include $file_timer_end;
?>