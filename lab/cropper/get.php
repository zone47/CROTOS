<?php
include "../../init.php";
if ($q=="") $q="21013224";
include "../../traduction.php";
include "../../functions.php";
include "../../config.php";
//error_reporting(E_ALL & ~E_NOTICE);
$link = mysqli_connect ($host,$user,$pass,$db) or die ('Erreur : '.mysqli_error());
mysqli_query($link,"SET NAMES 'utf8'");

function label($wdq,$l){
	$url_api="https://www.wikidata.org/w/api.php?action=wbgetentities&ids=Q".$wdq."&format=json&props=labels";
		
	$dfic =file_get_contents($url_api,true);
	$data_item=json_decode($dfic,true);
	$ent_qwd=$data_item["entities"]["Q".$wdq]["labels"];
	$label="";
	if ($ent_qwd[$l]["value"])
		$label=$ent_qwd[$l]["value"];
	else{
		if ($ent_qwd["en"]["value"])
			$label=$ent_qwd["en"]["value"];
		else{
			if ($ent_qwd)
				$label=$ent_qwd[key($ent_qwd)]["value"];
		}
	}
	if ($label!="")
		return $label;
	else 
		return $wdq;
}
$sparql="select distinct ?depeint ?coord ?img ?article
WHERE {
  wd:Q".$q."  p:P180 ?DeclarationDepeint.
  ?DeclarationDepeint ps:P180 ?depeint.
  ?DeclarationDepeint pq:P2677 ?coord.
  wd:Q".$q." wdt:P18 ?img.
    
  OPTIONAL {?article schema:about ?depeint .
    FILTER (SUBSTR(str(?article), 1, 25) = \"https://".$l.".wikipedia.org/\") .}
}";

$sparqlurl=urlencode($sparql);
$query="https://query.wikidata.org/#".str_replace("+"," ",$sparqlurl);

?><!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
    <meta name="author" content="/* / */" />
    <title>Crotos Lab - Image annotation</title>
    <link rel="icon" href="../../favicon.ico" />
    <link rel="stylesheet" href="../styles.css">
  	<script src="../../js/jquery.js"></script>
<script>
$(document).ready(function(){ 
	$('#lg').change(function() {
		$('#lgform').submit();
	});
}); 

    </script>
 <style>
#content{
	font-size:110%;	
}
 img{
	 border:1px solid #afafaf;
 }
#imgmain{
float:left;	
}
#imgmain img{
margin-right:20px;
}
p{
margin-top:30px}
#thumbs  {
	clear:both;	
	padding:20px 0;
}
#thumbs   img{
    max-width:200px;
	max-height:200px;
	vertical-align:middle;
}
.thumb {
float:left;
padding-right:15px;
padding-bottom:10px;
height:245px;
position:relative;
}
.thumb span{
font-size:80%;
}
/*.thumb_txt{
	position:absolute;
	white-space: nowrap;
	bottom:0;
	width:100%;
}*/
</style>
</head>
<body>

<?php include "../entete.php" ?>
<form id="lgform">
<h1><?php
if ($l=="fr")  echo "Annotation d'œuvres"; else  echo "Artworks annotation"; 
?> (cf. <a href="/crotos/lab/cropper/">IIIF Image Cropper</a>) - <select name="l" id="lg">
<?php 
for ($i=0;$i<count($lgs);$i++){
	if ($lgs[$i]=="mu"){
		if ($l=="mu")
			echo "				<option value=\"".translate($lgs[$i],"lang_code")."\" selected=\"selected\">".translate($lgs[$i],"lg")."</option>\n";
	}
	else{
	    echo "				<option value=\"".translate($lgs[$i],"lang_code")."\"";
		if ($l==$lgs[$i])
			 echo " selected=\"selected\"";
		echo " >".translate($lgs[$i],"lg")."</option>\n";	
	}

}
?></select>
<input type="hidden"  name="q" value="<?php echo $q ?>" /></h1>
 
</form>
<div id="content">
<?php 
$sql="SELECT * from artworks WHERE qwd=$q";
$rep=mysqli_query($link,$sql);
$data = mysqli_fetch_assoc($rep);
$id_artw=$data['id'];
$qwd_art=$data['qwd'];
$described_link=$data['link'];
$p18=$data['P18'];
if ($described_link==""){
		if ($data['P727']!="")
			$described_link="http://europeana.eu/portal/record/".$data['P727'].".html";
		if ($data['P350']!="")
			$described_link="https://rkd.nl/nl/explore/images/".$data['P350'];
		if ($data['P2108']!="")
			$described_link="https://www.kulturarv.dk/kid/VisVaerk.do?vaerkId=".$data['P2108'];
		if ($data['P347']!="")
			$described_link="http://www.culture.gouv.fr/public/mistral/joconde_fr?ACTION=CHERCHER&amp;FIELD_1=REF&amp;VALUE_1=".$data['P347'];
		if ($data['P1212']!="")
			$described_link="http://cartelfr.louvre.fr/cartelfr/visite?srv=car_not_frame&idNotice=".$data['P1212'];
	}
