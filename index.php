<?php
/* / */
$mode=0;
if (isset($_COOKIE['mode']))
	$mode=intval($_COOKIE['mode']);
if (isset($_GET['mode']))
	if ($_GET['mode']!=""){ 
		setcookie ("mode",$_GET['mode'], time() + 31536000);
		$mode=$_GET['mode'];
	}

$l="fr"; 
if (isset($_COOKIE['l']))
	$l=$_COOKIE['l'];
if (isset($_GET['l']))
	if ($_GET['l']!=""){ 
		setcookie ("l",$_GET['l'], time() + 31536000);
		$l=$_GET['l'];
	}

include "config.php";
include "init.php";
include "traduction.php";
include "functions.php";

$link = mysql_connect ($host,$user,$pass) or die ('Erreur : '.mysql_error());
mysql_select_db($db) or die ('Erreur :'.mysql_error());
mysql_query("SET NAMES 'utf8'");

$deb=($p-1)*$nb;


// Timer begin
list($g_usec, $g_sec) = explode(" ",microtime());
define ("t_start", (float)$g_usec + (float)$g_sec);

//Search queries
include "queries.php";

$nbpg=ceil($num_rows/$nb); // number of pages

//Suggests if home or random choice
if (($random)||($num_rows==0)){
	$alea_prop=array(170,135,136,180,144,921,0);
	$rand_keys = array_rand($alea_prop, 2);
	$alea_bar="";
	for ($i=0;$i<2;$i++){
		$alea_item=$alea_prop[$rand_keys[$i]];
		if ($alea_item==0){
			if ($alea_bar!="")
				$alea_bar.=", ";
			
			$sql_r="SELECT year1 FROM artworks";
			if ($mode==0) $sql_r.="  WHERE P18<>''";
			$sql_r.=" ORDER BY RAND()";
			$rep_r=mysql_query($sql_r);
			$data_r = mysql_fetch_assoc($rep_r);
			$y1=$data_r['year1'];
			$y2=0;
			if (($y1>1449)&&($y1<2005)){
				if (mt_rand(0, 1) === 0) {
					$y2=$y1+(mt_rand(1, 2)*10);
					if ($y2>2014)
						$y2=0;
				}
			}
			if ($y2==0)
				$affic_year=$y1;
			else
				$affic_year=$y1."-".$y2;
			$alea_bar.="<a href=\"?s=".$affic_year."\">".$affic_year."</a>";	
		}
		else{
			$sql_r="SELECT label,qwd FROM label_page WHERE type='1' AND prop=".$alea_item." AND lg='$l' AND label !='' ORDER BY RAND() LIMIT 0,1";
			$rep_r=mysql_query($sql_r);
			if (mysql_num_rows($rep_r)!=0){
				$data_r = mysql_fetch_assoc($rep_r);
				if ($alea_bar!="")
					$alea_bar.=", ";
				$alea_bar.="<a href=\"?p".$alea_item."=".$data_r['qwd']."\">".$data_r['label']."</a>";	
			}
		}
	}
	$alea_bar=translate($l,"suggest")." : ".$alea_bar;
}

// navigation link
$liennav="";
if ($nb!="20") $liennav.="&amp;nb=".$nb;
foreach($tab_idx as $key=>$value)
	if ($value!="")
		$liennav.="&amp;$key=".$value;
foreach($tab_miss as $key=>$value)
	if ($value!="")
		$liennav.="&amp;$key=".$value;
foreach($tab_check as $key=>$value)
	if ($value!="")
		$liennav.="&amp;$key=".$value;
if ($s!="") $liennav.="&amp;s=".$s;

include "text_nav.php";
?><!doctype html>
<html>
<head>
	<meta charset="utf-8">
    <meta name="author" content="/* / */" />
    <title><?php
foreach($tab_idx as $key=>$value)
	if ($value!="")
		echo txt_prop(0,$value,$l,"normal",0,0)." - ";
?>CROTOS</title>
	<meta name="description" content="CROTOS<?php
