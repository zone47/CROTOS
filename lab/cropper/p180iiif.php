<?php
set_time_limit(2400);
//error_reporting(E_ALL & ~E_NOTICE);
include "../../traduction.php";
include "../../config.php";
include "../../functions.php";
include "../../init.php";

function label($wdq,$l){
	global $fold_crotos;
	$qitem_path=$fold_crotos."lab/artworks/items/Q".$wdq.".json";
	if (!(file_exists($qitem_path))){
		$url_api="https://www.wikidata.org/w/api.php?action=wbgetentities&ids=Q".$wdq."&format=json&props=labels";
		copy($url_api, $qitem_path);
	}
	$dfic =file_get_contents($qitem_path,true);
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

$q_label="";
if ($q!=""){
	$q_label=label($q,$l);
	$q_label_esc=str_replace("'","\\'",$q_label);

}
$lgs_querry="en,fr,ar,be,bg,bn,ca,cs,da,de,el,es,et,fa,fi,he,hi,hu,hy,id,it,ja,jv,ko,nb,nl,eo,pa,pl,pt,ro,ru,sh,sk,sr,sv,sw,te,th,tr,uk,yue,vec,vi,zh";
if ($l!="en"){
	$lgs_querry=$l.",".str_replace(",".$l,"",$lgs_querry);	
}

$sparql="SELECT distinct ?item ?itemLabel ?coord (GROUP_CONCAT(distinct ?creatorLabel; separator=\" - \") as ?crea)
(GROUP_CONCAT(distinct STR(?collLabel); separator=\" - \") as ?collection) (SAMPLE(year(?d))as ?date)(SAMPLE(?image) as ?img) 
WHERE{
 ?item wdt:P180/wdt:P279* wd:Q".$q." .
 ?item p:P180 ?DeclarationDepeint.
 ?DeclarationDepeint ps:P180/wdt:P279* wd:Q".$q.".
 ?DeclarationDepeint pq:P2677 ?coord.
 ?item wdt:P18 ?image.
 OPTIONAL{?item wdt:P571 ?d.}
 OPTIONAL{?item wdt:P170 ?creator.}
 OPTIONAL{?item wdt:P195 ?coll.}
 SERVICE wikibase:label { bd:serviceParam wikibase:language \"[AUTO_LANGUAGE],".$lgs_querry."\".
  ?item rdfs:label ?itemLabel.
  ?creator rdfs:label ?creatorLabel.
  ?coll rdfs:label ?collLabel.
 }
}
GROUP BY ?item ?itemLabel ?coord 
ORDER BY ?date";
$sparqlurl=urlencode($sparql);
$query="https://query.wikidata.org/#".str_replace("+"," ",$sparqlurl);
?><!DOCTYPE html>
<html ng-app="artworkApp">
<head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8">
	<title>p180 - IIIF Fragment</title>
   	<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../js/blue/styles.css" />
    <link rel="stylesheet" href="../styles.css">
    <link rel="stylesheet" href="css/main.css">
	<!--<link rel="stylesheet" href="../artworks/styles.css">-->
   	<script src="../../js/jquery.js"></script>
   	<script src="../../js/jquery.tablesorter.min.js"></script>
    <script type="text/javascript" src="../artworks/addclear.js"></script>
   	<script src="//ajax.googleapis.com/ajax/libs/angularjs/1.2.10/angular.js"></script>
   	<script src="//angular-ui.github.io/bootstrap/ui-bootstrap-tpls-0.11.0.js"></script>
    <script>
var lg="<?php echo $l ?>";
    </script>
	<script src="../artworks/collection.js"></script>
    <script>
$(document).ready(function(){ 
	$("#search_wd").prop('disabled', false);

	$("#btn_search").click(function(event) {
		 $('#pform').submit();
	});
	$('#lg').change(function() {
		$('#pform').submit();
	});
}); 
jQuery(function($) {
  function tog(v){return v?'addClass':'removeClass';} 
  $(document).on('input', '.clearable', function(){
    $(this)[tog(this.value)]('x');
  }).on('mousemove', '.x', function( e ){
    $(this)[tog(this.offsetWidth-18 < e.clientX-this.getBoundingClientRect().left)]('onX');   
  }).on('click', '.onX', function(){
    $(this).removeClass('x onX').val('').change();
  });
});
    </script>
    <style>
body{
	font-size: 13px; 
	padding-left:0;	
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
label,select{
	font-size:75%;	
	font-weight:normal;
}
img{
	border:1px solid #afafaf;
	max-width:200px;
}
#corps{
	font-size:120%;
	margin-left:0;
}
#bl_search label,input{
	font-size:120%;
	margin-left:0;
}
 table {
	 margin-top:1em;
 }
 th {
	 text-align:center;
     padding:5px;}
 .ctxt{
	padding:0 3px;
 }
 .cimg{
	padding:5px 0;
	text-align:center;
 }
</style>
</head>
<body>
<?php include "../entete.php" ?>
<div id="container">
<div id="page" ng-controller="artworkController">
<?php if ($q!="")
	echo "<div ng-init=\"dataModel = {collection:[{text:'".$q_label_esc."',attribute:'',index:0,input:'".$q_label_esc."'}]}\"></div>";
?>
<form id="pform">
    <h1><?php
if ($l=="fr")  echo "Fragments d'œuvres"; else  echo "Artworks fragments"; 
?> (cf. <a href="/crotos/lab/cropper/">IIIF Image Cropper</a>) <label for="lg" id="label_lg"> - <?php echo translate($l,"language") ?></label>
				<select name="l" id="lg">
<?php
for ($i=0;$i<count($lgs);$i++){
	/* Easter egg */if (($lgs[$i]=="mu")&&($lg!="mu")) echo "<!--\n ";
    echo "				<option value=\"".translate($lgs[$i],"lang_code")."\"";
	if ($l==translate($lgs[$i],"lang_code"))
		 echo " selected=\"selected\"";
	echo " >".translate($lgs[$i],"lg")."</option>\n";	
	/* Easter egg */if (($lgs[$i]=="mu")&&($l!="mu")) echo " -->\n";
}
?>
		</select></h1>
    <div id="corps">
      <div>
        <div ng-repeat="collection in dataModel.collection">
        	<div id="top_form">
            
        
    	  
       	<div id="bl_search">

    	  <label for="search_wd" id="lb_search">p180 :</label>  

            <input type="text" ng-model="collection.text"
                  typeahead="label as label.display for label in suggestWikidata($viewValue, $index)"
            typeahead-min-length="1" typeahead-on-select="onSelectLine('collection', $item)" size="25" id="search_wd" autocomplete="off" value="<?php echo $q_label?>" <?php if ($q!="") echo "disabled=\"disabled\"" ?> class="clearable<?php
if ($q!="") echo " x Onx"; ?>"/> <?php 
if ($q!=""){
	echo " (";
	if ($l=="fr")  echo "<a href=\"".$query."\">requête SparQL</a>"; else  echo "<a href=\"".$query."\">SparQL query</a>"; 
	echo ")";
}
?>
            
            
            </div>
            
    		</div>

</div>
</div>
</div>
</form>
<?php 
if ($q!=""){
?>
<table  border="1">
  <tr>
    <th>Fragment</th>
    <th>Wikidata</th>
    <th style="width:200px;">Titre</th>
    <th>Créateur</th>
    <th>Année</th>
    <th>Collection</th>
    <th>Image</th>
  </tr>
 
<?php


$req="https://query.wikidata.org/sparql?format=json&query=".$sparqlurl;
$res  = file_get_contents($req);
$responseArray = json_decode($res,true);

foreach ($responseArray["results"]["bindings"] as $key => $value){
	$WDQ=str_replace("http://www.wikidata.org/entity/","",$value["item"]["value"]);
	$coord=$value["coord"]["value"];
	$img=str_replace("http://commons.wikimedia.org/wiki/Special:FilePath/","",$value["img"]["value"]);
	$fragmentIIIF="http://tools.wmflabs.org/zoomviewer/proxy.php?iiif=".$img."/".$coord."/full/0/default.jpg";
	$img=str_replace("%20","_",$img);
	$img=urldecode($img);
	$digest = md5($img);
	$folder = $digest[0] . '/' . $digest[0] . $digest[1] . '/' . str_replace(" ","_",$img);
	$urlimg = 'https://upload.wikimedia.org/wikipedia/commons/thumb/' . $folder."/200px-". str_replace(" ","_",$img);
	$crea=$value["crea"]["value"];
	if 	($crea[0]=="t")
		 $crea="";
	
    echo " <tr>\n";
    echo "   <td class=\"cimg\"><img src=\"".$fragmentIIIF."\" alt=\"\" /></td>\n";
    echo "   <td class=\"ctxt\"><a href=\"https://www.wikidata.org/wiki/".$WDQ."\">".$WDQ."</a></td>\n";
    echo "   <td class=\"ctxt\">".$value["itemLabel"]["value"]."</td>\n";
    echo "   <td class=\"ctxt\">".$crea."</td>\n";
    echo "   <td class=\"ctxt\">".$value["date"]["value"]."</td>\n";
    echo "   <td class=\"ctxt\">".$value["collection"]["value"]."</td>\n";
	echo "   <td class=\"cimg\"><img src=\"".$urlimg."\" alt=\"\" /></td>\n";
    echo " </tr>\n";

}

?>
</table>

<hr />
<small>data on <a href="http://creativecommons.org/publicdomain/zero/1.0/" title="CC0 1.0 Universal"><img src="/dozo/wp-content/themes/twentyten/cc-zero.png" alt="CC0 1.0 Universal" height="22" width="63"></a> / for images, see the <a href="https://commons.wikimedia.org/">Wikimedia Commons</a> page. 
</small>
<?php 
}
?>
</div>
</body>
</html>
