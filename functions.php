<?php
/* / */
function mb_ucfirst($string){
    $strlen = mb_strlen($string,"utf8");
    $firstChar = mb_substr($string, 0, 1,"utf8");
    $then = mb_substr($string, 1, $strlen - 1,"utf8");
    return mb_strtoupper($firstChar,"utf8") . $then;
}
function left($str, $length) {
	return substr($str, 0, $length);
}
function right($str, $length) {
	return substr($str, -$length);
}
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
function alias_item($qwd,$lg){
	global $link;
    $sql="SELECT label from label_page WHERE qwd=$qwd AND lg='$lg' AND type=2 AND prop=1";
	//echo $sql;
	$rep_alias=mysqli_query($link,$sql);
	$aliases="";
	while ($data_prop = mysqli_fetch_assoc($rep_alias)){
		if ($aliases!="")
			$aliases.=" ; ";
		$aliases.=$data_prop['label'];
	}
	return $aliases;
}
function val_prop($id_artw,$prop){
	global $link;
	$vals=array();
	$sql="SELECT p".$prop.".qwd as prop_qwd from artw_prop,p".$prop." WHERE artw_prop.prop=".$prop." AND  artw_prop.id_artw=$id_artw AND  artw_prop.id_prop=p".$prop.".id";
	$rep_prop=mysqli_query($link,$sql);
	$i=0;
	while ($data_prop = mysqli_fetch_assoc($rep_prop)){
		$vals[$i]=intval($data_prop['prop_qwd']);
		$i++;
	}
	return $vals;
}

function txt_prop($id_art,$id_prop,$lg,$type="normal",$entitled=true,$link=true){
	global $mode,$l,$d;//,$tab_miss;
	$txt="";
	if ($id_art!=0){
		$values=val_prop($id_art,$id_prop);
		$values=array_unique($values);	
	}
	else
		$values=array($id_prop);
	if (count($values)>0){
		if ($entitled)
			$txt.="<span class=\"libelle\">".translate($lg,$id_prop)."</span>&nbsp;:";
		for ($i=0;$i<count($values);$i++){
			if (isset($values[$i])&&($values[$i]!=0)){
				if ($i>0)
					$txt.=" - ";
				if ($type!="listlink"){
					if ($link){
						if ($id_prop=="1639") //pendant of
							$txt.=" <a href=\"?q=".$values[$i];
						else
							$txt.=" <a href=\"?p$id_prop=".$values[$i];
						// For publication date, date added
						if ($d!=0)
							$txt.="&amp;d=".$d;

						/*For adding filters to links
						if ($mode==1)
							foreach($tab_miss as $key=>$value)
								if ($value!="")
									$txt.="&amp;$key=".$value;*/
							
						$txt.="\" ";
						if ($type=="creator")
							$txt.=" class=\"lien_aut\" ";
						if ($type=="internal")
							$txt.=" class=\"interne\" ";
						$txt.=">";
					}
					$txt.=label_item($values[$i],$lg);
					if ($link)
						$txt.="</a>";
				}
				else
					$txt.="<a href=\"?q=".$values[$i]."&p".$id_prop."\">".label_item($values[$i],$lg)."</a>";
			}
		}
	}
	return $txt;
}
function loc_val($qwd,$prop){
	global $link;
	$sql="SELECT site from p".$prop." WHERE qwd=".$qwd;
	$rep_prop=mysqli_query($link,$sql);
	$site="";
	while ($data_prop = mysqli_fetch_assoc($rep_prop))
		$site=$data_prop['site'];
	return $site;
}
function local_link($id_art,$id_prop,$lg){
	global $l;
	$txt="";
	$values=val_prop($id_art,$id_prop);
	$values=array_unique($values);	
	if (count($values)>0){
		for ($i=0;$i<count($values);$i++){
			if (isset($values[$i])){
				$site=loc_val($values[$i],$id_prop);
				if (right($site,1)=="/")
					$site=left($site,strlen($site)-1);
				if (strpos($site,"louvre"))
					$site=str_replace("www.","",$site);
				if ($site!=""){
					if ($txt!="")
						$txt.="</br>";
					$txt.="<b>".label_item($values[$i],$lg)."</b>&nbsp;: ";
					/* Easter egg */if ($lg=="mu") $txt.="<a href=\"".$site."\" class=\"externe\">Houba</a>"; else	
					$txt.="<a href=\"".$site."\" class=\"externe\">".str_replace("http://","",$site)."</a>";
				}
			}
		}
	}
	return $txt;
}