foreach($tab_idx as $key=>$value)
	if ($value!="")
		echo " - ".txt_prop(0,$value,$l,"normal",0,0);
$txt_res="";
if ($num_rows<2)
	$txt_res.=$num_rows." ".mb_ucfirst(translate($l,"result"));
elseif ($num_rows<=$nb)
	$txt_res.=$num_rows_ec." ".mb_ucfirst(translate($l,"results"));
else {
	$txt_res.=mb_ucfirst(translate($l,"results"))." ".($deb+1)." - ".($deb+$num_rows_ec)." ".translate($l,"of")." ".$num_rows;
}
echo " - ".$txt_res;
?>" />
    <link rel="icon" href="favicon.ico" />
    <link rel="stylesheet" href="css/styles.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" media="screen and (max-width: 840px)" href="css/medium.css" type="text/css" />
    <link rel="stylesheet" media="screen and (max-width: 700px)" href="css/small.css" type="text/css" />
	<script src="js/jquery.js"></script>
    <script src="js/masonry.pkgd.min.js"></script>
    <script src="js/imagesloaded.pkgd.min.js"></script>
    <script>
$(document).ready(function() {
	$("#topic_title").focus();
	$(".notice").css({ display: "none" });
<?php if ($num_rows>1){ ?>
	var $container = $('#contenu').masonry();
	$container.imagesLoaded( function() {
	  $container.masonry({
		  itemSelector: '.item'
<?php
if (($l=="ar")||($l=="fa")||($l=="he"))
	echo ",\"isOriginLeft\": false,
    \"isOriginTop\": true\n";
?>
		});
	});
<?php } ?>	
	$('#lg,#nb,#miss_props input').change(function() {
		$('#form').submit();
	});
	$(".lien_notice").click(function() {
		var id_not = "#notice"+this.id.replace("iconot","");
		if($(id_not).css('display') == 'none'){
			$(id_not).css({ display: "block" });
			$("#"+this.id).attr("src","img/doc2.png");
		}
		else{
			$(id_not).css({ display: "none" });
			$("#"+this.id).attr("src","img/doc.png");
		}
	});
});
	</script>
    <style></style>
</head>
<?php
	if (($l=="ar")||($l=="fa")||($l=="he"))
		echo "<body id=\"lg_rtl\" >\n";
	else
		echo "<body>\n";
?>
	<div id="global">
<nav>
	<ul id="access">
		<li><a href="#contenu"><?php echo translate($l,"content") ?></a></li>
    	<li><a href="#topic_title"><?php echo translate($l,"search") ?></a></li>
	</ul>
</nav>
<div id="entete">
    <div id="bl_titre">
        <a href="/crotos/" title="CROTOS"><img src="img/crotos.jpg" alt="CROTOS" width="108" height="120" id="img_crotos"/></a>
        <a href="/crotos/" title="CROTOS"><h1><?php /* Easter egg */if ($l=="mu") echo "HOUBA"; else echo "CROTOS"; ?></h1></a>
    </div>
	<?php include "form.php" ?>    
</div>

<div class="nav navhaut">
    <div>
		<span class="nav_list"><?php  echo $txtnav; ?></span>
        <?php
        if (($random)||($num_rows==0))
			echo "<span class=\"alea\">".$alea_bar."</span>";
		?>
	</div>
</div>

