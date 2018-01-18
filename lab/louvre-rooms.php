<?php
/* / */
include "../init.php";
include "../traduction.php";
include "../functions.php";
include "../config.php";
error_reporting(0);

$link = mysqli_connect ($host,$user,$pass,$db) or die ('Erreur : '.mysqli_error());
mysqli_query($link,"SET NAMES 'utf8'");

function children_search($id_parent,$l){
	global $link;
	$sql="SELECT id, qwd,commonscategory from p276 WHERE id_parent=".$id_parent;
	$rep=mysqli_query($link,$sql);
	$num_rows= mysqli_num_rows($rep);
	if ($num_rows!=0)
		echo "\n<ul>";
	$data_rooms=array();
	while($data = mysqli_fetch_assoc($rep)){
		$lbl=ucfirst(trim(label_item($data['qwd'],$l)));
		$sorttxt="";
		preg_match('#[0-9][0-9]*[a-z]*#i',$lbl,$matches);
		if ($matches)
			$sorttxt=intval($matches[0]);
		else
			$sorttxt=$lbl;
		$sorttxt2="";
		preg_match('#.*,#i',$lbl,$matches);
		if ($matches)
			$sorttxt2=$matches[0];
		else
			$sorttxt2=$lbl;
		$data_rooms[]=array("id_loc"=>$data['id'],"qwd"=>$data['qwd'],"commonscategory"=>$data['commonscategory'],"label"=>$lbl,"for_sort"=>$sorttxt,"for_sort2"=>$sorttxt2);
	}
	$for_sort=array();
	$for_sort2=array();
	foreach ($data_rooms as $key => $row) {
		 $id_loc[$key] = $row['id_loc'];
		 $qwd[$key] = $row['qwd'];
		 $commonscategory[$key] = $row['commonscategory'];
		 $label[$key] = $row['label'];
	 	 $for_sort[$key] = $row['for_sort'];
		 $for_sort2[$key] = $row['for_sort2'];
	}
	array_multisort($for_sort2, SORT_ASC,$for_sort, SORT_ASC,$data_rooms);
	
	for($i=0;$i<count($data_rooms);$i++){
		//$sql="SELECT count(id) as total from artw_prop  WHERE prop=276 and id_prop=".$data_rooms[$i]["id_loc"];
		$sql="select count(distinct artworks.id) as total from p276, artw_prop, artworks WHERE p276.id=".$data_rooms[$i]["id_loc"]." AND artw_prop.id_prop=p276.id AND artw_prop.prop=276 AND artworks.id=artw_prop.id_artw";
		$rep2=mysqli_query($link,$sql);
		$data2=mysqli_fetch_assoc($rep2);
		$nbartworks=$data2['total'];
		
		$sql="select count(distinct artworks.id) as total from p276, artw_prop, artworks WHERE p276.id=".$data_rooms[$i]["id_loc"]." AND artw_prop.id_prop=p276.id AND artw_prop.prop=276 AND artworks.id=artw_prop.id_artw and artworks.P18=0";
		$rep2=mysqli_query($link,$sql);
		$data2=mysqli_fetch_assoc($rep2);
		$nbmiss=$data2['total'];
		
		$txt="\n<li>".$data_rooms[$i]["label"]." (<b>$nbartworks item";
		if ($nbartworks>1)
			$txt.="s";
		$txt.="</b>";
		if ($nbmiss>0){
			$txt.=" - ".$nbmiss;
			if ($nbmiss==1){
				if ($l=="fr")
					$txt.=" image manquante";
				else
					$txt.=" missing image";
			}
			else{
				if ($l=="fr")
					$txt.=" images manquantes";
				else
					$txt.=" missing images";
			}
		}
		$txt.=")";
		$txt.=" <a href=\"https://www.wikidata.org/wiki/Q".$data_rooms[$i]["qwd"]."\">Q".$data_rooms[$i]["qwd"]."</a>";
		if ($data_rooms[$i]["commonscategory"]!="")
			$txt.=" - <a href=\"https://commons.wikimedia.org/wiki/Category:".$data_rooms[$i]["commonscategory"]."\">WikiCommons</a>";
		$txt.=" - <a href=\"http://www.zone47.com/crotos/?p276=".$data_rooms[$i]["qwd"]."\">Crotos</a>";
		$txt.=" - <a href=\"http://www.zone47.com/crotos/lab/artworks/?p=276&q=Q".$data_rooms[$i]["qwd"]."&c0=1&c1=1&c170=1&c571=1&c31=1&c217=1&c3=1&c347=1&c1212=1&c18=1\">";
		if ($l=="fr")
			$txt.="Liste";
		else
			$txt.="List";
		$txt.="</a></li>";

		// - <a href=\"http://tools.wmflabs.org/autolist/index.php?language=fr&wdq=claim%5B276%3A%28tree%5B$qwd%5D%5B%5D%5B276%5D%29%5D&run=Run\">Autolist</a>";

		echo $txt;
		$next=$data_rooms[$i]["id_loc"];
		children_search($next,$l,$miss);
	}
	if ($num_rows!=0)
		echo "\n</ul>";
}

