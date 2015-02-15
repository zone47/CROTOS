<?php
/* / */
include "../init.php";
include "../traduction.php";
include "../functions.php";
include "../config.php";
$link = mysql_connect ($host,$user,$pass) or die ('Erreur : '.mysql_error());
mysql_select_db($db) or die ('Erreur :'.mysql_error());
mysql_query("SET NAMES 'utf8'");
$l="en";
if (isset($_GET['l']))
	if ($_GET['l']!="")
		$l=$_GET['l'];
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
if (isset($_GET['nb']))
	if ($_GET['nb']!="")
		$nb=$_GET['nb'];
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
        $("#occ").tablesorter( {sortList: [[2,1], [1,0]]} ); 
    } 
); 
    </script>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="../js/blue/styles.css" />
</head>
<body>
<?php include "entete.php" ?>
	<form id="prop_form">
    
    	<label for="props" id="label_lg">Property<?php //echo translate($l,"language") ?></label>
    	<select name="prop" id="props">
<?php
$tab_props=array(31,135,136,144,170,180,186,195,276,921,941);
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
        <label for="nb_min" id="label_lg">Minimum number</label>
    	<select name="nb" id="nb_min">
<?php
$tab_nb=array(0,5,10,20,50,100,200,500,1000);
for ($i=0;$i<count($tab_nb);$i++){
    echo "			<option value=\"".$tab_nb[$i]."\"";
	if ($nb==$tab_nb[$i]) echo " selected=\"selected\"";
	echo " >".$tab_nb[$i]."</option>\n";	
}
?>    
        </select>
        <input type="submit" value="<?php echo translate($l,"search") ?>" id="ok" />
    </form>
<table id="occ" class="tablesorter ">
<caption>Number of <b>visual artworks</b> items on <a href="https://www.wikidata.org/"><b>Wikidata</b></a> by <b><?php echo $lb_prop ?></b><?php if ($prop!=0) echo "/<a href=\"https://www.wikidata.org/wiki/Property:P$prop\">p$prop</a>" ?> (><?php echo $nb ?>), via <a href="/crotos/">Crotos</a></caption>
<thead> 
<tr> 
    <th><?php echo ucfirst($lb_prop) ?></th> 
    <th id="artworks">Artworks</th> 
    <th id="images">with images</th>
    <th></th>  
</tr> 
</thead> 
<?php
$sql="SELECT id, qwd from p$prop";
if ($prop==0)
	$sql="SELECT id, qwd from p$prop_query WHERE level=0";
$rep=mysql_query($sql);
$data=mysql_fetch_assoc($rep_s);
while($data = mysql_fetch_assoc($rep)) {
	$id_coll=$data['id'];
	$sql="SELECT count(id) as total from artw_prop  WHERE prop=$prop_query and id_prop=".$id_coll;
	$rep2=mysql_query($sql);
	$data2=mysql_fetch_assoc($rep2);
	$nbartworks=$data2['total'];
	
	$sql="SELECT count(artworks.id) as total from artworks, artw_prop  WHERE artworks.id=artw_prop.id_artw and  artworks.P18!='' and artw_prop.prop=$prop_query and id_prop=".$id_coll;
	$rep2=mysql_query($sql);
	$data2=mysql_fetch_assoc($rep2);
	$nbimg=$data2['total'];
	if (($nbartworks>$nb)&&($data['qwd']!=0)){
		echo "<tr>\n";
		echo "	<td>".label_item($data['qwd'],$l)." <a href=\"https://www.wikidata.org/wiki/Q".$data['qwd']."\"> (Q".$data['qwd'].")</a></td>\n";
		echo "	<td class=\"artworks\">$nbartworks</td>\n";
		echo "	<td class=\"images\">$nbimg</td>\n";
		echo "	<td><a href=\"/crotos/?p$prop_query=".$data['qwd']."\">view artworks</a></td>\n";
		echo "</tr>\n";
	}
}
?>
</table>
</body>
</html>