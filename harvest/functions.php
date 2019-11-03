<?php
/* / */
set_time_limit(480000);
if ( !function_exists('json_decode') ){
	require_once ('JSON.php');
	function json_decode($content, $assoc=false){
		if ( $assoc ){
			$json = new Services_JSON(SERVICES_JSON_LOOSE_TYPE);
		} else {
			$json = new Services_JSON;
		}
		return $json->decode($content);
	}
}
function get_WDjson($qitem){
	global $fold_crotos;
	$qitem_path=$fold_crotos."harvest/items_props/".$qitem.".json";
	if (file_exists($qitem_path))
		return file_get_contents($qitem_path,true);
	else{
		copy("https://www.wikidata.org/w/api.php?action=wbgetentities&ids=q".$qitem."&format=json", $qitem_path);
		return file_get_contents($qitem_path,true);
	}
}
function get_query($prop,$qwd){
	global $fold_crotos;
	if (($prop==31)||($prop==136)||($prop==186))
		$req="SELECT distinct ?item WHERE { ?item wdt:P279* wd:Q".$qwd." }";
	elseif (($prop==195))
		$req="SELECT distinct ?item WHERE { ?item wdt:P361* wd:Q".$qwd." }";
	elseif ($prop==276)
		$req="SELECT distinct ?item WHERE {{ ?item wdt:P361* wd:Q".$qwd." }UNION{ ?item wdt:P276* wd:Q".$qwd."}}";
	elseif (($prop==135)||($prop==144)||($prop==180)||($prop==921)||($prop==941))
		$req="SELECT distinct ?item WHERE {{ ?item wdt:P279* wd:Q".$qwd." }UNION{ ?item wdt:P361* wd:Q".$qwd."}}";
	else 
		$req="";
	if ($req!=""){
		$query_path=$fold_crotos."harvest/queries/".$prop."_".$qwd.".json";
		if (file_exists($query_path))
			return json_decode(file_get_contents($query_path,true));
		else{
			$sparqlurl=urlencode($req);
			$req="https://query.wikidata.org/sparql?format=json&query=".$sparqlurl;
			$opts = [
				'http' => [
					'method' => 'GET',
					'user_agent' => 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13',
					'header' => ['Accept: application/sparql-results+json'],
				],
			];
			$context = stream_context_create($opts);
			$res  = file_get_contents($req,true,$context);
			$responseArray = json_decode($res,true);
			$items=array();
			foreach ($responseArray["results"]["bindings"] as $key => $value)
				$items[]=str_replace("http://www.wikidata.org/entity/Q","",$value["item"]["value"]);;
			$js_res=json_encode($items);
			$resfile = fopen($query_path, 'w');
			fputs($resfile, $js_res); 
			fclose($resfile);
			return $items;
		}
	}
	else 
		return "";
}

