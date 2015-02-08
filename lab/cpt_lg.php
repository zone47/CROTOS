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
    <title>Crotos - Occurences des labels selon les langues</title>
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
    <link rel="stylesheet" href="../js/blue/style.css" />
</head>
<body>
<?php include "entete.php" ?>
<table id="occ" class="tablesorter ">
<caption><b>Crotos</b> - Occurences des libellés selon les langues</caption>
<thead> 
<tr> 
    <th>Code</th> 
    <th>Langue</th> 
    <th>Labels</th> 
</tr> 
</thead> 
<?php

foreach($trads as $key=>$value){
	if ($key!="mu"){
		$sql="SELECT count(id) as total from label_page  WHERE lg LIKE '$key'";
		$rep_s=mysql_query($sql);
		$data=mysql_fetch_assoc($rep_s);
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

?>
</table>
</body>
</html>