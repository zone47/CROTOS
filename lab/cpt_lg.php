<?php
/* / */
include "../init.php";
include "../traduction.php";
include "../functions.php";
include "../config.php";
include "../lg.php";
$link = mysqli_connect ($host,$user,$pass,$db) or die ('Erreur : '.mysqli_error());
mysqli_query($link,"SET NAMES 'utf8'");

?><!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
    <meta name="author" content="/* / */" />
    <title>Crotos - <?php
if ($l=="fr")
	echo "Occurences des langues selon les libellés";
else
	echo "Languages occurences from labels";
?></title>
	<script src="../js/jquery.js"></script>
   	<script src="../js/jquery.tablesorter.min.js"></script>
    <script>
$(document).ready(function(){ 
	$("#occ").tablesorter( {sortList: [[2,1]]} ); 
	$('#lg').change(function() {
		$('#lgform').submit();
	});
}); 
    </script>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="../js/blue/style.css" />
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
if ($l=="fr")
	echo "<b>Crotos</b> - Occurences des langues selon les libellés";
else
	echo "<b>Crotos</b> - Languages occurences from labels";
?></h1>
</form>
<table id="occ" class="tablesorter ">
<thead> 
<tr> 
    <th>Code</th> 
    <th><?php
if ($l=="fr")
	echo "Langue";
else
	echo "Language";
?></th> 
    <th><?php
if ($l=="fr")
	echo "Libellés";
else
	echo "Labels";
?></th> 
</tr> 
</thead> 
<?php

foreach($trads as $key=>$value){
	if ($key!="mu"){
		$sql="SELECT count(id) as total from label_page  WHERE lg LIKE '$key'";
		$rep_s=mysqli_query($link,$sql);
		$data=mysqli_fetch_assoc($rep_s);
		echo "<tr><td>$key</td>";
		if (($key=="ar")||($key=="fa")||($key=="he"))
			echo "<td class=\"rtl\">".$value['lg']."</td>";
		else
			echo "<td>".$value['lg']."</td>";
		echo "<td class=\"nb\">".$data['total']."</td></tr>\n";
	}
	else
		echo "<tr><td>$key</td><td>".$value['lg']."</td><td class=\"nb\">1</td></tr>\n";

}
mysqli_close($link);
?>
</table>
</body>
</html>