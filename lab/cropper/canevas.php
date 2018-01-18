<?php
set_time_limit(2400);
//error_reporting(E_ALL);
include "../../traduction.php";
include "../../config.php";
include "../../functions.php";
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


$lgs=array("ar","bn","br","ca","cs","cy","da","de","el","en","eo","es","fa","fi","fr","he","hi","id","it","ja","jv","ko","mu","nl","pa","pl","pt","ru","sw","sv","te","th","tr","uk","vi","zh");
$lg="fr";
if (isset($_COOKIE['l']))
	$lg=$_COOKIE['l'];
if (isset($_GET['l']))
	if ($_GET['l']!=""){ 
		setcookie ("l",$_GET['l'], time() + 31536000);
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


$q_label="";
$nbartworks=0;
if ($q!=""){
	$q_label=label($q,$lg);
	$q_label_esc=str_replace("'","\\'",$q_label);

}
?><!DOCTYPE html>
<html ng-app="artworkApp">
<head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8">
	<title><? echo translate($lg,$prop); if ($q_label!="") echo " - ".$q_label; ?> </title>
   	<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../js/blue/styles.css" />
    <link rel="stylesheet" href="../styles.css">
	<link rel="stylesheet" href="../artworks/styles.css">
   	<script src="../../js/jquery.js"></script>
   	<script src="../../js/jquery.tablesorter.min.js"></script>
    <script type="text/javascript" src="../artworks/addclear.js"></script>
   	<script src="//ajax.googleapis.com/ajax/libs/angularjs/1.2.10/angular.js"></script>
   	<script src="//angular-ui.github.io/bootstrap/ui-bootstrap-tpls-0.11.0.js"></script>
    <script>
var lg="<?php echo $lg ?>";
    </script>
	<script src="../artworks/collection.js"></script>
    <script>
$(document).ready(function(){ 
	$("#search_wd").prop('disabled', false);

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
		echo "fr</a>";
	else
		echo "<en</a>";
	?></span></h1>
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
        
    	  
       	<div id="bl_search">

    	  <label for="search_wd" id="lb_search"><?php echo translate($lg,"search") ?></label>  

            <input type="text" ng-model="collection.text"
                  typeahead="label as label.display for label in suggestWikidata($viewValue, $index)"
            typeahead-min-length="1" typeahead-on-select="onSelectLine('collection', $item)" size="44" id="search_wd" autocomplete="off" value="<?php echo $q_label?>" <?php if ($q!="") echo "disabled=\"disabled\"" ?> class="clearable<?php
if ($q!="") echo " x Onx"; ?>"/>
            
            
            </div>
            
    		</div>

</div>


<hr />
<small>data on <a href="http://creativecommons.org/publicdomain/zero/1.0/" title="CC0 1.0 Universal"><img src="/dozo/wp-content/themes/twentyten/cc-zero.png" alt="CC0 1.0 Universal" height="22" width="63"></a> / for images, see the <a href="https://commons.wikimedia.org/">Wikimedia Commons</a> page. 

</body>
</html>
