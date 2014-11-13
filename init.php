<?php 
/* / */
$script_name="index.php";
$p=1; // numéro de page par défaut
$lgs=array("ar","bn","br","ca","cs","de","el","en","eo","es","fa","fi","fr","he","hi","id","it","ja","jv","ko","mu","nl","pa","pl","pt","ru","sw","sv","te","th","tr","uk","vi","zh");
$random=false;
$s=""; // Search
$q="";
$y1=-40000;
$y2=2014;
$tab_idx = array(
	"p31" => "",// qwd type
	"p135"=> "",// qwd mouvement
	"p136"=> "",// qwd genre
	"p144"=> "",// qwd based on 
	"p170"=> "",// qwd creator
	"p179"=> "",// qwd series
	"p180"=> "",// qwd depicts
	"p186"=> "",// qwd material
	"p195"=> "",// qwd collection
	"p276"=> "",// qwd location
	"p361"=> "",// qwd part of
	"p921"=> "",// qwd subject heading
	"p941"=> ""// qwd inspired by
);
$tab_miss = array(
	"m1" => "",// label
	"m18"=> "",// image
	"m135"=> "",// movement
	"m136"=> "",// genre
	"m144"=> "",// based on
	"m170"=> "",// creator
	"m179"=> "",// series
	"m180"=> "",// depicts
	"m186"=> "",// material
	"m195"=> "",// collection
	"m214"=> "",// VIAF ID
	"m217"=> "",// inventory number
	"m276"=> "",// location
	"m347"=> "",// Joconde Id
	"m350"=> "",// RKDimages Id
	"m361"=> "",// part of
	"m373"=> "",// Commons category
	"m571"=> "",// date of creation
	"m727"=> "",// Europeana ID
	"m921"=> "",// subject heading
	"m941"=> "",// inspired by
	"m973"=> "",// described at URL
	"m1212"=> ""// Atlas ID
);
$tab_check = array(
	"c1" => "",// label
	"c18"=> "",// image
	"c135"=> "",// movement
	"c136"=> "",// genre
	"c144"=> "",// based on
	"c170"=> "",// creator
	"c179"=> "",// series
	"c180"=> "",// depicts
	"c186"=> "",// material
	"c195"=> "",// collection
	"c214"=> "",// VIAF ID
	"c217"=> "",// inventory number
	"c276"=> "",// location
	"c347"=> "",// Joconde Id
	"c350"=> "",// RKDimages Id
	"c361"=> "",// part of
	"c373"=> "",// Commons category
	"c571"=> "",// date of creation
	"c727"=> "",// Europeana ID
	"c921"=> "",// subject heading
	"c941"=> "",// inspired by
	"c973"=> "",// described at URL
	"c1212"=> ""// Atlas ID
);

foreach($tab_idx as $key=>$value)
	if (isset($_GET[$key]))
		$tab_idx[$key]=$_GET[$key];	

if ($mode==1){
	foreach($tab_miss as $key=>$value)
		if (isset($_GET[$key]))
			$tab_miss[$key]=$_GET[$key];
	foreach($tab_check as $key=>$value)
		if (isset($_GET[$key]))
			$tab_check[$key]=$_GET[$key];
}

if (isset($_GET['q']))
	if ($_GET['q']!="") 
		$q=$_GET['q'];
if (isset($_GET['p']))
	if ($_GET['p']!="") 
		$p=intval($_GET['p']);
if (isset($_GET['s'])){
	$s=$_GET['s'];
	$s=trim(str_replace("\"","",urldecode($s)));
	/* Easter egg */ if (str_replace("!","",str_replace(" ","",strtolower($s)))=="houba"){ $s="";$l="mu";setcookie ("l","mu", time() + 31536000);}
}
if (isset($_GET['y1']))
	if (is_int(intval($_GET['y1']))) 
		$y1=intval($_GET['y1']);
if (isset($_GET['y2']))
	if (is_int(intval($_GET['y2']))) 
		$y2=intval($_GET['y2']);
?>