function insert_label_page($prop,$val_item,$id_art_or_prop){
	global $tab_lg,$tab_miss,$link; 
	echo "$prop , $val_item , $id_art_or_prop\n";
	if ($prop==1)
		$dfic=file_get_contents("items/$val_item.json",true);
	else
		$dfic=get_WDjson($val_item);
		
	$data_item=json_decode($dfic,true);
	$ent_qwd=$data_item["entities"]["Q".$val_item];
	
	for($k=0;$k<count($tab_lg);$k++){
		$lg=$tab_lg[$k];
		// label
		$lab="";
		if ($ent_qwd["labels"][$lg]["value"])
			$lab=$ent_qwd["labels"][$lg]["value"];
		//page
		$page="";
		if ($ent_qwd["sitelinks"][$lg."wiki"]["title"])
			$page=$ent_qwd["sitelinks"][$lg."wiki"]["title"];
		
		if (($lab!="")||($page!="")){
			$lab=esc_dblq($lab);
			$page=esc_dblq($page);
			$sql="INSERT INTO label_page (type,prop,qwd,label,page,lg,id_art_or_prop) VALUES (1,$prop,$val_item,\"$lab\",\"$page\",\"$lg\",$id_art_or_prop)";
			$rep=mysqli_query($link,$sql);
		}
		if (($lab=="")&&($prop==1))
			$tab_miss[$lg]=1;
			
		if (($page!="")&&($prop==1))
			$tab_miss["mw"]=0;
			
		// alias
		if ($ent_qwd["aliases"][$lg]){
			foreach ($ent_qwd["aliases"][$lg] as $vallab){
				$alias=esc_dblq($vallab["value"]);
				$sql="INSERT INTO label_page (type,prop,qwd,label,lg,id_art_or_prop) VALUES (2,$prop,$val_item,\"".$alias."\",\"$lg\",$id_art_or_prop)";
				$rep=mysqli_query($link,$sql);
			}
		}
	}
	// if 276 ou 195 update site
	if (($prop==276)||($prop==195)){
		if ($ent_qwd["claims"]["P856"]){
			$site="";
			$tab856=$ent_qwd["claims"]["P856"];
			foreach ($tab856 as $value){
				if ($site==""){
					$site=$value["mainsnak"]["datavalue"]["value"];
					$sql="UPDATE p".$prop." SET site=\"".$site."\" WHERE qwd=$val_item";
					$rep=mysqli_query($link,$sql);
				}
			}
		}
		if ($ent_qwd["claims"]["P373"]){
			$cat="";
			$tab373=$ent_qwd["claims"]["P373"];
			foreach ($tab373 as $value){
				if ($site==""){
					$site=$value["mainsnak"]["datavalue"]["value"];
					$sql="UPDATE p".$prop." SET commonscategory=\"".$site."\" WHERE qwd=$val_item";
					$rep=mysqli_query($link,$sql);
				}
			}
		}
		if ($ent_qwd["claims"]["P625"]){
			$coord=$ent_qwd["claims"]["P625"];
			foreach ($coord as $value){
				$latitem=rtrim(number_format($value["mainsnak"]["datavalue"]["value"]["latitude"],10,'.',''),"0");
				if ($latitem{strlen($latitem)-1}==".") $latitem.="0";
				$lonitem=rtrim(number_format($value["mainsnak"]["datavalue"]["value"]["longitude"],10,'.',''),"0");
				if ($lonitem{strlen($lonitem)-1}==".") $lonitem.="0";
				$sql="UPDATE p".$prop." SET lat=\"".$latitem."\", lon=\"".$lonitem."\" WHERE qwd=$val_item";
				$rep=mysqli_query($link,$sql);
			}
		}
		
	}
	if ($prop==180){
	   if ($ent_qwd["claims"]["P625"]){
			$coord=$ent_qwd["claims"]["P625"];
			foreach ($coord as $value){
				$latitem=rtrim(number_format($value["mainsnak"]["datavalue"]["value"]["latitude"],10,'.',''),"0");
				if ($latitem{strlen($latitem)-1}==".") $latitem.="0";
				$lonitem=rtrim(number_format($value["mainsnak"]["datavalue"]["value"]["longitude"],10,'.',''),"0");
				if ($lonitem{strlen($lonitem)-1}==".") $lonitem.="0";
				$sql="UPDATE p".$prop." SET lat=\"".$latitem."\", lon=\"".$lonitem."\" WHERE qwd=$val_item";
				$rep=mysqli_query($link,$sql);
			}
		} 
	}
	// if 170 life dates
	if ($prop==170){
		$dates="";
		$year1="";
		$year2="";
		$tab_date=array("P569","P570");
		for ($j=0;$j<count($tab_date);$j++){
			$b_date=0;
			$Pdate=$ent_qwd["claims"][$tab_date[$j]];
			if ($Pdate){
				foreach ($Pdate as $value){
					$time=$value["mainsnak"]["datavalue"]["value"]["time"];
					$precision=$value["mainsnak"]["datavalue"]["value"]["precision"];
					if ($time){
						$year=intval(substr($time,1,strpos($time,"-")-1));
						if (intval($precision)<9)
							$year="~".$year;
					}
					else
						$year="";
					if ($tab_date[$j]=="P569")
						$year1=$year;
					else 
						$year2=$year;		
				}
			}
		}
		if (($year1!="")||($year2!="")){
			$dates=" (";
			if 	($year1!="")
				$dates.=$year1;
			else
				$dates.="?";
			$dates.="–";
			if 	($year2!="")
				$dates.=$year2;
			$dates.=")";
		}
		if ($dates!=""){
			$sql="UPDATE p170 SET dates=\"".$dates."\" WHERE qwd=$val_item";
			$rep=mysqli_query($link,$sql);
		}
	}
}
function parent_cherche($prop,$val_prop,$id_artw,$new_ids){
	global $link;
	 if ($prop=="276") echo "\n parent_cherche($prop,$val_prop,$id_artw,$new_ids)"; //test
	if ($new_ids==""){ // already exists
		$sql="SELECT id, id_parent FROM p$prop WHERE qwd=$val_prop";
		$rep=mysqli_query($link,$sql);
		$row = mysqli_fetch_assoc($rep);
		$id_parent=$row['id_parent'];
		$sql="INSERT INTO artw_prop (prop,id_artw,id_prop) VALUES ($prop,$id_artw,$id_parent)";
		//test if ($prop=="276") echo "\nINSERT INTO artw_prop (prop,id_artw,id_prop) VALUES ($prop,$id_artw,$id_parent)";
		$rep=mysqli_query($link,$sql);
		
		$sql="SELECT qwd,level FROM p$prop WHERE id=$id_parent";
		$rep=mysqli_query($link,$sql);
		$row = mysqli_fetch_assoc($rep);
		$qwd_parent=$row['qwd'];
		$level=$row['level'];
		//test if ($prop=="276") echo "\nlevel $level id_parent $id_parent($prop,$qwd_parent,$id_artw,)";
		if ($level!=0){
			parent_cherche($prop,$qwd_parent,$id_artw,"");	
		}
	}
	else{ // new
		$dfic=get_WDjson($val_prop);
		$data_item=json_decode($dfic,true);
		$claims_qwd=$data_item["entities"]["Q".$val_prop]["claims"];
		$nouv_qwd="";		
		if ((($claims_qwd["P361"])&&(!($claims_qwd["P856"])))||(($claims_qwd["P276"])&&(!($claims_qwd["P856"])))){
			if ($claims_qwd["P361"]){
				foreach ($claims_qwd["P361"] as $val){
					if ($nouv_qwd=="")
					   $nouv_qwd=$val["mainsnak"]["datavalue"]["value"]["numeric-id"];
					else
						break;
				}
			}
			if ($claims_qwd["P276"]){
				foreach ($claims_qwd["P276"] as $val){
					if ($nouv_qwd=="")
					   $nouv_qwd=$val["mainsnak"]["datavalue"]["value"]["numeric-id"];
					else
						break;
				}
			}
			if ($nouv_qwd!=$val_prop){// security against infinite loop
				// parent found

				$sql="SELECT id,level FROM p$prop WHERE qwd=$nouv_qwd";
				$rep=mysqli_query($link,$sql);
				$nids="";
				$level_found=-1;
				if (mysqli_num_rows($rep)==0){
					
					/*//Value of property inserted
					$p18_str=img_qwd($nouv_qwd);
					if ($p18_str!="")
						$p18=id_commons($p18_str);
					else*/
						$p18=0;
						
					$sql="INSERT INTO p$prop (qwd,P18) VALUES ($nouv_qwd,".$p18.")";
					$rep=mysqli_query($link,$sql);
					
					$sql="SELECT id FROM p$prop WHERE qwd=$nouv_qwd";
					$rep=mysqli_query($link,$sql);
					
					$row = mysqli_fetch_assoc($rep);
					$id_prop=$row['id'];
					$nids=$new_ids."|".$id_prop;
					//Labels of property inserted
					insert_label_page($prop,$nouv_qwd,$id_prop);
					
				}
				else{			
					$row = mysqli_fetch_assoc($rep);
					$id_prop=$row['id'];	
					$level_found=$row['level'];
				}
				
				// Update levels of ids already found and update parent of lest id
				$tab_ids=explode("|",$new_ids);
				if ($nids!=""){ // new prop value
					for ($i=0;$i<count($tab_ids);$i++){
						$sql="SELECT level FROM p$prop WHERE id=".$tab_ids[$i];
						$rep=mysqli_query($link,$sql);
						$row = mysqli_fetch_assoc($rep);
						
						$new_level=$row['level']+1;
						
						$sql="UPDATE p$prop SET level=$new_level WHERE id=".$tab_ids[$i];
						$rep=mysqli_query($link,$sql);
						if ($i==(count($tab_ids)-1)){
							$sql="UPDATE p$prop SET id_parent=$id_prop WHERE id=".$tab_ids[$i];
							$rep=mysqli_query($link,$sql);
						}
					}
					
				}
				else{
					$cpt=1;
					for ($i=(count($tab_ids)-1);$i>-1;$i--){
						$sql="UPDATE p$prop SET level=".($level_found+$cpt)." WHERE id=".$tab_ids[$i];
						$rep=mysqli_query($link,$sql);	
						if ($i==(count($tab_ids)-1)){
							$sql="UPDATE p$prop SET id_parent=$id_prop WHERE id=".$tab_ids[$i];
							$rep=mysqli_query($link,$sql);
						}						
						$cpt++;
					}
							
				}
				
				$sql="INSERT INTO artw_prop (prop,id_artw,id_prop) VALUES ($prop,$id_artw,$id_prop)";
				//test if ($prop=="276") echo "\nINSERT INTO artw_prop (prop,id_artw,id_prop) VALUES ($prop,$id_artw,$id_prop)";
				$rep=mysqli_query($link,$sql);
				
				if ($level_found!=0)
					parent_cherche($prop,$nouv_qwd,$id_artw,$nids);
			}
			
		}
	}
	
}
function request($url){
   // is curl installed?
   if (!function_exists('curl_init'))
	  die('CURL is not installed!');
   // get curl handle
   $ch= curl_init();
   // set request url
   curl_setopt($ch,
	  CURLOPT_URL,
	  $url);
   // return response, don't print/echo
   curl_setopt($ch,
	  CURLOPT_RETURNTRANSFER,
	  true);
   $response = curl_exec($ch);
   curl_close($ch);
   return $response;
}
function getjpegsize($img_loc) {
	$handle = fopen($img_loc, "rb");// or die("Invalid file stream.");
	$new_block = NULL;
	if(!feof($handle)) {
		$new_block = fread($handle, 32);
		$i = 0;
		if($new_block[$i]=="\xFF" && $new_block[$i+1]=="\xD8" && $new_block[$i+2]=="\xFF" && $new_block[$i+3]=="\xE0") {
			$i += 4;
			if($new_block[$i+2]=="\x4A" && $new_block[$i+3]=="\x46" && $new_block[$i+4]=="\x49" && $new_block[$i+5]=="\x46" && $new_block[$i+6]=="\x00") {
				// Read block size and skip ahead to begin cycling through blocks in search of SOF marker
				$block_size = unpack("H*", $new_block[$i] . $new_block[$i+1]);
				$block_size = hexdec($block_size[1]);
				while(!feof($handle)) {
					$i += $block_size;
					$new_block .= fread($handle, $block_size);
					if($new_block[$i]=="\xFF") {
						// New block detected, check for SOF marker
						$sof_marker = array("\xC0", "\xC1", "\xC2", "\xC3", "\xC5", "\xC6", "\xC7", "\xC8", "\xC9", "\xCA", "\xCB", "\xCD", "\xCE", "\xCF");
						if(in_array($new_block[$i+1], $sof_marker)) {
							// SOF marker detected. Width and height information is contained in bytes 4-7 after this byte.
							$size_data = $new_block[$i+2] . $new_block[$i+3] . $new_block[$i+4] . $new_block[$i+5] . $new_block[$i+6] . $new_block[$i+7] . $new_block[$i+8];
							$unpacked = unpack("H*", $size_data);
							$unpacked = $unpacked[1];
							$height = hexdec($unpacked[6] . $unpacked[7] . $unpacked[8] . $unpacked[9]);
							$width = hexdec($unpacked[10] . $unpacked[11] . $unpacked[12] . $unpacked[13]);
							return array($width, $height);
						} else {
							// Skip block marker and read block size
							$i += 2;
							$block_size = unpack("H*", $new_block[$i] . $new_block[$i+1]);
							$block_size = hexdec($block_size[1]);
						}
					} else {
						return FALSE;
					}
				}
			}
		}
	}
	return FALSE;
}
function esc_dblq($text){
	return str_replace("\"","\\\"",$text);
}