$titre="";
$titre=label_item($qwd_art,$l);
$creator=txt_prop($id_artw,170,$l,"normal",false,false);
$pageWP=page_item($qwd_art,$l);
$lgWP="";
$pos=strpos($pageWP,"|");
if ($pos){
	$lgWP=substr($pageWP,0,$pos);
	$pageWP=substr($pageWP,$pos+1,strlen($pageWP));
}
$coll0=txt_prop($id_artw,195,$l,"normal",false,false);
$location0=txt_prop($id_artw,276,$l,"normal",false,false);

$coll_or_loc=$coll0;
if ($coll0=="")
	$coll_or_loc=$location0;
$date="";
if ((!(is_null($data['year2'])))||($data['year1']!=$data['year2'])){
	$date1=intval($data['year1']);
	$date2=intval($data['year2']);
	if (($date2-$date1)==1)
		$date.="~".$data['year1'];
	else{
		if ($data['b_date']==1)
			$date.="~";
		if (!(is_null($data['year1'])))
			$date.=$data['year1'];
		if ((!(is_null($data['year2'])))&&($data['year1']!=$data['year2'])) 
			$date.="-".$data['year2'];
	}
}

$thumb_h="";
if ($p18!=0){
	$sql="select * from commons_img where id=".$p18;
	$rep18=mysqli_query($link,$sql);
	if (mysqli_num_rows($rep18)!=0){
		$data_p18 = mysqli_fetch_assoc($rep18);
		$p18_str=$data_p18['P18'];
		$thumb_h=$data_p18['thumb_h'];
		$width_h=$data_p18['width_h'];
		// Hack to move to compilation
		if ((substr ($thumb_h,-3)=="tif")||(substr ($thumb_h,-3)=="iff")){
			$thumb_h=str_replace("tif/","tif/lossy-page1-",$thumb_h).".jpg";
		}
	}
}

if ($thumb_h!=""){
		$commons_link="http://commons.wikimedia.org/wiki/File:".htmlentities(str_replace("?","%3F",str_replace(" ","_",$p18_str)), ENT_QUOTES, "UTF-8");
		echo "<div id=\"imgmain\"><a href=\"".$commons_link."\"><img src=\"".esc_dblq($thumb_h)."\" alt=\"".esc_dblq($titre)."\" /></a></div>";
}


echo "<p><a href=\"https://www.wikidata.org/wiki/Q".$q."\"><b>".$titre."</b></a>";
if ($creator!="")
	echo ", ".$creator;
if ($date!="")
	echo ", ".$date;
if ($coll_or_loc!="")
	echo ", ".$coll_or_loc;
$links="";
if ($described_link!="")
	$links.="<a href=\"".$described_link."\">".$described_link."</a>";
if ($pageWP!=""){
	if ($links!="") $links.=" – ";
	if ($pageWP!=""){
		$pageWP_link=" <a href=\"https://";
		if ($lgWP!="")
			$pageWP_link.=$lgWP;
		else 
			$pageWP_link.=$l;
		$pageWP_link.=".wikipedia.org/wiki/".str_replace(" ","_",str_replace("\"","",$pageWP))."\"  title=\"".translate($l,"Wikipedia")."\">";

		$pageWP_link.=translate($l,"Wikipedia");
		
		if ($lgWP!="")
			$pageWP_link.=" [".$lgWP."]";
		$pageWP_link.="</a>";
	}
	$links.=$pageWP_link;
}
if ($links!="")
	echo "<br/>".$links;
echo "<br/>(";
if ($l=="fr")  echo "<a href=\"".$query."\">requête SparQL</a>)"; else  echo "<a href=\"".$query."\">SparQL query</a>)"; 
echo "</p>";



echo "<div id=\"thumbs\">";

$req="https://query.wikidata.org/sparql?format=json&query=".$sparqlurl;
$res  = file_get_contents($req);
$responseArray = json_decode($res,true);

foreach ($responseArray["results"]["bindings"] as $key => $value){
	$WDQdepeint=$value["depeint"]["value"];
	$Qdepeint=str_replace("http://www.wikidata.org/entity/Q","",$WDQdepeint);
	$labeldepeint=label_item($Qdepeint,$l);
	if ($labeldepeint=="")
		$labeldepeint=label($Qdepeint,$l);
	$WP="";
	$WP=$value["article"]["value"];
	$coord=$value["coord"]["value"];
	$vign="http://tools.wmflabs.org/zoomviewer/proxy.php?iiif=".$p18_str."/".$coord."/full/0/default.jpg";
	echo "<div class=\"thumb\"><div class=\"thumb_img\"><img src=\"".$vign."\" /></div><div class=\"thumb_txt\">";
	if ($WP!="")
		echo "<a href=\"".$WP."\">";
	echo $labeldepeint;	
	if ($WP!="")
		echo "</a>";
	echo"<br/><span><a href=\"https://www.wikidata.org/wiki/Q".$Qdepeint."?uselang=".$l."\">Q".$Qdepeint."</a></span></div>";
	echo "</div>";
}
echo "</div>";
?>

</div>
</body>
</html>
