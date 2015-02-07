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
    $sql="SELECT label from label_page WHERE qwd=$qwd AND lg='$lg' AND label!='' LIMIT 0,1";
	$rep_lab=mysql_query($sql);
	$num_rows= mysql_num_rows($rep_lab);
	if ($num_rows==0){
		$sql="SELECT label from label_page WHERE qwd=$qwd AND lg='en' AND label!='' LIMIT 0,1";
		$rep_lab=mysql_query($sql);
		$num_rows = mysql_num_rows($rep_lab);
		if ($num_rows==0){
			$sql="SELECT label from label_page WHERE qwd=$qwd AND label!='' LIMIT 0,1";
			$rep_lab=mysql_query($sql);
			$num_rows = mysql_num_rows($rep_lab);
		}
	}
	if ($num_rows!=0){
		$data_lab = mysql_fetch_assoc($rep_lab);
		$label=$data_lab['label'];
	}else
		$label="";
	
	/* Easter egg */if ($lg=="mu") return "Houba"; else	
	return $label;
}
function alias_item($qwd,$lg){
    $sql="SELECT label from label_page WHERE qwd=$qwd AND lg='$lg' AND type=2 AND prop=1";
	//echo $sql;
	$rep_alias=mysql_query($sql);
	$aliases="";
	while ($data_prop = mysql_fetch_assoc($rep_alias)){
		if ($aliases!="")
			$aliases.="<br />";
		$aliases.=$data_prop['label'];
	}
	return $aliases;
}
function val_prop($id_artw,$prop){
	$vals=array();
	$sql="SELECT p".$prop.".qwd as prop_qwd from artw_prop,p".$prop." WHERE artw_prop.prop=".$prop." AND  artw_prop.id_artw=$id_artw AND  artw_prop.id_prop=p".$prop.".id";
	$rep_prop=mysql_query($sql);
	$i=0;
	while ($data_prop = mysql_fetch_assoc($rep_prop)){
		$vals[$i]=intval($data_prop['prop_qwd']);
		$i++;
	}
	return $vals;
}

function txt_prop($id_art,$id_prop,$lg,$type="normal",$entitled=true,$link=true){
	global $mode,$l,$tab_miss;
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
			if (isset($values[$i])){
				if ($i>0)
					$txt.=" - ";
				if ($link){
					if ($id_prop=="1639") //pendant of
						$txt.=" <a href=\"?q=".$values[$i];
					else
						$txt.=" <a href=\"?p$id_prop=".$values[$i];
					if ($mode==1)
						foreach($tab_miss as $key=>$value)
							if ($value!="")
								$txt.="&amp;$key=".$value;
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
		}
	}
	return $txt;
}
function loc_val($qwd,$prop){
	$sql="SELECT site from p".$prop." WHERE qwd=".$qwd;
	$rep_prop=mysql_query($sql);
	$site="";
	while ($data_prop = mysql_fetch_assoc($rep_prop))
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
	$page="";
    $sql="SELECT page from label_page WHERE qwd=$qwd AND lg='$lg' AND page!='' LIMIT 0,1";
	$rep_lab=mysql_query($sql);
	$num_rows= mysql_num_rows($rep_lab);
	if ($num_rows!=0){
		$data_page = mysql_fetch_assoc($rep_lab);
		$page=$data_page['page'];
	}else{
		$sql="SELECT page from label_page WHERE qwd=$qwd AND lg='en' AND page!='' LIMIT 0,1";
		$rep_lab=mysql_query($sql);
		$num_rows= mysql_num_rows($rep_lab);	
		if ($num_rows!=0){
			$data_page = mysql_fetch_assoc($rep_lab);
			$page="en|".$data_page['page'];
		}
		else{
			$sql="SELECT page,lg from label_page WHERE qwd=$qwd AND page!='' LIMIT 0,1";
			$rep_lab=mysql_query($sql);
			$num_rows= mysql_num_rows($rep_lab);	
			if ($num_rows!=0){
				$data_page = mysql_fetch_assoc($rep_lab);
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
	$sql="SELECT p".$id_prop.".qwd as prop_qwd from artw_prop,p".$id_prop." WHERE artw_prop.prop=".$id_prop." AND  artw_prop.id_artw=".$id_artw." AND artw_prop.id_prop=p".$id_prop.".id AND p".$id_prop.".level=0";
	$rep=mysql_query($sql);
	if (mysql_num_rows($rep)==0)
		return "";
	else{
		$row = mysql_fetch_assoc($rep);
		return "<a href=\"?p$id_prop=".$row["prop_qwd"]."\" class=\"interne\">".label_item($row["prop_qwd"],$lg)."</a>";
	}
}
?>