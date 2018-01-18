<?php
include "../../init.php";
if ($q=="") $q="302";
include "../../traduction.php";
include "../../functions.php";
include "../../config.php";
error_reporting(E_ALL & ~E_NOTICE);
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
?><!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
    <meta name="author" content="/* / */" />
    <title>Crotos Lab - Image annotation</title>
    <link rel="icon" href="../../favicon.ico" />
    <link rel="stylesheet" href="../styles.css">
     <style>
#content{
	font-size:110%;	
}
 img{
	 border:1px solid #afafaf;
	  max-width:200px;
 }
 </style>
</head>
<body>
<?php include "../entete.php" ?>
<form id="lgform">
<h1><?php
if ($l=="fr")  echo "Fragments d'œuvres"; else  echo "Artworks fragments"; 
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
<h2>p180 : <?php echo label($q,$l) ?></h2>
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
/*$sparql="select distinct ?item ?coord (year(?date) as ?year) (IRI(CONCAT(\"http://tools.wmflabs.org/zoomviewer/proxy.php?iiif=\",REPLACE(str(?img),\"http://commons.wikimedia.org/wiki/Special:FilePath/\",\"\"),\"/\",?coord,\"/full/0/default.jpg\")) as ?FragmentIIIF ) ?img
WHERE {
 ?item wdt:P180 wd:Q302 .
 ?item p:P180 ?DeclarationDepeint.
 ?DeclarationDepeint ps:P180 wd:Q302.
 ?DeclarationDepeint pq:P2677 ?coord.
 ?item wdt:P18 ?img.
 ?item wdt:P571 ?date

} ORDER BY ?date";*/
$lgs="en,fr,ar,be,bg,bn,ca,cs,da,de,el,es,et,fa,fi,he,hi,hu,hy,id,it,ja,jv,ko,nb,nl,eo,pa,pl,pt,ro,ru,sh,sk,sr,sv,sw,te,th,tr,uk,yue,vec,vi,zh";
if ($l!="en"){
	$lgs=$l.",".str_replace(",".$l,"",$lgs);	
}
$sparql="select distinct ?item ?itemLabel ?coord ?crea  ?creaLabel ?collLabel (year(?date) as ?year)  ?img
WHERE {
 ?item wdt:P180 wd:Q".$q." .
 ?item p:P180 ?DeclarationDepeint.
 ?DeclarationDepeint ps:P180 wd:Q".$q.".
 ?DeclarationDepeint pq:P2677 ?coord.
 ?item wdt:P18 ?img.
 OPTIONAL{?item wdt:P571 ?date.}
 OPTIONAL{?item wdt:P170 ?crea.}
 OPTIONAL{?item wdt:P195 ?coll.}
 SERVICE wikibase:label { bd:serviceParam wikibase:language \"".$lgs."\". }
}

 ORDER BY ?date";

$sparqlurl=urlencode($sparql);
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
	$creaqwd=$value["crea"]["value"];	
    $crealb="";
	if 	($creaqwd[0]="h")
		 $crealb=$value["creaLabel"]["value"];
	
    echo " <tr>\n";
    echo "   <td><img src=\"".$fragmentIIIF."\" alt=\"\" /></td>\n";
    echo "   <td><a href=\"https://www.wikidata.org/wiki/".$WDQ."\">".$WDQ."</a></td>\n";
    echo "   <td>".$value["itemLabel"]["value"]."</td>\n";
    echo "   <td>".$crealb."</td>\n";
    echo "   <td>".$value["year"]["value"]."</td>\n";
    echo "   <td>".$value["collLabel"]["value"]."</td>\n";
	echo "   <td><img src=\"".$urlimg."\" alt=\"\" /></td>\n";
    echo " </tr>\n";

	
}

?>
</table>
</body>
</html>
