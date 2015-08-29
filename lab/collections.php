<?php
/* / */
include "../init.php";
include "../traduction.php";
include "../functions.php";
include "../config.php";
$link = mysqli_connect ($host,$user,$pass,$db) or die ('Erreur : '.mysqli_error());
mysqli_query($link,"SET NAMES 'utf8'");
$l="fr";
if (isset($_COOKIE['l']))
	$l=$_COOKIE['l'];
if (isset($_GET['l']))
	if ($_GET['l']!=""){
		setcookie ("l",$_GET['l'], time() + 31536000);
		$l=$_GET['l'];
	}
$prop=0;
$prop_query=195;
$nb=50;
function percent($num, $total){
	return number_format((100.0*$num)/$total,1)."&nbsp;%";
}
function nb_prop($prop, $id_prop,$glob=0){
	global $link;
	$clause=" m$prop=0 ";
	if ($prop=="571")
		$clause=" year1 is not null ";
	if ($prop=="217")
		$clause=" P217!='' ";
	if ($prop=="217")
		$clause=" P217!='' ";
	if ($prop=="link")
		$clause=" (P347!='' OR P350!='' OR P727!='' OR P1212!='' OR link!='')";
	
	if ($glob!=1){
		$where="(id_prop=$id_prop";

		$sql_sub="SELECT id_sub FROM prop_sub WHERE prop_sub.prop=195 AND prop_sub.id_prop=$id_prop";
		$rep_sub=mysqli_query($link,$sql_sub);
		while($data = mysqli_fetch_assoc($rep_sub))
			$where.=" OR id_prop=".$data['id_sub'];
		$where.=")";
		//echo $where;
		
		$req_creat="select count(distinct(artworks.id)) as nb from artworks, artw_prop WHERE $clause and artw_prop.id_artw=artworks.id AND artw_prop.prop=195 and $where";
		if ($prop=="180"){
			$req1="select distinct(id_artw) from artw_prop WHERE artw_prop.prop=195 and $where";
			$rep2=mysqli_query($link,$req1);
			$liste="";	
			while($data2 = mysqli_fetch_assoc($rep2)) {
				if ($liste!="")
					$liste.=" OR ";
				$liste.="artw_prop.id_artw=".$data2["id_artw"];
			}
			$req_creat="select count(artw_prop.id) as nb from artw_prop WHERE artw_prop.prop=180 AND ($liste)";
		}
	}
	else{
		$req_creat="select count(distinct(artworks.id)) as nb from artworks, artw_prop WHERE $clause and artw_prop.id_artw=artworks.id";
		if ($prop=="180"){
			$req_creat="select count(artw_prop.id) as nb from artw_prop WHERE artw_prop.prop=180";
		}
	}
	
	$rep2=mysqli_query($link,$req_creat);
	$data2 = mysqli_fetch_assoc($rep2);
	$nb_crea=$data2["nb"];
	return $data2["nb"];
}
?><!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
    <meta name="author" content="/* / */" />
    <title>Crotos - <?php echo ucfirst($lb_prop) ?></title>
	<script src="../js/jquery.js"></script>
   	<script src="../js/jquery.tablesorter.min.js"></script>
    <script>
$(document).ready(function() 
    { 
        $("#occ").tablesorter( {sortList: [[2,1]]} );  
    } 
); 
    </script>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="../js/blue/styles.css" />
<style>
caption {
	height:auto;
	padding-bottom:5px;
}
</style>
</head>
<body>
<?php include "entete.php" ?>
	
<table id="occ" class="tablesorter ">
<caption style="padding-bottom:20px;"><strong>Statistiques par collection (50+ items) sur Crotos (données issues de <a href="http://www.wikidata.org/">Wikidata</a>)</strong><br>
<small>Les ensembles des collections sont récupérés à partir de Wikidata et non-exhaustifs car filtrés selon certains types d'œuvres (cf <a href="http://zone47.com/dozo/crotos-moteur-de-recherche-sur-les-oeuvres-dart-dans-wikidata#selection">sélection</a>) et pour certaines institutions avec beaucoup d'œuvres mais dont moins de 10% avec image, seules les œuvres avec image ont été incorporées dans Crotos (cf <a href="#min_list">liste</a>).</small></caption>
<thead> 
<tr> 
    <th>Institution</th> 
    <th id="artworks">Œuvres</th> 
    <th id="images">Image</th>
    <th id="images">% Images</th>
    <th>Créateur</th>  
    <th>Date</th>  
    <th>N° inv</th>  
    <th>Genre</th>
    <th>Basé sur</th>  
    <th>Réf. autorité</th>  
    <th>Dépeint p180 (moyenne)</th>  
    <th></th>  
    <th></th>  
      
</tr> 
</thead> 
<?php
$sql="SELECT count(id) as nbartworks FROM artworks";
$rep=mysqli_query($link,$sql);
$data = mysqli_fetch_assoc($rep);
$nbartworks=$data['nbartworks'];

$sql="SELECT count(id) as nbimg FROM artworks WHERE P18!=0";
$rep=mysqli_query($link,$sql);
$data = mysqli_fetch_assoc($rep);
$nbimg=$data['nbimg'];

