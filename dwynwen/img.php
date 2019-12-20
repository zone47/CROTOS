<?php
/* / */
include "config.php";
	$link = mysqli_connect ($host,$user,$pass,$db) or die ('Erreur : '.mysqli_error());
	$sql="SELECT qwd, height, width FROM `commons_img`,artworks WHERE width/height>1.3333 AND width/height<1.3334 AND artworks.P18=`commons_img`.id" ;
	$rep=mysqli_query($link,$sql);
	while ($data = mysqli_fetch_assoc($rep)){
		//echo $data["qwd"]." - ".$data["width"]/$data["height"]."<br/>";
		echo "<a href=\"http://zone47.com/crotos/dwynwen/?q=".$data["qwd"]."\">".$data["qwd"]."</a><br/>";
	}
?>