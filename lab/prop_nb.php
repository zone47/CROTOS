<?php
/* / */
include "../init.php";
include "../traduction.php";
include "../functions.php";
include "../config.php";
include "../lg.php";
$link = mysqli_connect ($host,$user,$pass,$db) or die ('Erreur : '.mysqli_error());
mysqli_query($link,"SET NAMES 'utf8'");

$prop=31;
if (isset($_GET['prop']))
	if ($_GET['prop']!="")
		$prop=$_GET['prop'];
if ($prop!=0)
	$lb_prop=translate($l,$prop);
else
	$lb_prop="institution";
$prop_query=$prop;
if ($prop==0)
	$prop_query=195;
$nb=5;
if (isset($_GET['nbres']))
	if ($_GET['nbres']!="")
		$nb=$_GET['nbres'];
$thb=0;
if (isset($_GET['thb']))
	if ($_GET['thb']!="")
		$thb=$_GET['thb'];
$h_thumb=80;
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
        $("#occ").tablesorter( {sortList: [[1,1]]} ); 
    } 
); 
    </script>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="../js/blue/styles.css" />
</head>
<body>
<?php include "entete.php" ?>
	<form id="prop_form">
    
    	<label for="props" id="label_lg"><?php if ($l=="fr") echo "Propriété"; else echo "Property"; ?></label>
    	<select name="prop" id="props">
<?php
$tab_props=array(31,135,136,144,170,180,186,195,276,608,921,941);
for ($i=0;$i<count($tab_props);$i++){
    echo "			<option value=\"".$tab_props[$i]."\"";
	if ($prop==$tab_props[$i]) echo " selected=\"selected\"";
	echo " >".translate($l,$tab_props[$i])." / p".$tab_props[$i]."</option>\n";	
}
?>
			<option value="0" <?  if ($prop==0) echo " selected=\"selected\""; ?>>Institution</option>
		</select>        
    	<label for="lg" id="label_lg"><?php echo translate($l,"language") ?></label>
		<select name="l" id="lg">
<?php
for ($i=0;$i<count($lgs);$i++){
	/* Easter egg */if (($lgs[$i]=="mu")&&($l!="mu")) echo "<!-- ";
    echo "			<option value=\"".translate($lgs[$i],"lang_code")."\"";
	if ($l==translate($lgs[$i],"lang_code"))
		 echo " selected=\"selected\"";
	echo " >".translate($lgs[$i],"lg")."</option>\n";	
	/* Easter egg */if (($lgs[$i]=="mu")&&($l!="mu")) echo " -->";
}
?>
		</select>
        <label for="nb_min" id="label_lg" >Minimum</label>
    	<select name="nbres" id="nb_min">
<?php
$tab_nb=array(0,5,10,20,50,100,200,500,1000);
for ($i=0;$i<count($tab_nb);$i++){
    echo "			<option value=\"".$tab_nb[$i]."\"";
	if ($nb==$tab_nb[$i]) echo " selected=\"selected\"";
	echo " >".$tab_nb[$i]."</option>\n";	
}
?>    
        </select>
        <label for="thb" ><?php if ($l=="fr") echo "Vignettes"; else echo "Thumbnails"; ?></label>
        <input type="checkbox" name="thb" <?  if ($thb==1) echo " checked=\"checked\""; ?> value="1"/>
        <input type="submit" value="<?php echo translate($l,"search") ?>" id="ok" />
    </form>
<table id="occ" class="tablesorter ">
<?php 
if ($l=="fr"){ ?>
<caption>Nombre d'items <b>œuvres d'art</b> sur <a href="https://www.wikidata.org/"><b>Wikidata</b></a> par <b><?php echo $lb_prop ?></b><?php if ($prop!=0) echo "/<a href=\"https://www.wikidata.org/wiki/Property:P$prop\">p$prop</a>" ?> (><?php echo $nb ?>), via <a href="/crotos/">Crotos</a></caption>

<?php
}
else { ?>
<caption>Number of <b>visual artworks</b> items on <a href="https://www.wikidata.org/"><b>Wikidata</b></a> by <b><?php echo $lb_prop ?></b><?php if ($prop!=0) echo "/<a href=\"https://www.wikidata.org/wiki/Property:P$prop\">p$prop</a>" ?> (><?php echo $nb ?>), via <a href="/crotos/">Crotos</a></caption>
<?php } ?>
<thead> 
<tr> 
    <th><?php echo ucfirst($lb_prop) ?></th> 
    <th id="artworks"><?php if ($l=="fr") echo "Œuvres"; else echo "Artworks"; ?></th> 
    <th></th>  
    <th></th>  
</tr> 
</thead> 
<?php
$sql="SELECT id, qwd, P18, nb, nbimg from p$prop WHERE nb>".$nb." ORDER BY nbimg DESC";
if ($prop==0)
	$sql="SELECT id, qwd, P18, nb, nbimg from p195 WHERE level=0 AND nb>".$nb." ORDER BY nbimg DESC";
$rep=mysqli_query($link,$sql);
while($data = mysqli_fetch_assoc($rep)) {
	$id_prop=$data['id'];
	$nbartworks=$data['nb'];
	$nbimg=$data['nbimg'];
	if ($data['qwd']!=0){
		echo "<tr>\n";
		echo "	<td>";
		if ($thb==1){
			echo "	<div class=\"td_thumb\">";
			if (intval($data['P18'])!=0){
				$sql="SELECT P18, width, height from commons_img  WHERE id=".$data['P18'];
				$rep2=mysqli_query($link,$sql);
				$data2=mysqli_fetch_assoc($rep2);
				$img=str_replace(" ","_",$data2['P18']);
				$digest = md5($img);
				$folder = $digest[0] . '/' . $digest[0] . $digest[1] . '/' . urlencode($img);
				$w_thumb=floor(intval($data2['width'])/intval($data2['height'])*$h_thumb);
				$thumb="http://upload.wikimedia.org/wikipedia/commons/thumb/" . $folder."/".$w_thumb."px-". urlencode($img);
				if (substr ($img,-3)=="svg")
					$thumb.=".png";	
				echo "	<a href=\"https://commons.wikimedia.org/wiki/File:".urlencode($img)."\"><img src=\"".$thumb."\" /></a>";
			}
			echo "	</div>\n";
			echo "<span>";
		}
		
		echo label_item($data['qwd'],$l)." <a href=\"https://www.wikidata.org/wiki/Q".$data['qwd']."\"> (Q".$data['qwd'].")</a>";
		if ($thb==1)
			echo "</span>";
		echo "</td>\n";
		echo "	<td class=\"artworks\">$nbartworks</td>\n";
		echo "	<td><a href=\"/crotos/?p$prop_query=".$data['qwd']."\">";
		if ($l=="fr") echo "voir les œuvres"; else echo "view artworks";
		echo "</a></td>\n";
		echo "	<td><a href=\"/crotos/lab/artworks/?p=$prop_query&q=Q".$data['qwd']."\">";
		if ($l=="fr") echo "liste"; else echo "list";
		echo "</a></td>\n";
		echo "</tr>\n";
	}
}
mysqli_close($link);
?>
</table>
</body>
</html>