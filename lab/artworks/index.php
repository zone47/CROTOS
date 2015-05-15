<?php
set_time_limit(2400);
//error_reporting(E_ALL);
include "../../traduction.php";
include "../../config.php";
include "../../functions.php";
include "functions.php";
$h_thumb=80;
list($g_usec, $g_sec) = explode(" ",microtime());
$t_start=(float)$g_usec + (float)$g_sec;


$lgs=array("ar","bn","br","ca","cs","de","el","en","eo","es","fa","fi","fr","he","hi","id","it","ja","jv","ko","mu","nl","pa","pl","pt","ru","sw","sv","te","th","tr","uk","vi","zh");
$lg="fr";
if (isset($_COOKIE['l']))
	$lg=$_COOKIE['l'];
if (isset($_GET['l']))
	if ($_GET['l']!=""){ 
		setcookie ("l",$_GET['l'], time() + 31536000, "/");
		$lg=$_GET['l'];
	}
$prop=195;	
if (isset($_GET['p'])){
	$prop=$_GET['p'];
}
$q="";
if (isset($_GET['q'])){
	$q=str_ireplace("q","",$_GET['q']);
}
$tab_check = array(
	"c0" => "1",
	"c18"=> "1",
	"c373"=> "",
	"c1"=> "1",
	"c2"=> "",
	"c170"=> "1",
	"c571"=> "1",
	"c31"=> "",
	"c186"=> "",
	"c195"=> "",
	"c217"=> "",
	"c276"=> "",
	"c179"=> "",
	"c3"=> "1",
	"c973"=> "",
	"c727"=> "",
	"c347"=> "",
	"c1212"=> "",
	"c214"=> "",
	"c350"=> "",
	"c4"=> ""
);
if ($prop==170){
	$tab_check["c170"]="";
	$tab_check["c195"]="1";
}
$cpt_check=0;
foreach($tab_check as $key=>$value){
	if (isset($_GET[$key])){
		if ($cpt_check==0){
			$tab_check["c0"]="";$tab_check["c1"]="";$tab_check["c170"]="";$tab_check["c571"]="";$tab_check["c3"]="";$tab_check["c18"]="";$tab_check["c170"]="";$tab_check["c195"]="";
		}
		$cpt_check++;
		$tab_check[$key]=$_GET[$key];
	}
}

$link = mysqli_connect ($host,$user,$pass,$db) or die ('Erreur : '.mysqli_error());
mysqli_query($link,"SET NAMES 'utf8'"); 

$q_label="";
$nbartworks=0;
if ($q!=""){
	$q_label=label($q,$lg);
	$q_label_esc=str_replace("'","\\'",$q_label);

	$cqitem_path=$fold_crotos."lab/artworks/queries/cQ".$q."-p".$prop.".json";
	$wqitem_path=$fold_crotos."lab/artworks/queries/wQ".$q."-p".$prop.".json";
	$nqitem_path=$fold_crotos."lab/artworks/queries/nQ".$q."-p".$prop.".json";
	if (intval($prop)==195)
		$query="q=claim[".$prop.":%28tree[".$q."][][361]%29,276:%28tree[".$q."][][361]%29]&run=Run";
	elseif (intval($prop)==276)
		$query="q=claim[276:%28tree[".$q."][][361]%29,276:%28tree[".$q."][][276]%29]&run=Run";
	else
		$query="q=claim[".$prop.":%28tree[".$q."][][279]%29]&run=Run";
	$wdq_link="https://tools.wmflabs.org/autolist/index.php?wd".$query;

	if (!(file_exists($cqitem_path))){
		$url_api="http://wdq.wmflabs.org/api?".$query."&download=1";			
		$res =file_get_contents($url_api,true);
		$responseArray = json_decode($res,true);
		$nbartworks=count($responseArray["items"]);
	}
	else
		$nbartworks=intval(file_get_contents($nqitem_path));
}
?><!DOCTYPE html>
<html ng-app="artworkApp">
<head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8">
	<title><? echo translate($lg,$prop); if ($q_label!="") echo " - ".$q_label; ?> </title>
   	<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../js/blue/styles.css" />
    <link rel="stylesheet" href="../styles.css">
	<link rel="stylesheet" href="styles.css">
   	<script src="../../js/jquery.js"></script>
   	<script src="../../js/jquery.tablesorter.min.js"></script>
    <script type="text/javascript" src="addclear.js"></script>
   	<script src="//ajax.googleapis.com/ajax/libs/angularjs/1.2.10/angular.js"></script>
   	<script src="//angular-ui.github.io/bootstrap/ui-bootstrap-tpls-0.11.0.js"></script>
    <script>
