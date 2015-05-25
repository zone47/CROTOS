<?php
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
	$sql="SELECT p170.qwd from artw_prop,p170 WHERE artw_prop.prop=170 AND  artw_prop.id_artw=$id_artw AND  artw_prop.id_prop=p170.id";
	$rep_prop=mysqli_query($link,$sql);
	while ($data_prop = mysqli_fetch_assoc($rep_prop))
		$vals[]=intval($data_prop['qwd']);
	for ($i=0;$i<count($vals);$i++){
		global $fold_crotos;
		$dates="";
		$Q_creator=$vals[$i];
		$year1="";
		$year2="";
		$qitem_path=$fold_crotos."lab/artworks/creators/Q".$Q_creator.".txt";
		if (!(file_exists($qitem_path))){
			$url_api="https://www.wikidata.org/w/api.php?action=wbgetentities&ids=Q".$Q_creator."&format=json";
			$dfic =file_get_contents($url_api,true);
			$data_item=json_decode($dfic,true);
			$claims=$data_item["entities"]["Q".$Q_creator]["claims"];
			$tab_date=array("P569","P570");
			for ($j=0;$j<count($tab_date);$j++){
				$b_date=0;
				if ($claims[$tab_date[$j]]){
					foreach ($claims[$tab_date[$j]] as $value){
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
				else
					$dates.="?";
				$dates.=")";
			}
			$fp2 = fopen("creators/Q".$Q_creator.".txt", "w");
			fputs($fp2,$dates);
			fclose($fp2);
		}
		else{
			$fp2 = fopen("creators/Q".$Q_creator.".txt", "r");
			$dates=fgets($fp2);
			fclose($fp2);
		}
		if ($creators!="")
			$creators.=", ";
		$creators.=label_item($vals[$i],$l);
		
		if ($dates!="")
			$creators.=" ".$dates;
		$creators="<a href=\"?q=Q".$Q_creator."&p=170\">".$creators."</a>";	
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
?>