<?php
$l="fr";
$locale = substr(locale_accept_from_http($_SERVER['HTTP_ACCEPT_LANGUAGE']),0,2);
$lgsc=array("ar","bn","br","ca","cs","cy","da","de","el","en","eo","es","eu","fa","fi","fr","he","hi","id","it","ja","jv","ko","mu","nl","pa","pl","pt","ru","sw","sv","te","th","tr","uk","vi","zh");
if (in_array($locale,$lgsc)) 
	$l=$locale;
if (isset($_COOKIE['l']))
	$l=$_COOKIE['l'];
if (isset($_GET['l']))
	if ($_GET['l']!=""){ 
		setcookie ("l",$_GET['l'],time() + 31536000,'/');
		$l=$_GET['l'];
	}
?>