function page_item($qwd,$lg){
	global $link;
	$page="";
    $sql="SELECT page from label_page WHERE qwd=$qwd AND lg='$lg' AND page!='' LIMIT 0,1";
	$rep_lab=mysqli_query($link,$sql);
	$num_rows= mysqli_num_rows($rep_lab);
	if ($num_rows!=0){
		$data_page = mysqli_fetch_assoc($rep_lab);
		$page=$data_page['page'];
	}else{
		$sql="SELECT page from label_page WHERE qwd=$qwd AND lg='en' AND page!='' LIMIT 0,1";
		$rep_lab=mysqli_query($link,$sql);
		$num_rows= mysqli_num_rows($rep_lab);	
		if ($num_rows!=0){
			$data_page = mysqli_fetch_assoc($rep_lab);
			$page="en|".$data_page['page'];
		}
		else{
			$sql="SELECT page,lg from label_page WHERE qwd=$qwd AND page!='' LIMIT 0,1";
			$rep_lab=mysqli_query($link,$sql);
			$num_rows= mysqli_num_rows($rep_lab);	
			if ($num_rows!=0){
				$data_page = mysqli_fetch_assoc($rep_lab);
				$page=$data_page['lg']."|".$data_page['page'];
			}
		}
	}
	return $page;
}

function translate($lg,$term){
	global $trads;
    if ($trads[$lg][$term])
		return $trads[$lg][$term];
	else
		return $trads["en"][$term];
}
function esc_dblq($text){
	return str_replace("\"","\\\"",$text);
}
function truncate($text, $chars =  56) {
	if (strlen($text)>56)
		$trunk=true;
	else
		$trunk=false;
    $text = $text." ";
    $text = substr($text,0,$chars);
    $text = substr($text,0,strrpos($text,' '));
    if ($trunk)
		$text = $text."...";
    return $text;
}
function val_0($id_artw,$id_prop,$lg) {
	global $link,$d;
	$sql="SELECT p".$id_prop.".qwd as prop_qwd from artw_prop,p".$id_prop." WHERE artw_prop.prop=".$id_prop." AND  artw_prop.id_artw=".$id_artw." AND artw_prop.id_prop=p".$id_prop.".id AND p".$id_prop.".level=0";
	$rep=mysqli_query($link,$sql);
	if (mysqli_num_rows($rep)==0)
		return "";
	else{
		$row = mysqli_fetch_assoc($rep);
		if ($d!=0)
			return "<a href=\"?p$id_prop=".$row["prop_qwd"]."&amp;d=$d\" class=\"interne\">".label_item($row["prop_qwd"],$lg)."</a>";
		else
			return "<a href=\"?p$id_prop=".$row["prop_qwd"]."\" class=\"interne\">".label_item($row["prop_qwd"],$lg)."</a>";
	}
}
function test_dp($id_art){
	global $link,$d;
	$values=val_prop($id_art,31);
	$values=array_unique($values);	
	
	if (count($values)==1){
		if ($values[0]==3305213){
			$values2=val_prop($id_art,170);
			$values2=array_unique($values2);
			if (count($values2)==1){
				$sql="SELECT dates from p170 WHERE qwd=".$values2[0];
				$rep=mysqli_query($link,$sql);
				$row = mysqli_fetch_assoc($rep);
				$dates=$row["dates"];
				if ($dates!=""){
					if (mb_substr($dates,6,1, 'UTF-8')=="–"){
						if ((intval(mb_substr($dates,7,4,'UTF-8'))>1944)||((intval(mb_substr($dates,2,4,'UTF-8'))>1900)&&(intval(mb_substr($dates,7,1,'UTF-8')==")"))))
							return false;
						else
							return true;
					}
					else
						return true;
				}
				else
					return true;
			}
			else
				return true;
		}
		else
			return true;
	}
	else
		return true;
}
function del_html($str){
	return preg_replace("/<ul[^>]+\>/i", "",preg_replace("/<li[^>]+\>/i", "",preg_replace("/<p[^>]+\>/i", "",preg_replace("/<dd[^>]+\>/i", "",preg_replace("/<dl[^>]+\>/i", "",preg_replace("/<img[^>]+\>/i", "",preg_replace("/<\/?td[^>]*\>/i", "",preg_replace("/<\/?tr[^>]*\>/i", "",preg_replace("/<\/?table[^>]*\>/i", "",preg_replace("/<\/?li[^>]*\>/i", "", preg_replace("/<\/?ul[^>]*\>/i", "",  preg_replace("/<\/?br[^>]*\>/i", " ",preg_replace("/<\/?hr[^>]*\>/i", " ", preg_replace("/<\/?p[^>]*\>/i", "", preg_replace("/<\/?div[^>]*\>/i", "", $str)))))))))))))));
}
?>