<div id="contenu" >
<?php
$cpt=0;
$nitem=1;
while($data = mysql_fetch_assoc($rep)) {
	$content="";
	$cpt++;
	$id_artw=$data['id'];
	$qwd_art=$data['qwd'];
	$inv=$data['P217'];
	$titre="";
	$titre=label_item($qwd_art,$l);
	$creator=txt_prop($id_artw,170,$l,"creator",false);
	$pageWP=page_item($qwd_art,$l);
	$lgWP="";
	$pos=strpos($pageWP,"|");
	//$content.="+".$pageWP."+";
	if ($pos){
		$lgWP=substr($pageWP,0,$pos);
		$pageWP=substr($pageWP,$pos+1,strlen($pageWP));
	}
	$location=txt_prop($id_artw,276,$l,"location",0);
	if ($location==""){
		$location=txt_prop($id_artw,195,$l,"location",0);
		$loc_link=local_link($id_artw,195,$l);
	}
	else
		$loc_link=local_link($id_artw,276,$l);
	
	$l_loc=
	$type=txt_prop($id_artw,31,$l,"normal",0);
	$material=txt_prop($id_artw,186,$l);
	$mouvement=txt_prop($id_artw,135,$l);
	$genre=txt_prop($id_artw,136,$l);
	$depicts=txt_prop($id_artw,180,$l);
	$based=txt_prop($id_artw,144,$l);
	$subject=txt_prop($id_artw,921,$l);
	$inspired=txt_prop($id_artw,941,$l);
	
		
	if ($num_rows>1){
		$content.="	<div class=\"item";
		if ($nitem==2) $content.= " w2";
		$content.= "\">\n";
	}
	else 
		$content.="	<div class=\"solo\">\n";
	if ($nitem==1) 
		$nitem=2;
	else
		$nitem=1;
	if (!(is_null($data['P18']))&&($data['P18']!=""))
		$content.="		<a href=\"https://commons.wikimedia.org/wiki/File:".str_replace(" ","_",$data['P18'])."\"><img src=\"http://commons.wikimedia.org/w/thumb.php?f=".str_replace("&","%26",str_replace(" ","_",$data['P18']))."&amp;width=200\" alt=\"".str_replace("\"","",$titre)."\" /></a>\n";
	else 
		$content.="<img src=\"img/no_image.png\" alt=\"\">";
	$content.="		<div class=\"cartel\">\n";
	$content.="			<div class=\"act_not\">";
	if ($pageWP!=""){
		$content.="<a href=\"https://";
		if ($lgWP!="")
			$content.=$lgWP;
		else 
			$content.=$l;
		$content.=".wikipedia.org/wiki/".str_replace(" ","_",str_replace("\"","",$pageWP))."\"><img src=\"img/w.png\" alt=\"WP\" /></a>";
		if ($lgWP!="")
			$content.="<a href=\"https://".$lgWP.".wikipedia.org/wiki/".str_replace(" ","_",str_replace("\"","",$pageWP))."\" class=\"lgWP\">".$lgWP."</a>";
	}
	$content.=" <img id=\"iconot$cpt\" src=\"img/doc.png\" alt=\"notice\" class=\"lien_notice\" /></div>";
	$uri_link="https://www.wikidata.org/wiki/Q".$qwd_art;
	$content.="			<div class=\"entete\"><span><a href=\"".$uri_link."\" class=\"wikidata\">".$titre."</a></span>";
	if ($creator!="")
		$content.="<br/>".$creator;
	if ($location!=""){
		if ($creator!="")
			$content.=" - ";
		else
			$content.="<br />";
		$content.=$location;
	}
	
		
	$content.="			</div>";	

	$content.="<div id=\"notice$cpt\" class=\"notice\">";
		
	if ((!(is_null($data['year2'])))||($data['year1']!=$data['year2'])){
		$content.="<p>";
		if ($data['b_date']==1)
			$content.="~&nbsp;&nbsp;";
		if (!(is_null($data['year1'])))
			$content.=$data['year1'];
		if ((!(is_null($data['year2'])))&&($data['year1']!=$data['year2']))
			$content.=" / ".$data['year2'];
		$content.="</p>";
	}
	if ($type!="")
		$content.="<p>".$type."</p>";
	if ($material!="")
		$content.="<p>".$material."</p>";
	if ($inv!="")
		$content.="<p><span class=\"libelle\">".translate($l,"217")."</span>&nbsp;: ".$inv."</p>";
		
	if ($mouvement!="")
		$content.="<p>".$mouvement."</p>";
	if ($genre!="")
		$content.="<p>".$genre."</p>";
	if ($based!="")
		$content.="<p>".$based."</p>";
	if ($subject!="")
		$content.="<p>".$subject."</p>";
	if ($inspired!="")
		$content.="<p>".$inspired."</p>";
	if ($depicts!="")
		$content.="<p>".$depicts."</p>";
	
	$content.="<div class=\"liens\">";
	if ($loc_link!="")
		$content.="<p>".$loc_link."</p>";
	$uri_link="http://tools.wmflabs.org/reasonator/?lang=".$l."&amp;q=".$qwd_art;	
	$content.="<p> <a href=\"".$uri_link."\"><img src=\"img/reasonator.png\" alt=\"Reasonator\"/></a> <a href=\"".$uri_link."\" class=\"externe\">".translate($l,"reasonator")."</a></p>";
	if ($data['P373']!=""){
		/* Easter egg */if ($l=="mu") $content.="<p><a href=\"https://commons.wikimedia.org/wiki/Category:".str_replace(" ","_",$data['P373'])."\"><img src=\"img/commons.png\" alt=\"Commons\"/></a> <a href=\"https://commons.wikimedia.org/wiki/Category:".str_replace(" ","_",$data['P373'])."\" class=\"externe\">Houba</a></p>"; else	
		$content.="<p><a href=\"https://commons.wikimedia.org/wiki/Category:".str_replace(" ","_",$data['P373'])."\"><img src=\"img/commons.png\" alt=\"Commons\"/></a> <a href=\"https://commons.wikimedia.org/wiki/Category:".str_replace(" ","_",$data['P373'])."\" class=\"externe\">".$data['P373']."</a></p>";
	}
	if ($data['P347']!="")
		$content.="<p><a href=\"http://www.culture.gouv.fr/public/mistral/joconde_fr?ACTION=CHERCHER&amp;FIELD_1=REF&amp;VALUE_1=".$data['P347']."\"></a><img src=\"img/joconde.png\" alt=\"Joconde\"/> <a href=\"http://www.culture.gouv.fr/public/mistral/joconde_fr?ACTION=CHERCHER&amp;FIELD_1=REF&amp;VALUE_1=".$data['P347']."\" class=\"externe\">".$data['P347']."</a></p>";
	$content.="</div>";
	
	$content.="</div>";

	$content.="		</div>\n";
	$content.="	</div>\n";
	
	echo $content;	
}
mysql_close();