function simplexml_load_file_from_url($url, $timeout = 30){
  $context = stream_context_create(array('http'=>array('user_agent' => 'PHP script','timeout' => (int)$timeout)));
  $data = file_get_contents($url, false, $context);
  if(!$data){
	trigger_error('Cannot load data from url: ' . $url, E_USER_NOTICE);
	return false;
  }
  return simplexml_load_string($data);
}
function img_qwd($qwd){
	$p18="";
	$dfic=get_WDjson($qwd);
	$data_item=json_decode($dfic,true);
	$claims_qwd=$data_item["entities"]["Q".$qwd]["claims"];
	if ($claims_qwd["P18"])
		foreach ($claims_qwd["P18"] as $val){
		   $p18=$val["mainsnak"]["datavalue"]["value"];
		   break;
		}
	return $p18;
}

function id_commons($p18_str){
	global $link;
	$p18=0;
	$commons_artist="";
	$commons_credit="";
	$commons_license="";
	$thumb="";
	$thumb_h="";
	$large="";
	$w_thumb_h=0;
	$sql="SELECT id FROM commons_img WHERE P18 = _utf8 \"".esc_dblq($p18_str)."\" collate utf8_bin";
	$rep=mysqli_query($link,$sql);
	if (mysqli_num_rows($rep)==0){
		$img=str_replace(" ","_",$p18_str);
		$longfilename=false;
		if (strlen($img)>160)
			$longfilename=true;
		$urlapi="https://commons.wikimedia.org/w/api.php?action=query&prop=imageinfo&format=xml&iiprop=extmetadata&iilimit=10&titles=File:".urlencode($img);
		$xml = simplexml_load_file_from_url($urlapi);
		if ($xml){
			$commons_artist=$xml->xpath('//Artist/@value')[0];
			$commons_credit=$xml->xpath('//Credit/@value')[0];
			$commons_license=$xml->xpath('//License/@value')[0];
		
			$digest = md5($img);
			$folder = $digest[0] . '/' . $digest[0] . $digest[1] . '/' . urlencode($img);
			$urlimg = 'https://upload.wikimedia.org/wikipedia/commons/' . $folder;
			
			if (substr ($img,-3)=="svg"){
				if ($longfilename)
					$thumb="https://upload.wikimedia.org/wikipedia/commons/thumb/" . $folder."/200px-thumbnail.png";
				else
					$thumb="https://upload.wikimedia.org/wikipedia/commons/thumb/" . $folder."/200px-". urlencode($img).".png";
				$size=getimagesize($thumb);
				$width_tmp=$size[0];
				$height_tmp=$size[1];
				if ($width_tmp/$height_tmp>200/350)
					$w_thumb=200;
				else
					$w_thumb=floor(350*$width_tmp/$height_tmp);
				if ($longfilename)
					$thumb="https://upload.wikimedia.org/wikipedia/commons/thumb/" . $folder."/".$w_thumb."px-thumbnail.png";
				else
					$thumb="https://upload.wikimedia.org/wikipedia/commons/thumb/" . $folder."/".$w_thumb."px-". urlencode($img).".png";
				if ($width_tmp/$height_tmp>1400/900)
					$width=1400;
				else
					$width=floor(900*$width_tmp/$height_tmp);
				$height=floor($height_tmp*$width/$width_tmp);
				if ($longfilename)
					$large="https://upload.wikimedia.org/wikipedia/commons/thumb/" . $folder."/".$width."px-thumbnail.png";
				else
					$large="https://upload.wikimedia.org/wikipedia/commons/thumb/" . $folder."/".$width."px-". urlencode($img).".png";
			}
			else{
				$ext = strtolower(pathinfo($img, PATHINFO_EXTENSION));
				$filename = pathinfo($img, PATHINFO_FILENAME);
				$lossy="";
				$tif=false;
				if (($ext=="tif")||($ext=="tif")){
					$tif=true;
					$lossy="lossy-page1-";
					$ext.=".jpg";
				}

				$urlapimagnus="https://tools.wmflabs.org/magnus-toolserver/commonsapi.php?image=".urlencode($img);
				$xml = simplexml_load_file_from_url($urlapimagnus);
				if ($xml){
					$width=intval($xml->xpath('//file/width/text()')[0]);
					$height=intval($xml->xpath('//file/height/text()')[0]);
				}
				else{
					$size=getjpegsize($urlimg);
					if (!(isset($size[1])))
						$size=getimagesize($urlimg);
					$width=$size[0];
					$height=$size[1];
				}
			
				if(!((is_null($width))||($width==0))){
					// thumb vertical
					if ($width/$height>200/350){
						if ($width>200)
							$w_thumb=200;
						else
							$w_thumb=$width;
					}
					else{
						if ($height>350)
							$w_thumb=floor(350*$width/$height);
						else
							$w_thumb=$width;
					}
					if ($w_thumb==$width)
						$thumb=$urlimg;
					else{
						if ($longfilename)
							$thumb="https://upload.wikimedia.org/wikipedia/commons/thumb/" . $folder."/".$lossy.$w_thumb."px-thumbnail.".$ext;
						else{
							if (!$tif)
								$thumb="https://upload.wikimedia.org/wikipedia/commons/thumb/" . $folder."/".$w_thumb."px-".urlencode($img);
							else
								$thumb="https://upload.wikimedia.org/wikipedia/commons/thumb/" . $folder."/".$lossy.$w_thumb."px-".urlencode($filename).".".$ext;
						}
					}
						
					// thumb horizontal
					if ($width/$height>320/240){
						if ($width>320)
							$w_thumb_h=320;
						else
							$w_thumb_h=$width;
					}
					else{
						if ($height>240)
							$w_thumb_h=floor(240*$width/$height);
						else
							$w_thumb_h=$width;
					}
					if ($w_thumb_h==$width)
						$thumb_h=$urlimg;
					else{
						if ($longfilename)
							$thumb_h="https://upload.wikimedia.org/wikipedia/commons/thumb/" . $folder."/".$lossy.$w_thumb_h."px-thumbnail.".$ext;
						else{
							if (!$tif)
								$thumb_h="https://upload.wikimedia.org/wikipedia/commons/thumb/" . $folder."/".$w_thumb_h."px-". urlencode($img);
							else
								$thumb_h="https://upload.wikimedia.org/wikipedia/commons/thumb/" . $folder."/".$lossy.$w_thumb_h."px-".urlencode($filename).".".$ext;
						}
					}
						
				
					// large
					if ($width/$height>1400/900){
						if ($width>1400)
							$w_large=1400;
						else
							$w_large=$width;
					}
					else{
						if ($height>900)
							$w_large=floor(900*$width/$height);
						else
							$w_large=$width;
					}
					if ($w_large==$width)
						$large=$urlimg;
					else{
						if ($longfilename)
							$large="https://upload.wikimedia.org/wikipedia/commons/thumb/" . $folder."/".$lossy.$w_large."px-thumbnail.".$ext;
						else{
							if (!$tif)
								$large="https://upload.wikimedia.org/wikipedia/commons/thumb/" . $folder."/".$w_large."px-". urlencode($img);
							else
								$large="https://upload.wikimedia.org/wikipedia/commons/thumb/" . $folder."/".$lossy.$w_large."px-".urlencode($filename).".".$ext;
						}
					}
				}
				else 
					$width=0;

				unset($size);
			}
			if ($width!=0){
				$commons_artist=esc_dblq($commons_artist);
				$commons_credit=esc_dblq($commons_credit);
				$commons_license=esc_dblq($commons_license);	
				$thumb=esc_dblq($thumb);
				$thumb_h=esc_dblq($thumb_h);
				$large=esc_dblq($large);
				$sql="INSERT INTO commons_img (P18,commons_artist,commons_credit,commons_license,thumb,thumb_h,width_h,large,width,height) VALUES (\"".esc_dblq($p18_str)."\",\"".$commons_artist."\",\"".$commons_credit."\",\"".$commons_license."\",\"".$thumb."\",\"".$thumb_h."\",$w_thumb_h,\"".$large."\",$width,$height)";

				$rep=mysqli_query($link,$sql);
				$sql="SELECT id FROM commons_img WHERE P18 = _utf8 \"".esc_dblq($p18_str)."\" collate utf8_bin";
				$rep=mysqli_query($link,$sql);
				$row = mysqli_fetch_assoc($rep);
				$p18=$row['id'];
			}
		}
	}
	else{
		$row = mysqli_fetch_assoc($rep);
		$p18=$row['id'];
	}
	return $p18;
	
}

