<?php
/* / */
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
function esc_dblquote($chaine){
	$chaine=str_replace("\"","\\\"",$chaine);
	return $chaine;
}
function insert_label_page($prop,$val_item,$id_art_or_prop){
	global $tab_lg; 
    global $tab_miss; 
	if ($prop==1)
		$dfic=file_get_contents("items/$val_item.json",true);
	else
		$dfic=file_get_contents("https://www.wikidata.org/w/api.php?action=wbgetentities&ids=q$val_item&format=json",true);
		
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
			$lab=esc_dblquote($lab);
			$page=esc_dblquote($page);
			$sql="INSERT INTO label_page (type,prop,qwd,label,page,lg,id_art_or_prop) VALUES (1,$prop,$val_item,\"$lab\",\"$page\",\"$lg\",$id_art_or_prop)";
			$rep=mysql_query($sql);
		}
        if (($lab=="")&&($prop==1))
            $tab_miss[$lg]=1;
            
		// alias
		if ($ent_qwd["aliases"][$lg]){
			foreach ($ent_qwd["aliases"][$lg] as $vallab){
				$alias=esc_dblquote($vallab["value"]);
				$sql="INSERT INTO label_page (type,prop,qwd,label,lg,id_art_or_prop) VALUES (2,$prop,$val_item,\"".$alias."\",\"$lg\",$id_art_or_prop)";
				$rep=mysql_query($sql);
			}
		}
	}
	// if 276 ou 195 update site
	if (($prop==276)||($prop==195)){
		$new_val=parent_cherche($val_item);
		if ($ent_qwd["claims"]["P856"]){
			$site="";
			$tab856=$ent_qwd["claims"]["P856"];
			foreach ($tab856 as $value){
				if ($site==""){
					$site=$value["mainsnak"]["datavalue"]["value"];
					$sql="UPDATE p".$prop." SET site=\"".$site."\" WHERE qwd=$val_item";
					$rep=mysql_query($sql);
				}
			}
		}
	}
}
function parent_cherche($val_qwd){
	$dfic=file_get_contents("https://www.wikidata.org/w/api.php?action=wbgetentities&ids=q$val_qwd&format=json",true);
	$data_item=json_decode($dfic,true);
	$claims_qwd=$data_item["entities"]["Q".$val_qwd]["claims"];
	$nouv_qw="";
	if (($claims_qwd["P361"])&&(!($claims_qwd["P856"]))){
		foreach ($claims_qwd["P361"] as $val)
			if ($nouv_qw=="")
			   $nouv_qwd=$val["mainsnak"]["datavalue"]["value"]["numeric-id"];
			else
				break;
		if ($nouv_qw!=$val_qwd)
			return parent_cherche($nouv_qwd);
		else
			return $val_qwd;
			   
	}
	else
		return $val_qwd;
		
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
function esc_dblq($text){
	return str_replace("\"","\\\"",$text);
}
?>