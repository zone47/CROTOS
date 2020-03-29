<?php
/* / */
/* Count occurences of labels for suggestions */
echo "\nNb labels";
include $file_timer_begin;

$link = mysqli_connect ($host,$user,$pass,$db) or die ('Erreur : '.mysqli_error());
$tab_props=array(31,135,136,144,170,179,180,186,189,276,608,921,941,1071,1639,2079,2596);

$sql="ALTER TABLE `label_page` ADD INDEX(`id_art_or_prop`)";
$rep=mysqli_query($link,$sql);

for ($i=0;$i<count($tab_props);$i++){
	$prop=$tab_props[$i];
	echo "\ntable p".$prop;
	$sql="SELECT id,nb,nbimg FROM p".$prop;
	$rep=mysqli_query($link,$sql);
	//or die ("SQL error:" .mysqli_error($link));
	if (mysqli_num_rows($rep)!=0){
		while($data = mysqli_fetch_assoc($rep)) {
			$sql="UPDATE label_page SET nb=".$data["nb"].", nbimg=".$data["nbimg"]." WHERE id_art_or_prop=".$data["id"]." AND prop=".$prop;
			mysqli_query($link,$sql);
		}
	}
}

$sql="SELECT id,P18 from artworks";
$rep=mysqli_query($link,$sql);
$cpt=0;

while($data = mysqli_fetch_assoc($rep)) {
	$cpt++;
	if ($cpt%1000==0)
		echo "\n".$cpt;
	$sql="SELECT count(id) as nbid FROM label_page WHERE id_art_or_prop=".$data["id"]." and prop=1 AND page!=''";
	$rep2=mysqli_query($link,$sql);
	$data2 = mysqli_fetch_assoc($rep2);
	$nb=1+intval($data2["nbid"]);
	$nbimg=$nb;
	if ($data["P18"]==0)
		$nbimg=0;	
	$sql="UPDATE label_page SET nb=".$nb.", nbimg=".$nbimg." WHERE id_art_or_prop=".$data["id"]." and prop=1";
	mysqli_query($link,$sql);
}
$sql="ALTER TABLE label_page DROP INDEX id_art_or_prop;";
$rep=mysqli_query($link,$sql);
mysqli_close($link);

echo "\nNb labels done";
include $file_timer_end;
?>