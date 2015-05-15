<?php
$lg="fr";
if (isset($_COOKIE['l']))
	$lg=$_COOKIE['l'];
if (isset($_GET['l']))
	if ($_GET['l']!=""){ 
		setcookie ("l",$_GET['l'], time() + 31536000, "/");
		$lg=$_GET['l'];
	}
?><!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
    <meta name="author" content="/* / */" />
    <title>Crotos Lab</title>
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
<h1>Crotos Lab - <select name="l" id="lg">
<?php 
$lgs=array("en","fr");
include "../traduction.php";
include "../functions.php";
for ($i=0;$i<count($lgs);$i++){
    echo "				<option value=\"".translate($lgs[$i],"lang_code")."\"";
	if ($lg==$lgs[$i])
		 echo " selected=\"selected\"";
	echo " >".translate($lgs[$i],"lg")."</option>\n";	
}
?></select></h1>
</form>
<ul id="stuff">
	<li><?php
if ($lg=="fr")
	echo "<a href=\"artworks/\">Liste d'œuvres d'art sur Wikidata par collection ou créateur</a>";
else
	echo "<a href=\"artworks/?l=en\">Wikidata artworks items by collection or creator</a>";
?></li>
	<li><a href="prop_nb.php"><?php
if ($lg=="fr")
	echo "Nombre d'œuvres d'art par propriété";
else
	echo "Number of visual artworks items by property";
?></a></li>
    <li><a href="../new.php"><?php
if ($lg=="fr")
	echo "Nouvelles œuvres d'art avec images sur Crotos";
else
	echo "New items with images on Crotos";
echo "</a> <small>(";
$fp = fopen ("../dateupdate.txt", "r");
echo fgets ($fp, 255);
fclose ($fp);

?>)</small></li>
    <li><a href="cpt_lg"><?php
if ($lg=="fr")
	echo "Occurences des langues selon les libellés";
else
	echo "Languages occurences from labels";
?></a></li>
    <li><a href="louvre-rooms.php"><?php
if ($lg=="fr")
	echo "Œuvres du musée du Louvre par salles";
else
	echo "Artworks of the Musée du Louvre by rooms";
?></a></li>
</ul>
<p id="source">
Source: <a href="https://github.com/zone47/CROTOS" class="externe">Github/Crotos</a>
</p>
</body>
</html>