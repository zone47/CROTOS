<?php
$lg="fr";
if (isset($_COOKIE['l']))
	$lg=$_COOKIE['l'];
if (isset($_GET['l']))
	if ($_GET['l']!=""){ 
		setcookie ("l",$_GET['l'], time() - 3600);
		setcookie ("l",$_GET['l'], time() + 31536000, "/");
		$lg=$_GET['l'];
	}
?><!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="Content-type" content="text/html;charset=UTF-8" />

	<title>IIIF Image Cropper</title>

	<link rel="stylesheet" href="css/jquery.Jcrop.min.css"> <!-- required for jcrop to work right! -->
	<link rel="stylesheet" href="css/main.css">
	<link rel=stylesheet href="css/colors.css">
	<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">

	<script type="text/javascript" src="js/jquery.min.js"></script>
	<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
	<script type="text/javascript" src="js/jquery.Jcrop.min.js"></script>
	<script type="text/javascript" src="js/clipboard.min.js"></script>
	<script type="text/javascript" src="js/common.js"></script>
	<script type="text/javascript" src="js/cropper.js"></script>
    <style>
body{
	font-size: 13px; 	
}
#bl_titre a img {
  border:1px solid #333  ;
}
#bl_titre {
	float:left;
	width:65px;
	z-index:10;	
	font-family: Arial, Helvetica, FreeSans, sans-serif; 
	font-size: 13px; 
	line-height: 1.3;
	color: black;
	margin-left:1em;
}
#bl_titre img{
	vertical-align:top;
	box-sizing: content-box;
}
#bl_titre a,#bl_titre a:link ,#bl_titre a:visited,#bl_titre a:hover,#bl_titre a:focus,#bl_titre a:active{
	color:#000000;	
	text-decoration:none;
}
h1.entete{
	font-size:130%;
	font-weight:bold;	
	margin-top:10px;
	margin-bottom:10px;
	text-align:left;
	font:bold 14px  Verdana, Geneva, sans-serif;
}
#container h1{
	text-align:left;
}
#container p{
	font-size:110%;
}
#output{
	width:145px;
}    
#fragment{
	width:150px;
}
#footer{
	font-size:110%;
}
    </style>
</head>
<body>
<?php include "../entete.php" ?>

	<div id="container">
    <form id="lgform">
		<h1><?php if ($lg=="fr") echo "IIIF Image Cropper pour Wikimedia Commons et Wikidata"; else echo "IIIF Image Cropper for Wikimedia Commons and Wikidata" ; ?> - <select name="l" id="lg">
<?php 
$lgs=array("en","fr");
include "../../traduction.php";
include "../../functions.php";
for ($i=0;$i<count($lgs);$i++){
    echo "				<option value=\"".translate($lgs[$i],"lang_code")."\"";
	if ($lg==$lgs[$i])
		 echo " selected=\"selected\"";
	echo " >".translate($lgs[$i],"lg")."</option>\n";	
}
?></select></h1></form>
<?php
if ($lg=="fr") { ?>
<p>Outil  pour créer des fragments d'image avec URL <a href="http://www.iiif.io" target="_blank">IIIF</a> pour les fichiers image de Wikimedia Commons, et indiquer les valeurs à fournir pour la propriété Wikidata <a href="https://www.wikidata.org/wiki/Property:P2677" target="_blank">position relative dans l'image / P2677</a>. Exemples d'utilisation :<br/>
&nbsp;- pour les fragments sur une œuvre, <a href="/crotos/lab/cropper/get.php?q=21013224">Vierge entre les vierges, Gérard David</a> – <a href="/crotos/lab/cropper/get.php?q=14619165">Galerie de vues de la Rome antique, Giovanni Paolo Panini</a>.</br>
&nbsp;- pour un élement décrit sur plusieurs œuvres. <a href="/crotos/lab/cropper/p180iiif.php">Recherche</a>. Exemple : <a href="/crotos/lab/cropper/p180iiif.php?q=302">Jésus Christ</a></p>
<!--<p>Indiquer un nom de fichier de Wikimedia Commons, et ensuite cliquer-glisser pour délimiter un fragment rectangulaire.</p>-->
<?php
} else {?>
<p>A simple little tool to create image fragments with <a href="http://www.iiif.io" target="_blank">IIIF</a> URL for Wikimedia Commons image files, and to provide values for the Wikidata property <a href="https://www.wikidata.org/wiki/Property:P2677" target="_blank">relative position within image / P2677</a>. Example of use:</br>
&nbsp;- for fragments on an artwork, <a href="/crotos/lab/cropper/get.php?q=21013224">Virgin among the Virgins, Gérard David</a> – <a href="/crotos/lab/cropper/get.php?q=14619165">Ancient Rome, Giovanni Paolo Panin</a>.</br>
&nbsp;- for an item depicted by several artworks.  <a href="/crotos/lab/cropper/p180iiif.php">Search</a>. Example: <a href="/crotos/lab/cropper/p180iiif.php?q=302">Jesus Christ</a></p>
 <!--(example: <a href="https://www.wikidata.org/wiki/Q21013224">Virgin among the Virgins</a> by Gerard David ; <a href="/crotos/lab/cropper/get.php?q=21013224">display</a> – <a href="/crotos/lab/cropper/get.php?q=14619165">example 2</a>)). Input the filename if Wikimedia Commons file, then click and drag on the image to crop.</p>-->
<?php } ?>
		
		<div id="fields">
			<label for="URL"><?php if ($lg=="fr") echo "Fichier Commons :"; else echo "Commons file:" ; ?></label>
			<input type="text" id="URL" name="URL" value="David Virgin among the Virgins.jpg">
			<button type="button" id="submit"><?php if ($lg=="fr") echo "Charger"; else echo "Load" ; ?></button>

			<label for="output"><?php if ($lg=="fr") echo "Posititon relative :"; else echo "Relative position:" ; ?></label>
			<input type="text" name="output" id="output" readonly>

			<!-- Copy to clipboad -->
			<div class="tooltip-wrap" id="copy-tip">
				<button class="btn" id="copy" data-clipboard-target="#output">
					<img src="img/clippy.svg" alt="Copy to clipboard">
				</button>
				<div class="tooltip" id="copy-tip-text">Copied!</div>
			</div>
            
            <label for="fragment"><?php if ($lg=="fr") echo "Url IIIF :"; else echo "IIIF Url:" ; ?></label>
			<input type="text" name="fragment" id="fragment" readonly>
			<span id="linkfragment"></span>
		</div>

		<!-- img placeholder -->
		<img style="display:none" src="" id="target">
	</div>

	<div id="footer">
		<a href="https://github.com/lizfischer/iiif-tools" target="_blank">Liz Fischer</a>, 2016, forked by /* / */, <a href="cropper.zip">source</a> [zip file].
	</div>
</body>
</html>