var lg="<?php echo $lg ?>";
    </script>
	<script src="collection.js"></script>
    <script>
$(document).ready(function(){ 
	$("#search_wd").prop('disabled', false);
	$("#download").css('visibility','visible');
	$("#occ").tablesorter( {sortList: [[0,0]]} ); 
	$('#lg,#props').change(function() {
		$('#prop_form').submit();
	});
	$("#btn_search").click(function(event) {
		 $('#prop_form').submit();
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
</head>
<body>
<?php include "../entete.php" ?>
<div id="page" ng-controller="artworkController">
<?php if ($q!="")
	echo "<div ng-init=\"dataModel = {collection:[{text:'".$q_label_esc."',attribute:'',index:0,input:'".$q_label_esc."'}]}\"></div>";
?>
    <h1 id="page_title"><span><?php
    if ($lg=="fr")
		echo "<a href=\"../artworks/\">Œuvres d'art</a> sur <a href=\"https://www.wikidata.org/\">Wikidata</a> <a href=\"https://www.wikidata.org/\"><img src=\"wikidata.png\" alt=\"wikidata\"/></a>";
	else
		echo "<a href=\"https://www.wikidata.org/\">Wikidata</a> <a href=\"https://www.wikidata.org/\"><img src=\"wikidata.png\" alt=\"wikidata\"/></a> <a href=\"../artworks/\">artworks items</a>";
	?>, via <a href="/crotos/">Crotos</a> <a href="/crotos/"><img src="crotos.png" alt="crotos"/></a> & <a href="http://tools.wmflabs.org/autolist/index.php">Wikidata Query</a> <a href="http://tools.wmflabs.org/autolist/index.php"><img src="tools_lab.png" alt="Tools Lab"/></a></span></h1>
    <div id="container">
      <div>
        <div ng-repeat="collection in dataModel.collection">
        <form id="prop_form">
        	<div id="top_form">
		  	<label for="lg" id="label_lg"><?php echo translate($lg,"language") ?></label>
				<select name="l" id="lg">
<?php
for ($i=0;$i<count($lgs);$i++){
	/* Easter egg */if (($lgs[$i]=="mu")&&($lg!="mu")) echo "<!--\n ";
    echo "				<option value=\"".translate($lgs[$i],"lang_code")."\"";
	if ($lg==translate($lgs[$i],"lang_code"))
		 echo " selected=\"selected\"";
	echo " >".translate($lgs[$i],"lg")."</option>\n";	
	/* Easter egg */if (($lgs[$i]=="mu")&&($lg!="mu")) echo " -->\n";
}
?>
		</select>
        
    	  <label for="props" id="label_prop"><b><?php if ($lg=="fr") echo "Propriété"; else echo "Property"; ?></b></label>  
         <select name="p" id="props"><b>
<?php
//$tab_props=array(31,135,136,144,170,180,186,195,276,921,941);
$tab_props=array(170,195);
if (!(in_array(intval($prop),$tab_props)))
	$tab_props[]=intval($prop);
for ($i=0;$i<count($tab_props);$i++){
    echo "			<option value=\"".$tab_props[$i]."\"";
	if ($prop==$tab_props[$i]) echo " selected=\"selected\"";
	echo " >";
	$label_prop=translate($lg,$tab_props[$i]);
	echo "$label_prop";
	if ($label_prop!="")
		echo " / ";
	echo "p".$tab_props[$i]."</option>\n";	
}
?>
		</b></select>    
       	<div id="bl_search">

    	  <label for="search_wd" id="lb_search"><?php echo translate($lg,"search") ?></label>  

            <input type="text" ng-model="collection.text"
                  typeahead="label as label.display for label in suggestWikidata($viewValue, $index)"
            typeahead-min-length="1" typeahead-on-select="onSelectLine('collection', $item)" size="44" id="search_wd" autocomplete="off" value="<?php echo $q_label?>" <?php if ($q!="") echo "disabled=\"disabled\"" ?> class="clearable<?php
if ($q!="") echo " x Onx"; ?>"/>
            
            
            </div>
            <div id="suggest">
<?php
	$alea_bar="";
	for ($i=0;$i<2;$i++){
		$sql="SELECT label,label_page.qwd FROM label_page,p".$prop." WHERE label_page.type=1 AND label_page.prop=".$prop." AND label_page.lg='".$lg."' AND label_page.label!='' AND p".$prop.".id=label_page.id_art_or_prop AND p".$prop.".nb>10 ORDER BY RAND() LIMIT 0,1";
		$rep=mysqli_query($link,$sql);
		if (mysqli_num_rows($rep)>0){
			$data_r = mysqli_fetch_assoc($rep);
			if ($alea_bar!="")
				$alea_bar.=", ";
			$alea_bar.="<a href=\"?q=Q".$data_r['qwd']."&l=".$lg."&p=".$prop."\">".$data_r['label']."</a>";	
		}
	}
	$alea_bar="<span>".translate($lg,"suggest")." :</span> ".$alea_bar;
	echo $alea_bar;

?>
			</div>
    		</div>
<?php

if ($q!=""){?>
<fieldset>
<?php 

$crit_str="";
$crits=array(0,1,2,170,571,31,186,195,217,276,179,3,973,727,347,1212,214,350,18,4,373);
	for ($i=0;$i<count($crits);$i++){
		echo "<label>".lab_prop($lg,$crits[$i])."</label>";
		echo "<input name=\"c".$crits[$i]."\" id=\"c".$crits[$i]."\" type=\"checkbox\" value=\"1\"";
		if ($tab_check["c".$crits[$i]]=="1"){
			echo " checked=\"checked\"";
			$crit_str.="1";
		}
		else
			$crit_str.="0";
		echo " />&nbsp;&nbsp;&nbsp;\n";
		if (($crits[$i]==186)||($crits[$i]==179)||($crits[$i]==350))
			echo "<br />";
	}
?>

  <div id="submit_form"><?php 
if ($q!="") echo "<input type=\"hidden\" value=\"".$q."\" name=\"q\" />";
  ?><button type="submit" id="btn_search" form="prop_form" value="Submit"><img src="magnifying.png" alt="" /></button></div>
 
</fieldset>

     </form>
        </div>
      </div>
    </div>
<?php 
}
if ($nbartworks>0){
	$csv=true;
	$fname="Q".$q."-".$prop."-".bindec($crit_str)."-".$lg.".csv";
	$f_path=$fold_crotos."lab/artworks/csv/".$fname;
	if (!(file_exists($f_path))){
		$csv=false;
		$fpcsv = fopen("csv/".$fname, "w");
		fputs($fpcsv, $bom =( chr(0xEF) . chr(0xBB) . chr(0xBF) ));
	}

?>
<table id="occ" class="tablesorter ">
<caption><?php echo "<a href=\"https://www.wikidata.org/wiki/Q".$q."\">".$q_label." (Q".$q.")</a> - ".$nbartworks ?> <?php if ($lg=="fr") echo "œuvres"; else echo "artworks"; ?> <br>
<span><a href="/crotos/?p<?php echo $prop."=". $q ?>" class="externe"><b>Crotos</b></a> – <a href="<?php echo $wdq_link ?>" class="externe">Wikidata query</a> – <a href="csv/<?php echo $fname ?>" id="download"><b><?php if ($lg=="fr") echo "Télécharger"; else echo "Dowload"; ?> CSV</b></a></span> </caption>
<thead> 
<tr> 
	<th>n°</th>
<?php 
if (!$csv){
	$csvtmp= array();
	$csvtmp[]="n°";
}
foreach($tab_check as $key=>$value){
	if ($value=="1"){
		if ($key!="c3")
			echo "	<th>".lab_prop($lg,str_replace("c","",$key))."</th>\n";
		else
			echo "	<th>".lab_prop($lg,str_replace("c","",$key))."*</th>\n";
		if (!$csv) 
			$csvtmp[]=lab_prop($lg,str_replace("c","",$key));
	}
}
if (!$csv) 
	fputcsv($fpcsv, $csvtmp,";");
?>
</tr> 
</thead> 
<tbody>
<?php
	$qwd_data=array();
	if (!(file_exists($cqitem_path))){
		$sql_artw="";
		for ($i=0;$i<$nbartworks;$i++){
			if ($sql_artw!="")
				$sql_artw.=" OR";
			$sql_artw.=" qwd=".$responseArray["items"][$i];	
		}
	
		$sql_artw="SELECT * from artworks WHERE ".$sql_artw. " ORDER by qwd";
		$rep=mysqli_query($link,$sql_artw);
		$miss_list="";
		$qog_idx=0;
		$ctext="";
		$miss_w=array();
		while ($row = mysqli_fetch_assoc($rep)){
			$id=$row['id'];
			if ($ctext!="")
				$ctext.=" OR";
			$ctext.=" id=".$id;			
			$qwd_idx = array_search($row['qwd'],$responseArray["items"]);
			for ($i=($qog_idx+1);$i<$qwd_idx;$i++)
				$miss_w[]=$responseArray["items"][$i];
			$qwd_data[]=data_qwd($row['qwd'],$row);
			$qog_idx=$qwd_idx;
		}
		for ($i=0;$i<count($miss_w);$i++)
			$qwd_data[]=data_qwd($miss_w[$i],0);
		
		$fp=fopen($nqitem_path,"w");	
		fputs($fp,$nbartworks);
		fclose($fp);
		$fp=fopen($cqitem_path,"w");	
		fputs($fp,$ctext);
		fclose($fp);
		$fp=fopen($wqitem_path,"w");
		fputs($fp,serialize($miss_w));
		fclose($fp);
	}
	else {
		$sql_artw="SELECT * from artworks WHERE ".file_get_contents($cqitem_path);
		$rep=mysqli_query($link,$sql_artw);
		while ($row = mysqli_fetch_assoc($rep))
			$qwd_data[]=data_qwd($row['qwd'],$row);
		$miss_w=unserialize(file_get_contents($wqitem_path,true));
		for ($i=0;$i<count($miss_w);$i++)
			$qwd_data[]=data_qwd($miss_w[$i],0);
	}

	$qwd_idx = array();
	foreach ($qwd_data as $key => $row)
		$qwd_idx[$key] = $row["lb0"];
	array_multisort($qwd_idx, SORT_ASC, $qwd_data);
	
	for ($i=0;$i<count($qwd_data);$i++){
		if (!$csv){ 
			$csvtmp= array();
			$csvtmp[]=($i+1);
		}
		echo "<tr>\n";
		echo "	<td>".($i+1)."</td>\n";
		foreach($tab_check as $key=>$value){
			if ($value=="1"){
				switch ($key){
					case "c0":
					    echo "	<td><a href=\"https://www.wikidata.org/wiki/Q".$qwd_data[$i]["lb0"]."\" title=\"https://www.wikidata.org/wiki/Q".$qwd_data[$i]["lb0"]."\">Q".$qwd_data[$i]["lb0"]."</a></td>\n";
						if (!$csv) $csvtmp[]="https://www.wikidata.org/wiki/Q".$qwd_data[$i]["lb0"];
						break;
					case "c3":
					    echo "	<td>".$qwd_data[$i]["lb3"]."</td>\n"; 
						if (!$csv) $csvtmp[]=str_replace(", ",";",$qwd_data[$i]["wp_links"]);
						break;
					case "c4":
					    echo "	<td><a href=\"https://tools.wmflabs.org/reasonator/?q=Q".$qwd_data[$i]["lb0"]."&lang=".$lg."\">Q".$qwd_data[$i]["lb0"]."</a></td>\n";
						if (!$csv) $csvtmp[]="https://tools.wmflabs.org/reasonator/?q=Q".$qwd_data[$i]["lb0"]."&lang=".$lg;
						break;
					case "c18":
					    echo "	<td class=\"img_cell\">".$qwd_data[$i]["lb18"]."</td>\n";
						if (!$csv) $csvtmp[]=$qwd_data[$i]["url_img"];
						break;
					case "c373":
						echo "	<td>";
						if ($qwd_data[$i]["lb373"]!=""){
							echo "<a href=\"https://commons.wikimedia.org/wiki/Category:".str_replace(" ","_",$qwd_data[$i]["lb373"])."\" title=\"".str_replace(" ","_",$qwd_data[$i]["lb373"])."\">".translate($lg,"CommonsCat")."</a>";
							if (!$csv) $csvtmp[]="https://commons.wikimedia.org/wiki/Category:".str_replace(" ","_",$qwd_data[$i]["lb373"]);
						}
						else
							if (!$csv) $csvtmp[]="";
						echo "	</td>";
						break;
					case "c973":
						echo "	<td class=\"img_cell2\">";
						if ($qwd_data[$i]["lb973"]!=""){
							echo "<a href=\"".$qwd_data[$i]["lb973"]."\" title=\"".$qwd_data[$i]["lb973"]."\"><img src=\"../../img/site_link.png\" alt=\"\"/></a>";
							if (!$csv) $csvtmp[]=$qwd_data[$i]["lb973"];
						}
						else
							if (!$csv) $csvtmp[]="";
						echo "	</td>";
						break;
					case "c727":
						echo "	<td class=\"img_cell2\">";
						if ($qwd_data[$i]["lb727"]!=""){
							echo "<a href=\"http://europeana.eu/portal/record/".$qwd_data[$i]["lb727"].".html\" title=\"".translate($lg,"Europeana")." ".$qwd_data[$i]["lb727"]."\"><img src=\"../../img/europeana.png\" alt=\"\"/></a>";
							if (!$csv) $csvtmp[]="http://europeana.eu/portal/record/".$qwd_data[$i]["lb727"].".html";
						}
						else
							if (!$csv) $csvtmp[]="";
						echo "	</td>";
						break;	
					case "c347":
						echo "	<td>";
						if ($qwd_data[$i]["lb347"]!=""){
							echo "<a href=\"http://www.culture.gouv.fr/public/mistral/joconde_fr?ACTION=CHERCHER&amp;FIELD_1=REF&amp;VALUE_1=".$qwd_data[$i]["lb347"]."\" title=\"".translate($lg,"Joconde")."\">".$qwd_data[$i]["lb347"]."</a>";
							if (!$csv) $csvtmp[]="http://www.culture.gouv.fr/public/mistral/joconde_fr?ACTION=CHERCHER&FIELD_1=REF&VALUE_1=".$qwd_data[$i]["lb347"];
						}
						else
							if (!$csv) $csvtmp[]="";
						echo "	</td>";
						break;	
					case "c1212":
						echo "	<td>";
						if ($qwd_data[$i]["lb1212"]!=""){
							echo "<a href=\"http://cartelfr.louvre.fr/cartelfr/visite?srv=car_not_frame&idNotice=".$qwd_data[$i]["lb1212"]."\" title=\"".translate($lg,"Atlas")."\">".$qwd_data[$i]["lb1212"]."</a>";
							if (!$csv) $csvtmp[]="http://cartelfr.louvre.fr/cartelfr/visite?srv=car_not_frame&idNotice=".$qwd_data[$i]["lb1212"];
						}
						else
							if (!$csv) $csvtmp[]="";
						echo "	</td>";
						break;
					case "c214":
						echo "	<td>";
						if ($qwd_data[$i]["lb214"]!=""){
							echo "<a href=\"http://viaf.org/viaf/".$qwd_data[$i]["lb214"]."\" title=\"".translate($lg,"VIAF")."\">".$qwd_data[$i]["lb214"]."</a>";
							if (!$csv) $csvtmp[]="http://viaf.org/viaf/".$qwd_data[$i]["lb214"];
						}
						else
							if (!$csv) $csvtmp[]="";
						echo "	</td>";
						break;
					case "c350":
						echo "	<td>";
						if ($qwd_data[$i]["lb350"]!=""){
							echo "<a href=\"https://rkd.nl/nl/explore/images/".$qwd_data[$i]["lb350"]."\" title=\"".translate($lg,"RKDimages")."\">".$qwd_data[$i]["lb350"]."</a>";
							if (!$csv) $csvtmp[]="https://rkd.nl/nl/explore/images/".$qwd_data[$i]["lb350"];
						}
						else
							if (!$csv) $csvtmp[]="";
						echo "	</td>";
						break;
					case "url_img":
					case "wp_links":
						break;
					default:
						echo "	<td>".$qwd_data[$i][str_replace("c","lb",$key)]."</td>\n"; 
						if (!$csv) $csvtmp[]=strip_tags($qwd_data[$i][str_replace("c","lb",$key)]);
				}
			}
		}
		echo "</tr>\n"; 
		if (!$csv) fputcsv($fpcsv, $csvtmp,";");

	}
	if (!$csv) fclose($fpcsv);
	echo "</tbody>\n";
	echo "</table>\n";
	if ($tab_check["c3"]==1) {?>
<div><small>* in Wikipedia <a href="https://ar.wikipedia.org/">ar</a>, 
<a href="https://bn.wikipedia.org/">bn</a>, 
<a href="https://br.wikipedia.org/">br</a>, 
<a href="https://ca.wikipedia.org/">ca</a>, 
<a href="https://cs.wikipedia.org/">cs</a>, 
<a href="https://de.wikipedia.org/">de</a>, 
<a href="https://el.wikipedia.org/">el</a>, 
<a href="https://en.wikipedia.org/">en</a>, 
<a href="https://eo.wikipedia.org/">eo</a>, 
<a href="https://es.wikipedia.org/">es</a>, 
<a href="https://fa.wikipedia.org/">fa</a>, 
<a href="https://fi.wikipedia.org/">fi</a>, 
<a href="https://fr.wikipedia.org/">fr</a>, 
<a href="https://he.wikipedia.org/">he</a>, 
<a href="https://hi.wikipedia.org/">hi</a>, 
<a href="https://id.wikipedia.org/">id</a>, 
<a href="https://it.wikipedia.org/">it</a>, 
<a href="https://ja.wikipedia.org/">ja</a>, 
<a href="https://jv.wikipedia.org/">jv</a>, 
<a href="https://ko.wikipedia.org/">ko</a>, 
<a href="https://mu.wikipedia.org/">mu</a>, 
<a href="https://nl.wikipedia.org/">nl</a>, 
<a href="https://pa.wikipedia.org/">pa</a>, 
<a href="https://pl.wikipedia.org/">pl</a>, 
<a href="https://pt.wikipedia.org/">pt</a>, 
<a href="https://ru.wikipedia.org/">ru</a>, 
<a href="https://sw.wikipedia.org/">sw</a>, 
<a href="https://sv.wikipedia.org/">sv</a>, 
<a href="https://te.wikipedia.org/">te</a>, 
<a href="https://th.wikipedia.org/">th</a>, 
<a href="https://tr.wikipedia.org/">tr</a>, 
<a href="https://uk.wikipedia.org/">uk</a>, 
<a href="https://vi.wikipedia.org/">vi</a>, 
<a href="https://zh.wikipedia.org/">zh</a> 
</small></div>    
    <?
	}

	mysqli_close($link);
}
else{
	if ($q!="")
		echo "\n<p style=\"clear:both;margin-left:100px;padding-top:10px;font-weight:bold\">Pas de résultats</p>";	
}

?>
</div>

<p id="duration">
<?php
if ($nbartworks>0){
	list($g2_usec, $g2_sec) = explode(" ",microtime());
	$t_end= (float)$g2_usec + (float)$g2_sec;
	print "\n".gmdate("H:i:s",round ($t_end-$t_start, 1));	
}
?>
</p>
<hr />
<small>data on <a href="http://creativecommons.org/publicdomain/zero/1.0/" title="CC0 1.0 Universal"><img src="/dozo/wp-content/themes/twentyten/cc-zero.png" alt="CC0 1.0 Universal" height="22" width="63"></a> / for images, see the <a href="https://commons.wikimedia.org/">Wikimedia Commons</a> page<br>
tool by /* / */. Thanks <a href="https://www.wikidata.org/wiki/User:Poulpy">Poulpy</a> for the search box! 

</body>
</html>