$nb_crea=nb_prop("170",$id_prop,1);
$nb_date=nb_prop("571",$id_prop,1);
$nb_inv=nb_prop("217",$id_prop,1);
$nb_genre=nb_prop("136",$id_prop,1);
$nb_base=nb_prop("144",$id_prop,1);
$nb_link=nb_prop("link",$id_prop,1);
$nb_dep=nb_prop("180",$id_prop,1);

echo "<tr>\n";
echo "	<td>";
echo "Global";
echo "</td>\n";
echo "	<td class=\"artworks\">$nbartworks</td>\n";
echo "	<td class=\"images\">$nbimg</td>\n";
echo "	<td class=\"images\">".percent($nbimg,$nbartworks)."</td>\n";
echo "	<td class=\"images\">".percent($nb_crea,$nbartworks)."</td>\n";
echo "	<td class=\"images\">".percent($nb_date,$nbartworks)."</td>\n";
echo "	<td class=\"images\">".percent($nb_inv,$nbartworks)."</td>\n";
echo "	<td class=\"images\">".percent($nb_genre,$nbartworks)."</td>\n";
echo "	<td class=\"images\">".percent($nb_base,$nbartworks)."</td>\n";
echo "	<td class=\"images\">".percent($nb_link,$nbartworks)."</td>\n";
echo "	<td class=\"images\">".round(intval($nb_dep)/intval($nbartworks),2)."</td>\n";
echo "	<td><a href=\"/crotos/\">";
if ($l=="fr") echo "voir les œuvres"; else echo "view artworks";
echo "</a></td>\n";
echo "	<td><a href=\"/crotos/lab/artworks/\">";
if ($l=="fr") echo "liste"; else echo "list";
echo "</a></td>\n";
echo "</tr>\n";




$sql="SELECT id, qwd, P18, nb, nbimg from p195 WHERE level=0 AND nb>".$nb;
$rep=mysqli_query($link,$sql);
while($data = mysqli_fetch_assoc($rep)) {
	$id_prop=$data['id'];
	$nbartworks=$data['nb'];
	$nbimg=$data['nbimg'];

	$nb_crea=nb_prop("170",$id_prop);
	$nb_date=nb_prop("571",$id_prop);
	$nb_inv=nb_prop("217",$id_prop);
	$nb_genre=nb_prop("136",$id_prop);
	$nb_base=nb_prop("144",$id_prop);
	$nb_link=nb_prop("link",$id_prop);
	$nb_dep=nb_prop("180",$id_prop);
	
	if ($data['qwd']!=0){
		echo "<tr>\n";
		echo "	<td>";
		echo label_item($data['qwd'],$l)." <a href=\"https://www.wikidata.org/wiki/Q".$data['qwd']."\"> (Q".$data['qwd'].")</a>";
		echo "</td>\n";
		echo "	<td class=\"artworks\">$nbartworks</td>\n";
		echo "	<td class=\"images\">$nbimg</td>\n";
		echo "	<td class=\"images\">".percent($nbimg,$nbartworks)."</td>\n";
		echo "	<td class=\"images\">".percent($nb_crea,$nbartworks)."</td>\n";
		echo "	<td class=\"images\">".percent($nb_date,$nbartworks)."</td>\n";
		echo "	<td class=\"images\">".percent($nb_inv,$nbartworks)."</td>\n";
		echo "	<td class=\"images\">".percent($nb_genre,$nbartworks)."</td>\n";
		echo "	<td class=\"images\">".percent($nb_base,$nbartworks)."</td>\n";
		echo "	<td class=\"images\">".percent($nb_link,$nbartworks)."</td>\n";
		echo "	<td class=\"images\">".round(intval($nb_dep)/intval($nbartworks),2)."</td>\n";
		echo "	<td><a href=\"/crotos/?p$prop_query=".$data['qwd']."\">";
		if ($l=="fr") echo "voir les œuvres"; else echo "view artworks";
		echo "</a></td>\n";
		echo "	<td><a href=\"/crotos/lab/artworks/?p=$prop_query&q=Q".$data['qwd']."\">";
		if ($l=="fr") echo "liste"; else echo "list";
		echo "</a></td>\n";
		echo "</tr>\n";
	}
}

?>
</table>
<p id="min_list"> Liste des institutions avec plus de 50 œuvres dont moins de 10% avec image :<br/>
<?php 
$museum_min = array(1464509,705551,671384,1192305,188740,1416890,49133,239303,160236,510324,430682,844926,842858,2296362,526170,214867,2983474,5476145,1952033);
for ($i=0;$i<count($museum_min);$i++){
	if ($i!=0)
		echo " – ";
	$wdq_link="https://tools.wmflabs.org/autolist/index.php?wdq=claim[195:%28tree[".$museum_min[$i]."][][361]%29]&run=Run";
	echo "<a href=\"".$wdq_link."\"  class=\"externe\">".label_item($museum_min[$i],$l)."</a>";
}
mysqli_close($link);
?>
</p>
</body>
</html>