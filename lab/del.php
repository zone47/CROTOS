<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Crotos</title>
</head>
<body>
<ul>
<?php

include "../init.php";
include "../traduction.php";
include "../functions.php";
include "../config.php";

error_reporting(E_ALL);

$link = mysqli_connect ($host,$user,$pass,$db) or die ('Erreur : '.mysqli_error());
mysqli_query($link,"SET NAMES 'utf8'");

$rep=mysqli_query($link,"select qwd from publi where del=".$d." ORDER BY del ASC");
$num_rows= mysqli_num_rows($rep);
if ($num_rows>0){
	while($data = mysqli_fetch_assoc($rep))
		echo "<li><a href=\"http://www.wikidata.org/entity/Q".$data['qwd']."\">".$data['qwd']."</a></li>\n";	
			
}

mysqli_close($link);
?>
</ul>
</body>
</html>
