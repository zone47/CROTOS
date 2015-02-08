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
    <title>Crotos - Salles du Louvre</title>
    <link rel="icon" href="../favicon.ico" />
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<?php include "entete.php" ?>
<h1>Œuvres du Louvre par salles sur Wikidata</h1>

<?php
$lvl=1;
$l=fr;
children_search(18,$lvl,$l);

function children_search($id_parent,$level,$l){
	$sql="SELECT id, qwd,commonscategory from p276 WHERE id_parent=".$id_parent;
	$rep=mysql_query($sql);
	$num_rows= mysql_num_rows($rep);
	if ($num_rows!=0)
		echo "\n<ul>";
	while($data = mysql_fetch_assoc($rep)) {
		$id_loc=$data['id'];
		$qwd=$data['qwd'];
		$cat=$data['commonscategory'];
	
		$sql="SELECT count(id) as total from artw_prop  WHERE prop=276 and id_prop=".$id_loc;
		$rep2=mysql_query($sql);
		$data2=mysql_fetch_assoc($rep2);
		$nbartworks=$data2['total'];

		
		$txt="\n<li>".ucfirst(label_item($qwd,$l))." <b>($nbartworks items)</b> <a href=\"https://www.wikidata.org/wiki/Q$qwd\">Q$qwd</a>";
		if ($cat!="")
			$txt.=" - <a href=\"https://commons.wikimedia.org/wiki/Category:$cat\">WikiCommons</a>";
		$txt.=" - <a href=\"http://www.zone47.com/crotos/?p276=$qwd\">Crotos</a></li>";
		// - <a href=\"http://tools.wmflabs.org/autolist/index.php?language=fr&wdq=claim%5B276%3A%28tree%5B$qwd%5D%5B%5D%5B276%5D%29%5D&run=Run\">Autolist</a>";

		echo $txt;
		children_search($id_loc,$level+1,$l);
	}
	if ($num_rows!=0)
		echo "\n</ul>";
}

?>

</body>
</html>