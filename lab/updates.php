<?php
/* / */
include "../init.php";
include "../traduction.php";
include "../functions.php";
include "../config.php";

error_reporting(E_ALL);

$link = mysqli_connect ($host,$user,$pass,$db) or die ('Erreur : '.mysqli_error());
mysqli_query($link,"SET NAMES 'utf8'");


?><!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="author" content="/* / */" />
	<title>Crotos - <?php
if ($l=="fr")
	echo "Crotos - Mises à jour";
else
	echo "Crotos - Updates";
?></title>
	<link rel="icon" href="../favicon.ico" />
	<link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="../js/blue/styles.css" />
    <script src="../js/jquery.js"></script>
<script>
$(document).ready(function(){ 
	$('#lg').change(function() {
		$('#lgform').submit();
	});
}); 

	</script>
    <style>
.tablesorter thead th {
	text-align:center;
}	
.tablesorter tbody td {
	text-align:right;
}
small{
	font-size:67%;
	font-weight:normal;
		
}
	</style>
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
</form>
<?php

if ($l=="fr")
	echo "Mises à jour de Crotos<br/>
<small>Indicateurs issus de rapports générés lors des mises à jour. Des variations sur l'état actuel de la mise à jour dans Crotos sont possibles (éléments supprimés ou restaurés, images ajoutées depuis).</small>";
else
	echo "Crotos - Updates<br/>
<small>Indicators from reports generated after updates. Variations on the current status of the update in Crotos are possible (deleted or restored elements, images added).</small>";
?></h1>
<div>
<table class="tablesorter">
	<thead>
		<tr>
            <th>date</th>
            <th><?php if ($l=="fr") echo "Nouvelles œuvres"; else	echo "New artworks"; ?></th>
            <th><?php if ($l=="fr") echo "Nouvelles images"; else	echo "New images"; ?></th>
            <th><?php if ($l=="fr") echo "Éléments supprimés"; else	echo "Deleted items"; ?></th>
            <th><?php if ($l=="fr") echo "Total œuvres"; else	echo "Total artworks"; ?></th>
            <th><?php if ($l=="fr") echo "Total œuvres avec image"; else	echo "Total artworks with image"; ?></th>
		</tr>
	</thead>
    <tbody>
<?php

$dir = '../updates';
$files = scandir($dir, 1);
for ($i=0;$i<count($files);$i++){
	$file=$files[$i];
	if($file != '.' && $file != '..'){
		echo "        <tr>";
		$datafic=explode("|",file_get_contents("../updates/$file",true));
		$update=str_replace(".txt","",$file);
		if (intval($update)>20150701)
			echo "            <td><a href=\"../?d=$update\">".substr($update, 6, 2)." / ".substr($update, 4, 2)." / ".substr($update, 0, 4)."</a></td>";
		else
			echo "            <td>".substr($update, 6, 2)." / ".substr($update, 4, 2)." / ".substr($update, 0, 4)."</td>";
		for ($j=0;$j<count($datafic);$j++){
			if (($j==2)&&(intval($update)>20150818))
				echo "            <td><a href=\"del.php?d=$update\">".$datafic[$j]."</a></td>";
			else
				echo "            <td>".$datafic[$j]."</td>";
		}
		echo "        </tr>";
	}
}
mysqli_close($link);
?>
    </tbody>
</table>

</div>


</body>
</html>