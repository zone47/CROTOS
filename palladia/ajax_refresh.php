<?php
$l="fr";
$codecookie="palladia-";
$locale = substr(locale_accept_from_http($_SERVER['HTTP_ACCEPT_LANGUAGE']),0,2);
$lgsc=array("ar","bn","br","ca","cs","cy","da","de","el","en","eo","es","fa","fi","fr","he","hi","id","it","ja","jv","ko","mu","nl","pa","pl","pt","ru","sw","sv","te","th","tr","uk","vi","zh");
if (in_array($locale,$lgsc)) 
	$l=$locale;
if (isset($_COOKIE[$codecookie.'l']))
	$l=$_COOKIE[$codecookie.'l'];
$mode=0;
if (isset($_COOKIE[$codecookie.'mode']))
	$mode=intval($_COOKIE[$codecookie.'mode']);
include "../traduction.php";
function translate($lg,$term){
	global $trads;
    if ($trads[$lg][$term])
		return $trads[$lg][$term];
	elseif ($trads["en"][$term])
		return $trads["en"][$term];
	else
		return "";
}
include "config.php";
$link = mysqli_connect ($host,$user,$pass,$db) or die ('Erreur : '.mysqli_error());
mysqli_query($link,"SET NAMES 'utf8'");

function label_item($qwd,$lg){
	global $link;
    $sql="SELECT label from label_page WHERE qwd=$qwd AND lg='$lg' AND label!='' LIMIT 0,1";
	$rep_lab=mysqli_query($link,$sql);
	$num_rows= mysqli_num_rows($rep_lab);
	if ($num_rows==0){
		$sql="SELECT label from label_page WHERE qwd=$qwd AND lg='en' AND label!='' LIMIT 0,1";
		$rep_lab=mysqli_query($link,$sql);
		$num_rows = mysqli_num_rows($rep_lab);
		if ($num_rows==0){
			$sql="SELECT label from label_page WHERE qwd=$qwd AND label!='' LIMIT 0,1";
			$rep_lab=mysqli_query($link,$sql);
			$num_rows = mysqli_num_rows($rep_lab);
		}
	}
	if ($num_rows!=0){
		$data_lab = mysqli_fetch_assoc($rep_lab);
		$label=$data_lab['label'];
	}else
		$label="";
	
	/* Easter egg */if ($lg=="mu") return "Houba"; else	
	return $label;
} 
$keyword = $_GET['keyword'];
$img="img";
if ($mode==1)
	$img="";
$cpt=0;
$ls ="";
$sql = "SELECT prop,type, qwd, label, id_art_or_prop FROM label_page WHERE lg=\"".$l."\" AND label LIKE \"%".$keyword ."%\" AND nb".$img."!=0 GROUP BY prop, qwd ORDER BY nb".$img." DESC LIMIT 0, 5";
$rep=mysqli_query($link,$sql);
while ($rs = mysqli_fetch_assoc($rep)){
	$cpt++;
	$rs['label'] = preg_replace("/".$keyword."/i", "<b>\$0</b>",$rs['label']);
	if ($rs['type']==2)
		$rs['label']=$rs['label'].' <span class="als">('.label_item($rs['qwd'],$l).')</span>';
		
	$txt="";
	if ($rs['prop']==1){
		$sql="SELECT commons_img.P18 as img, commons_img.width, commons_img.height from artworks, commons_img  WHERE artworks.id=".$rs['id_art_or_prop']." AND artworks.P18=commons_img.id";
		$rep2=mysqli_query($link,$sql);
		$thumb="/crotos/img/nis.png";
		while ($data2 = mysqli_fetch_assoc($rep2)){
			$img=str_replace(" ","_",$data2['img']);
			$digest = md5($img);
			$folder = $digest[0] . '/' . $digest[0] . $digest[1] . '/' . urlencode($img);
			$w_thumb=floor(intval($data2['width'])/intval($data2['height'])*30);
			if ($w_thumb>70)
				$w_thumb=70;
			$thumb="http://upload.wikimedia.org/wikipedia/commons/thumb/" . $folder."/".$w_thumb."px-". urlencode($img);
			if (substr ($img,-3)=="svg")
				$thumb.=".png";	
		}
		$sql="SELECT p170.qwd as prop_qwd, dates from artw_prop,p170 WHERE artw_prop.prop=170 AND  artw_prop.id_artw=".$rs['id_art_or_prop']." AND  artw_prop.id_prop=p170.id";
		$rep2=mysqli_query($link,$sql);
		$crea="";
		while ($data2 = mysqli_fetch_assoc($rep2)){
			$tl=label_item(intval($data2['prop_qwd']),$l);
			if ($tl!=""){
				$crea.=", ".$tl;
				if ($data2['dates']!="")
					$crea.=" ".$data2['dates'];
			}
		}
		if ($crea!="")
			$crea='<span class="lbs">'.$crea.'</span>';
		$txt.='q='.$rs['qwd'].'¤<span class="ims"><span class="is"><img src="'.$thumb.'"/></span><span class="lbs">'.$rs['label'].'</span><span class="lbi">'.$crea.'</span></span>';
	}
	elseif ($rs['prop']==170){
		$sql="SELECT dates from p170 WHERE id=".$rs['id_art_or_prop'];
		$rep2=mysqli_query($link,$sql);
		while ($data2 = mysqli_fetch_assoc($rep2))
			if ($data2['dates']!="")
				$rs['label'].=" ".$data2['dates'];
		$txt.='p'.$rs['prop'].'='.$rs['qwd'].'¤<span class="lis">'.translate($l,$rs['prop']).'</span> '.$rs['label'];
	}
	else
		$txt.='p'.$rs['prop'].'='.$rs['qwd'].'¤<span class="lis">'.translate($l,$rs['prop']).'</span> '.$rs['label'];

	if ($cpt!=1)
		$ls.="|";
	$ls.=$txt;
}
echo $ls;
?>