function label($tab_labels,$l){
	$label="";
	if ($tab_labels[$l]["value"])
		$label=$tab_labels[$l]["value"];
	else{
		if ($tab_labels["en"]["value"])
			$label=$tab_labels["en"]["value"];
		else{
			if ($tab_labels)
				$label=$tab_labels[key($tab_labels)]["value"];
		}
	}
	if ($label!="")
		return $label;
	else 
		return "";

}
function unit_search($qwd){
	global $fold_crotos,$tab_lg,$link; 
	$tab_lb_units = array();
	$tab_lg_without = array("bn","el","he","hi","ko","pa","te","th","tr","vi");
	for($k=0;$k<count($tab_lg);$k++)
		$tab_lb_units[$tab_lg[$k]]="";
	$qitem_path=$fold_crotos."harvest/units/".$qwd.".json";
	if (!(file_exists($qitem_path))){
		copy("https://www.wikidata.org/w/api.php?action=wbgetentities&ids=q".$qwd."&format=json", $qitem_path);
		$file_unit=file_get_contents($qitem_path,true);
		$data = json_decode($file_unit,true);

		$varlab=$data["entities"]["Q".$qwd];
		$claims=$varlab["claims"];
		$labels=$varlab["labels"];
		$latin_symbol="";
		if ($claims["P558"]){
		foreach ($claims["P558"] as $value){
			if (isset($value["qualifiers"]["P282"])){
				$sys="";
				$symbol=$value["mainsnak"]["datavalue"]["value"];
				foreach ($value["qualifiers"]["P282"] as $value_sys){
					$sys=$value_sys["datavalue"]["value"]["id"];
					if ($sys=="Q8196"){
						$tab_lb_units["ar"]=$symbol;
						$tab_lb_units["fa"]=$symbol;
					}
					if ($sys=="Q8209"){
						$tab_lb_units["ru"]=$symbol;
						$tab_lb_units["uk"]=$symbol;
					}
					if ($sys=="Q82772")
						$tab_lb_units["ja"]=$symbol;
					if ($sys=="Q8229")
						$latin_symbol=$symbol;
				}
			}
		}}
		for($k=0;$k<count($tab_lg_without);$k++)
			$tab_lb_units[$tab_lg_without[$k]]=label($labels,$tab_lg_without[$k]);
				 
		for($k=0;$k<count($tab_lg);$k++){
			$lg=$tab_lg[$k];
			if($tab_lb_units[$lg]==""){
				if ($latin_symbol!="")
					$tab_lb_units[$lg]=$latin_symbol;
				else{
					$tab_lb_units[$lg]=label($labels,$lg);
					if($tab_lb_units[$lg]=="")
						$tab_lb_units[$lg]=label($labels,"en");
				}
			}
			if($tab_lb_units[$lg]!=""){
				$sql="INSERT INTO units (qwd,label,lg) VALUES (".$qwd.",\"".$tab_lb_units[$lg]."\",\"".$lg."\")";
				$rep=mysqli_query($link,$sql);
			}
		}
	}
	
}
function get_one_sparql($queryWD,$varWD){
	$sparqlurl=urlencode($queryWD);
	$req="https://query.wikidata.org/sparql?format=json&query=".$sparqlurl;
	$opts = [
		'http' => [
			'method' => 'GET',
			'user_agent' => 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13',
			'header' => ['Accept: application/sparql-results+json'],
		],
	];
	$context = stream_context_create($opts);
	$res  = file_get_contents($req,true,$context);	
	$responseArray = json_decode($res,true);
	$result="";
	if ($responseArray["results"]["bindings"]){
	foreach ($responseArray["results"]["bindings"] as $key => $value)
		$result=$value[$varWD]["value"];
	}
	return $result;
	
}

