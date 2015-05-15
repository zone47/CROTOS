<?php

include "config_harvest.php";
$link = mysqli_connect ($host,$user,$pass,$db) or die ('Erreur : '.mysqli_error());
mysqli_query($link,"SET NAMES 'utf8'");
$sql="SELECT id,P18,qwd FROM artworks WHERE P18!=0";
$rep=mysqli_query($link,$sql);
$cpt=0;
while ($row=mysqli_fetch_assoc($rep)){
	$sql="SELECT P18, width, height FROM commons_img WHERE id=".$row['P18'];
	$rep2=mysqli_query($link,$sql);
	$row2=mysqli_fetch_assoc($rep2);
	if (($row2['width']>=2000)||($row2['height']>=2000)){
		//echo $row['qwd']." ".$row2['P18']." ".$row2['width']."*".$row2['height']."-";
		$cpt++;
		$sql="UPDATE artworks SET hd=1 WHERE id=".$row['id'];
		$rep3=mysqli_query($link,$sql);
	}
}
mysqli_close($link);
echo "\nhd done $cpt";

?>