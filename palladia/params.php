<?php 
$deb=($p-1)*$nb;

//Search queries
include "queries.php";

if (($p!=0)or($new)){ // hack if $p indicated out of range because of $nb parameter
	if ((($p-1)*$nb>$num_rows)or($new)){
		$new_url="?";
		foreach($_GET as $key => $value) 
			if ($key!="p")
				$new_url.="&".$key."=".$value;
		
	}
	if (($p-1)*$nb>$num_rows)
		header("Location:http://www.zone47.com/crotos/palladia/".$new_url);
}
$nbpg=ceil($num_rows/$nb); // number of pages
/* Disabled 20190615 Too much time
//Suggests if home or random choice
if (($random)||($num_rows==0)){
	$alea_prop=array(170,135,136,180,144,921);
	$rand_keys = array_rand($alea_prop, 2);
	$alea_bar="";
	for ($i=0;$i<2;$i++){
		$alea_item=$alea_prop[$rand_keys[$i]];
		$sql_r="SELECT label,qwd FROM label_page WHERE type='1' AND prop=".$alea_item." AND lg='$l' AND label !='' ORDER BY RAND() LIMIT 0,1";
		$rep_r=mysqli_query($link,$sql_r);
		if (mysqli_num_rows($rep_r)!=0){
			$data_r = mysqli_fetch_assoc($rep_r);
			if ($alea_bar!="")
				$alea_bar.=", ";
			$alea_bar.="<a href=\"?p".$alea_item."=".$data_r['qwd']."\">".$data_r['label']."</a>";	
		}
	}
	$alea_bar=translate($l,"suggest")." : ".$alea_bar;
}
*/
// navigation link
$liennav="";
//if ($nb!="20") $liennav.="&amp;nb=".$nb;
foreach($tab_idx as $key=>$value)
	if ($value!="")
		$liennav.="&amp;$key=".$value;
foreach($tab_miss as $key=>$value)
	if ($value!="")
		$liennav.="&amp;$key=".$value;
foreach($tab_check as $key=>$value)
	if ($value!="")
		$liennav.="&amp;$key=".$value;
if ($s!="") $liennav.="&amp;s=".$s;
if(!(($y1==1000)&&(($y2==2020)))){
	if ($y1!="") $liennav.="&amp;y1=".$y1;
	if ($y2!="") $liennav.="&amp;y2=".$y2;
}
if ($d!="") $liennav.="&amp;d=".$d;
if ($b!=0) $liennav.="&amp;b=".$b;
?>