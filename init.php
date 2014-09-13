<?php 
/* / */
$script_name="index.php";
$nb=20; // Nombre de résultats par défaut
$p=1; // numéro de page par défaut
$lgs=array("ar","bn","br","ca","cs","de","el","en","eo","es","fa","fi","fr","he","hi","id","it","ja","jv","ko","mu","nl","pa","pl","pt","ru","sw","sv","te","th","tr","uk","vi","zh");
$random=false;
$tab_idx = array(
	"p31" => "",// qwd type
	"p135"=> "",// qwd mouvement
	"p136"=> "",// qwd genre
	"p144"=> "",// qwd based on 
	"p170"=> "",// qwd creator
	"p180"=> "",// qwd depicts
	"p186"=> "",// qwd material
	"p195"=> "",// qwd collection
	"p276"=> "",// qwd location
	"p921"=> "",// qwd subject heading
	"p941"=> ""// qwd inspired by
);
$tab_miss = array(
	"m1" => "",// label
	"m18"=> "",// image
	"m170"=> "",// creator
	"m571"=> "",// date of creation
	"m186"=> "",// material
	"m195"=> "",// collection
	"m276"=> "",// location
	"m180"=> "",// depicts
	"m136"=> "",// genre
	"m135"=> "",// movement
	"m347"=> "",// Joconde Id
	"m217"=> "",// Inventory number
	"m144"=> "",// Based on
	"m921"=> "",// Subject heading
	"m941"=> ""// Inspired by
);
$tab_check = array(
	"c1" => "",// label
	"c18"=> "",// image
	"c170"=> "",// creator
	"c571"=> "",// date of creation
	"c186"=> "",// material
	"c195"=> "",// collection
	"c276"=> "",// location
	"c180"=> "",// depicts
	"c136"=> "",// genre
	"c135"=> "",// movement
	"c347"=> "",// Joconde Id
	"c217"=> "",// Inventory number
	"c144"=> "",// Based on
	"c921"=> "",// Subject heading
	"c941"=> ""// Inspired by
);
$s=""; // Search

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
if (isset($_GET['nb']))
	if ($_GET['nb']!="") 
		$nb=intval($_GET['nb']);
if (isset($_GET['p']))
	if ($_GET['p']!="") 
		$p=intval($_GET['p']);
if (isset($_GET['s'])){
	$s=$_GET['s'];
	$s=trim(str_replace("\"","",urldecode($s)));
	/* Easter egg */ if (str_replace("!","",str_replace(" ","",strtolower($s)))=="houba"){ $s="";$l="mu";setcookie ("l","mu", time() + 31536000);}
}

?>