?>
</div>
<div class="nav navbas">
<?php
if ($num_rows>5){
	echo "<span class=\"nav_list\">";
	echo $txtnav;
	echo "</span>";
}
?>
</div>
<div style="float:left;color:#1e1e1e"><?php
// Timer end and print
list($g2_usec, $g2_sec) = explode(" ",microtime());
define ("t_end", (float)$g2_usec + (float)$g2_sec);
 print round (t_end-t_start, 1)." secondes"; ?>
</div>
<footer <?php if ($num_rows<6) echo "class=\"marge\"" ?>>
	<span class="bl_foot">by <a href="https://twitter.com/shona_gon">/* / */</a>&nbsp;&nbsp; (<a href="/dozo/crotos-moteur-de-recherche-sur-les-oeuvres-dart-dans-wikidata" class="externe">info [fr]</a> , <a href="https://github.com/zone47/CROTOS"class="externe">source</a>, <a href="bdd/crotos.sql.zip">data</a> )&nbsp;&nbsp; with </span>
    <span class="bl_foot"><a href="http://www.wikidata.org"><img src="img/wikidata.png" alt="Wikidata" /></a>  <a href="http://commons.wikimedia.org"><img src="img/wikimedia-commons.png" alt="Wikimedia Commons" /></a>  <a href="http://www.semanticpedia.org/"><img src="img/semanticpedia.png" alt="Sémanticpedia" /></a>    <img src="img/photographer.png" alt="Photographers" />  al.</span>
    <span class="bl_foot"> and &lt;3</span>
    <div class="update">Last update:
<?php
$fp = fopen ("datemaj.txt", "r");
echo fgets ($fp, 255);
fclose ($fp);
?></div>
<!-- Encore des étoiles. Qu'elles brillent sur vous et que vous brillez avec elles. -->
</footer>
	</div>
</body>
</html>