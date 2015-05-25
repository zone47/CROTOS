<?php
/* / */
$txtnav="";
$recordcount=$num_rows;
$limit=$nb;
$lien_param=$liennav;
if ($recordcount>$limit){
	$txtnav="";
	if ($p!=1){
		$txtnav.="<a href=\"";
		$txtnav.=$script_name."?p=".($p-1);
		if ($lien_param!="") $txtnav.=$lien_param;
		$txtnav.="\" class=\"precsuiv\">".translate($l,"previous")."</a>&nbsp;&nbsp;&nbsp;&nbsp;";
	}
	$nbpages=$nbpg;
	$page_en_cours=$p;
	if ($nbpages<5){
		$prem=1;
		$der=$nbpages;
	}
	elseif($deb==1){
		$prem=1;
		$der=5;
	}
	else{
		$prem=$page_en_cours-2;
		if ($prem<1)
			$prem=1;
		$der=$prem+4;
		if ($der>$nbpages){
			$der=$nbpages;
			$prem=$der-4;
		}
	}
	if ($prem!=1){
		$txtnav.="<a href=\"";
		$txtnav.=$script_name."?p=1";
		if ($lien_param!="") $txtnav.=$lien_param;
		$txtnav.="\">1</a> ...&nbsp;&nbsp;\n";
	}
	elseif ($p!=$prem){
		$txtnav.="<span class=\"nav_small\"><a href=\"";
		$txtnav.=$script_name."?p=1";
		if ($lien_param!="") $txtnav.=$lien_param;
		$txtnav.="\">1</a> ";
		if ($p!=2)
			$txtnav.="...&nbsp;";
		$txtnav.="&nbsp;</span>\n";
	}
	$suiv=true;
	for ($j=$prem;$j<=$der;$j++){
			$the_page=(($j-1)*$limit)+1;
			if ($j!=$p){
				$txtnav.="<a href=\"";
				$txtnav.=$script_name."?p=$j";
				if ($lien_param!="") $txtnav.=$lien_param;
				$txtnav.="\" class=\"nav_sec\">$j</a>\n";
			}
			else{
				$txtnav.="<span class=\"page_ec\">$j</span>\n";
				if ($j==ceil($der))
					$suiv=false;
			}
			if ($j!=ceil($der))
				$txtnav.="<span class=\"nav_sec\">&nbsp;&nbsp;&nbsp;</span>";
	}
	if (($j-1)!=$nbpages){
		$the_page=(($nbpages-1)*$limit)+1;
		$txtnav.="<span class=\"nav_sec\"> ...&nbsp;&nbsp;<a href=\"";
		$txtnav.=$script_name."?p=".$nbpages;
		if ($lien_param!="") $txtnav.=$lien_param;
		$txtnav.="\" >$nbpages</a></span>\n";
	}
	if ($p<($nbpages)){
		$txtnav.="<span class=\"nav_small\">";
		if ($p!=($nbpages-1))
			$txtnav.=" ...&nbsp;&nbsp;";
		$txtnav.="<a href=\"";	
		$txtnav.=$script_name."?p=".$nbpages;
		if ($lien_param!="") $txtnav.=$lien_param;
		$txtnav.="\">$nbpages</a></span>\n";		
	}
	if ($p!=$nbpg){
		$txtnav.="&nbsp;&nbsp;&nbsp;<a href=\"";
		$txtnav.=$script_name."?p=".($p+1);
		if ($lien_param!="") $txtnav.=$lien_param;
		$txtnav.="\" class=\"precsuiv\">".translate($l,"next")."</a>\n";
	}
}
else
	$txtnav="<span class=\"page\">".translate($l,"page")."&nbsp;:&nbsp;&nbsp;</span><span class=\"page_ec\">1</span>";

$txtnav=str_replace("\'","'",$txtnav);
if ($random){
	$txtnav= "<a href=\"".$script_name."?".$liennav."\" class=\"random\">".mb_ucfirst(translate($l,"random"))."</a> \n";
	$txtnav.= "<a href=\"".$script_name."?".$liennav."&amp;p=1\" class=\"nav_sec\">".translate($l,"chronology")."</a>\n";
}
?>