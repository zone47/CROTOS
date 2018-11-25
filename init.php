<?php 
/* / */
include "lg.php";
$script_name="index.php";
set_time_limit(120);
$mode=0;
if (isset($_COOKIE['mode']))
	$mode=intval($_COOKIE['mode']);
if (isset($_GET['mode']))
	if ($_GET['mode']!=""){ 
		setcookie ("mode",$_GET['mode'], time() - 3600);
		setcookie ("mode",$_GET['mode'], time() + 31536000, "/");
		$mode=$_GET['mode'];
	}
$disp=1;
if (isset($_COOKIE['disp']))
	$disp=intval($_COOKIE['disp']);
if (isset($_GET['disp']))
	if ($_GET['disp']!=""){
		setcookie ("disp",$_GET['disp'], time() - 3600); 
		setcookie ("disp",$_GET['disp'], time() + 31536000, "/");
		$disp=$_GET['disp'];
	}

$nb=20; 
if (isset($_COOKIE['nb']))
	$nb=$_COOKIE['nb'];
if (isset($_GET['nb']))
	if ($_GET['nb']!=""){ 
		if (intval($_GET['nb']<201)){
			setcookie ("nb",$_GET['nb'], time() - 3600);
			setcookie ("nb",$_GET['nb'], time() + 31536000, "/");
		}
		else {
			setcookie ("nb",$_GET['nb'], time() - 3600);
			setcookie ("nb",200, time() + 31536000, "/");
		}
		$nb=$_GET['nb'];
	}
$nb=intval($nb);
$nocartel=0; 
if (isset($_COOKIE['nc']))
	$nocartel=intval($_COOKIE['nc']);
$nocartel++;
if ($nocartel<3)
	setcookie ("nc",$nocartel, time() + 31536000, "/");
if ($nocartel>2)
	$nocartel=1;
else
	$nocartel=0;

$p=0; // numéro de page par défaut
$lgs=array("ar","bn","br","ca","cs","cy","da","de","el","en","eo","es","fa","fi","fr","he","hi","id","it","ja","jv","ko","mu","nb","nl","pa","pl","pt","ru","sw","sv","te","th","tr","uk","vi","zh");
$random=false;
$rand_sel=false;
if (isset($_GET['r']))
	if ($_GET['r']=="1") 
		$rand_sel=true;
if (isset($_GET['p'])){
	if ($_GET['p']!="") 
		$p=intval($_GET['p']);
	else
		$rand_sel=true;
}
else
	$rand_sel=true;
$s=""; // Search
$q="";
$y1=-40000;
$y2=2016;
$d=0;// publication date
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
	"p608"=> "",// qwd exhibtion
	"p921"=> "",// qwd subject heading
	"p941"=> "",// qwd inspired by
	"p1639"=> ""// qwd pendant of
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
	"m1212"=> "",// Atlas ID
	"mw"=> ""// Wikipedia page
);
$tab_check = array(
	"c1" => "",// label
	"c18"=> "",// image
	"c2" => "",// HD
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
	"c1212"=> "",// Atlas ID
	"cw"=> ""// Wikipedia page
);

foreach($tab_idx as $key=>$value)
	if (isset($_GET[$key]))
		$tab_idx[$key]=str_ireplace("q","",$_GET[$key]);	

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
		$q=str_ireplace("q","",$_GET['q']);

if ($cosmos){
	if ((!(isset($_GET['p'])))||($_GET['p']=="")){
		$_GET['p']="1";
		$p=1;
	}
	$rand_sel=false;
	if (isset($_GET['r']))
		if ($_GET['r']=="1"){ 
			$rand_sel=true;
			$_GET['p']="";
			$p=0;
		}
}
if (isset($_GET['s'])){
	$s=$_GET['s'];
	$s= preg_replace('/\p{C}+/u', "", $s);
	$s=trim(str_replace("\"","",urldecode($s)));
	/* Easter egg */ if (str_replace("!","",str_replace(" ","",strtolower($s)))=="houba"){ $s="";$l="mu";setcookie ("l","mu", time() + 31536000,"/");}
}
if ((isset($_GET['s']))&&($cosmos)){
	$s=$_GET['s'];
	if ($s!=""){ 
		$s= preg_replace('/\p{C}+/u', "", $s);
		$s=trim(str_replace("\"","",urldecode($s)));
		if ($cosmos)
			header('Location:../index.php?s='.$s);
		/* Easter egg */ if (str_replace("!","",str_replace(" ","",strtolower($s)))=="houba"){ $s="";$l="mu";setcookie ("l","mu", time() + 31536000,"/");}
	}
}
if (isset($_GET['y1']))
	if (is_int(intval($_GET['y1']))) 
		$y1=intval($_GET['y1']);
if (isset($_GET['y2']))
	if (is_int(intval($_GET['y2']))) 
		$y2=intval($_GET['y2']);
if (isset($_GET['d'])){
	if ($_GET['d']!=""){
		$d=$_GET['d'];
		if ((!($_GET['r']=="1"))&&($p==0)){
			$_GET['p']="1";
			$rand_sel=false;
			$p=1;
		}
	}
}
//$n minimum number of results
$n=3; 
if (isset($_COOKIE['n']))
	$n=intval($_COOKIE['n']);
if (isset($_GET['n']))
	if ($_GET['n']!=""){ 
		setcookie ("n",intval($_GET['n']), time() -3600);
		setcookie ("n",intval($_GET['n']), time() + 31536000, "/");
		$n=intval($_GET['n']);
	}
?>