function urlext_search($qwd){
	global $fold_crotos; 
	$ref_path=$fold_crotos."harvest/refext/";
	$IDcoll="";
	$p973="";
	$IDother="";
	$ref_path_IDcoll=$ref_path."IDcoll/".$qwd.".txt";
	$ref_path_p973=$ref_path."p973/".$qwd.".txt";
	$ref_path_IDother=$ref_path."IDother/".$qwd.".txt";
	
	if (file_exists($ref_path_IDcoll))
		$IDcoll=file_get_contents($ref_path_IDcoll,true);
	else{
		$reqsparql="SELECT (SAMPLE(?url) as ?urlext) 
WHERE{
  wd:Q".$qwd." p:P195 ?declarationlcoll.
  ?declarationlcoll ps:P195 ?coll.
  MINUS{?declarationlcoll pq:P582 [] }.
  
  {?Qprop wdt:P2378 ?coll.}
  UNION{
    ?coll  wdt:P361 ?Topcoll.
    ?Qprop wdt:P2378 ?Topcoll. 
  }
  ?Qprop wdt:P31/wdt:P279* wd:Q18618644. 
  ?Qprop wikibase:directClaim ?prop.        
  wd:Q".$qwd." ?prop ?id .
  ?Qprop wdt:P1630 ?formatterurl .  
  BIND(IRI(REPLACE(?id, '^(.+)$', ?formatterurl)) AS ?url)
} 
";
		sleep(1);
		$IDcoll=get_one_sparql($reqsparql,"urlext");
		$resfile = fopen($ref_path_IDcoll, 'w');
		fputs($resfile, $IDcoll); 
		fclose($resfile);
	}
	if (file_exists($ref_path_p973))
		$p973=file_get_contents($ref_path_p973,true);
	else{
		$reqsparql="SELECT (SAMPLE(?url) as ?urlext) 
WHERE{
	wd:Q".$qwd." wdt:P973 ?u.
	BIND(?u AS ?url)
}

";
		$p973=get_one_sparql($reqsparql,"urlext");
		$resfile = fopen($ref_path_p973, 'w');
		fputs($resfile, $p973); 
		fclose($resfile);
	}
	if (file_exists($ref_path_IDother))
		$IDother=file_get_contents($ref_path_IDother,true);
	else{
		$reqsparql="SELECT (SAMPLE(?url) as ?urlext) 
WHERE{
  wd:Q".$qwd." p:P195 ?declarationlcoll.
  ?declarationlcoll ps:P195 ?coll.
  
  ?Qprop wdt:P31/wdt:P279* wd:Q18618644. 
  ?Qprop wikibase:directClaim ?prop.     
  MINUS {?Qprop wdt:P2378 ?coll.}
  wd:Q".$qwd." ?prop ?id .
  ?Qprop wdt:P1630 ?formatterurl .  
  BIND(IRI(REPLACE(?id, '^(.+)$', ?formatterurl)) AS ?url)
}

";
		$IDother=get_one_sparql($reqsparql,"urlext");
		$resfile = fopen($ref_path_IDother, 'w');
		fputs($resfile, $IDother); 
		fclose($resfile);
	}
	
	if ($IDcoll!="")
		return $IDcoll;
	else{
		if ($p973!="")
			return $p973;
		else{
			if ($IDother!="")
				return $IDother;
			else
				return "";
		}
	}
}
?>