<?php
include "config.php";
$link = mysqli_connect ($host,$user,$pass,$db) or die ('Erreur : '.mysqli_error());
//$path="/kunden/homepages/42/d110278962/htdocs/zone47/crotos/";
/*include "init.php";*/
function gen_id_rand($nb_q){
	global $link ;
	$txt="";
	$cpt=0;
	$id_array=array();
	$sql="SELECT MAX(id) AS maxid from artworks";
	$rep=mysqli_query($link,$sql);
	$data = mysqli_fetch_assoc($rep);
	$max=$data["maxid"];
    
	while($cpt<$nb_q){
		$newid=rand(1,$max);	
		if (!in_array($newid,$id_array)){
			array_push($id_array,$newid);
			if ($txt!="") 
				$txt.=",";
			$txt.=$newid;
			$cpt++;
		}
	}
	return $txt;
}
$list_id=gen_id_rand(20);
echo $list_id;
?>