<?php
/* / */
include "../init.php";
include "../traduction.php";
include "../functions.php";
include "../config.php";
$link = mysql_connect ($host,$user,$pass) or die ('Erreur : '.mysql_error());
mysql_select_db($db) or die ('Erreur :'.mysql_error());
mysql_query("SET NAMES 'utf8'");

?><!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
    <meta name="author" content="/* / */" />
    <title>Crotos - Collections</title>
	<script src="../js/jquery.js"></script>
   	<script src="../js/jquery.tablesorter.min.js"></script>
    <script>
$(document).ready(function() 
    { 
        $("#occ").tablesorter( {sortList: [[1,1], [2,0]]} ); 
    } 
); 
    </script>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="../js/blue/styles.css" />
</head>
<body>
<?php include "entete.php" ?>
<table id="occ" class="tablesorter ">
<caption>Number of <b>visual artworks</b> items on <a href="https://www.wikidata.org/"><b>Wikidata</b></a> by <b>institution</b>, via <a href="/crotos/">Crotos</a></caption>
<thead> 
<tr> 
    <th>Institution</th> 
    <th id="artworks">Artworks</th> 
    <th id="images">with images</th>
    <th></th>  
</tr> 
</thead> 
<?php

$sql="SELECT id, qwd from p195 WHERE level=0";
$rep=mysql_query($sql);
$data=mysql_fetch_assoc($rep_s);
while($data = mysql_fetch_assoc($rep)) {
	$id_coll=$data['id'];
	$sql="SELECT count(id) as total from artw_prop  WHERE prop=195 and id_prop=".$id_coll;
	$rep2=mysql_query($sql);
	$data2=mysql_fetch_assoc($rep2);
	$nbartworks=$data2['total'];
	
	$sql="SELECT count(artworks.id) as total from artworks, artw_prop  WHERE artworks.id=artw_prop.id_artw and  artworks.P18!='' and artw_prop.prop=195 and id_prop=".$id_coll;
	$rep2=mysql_query($sql);
	$data2=mysql_fetch_assoc($rep2);
	$nbimg=$data2['total'];
	if ($nbartworks>0){
		echo "<tr>\n";
		echo "	<td class=\"institution\"><a href=\"http://tools.wmflabs.org/reasonator/?q=".$data['qwd']."\">".label_item($data['qwd'],"en")."</a></td>\n";
		echo "	<td class=\"artworks\">$nbartworks</td>\n";
		echo "	<td class=\"images\">$nbimg</td>\n";
		echo "	<td class=\"view\"><a href=\"/crotos/?p195=".$data['qwd']."\">view artworks</a></td>\n";
		echo "</tr>\n";
	}
}

?>
</table>
</body>
</html>