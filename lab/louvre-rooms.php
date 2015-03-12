<?php
/* / */
include "../init.php";
include "../traduction.php";
include "../functions.php";
include "../config.php";
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
		$sql="SELECT count(id) as total from artw_prop  WHERE prop=276 and id_prop=".$data_rooms[$i]["id_loc"];
		$rep2=mysqli_query($link,$sql);
		$data2=mysqli_fetch_assoc($rep2);
		$nbartworks=$data2['total'];

		
		$txt="\n<li>".$data_rooms[$i]["label"]." <b>($nbartworks items)</b> <a href=\"https://www.wikidata.org/wiki/Q".$data_rooms[$i]["qwd"]."\">Q".$data_rooms[$i]["qwd"]."</a>";
		if ($data_rooms[$i]["commonscategory"]!="")
			$txt.=" - <a href=\"https://commons.wikimedia.org/wiki/Category:".$data_rooms[$i]["commonscategory"]."\">WikiCommons</a>";
		$txt.=" - <a href=\"http://www.zone47.com/crotos/?p276=".$data_rooms[$i]["qwd"]."\">Crotos</a></li>";
		// - <a href=\"http://tools.wmflabs.org/autolist/index.php?language=fr&wdq=claim%5B276%3A%28tree%5B$qwd%5D%5B%5D%5B276%5D%29%5D&run=Run\">Autolist</a>";

		echo $txt;
		$next=$data_rooms[$i]["id_loc"];
		children_search($next,$l);
	}
	if ($num_rows!=0)
		echo "\n</ul>";
}

?><!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
    <meta name="author" content="/* / */" />
    <title>Crotos - Salles du Louvre</title>
    <link rel="icon" href="../favicon.ico" />
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<?php include "entete.php" ?>

<?php 
$l=fr;
$sql="SELECT id,nb FROM `p276` WHERE `commonscategory` = 'Palais du Louvre'";
$rep=mysqli_query($link,$sql);
$data=mysqli_fetch_assoc($rep);
$id_Louvre=$data['id'];
$nb=$data['nb'];
?>
<h1><a href="http://www.zone47.com/crotos/?p195=19675">Œuvres du Louvre</a> par salles sur Wikidata (<?php echo $nb; ?> items)</h1>
<?php

children_search($id_Louvre,$l);

mysqli_close($link);
?>

</body>
</html>