?><!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
    <meta name="author" content="/* / */" />
    <title>Crotos - <?php
if ($l=="fr")
	echo "Salles du musée du Louvre";
else
	echo "Rooms of the Musée du Louvre";
?></title>
    <link rel="icon" href="../favicon.ico" />
    <link rel="stylesheet" href="styles.css">
   	<script src="../js/jquery.js"></script>
<script>
$(document).ready(function(){ 
	$('#lg').change(function() {
		$('#lgform').submit();
	});
}); 

    </script>
</head>
<body>
<?php include "entete.php" ?>
<form id="lgform">
<h1>

<select name="l" id="lg">
<?php 
$lgs=array("en","fr");

for ($i=0;$i<count($lgs);$i++){
    echo "				<option value=\"".translate($lgs[$i],"lang_code")."\"";
	if ($l==$lgs[$i])
		 echo " selected=\"selected\"";
	echo " >".translate($lgs[$i],"lg")."</option>\n";	
}
?></select>
<?php
$sql="SELECT id,nb FROM `p276` WHERE `commonscategory` = 'Palais du Louvre'";
$rep=mysqli_query($link,$sql);
$data=mysqli_fetch_assoc($rep);
$id_Louvre=$data['id'];
$nb=$data['nb'];

if ($l=="fr")
	echo "Œuvres conservées au <a href=\"http://zone47.com/crotos/?p276=1075988\">Louvre</a> localisées par salles sur Wikidata  <!--(".$nb." items)-->";
else
	echo "Visual artworks at the <a href=\"http://zone47.com/crotos/?p276=1075988\">Musée du Louvre</a> located by rooms on Wikidata <!--(".$nb." items)-->";
?></h1>
<!--
<div>
<?php
if ($l=="fr")
	echo "<a href=\"artworks/?l=fr&p=195&c0=1&c1=1&c170=1&c571=1&c3=1&c347=1&c1212=1&c18=1&q=19675\">Liste des œuvres des collections du Louvre sur Wikidata</a>";
else
	echo "<a href=\"artworks/?l=en&p=195&c0=1&c1=1&c170=1&c571=1&c3=1&c347=1&c1212=1&c18=1&q=19675\">List of artworks from collections of the Musée du Louvre on Wikidata</a>";
?>
</div>-->
</form>
<?php 

children_search($id_Louvre,$l);

?>
<!--<p>
<?php
$sql_sub="SELECT id_sub FROM prop_sub, p195 WHERE prop_sub.prop=195 AND prop_sub.id_prop=p195.id AND p195.qwd=19675";
$rep_sub=mysqli_query($link,$sql_sub);
$where="(p195.qwd=19675";
while($data = mysqli_fetch_assoc($rep_sub))
	$where.=" OR p195.id=".$data['id_sub'];
$where.=")";

$sql_s="select count(distinct artworks.id) as total
			from p195, artw_prop, artworks WHERE ".$where." AND artw_prop.id_prop=p195.id AND artw_prop.prop=195 AND artworks.id=artw_prop.id_artw
			AND artworks.m276=1";
$sql_s2=$sql_s." AND artworks.P18=0";

$rep=mysqli_query($link,$sql_s);
$data=mysqli_fetch_assoc($rep);
$nbartworks=$data['total'];

$rep=mysqli_query($link,$sql_s2);
$data=mysqli_fetch_assoc($rep);
$nbmiss=$data['total'];

mysqli_close($link);
if ($l=="fr")
	echo "Œuvres du Louvre sans localisation : <b>".$nbartworks." items</b> - ".$nbmiss." images manquantes ";
else
	echo "Visual artworks of the Musée du louvre without location: <b>".$nbartworks." items</b> - ".$nbmiss." missing images";
?>
- <a href="http://www.zone47.com/crotos/?p195=19675&m276=1&mode=1">Crotos</a>
</p>-->
</body>
</html>