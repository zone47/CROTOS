<?php
function cpt_prop($id_art,$id_prop,$lg,$type="normal",$entitled=true,$link=true){
	global $mode,$l;//,$tab_miss;
	$txt="";
	if ($id_art!=0){
		$values=val_prop($id_art,$id_prop);
		$values=array_unique($values);	
	}
	else
		$values=array($id_prop);
	return count($values);
}
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
function lab_prop($l,$crit){
	switch ($crit){
		case 0:
			$label=translate($l,"Wikidata");
			break;
		case 1:
			$label=translate($l,"1");
			$label=str_replace(" [$l]","",$label);
			break;
		case 2:
			$label=translate($l,"alias");
			break;
		case 3:
			$label=translate($l,"Wikipedia");
			break;
		case 4:
			$label=translate($l,"reasonator");
			break;
		case 373:
			$label=translate($l,"CommonsCat");
			break;
		default:
			$label=translate($l,strval($crit));
	}
	return $label;
}
function creators($id_artw,$l){
	global $link,$q,$prop;
	$creators="";
	$vals=array();
	$sql="SELECT p170.qwd, p170.dates from artw_prop,p170 WHERE artw_prop.prop=170 AND  artw_prop.id_artw=$id_artw AND  artw_prop.id_prop=p170.id";
	$rep_prop=mysqli_query($link,$sql);
	$creators="";
	while ($data_prop = mysqli_fetch_assoc($rep_prop)){
		if ($creators!="")
			$creators.=", ";
		$creators.=label_item($data_prop['qwd'],$l);
		
		if ($data_prop['dates']!="")
			$creators.=" ".$data_prop['dates'];
		$creators="<a href=\"?q=Q".$data_prop['qwd']."&p=170\">".$creators."</a>";		
	}
	return $creators;
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
function data_qwd($qwd,$row){
	global $lg,$tab_check,$link,$h_thumb;
	$tab_lb = array(
			"lb0" => $qwd,
			"lb18"=> "",
			"lb373"=> "",
			"lb1"=> "",
			"lb2"=> "",
			"lb170"=> "",
			"lb571"=> "",
			"lb31"=> "",
			"lb186"=> "",
			"lb195"=> "",
			"lb217"=> "",
			"lb276"=> "",
			"lb179"=> "",
			"lb3"=> "",
			"lb973"=> "",
			"lb727"=> "",
			"lb347"=> "",
			"lb1212"=> "",
			"lb214"=> "",
			"lb350"=> "",
			"url_img"=>"",
			"wp_links"=>"",
			"lb180"=> ""
	);
	if ($row==0){
		$tab_lb["lb1"]=label($qwd,$lg);
		if ($tab_lb["lb1"]==$qwd)
			$tab_lb["lb1"]="";	
	}
	else{
		$id=$row['id'];
		
		if ($tab_check["c31"]=="1")
			$tab_lb["lb31"]= txt_prop($id,31,$lg,"normal",false,false);
		$tab_lb["lb1"]=label_item($qwd,$lg);
		if ($tab_check["c2"]=="1")
			$tab_lb["lb2"]=alias_item($qwd,$lg);
		if ($tab_check["c170"]=="1")
			$tab_lb["lb170"]=creators($id,$lg);
			
		if ($tab_check["c571"]=="1"){
			$year1=$row['year1'];
			$year2=$row['year1'];
		
			if (!(is_null($row['year1']))){
				$tab_lb["lb571"].=$row['year1'];
				if ((!(is_null($row['year2'])))&&($row['year1']!=$row['year2']))
					$tab_lb["lb571"].=" – ".$row['year2'];
				if ($row['b_date']==1)
					$tab_lb["lb571"].=" (~)";
			}
		}
		if ($tab_check["c186"]=="1")
			$tab_lb["lb186"]= txt_prop($id,186,$lg,"normal",false,false);
		if ($tab_check["c195"]=="1")
			$tab_lb["lb195"]= txt_prop($id,195,$lg,"listlink",false,false);
		if ($tab_check["c217"]=="1")
			$tab_lb["lb217"]=$row['P217'];
		if ($tab_check["c276"]=="1")
			$tab_lb["lb276"]= txt_prop($id,276,$lg,"normal",false,false);	
		if ($tab_check["c179"]=="1")
			$tab_lb["lb179"]= txt_prop($id,179,$lg,"normal",false,false);	
		if ($tab_check["c973"]=="1")
			$tab_lb["lb973"]=$row['link'];
		if ($tab_check["c727"]=="1")
			$tab_lb["lb727"]=$row['P727'];
		if ($tab_check["c347"]=="1")
			$tab_lb["lb347"]=$row['P347'];		
		if ($tab_check["c1212"]=="1")
			$tab_lb["lb1212"]=$row['P1212'];		
		if ($tab_check["c214"]=="1")
			$tab_lb["lb214"]=$row['P214'];	
		if ($tab_check["c350"]=="1")
			$tab_lb["lb350"]=$row['P350'];		
			
				
		if ($tab_check["c18"]=="1"){
			if (intval($row['P18'])!=0){
					$sql="SELECT P18, width, height from commons_img  WHERE id=".$row['P18'];
					$rep2=mysqli_query($link,$sql);
					$data2=mysqli_fetch_assoc($rep2);
					$img=str_replace(" ","_",$data2['P18']);
					
					$ext = pathinfo($img, PATHINFO_EXTENSION);
					$filename = pathinfo($img, PATHINFO_FILENAME);	
					$lossy="";
					$tif=false;
					if (($ext=="tif")||($ext=="tif")){
						$tif=true;
						$lossy="lossy-page1-";
						$ext.=".jpg";
					}
					
					$longfilename=false;
					if (strlen($img)>160)
						$longfilename=true;

					
					$digest = md5($img);
					$folder = $digest[0] . '/' . $digest[0] . $digest[1] . '/' . urlencode($img);
					$w_thumb=floor(intval($data2['width'])/intval($data2['height'])*$h_thumb);
					
					if ($longfilename)
						$thumb="http://upload.wikimedia.org/wikipedia/commons/thumb/" . $folder."/".$lossy.$w_thumb."px-thumbnail.".$ext;
					else{
						if (!$tif)
							$thumb="http://upload.wikimedia.org/wikipedia/commons/thumb/" . $folder."/".$w_thumb."px-". urlencode($img);
						else
							$thumb="http://upload.wikimedia.org/wikipedia/commons/thumb/" . $folder."/".$lossy.$w_thumb."px-".urlencode($filename).".".$ext;
					}
					
					if (substr ($img,-3)=="svg")
						$thumb.=".png";	
						
					$tab_lb["url_img"]="https://commons.wikimedia.org/wiki/File:".urlencode($img);	
					$tab_lb["lb18"]= "	<a href=\"".$tab_lb["url_img"]."\" title=\"https://commons.wikimedia.org/wiki/File:".urlencode($img)."\"><img src=\"".$thumb."\"  alt=\"".str_replace("\"","\\\"",$tab_lb["lb1"])."\" /></a>";
			}
		}
		if ($tab_check["c373"]=="1")
			$tab_lb["lb373"]=$row['P373'];
			
		if ($tab_check["c3"]=="1"){
			$sql="SELECT page,lg from label_page WHERE qwd=".$qwd." AND page!='' ORDER BY lg ";
			$rep2=mysqli_query($link,$sql);
			$num_rows= mysqli_num_rows($rep2);
			if ($num_rows>0){
				$cpt2=0;
				$lg_of="";
				while ($data2 = mysqli_fetch_assoc($rep2)){
					$cpt2++;
					if ($data2['lg']!=$lg_of){
						if ($cpt2!=1){
							$tab_lb["lb3"].=", ";
							$tab_lb["wp_links"].=", ";
						}
						$tab_lb["lb3"].=" <a href=\"https://".$data2['lg'].".wikipedia.org/wiki/".$data2['page']."\">[".$data2['lg']."]</a>";
						$tab_lb["wp_links"].="https://".$data2['lg'].".wikipedia.org/wiki/".$data2['page'];
					}
					$lg_of=$data2['lg'];
				}
			}
		}
		if ($tab_check["c180"]=="1")
			$tab_lb["lb180"]= cpt_prop($id,180,$lg,"normal",false,false);	
	}

	return $tab_lb;
}
function line_disp($data,$lg,$csv,$cpt){
	global $tab_check;
	if (!$csv){ 
		$csvtmp= array();
		$csvtmp[]=$cpt;
	}
	echo "<tr>\n";
	echo "	<td>".$cpt."</td>\n";
	foreach($tab_check as $key=>$value){
		if ($value=="1"){
			switch ($key){
				case "c0":
					echo "	<td><a href=\"https://www.wikidata.org/wiki/Q".$data["lb0"]."\" title=\"https://www.wikidata.org/wiki/Q".$data["lb0"]."\">Q".$data["lb0"]."</a></td>\n";
					if (!$csv) $csvtmp[]="https://www.wikidata.org/wiki/Q".$data["lb0"];
					break;
				case "c3":
					echo "	<td>".$data["lb3"]."</td>\n"; 
					if (!$csv) $csvtmp[]=str_replace(", ",";",$data["wp_links"]);
					break;
				case "c18":
					echo "	<td class=\"img_cell\">".$data["lb18"]."</td>\n";
					if (!$csv) $csvtmp[]=$data["url_img"];
					break;
				case "c373":
					echo "	<td>";
					if ($data["lb373"]!=""){
						echo "<a href=\"https://commons.wikimedia.org/wiki/Category:".str_replace(" ","_",$data["lb373"])."\" title=\"".str_replace(" ","_",$data["lb373"])."\">".translate($lg,"CommonsCat")."</a>";
						if (!$csv) $csvtmp[]="https://commons.wikimedia.org/wiki/Category:".str_replace(" ","_",$data["lb373"]);
					}
					else
						if (!$csv) $csvtmp[]="";
					echo "	</td>";
					break;
				case "c973":
					echo "	<td class=\"img_cell2\">";
					if ($data["lb973"]!=""){
						echo "<a href=\"".$data["lb973"]."\" title=\"".$data["lb973"]."\"><img src=\"../../img/site_link.png\" alt=\"\"/></a>";
						if (!$csv) $csvtmp[]=$data["lb973"];
					}
					else
						if (!$csv) $csvtmp[]="";
					echo "	</td>";
					break;
				case "c727":
					echo "	<td class=\"img_cell2\">";
					if ($data["lb727"]!=""){
						echo "<a href=\"http://europeana.eu/portal/record/".$data["lb727"].".html\" title=\"".translate($lg,"Europeana")." ".$data["lb727"]."\"><img src=\"../../img/europeana.png\" alt=\"\"/></a>";
						if (!$csv) $csvtmp[]="http://europeana.eu/portal/record/".$data["lb727"].".html";
					}
					else
						if (!$csv) $csvtmp[]="";
					echo "	</td>";
					break;	
				case "c347":
					echo "	<td>";
					if ($data["lb347"]!=""){
						echo "<a href=\"http://www.culture.gouv.fr/public/mistral/joconde_fr?ACTION=CHERCHER&amp;FIELD_1=REF&amp;VALUE_1=".$data["lb347"]."\" title=\"".translate($lg,"Joconde")."\">".$data["lb347"]."</a>";
						if (!$csv) $csvtmp[]="http://www.culture.gouv.fr/public/mistral/joconde_fr?ACTION=CHERCHER&FIELD_1=REF&VALUE_1=".$data["lb347"];
					}
					else
						if (!$csv) $csvtmp[]="";
					echo "	</td>";
					break;	
				case "c1212":
					echo "	<td>";
					if ($data["lb1212"]!=""){
						echo "<a href=\"http://cartelfr.louvre.fr/cartelfr/visite?srv=car_not_frame&idNotice=".$data["lb1212"]."\" title=\"".translate($lg,"Atlas")."\">".$data["lb1212"]."</a>";
						if (!$csv) $csvtmp[]="http://cartelfr.louvre.fr/cartelfr/visite?srv=car_not_frame&idNotice=".$data["lb1212"];
					}
					else
						if (!$csv) $csvtmp[]="";
					echo "	</td>";
					break;
				case "c214":
					echo "	<td>";
					if ($data["lb214"]!=""){
						echo "<a href=\"http://viaf.org/viaf/".$data["lb214"]."\" title=\"".translate($lg,"VIAF")."\">".$data["lb214"]."</a>";
						if (!$csv) $csvtmp[]="http://viaf.org/viaf/".$data["lb214"];
					}
					else
						if (!$csv) $csvtmp[]="";
					echo "	</td>";
					break;
				case "c350":
					echo "	<td>";
					if ($data["lb350"]!=""){
						echo "<a href=\"https://rkd.nl/nl/explore/images/".$data["lb350"]."\" title=\"".translate($lg,"RKDimages")."\">".$data["lb350"]."</a>";
						if (!$csv) $csvtmp[]="https://rkd.nl/nl/explore/images/".$data["lb350"];
					}
					else
						if (!$csv) $csvtmp[]="";
					echo "	</td>";
					break;
				case "url_img":
				case "wp_links":
					break;
				default:
					echo "	<td>".$data[str_replace("c","lb",$key)]."</td>\n"; 
					if (!$csv) $csvtmp[]=strip_tags($data[str_replace("c","lb",$key)]);
			}
		}
	}
	echo "</tr>\n"; 
	if (!$csv)
		return $csvtmp;
}
?>