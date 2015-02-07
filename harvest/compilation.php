<?php
//set_time_limit(108000);
//////////// Compilation ///////////////////// 
$link = mysql_connect ($host,$user,$pass) or die ('Erreur : '.mysql_error());
mysql_select_db($db) or die ('Erreur :'.mysql_error());
mysql_query("SET NAMES 'utf8'");

mysql_query("TRUNCATE `artworks`");
mysql_query("TRUNCATE `artw_prop`");
mysql_query("TRUNCATE `label_page`");
mysql_query("TRUNCATE `p31`");
mysql_query("TRUNCATE `p135`");
mysql_query("TRUNCATE `p136`");
mysql_query("TRUNCATE `p144`");
mysql_query("TRUNCATE `p170`");
mysql_query("TRUNCATE `p179`");
mysql_query("TRUNCATE `p180`");
mysql_query("TRUNCATE `p186`");
mysql_query("TRUNCATE `p195`");
mysql_query("TRUNCATE `p276`");
mysql_query("TRUNCATE `p361`");
mysql_query("TRUNCATE `p921`");
mysql_query("TRUNCATE `p941`");
mysql_query("TRUNCATE `p1639`");

$tab_lg=array("ar","bn","br","ca","cs","de","el","en","eo","es","fa","fi","fr","he","hi","id","it","ja","jv","ko","nl","pa","pl","pt","ru","sw","sv","te","th","tr","uk","vi","zh");

$dirname = $fold_crotos.'harvest/items/';
$dir = opendir($dirname); 
$cpt=0;
while($file = readdir($dir)) {
	//Test if ($cpt==20) break;  
	if($file != '.' && $file != '..' && !is_dir($dirname.$file)){
		$item=str_replace(".json","",$file);
		$cpt++;
		//Test echo $cpt." ".$item." | ";
		
		$tab_miss = array(
			"m135"=> 0,// movement
			"m136"=> 0,// genre
			"m144"=> 0,// based on
			"m180"=> 0,// depicts
			"m179"=> 0,// series
			"m170"=> 0,// creator
			"m186"=> 0,// material
			"m195"=> 0,// collection
			"m276"=> 0,// location
			"m361"=> 0,// part of
			"m921"=> 0,// subject heading
			"m941"=> 0,// inspired by
			"ar"=> 0,
			"bn"=> 0,
			"br"=> 0,
			"ca"=> 0,
			"cs"=> 0,
			"de"=> 0,
			"el"=> 0,
			"en"=> 0,
			"eo"=> 0,
			"es"=> 0,
			"fa"=> 0,
			"fi"=> 0,
			"fr"=> 0,
			"he"=> 0,
			"hi"=> 0,
			"id"=> 0,
			"it"=> 0,
			"ja"=> 0,
			"jv"=> 0,
			"ko"=> 0,
			"mu"=> 0,
			"nl"=> 0,
			"pa"=> 0,
			"pl"=> 0,
			"pt"=> 0,
			"ru"=> 0,
			"sw"=> 0,
			"sv"=> 0,
			"te"=> 0,
			"th"=> 0,
			"tr"=> 0,
			"uk"=> 0,
			"vi"=> 0,
			"zh"=> 0
		);
		if (($cpt % 500)==0)
		echo "\n$cpt";

$datafic=file_get_contents("items/$item.json",true);
$data = json_decode($datafic,true);

$varlab=$data["entities"]["Q".$item];
$claims=$varlab["claims"];


$tab_prop = array(
	"P18"=> "",  // Image
	"P214"=> "", // VIAF ID
	"P217"=> "", // Inventory number
	"P347"=> "", // Joconde ID
	"P350"=> "", // RKDimages ID
	"P373"=> "", // Commons Category
	"P727"=> "", // Europeana ID
	"P856"=> "", // Official website
	"P973"=> "", // described at URL
	"P1212"=> "" // Atlas ID
);

foreach($tab_prop as $key=>$val){
	if ($claims[$key]){
		foreach ($claims[$key] as $value){
			if ($tab_prop[$key]=="")
			   $tab_prop[$key]=$value["mainsnak"]["datavalue"]["value"];
			else
				break;
		}
		if ($key!="P18")
			$tab_prop[$key]=esc_dblquote($tab_prop[$key]);
	}
}
$commons_artist="";
$commons_credit="";
$commons_license="";
$thumb="";
$thumb_h="";
$large="";
$w_thumb_h=0;
$new_img=0;
if ($tab_prop["P18"]!=""){
	$sql="SELECT commons_artist,commons_credit,commons_license,thumb,thumb_h,width_h,large FROM commons_img WHERE P18=\"".esc_dblquote($tab_prop["P18"])."\"";
	$rep=mysql_query($sql);
	if (mysql_num_rows($rep)==0){
		$img=str_replace(" ","_",$tab_prop["P18"]);
		$tab_prop["P18"]=esc_dblq($tab_prop["P18"]);
		
		$urlapi="https://commons.wikimedia.org/w/api.php?action=query&prop=imageinfo&format=xml&iiprop=extmetadata&iilimit=10&titles=File:".urlencode($img);
		$xml = simplexml_load_file_from_url($urlapi);
		if ($xml){
			$commons_artist=$xml->xpath('//Artist/@value')[0];
			$commons_credit=$xml->xpath('//Credit/@value')[0];
			$commons_license=$xml->xpath('//License/@value')[0];
		
			$digest = md5($img);
			$folder = $digest[0] . '/' . $digest[0] . $digest[1] . '/' . urlencode($img);
			$urlimg = 'http://upload.wikimedia.org/wikipedia/commons/' . $folder;
			
			if (substr ($img,-3)=="svg"){
				$thumb="http://upload.wikimedia.org/wikipedia/commons/thumb/" . $folder."/200px-". urlencode($img).".png";
				$size=getimagesize($thumb);
				$width_tmp=$size[0];
				$height_tmp=$size[1];
				if ($width_tmp/$height_tmp>200/350)
					$w_thumb=200;
				else
					$w_thumb=floor(350*$width_tmp/$height_tmp);
				$thumb="http://upload.wikimedia.org/wikipedia/commons/thumb/" . $folder."/".$w_thumb."px-". urlencode($img).".png";
				if ($width_tmp/$height_tmp>1400/900)
					$width=1400;
				else
					$width=floor(900*$width_tmp/$height_tmp);
				$height=floor($height_tmp*$width/$width_tmp);
				$large="http://upload.wikimedia.org/wikipedia/commons/thumb/" . $folder."/".$width."px-". urlencode($img).".png";
			}
			else{
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
					else
						$thumb="http://upload.wikimedia.org/wikipedia/commons/thumb/" . $folder."/".$w_thumb."px-". urlencode($img);
						
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
					else
						$thumb_h="http://upload.wikimedia.org/wikipedia/commons/thumb/" . $folder."/".$w_thumb_h."px-". urlencode($img);
				
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
					else
						$large="http://upload.wikimedia.org/wikipedia/commons/thumb/" . $folder."/".$w_large."px-". urlencode($img);
					$new_img=1; // new image
				}
				else {
					$width=0;
					$height=0;	
					// no metadata on image -> no image
					$tab_prop["P18"]="";
				}
	
				unset($size);
			}
			$commons_artist=esc_dblq($commons_artist);
			$commons_credit=esc_dblq($commons_credit);
			$commons_license=esc_dblq($commons_license);	
			$thumb=esc_dblq($thumb);
			$thumb_h=esc_dblq($thumb_h);
			$large=esc_dblq($large);
			$sql="INSERT INTO commons_img (P18,commons_artist,commons_credit,commons_license,thumb,thumb_h,width_h,large,width,height) VALUES (\"".$tab_prop["P18"]."\",\"".$commons_artist."\",\"".$commons_credit."\",\"".$commons_license."\",\"".$thumb."\",\"".$thumb_h."\",$w_thumb_h,\"".$large."\",$width,$height)";
			if ($width!=0)
				$rep=mysql_query($sql);
		}
		else {
			// no metadata on image -> no image
			$tab_prop["P18"]="";
		}
	}
	else{
		$row = mysql_fetch_assoc($rep);
		$commons_artist=esc_dblq($row['commons_artist']);
		$commons_credit=esc_dblq($row['commons_credit']);
		$commons_license=esc_dblq($row['commons_license']);
		$thumb=esc_dblq($row['thumb']);
		$thumb_h=esc_dblq($row['thumb_h']);
		$w_thumb_h=$row['width_h'];
		$large=esc_dblq($row['large']);
		$tab_prop["P18"]=esc_dblq($tab_prop["P18"]);
	}
}

//date P571 or P585
$year1 = NULL;
$year2 = NULL;
$b_date=0;
$tab_date=array("P571","P585");
for ($i=0;$i<count($tab_date);$i++){
	if ($claims[$tab_date[$i]]){
		foreach ($claims[$tab_date[$i]] as $value){
			$time=$value["mainsnak"]["datavalue"]["value"]["time"];
			$precision=$value["mainsnak"]["datavalue"]["value"]["precision"];
			$after=$value["mainsnak"]["datavalue"]["value"]["after"];
			$date_tmp=intval(substr($time,1,strpos($time,"-")-1));
			if ((intval($precision<9))&&(intval($after)==0))
				$b_date=1;
			if (substr($time,0,1)=="-")
				$date_tmp = -1 * abs($date_tmp);
			if (($b_date==0)){
				$gap=9-intval($precision);
				switch ($gap) {
					case 0:
						$coef=0;
						break;
					case 1:
						$coef=10;
						break;
					case 2:
						$coef=100;
						break;
					case 3:
						$coef=1000;
						break;
					case 4:
						$coef=10000;
						break;
					case 5:
						$coef=100000;
						break;
					default:
					   $coef=0;
				}
				$date_tmp2=$date_tmp+(intval($after)*$coef);
			}
			else
				$date_tmp2=$date_tmp;
			if ($year1==NULL){
				$year1 = $date_tmp;
				$year2 = $date_tmp2;
			}else{
				if ($date_tmp<$year1)
					$year1 = $date_tmp;
				if ($date_tmp2>$year2)
					$year2 = $date_tmp2;
			}
		}
	}
}

if (($year1!=NULL)&&($year2==0))
		$year2=-1;
	
if (($year1==1)&&($precision<9))
		$year2=10*floor($year2/10);
if ($year1==NULL)
	$year1="NULL";
if ($year2==NULL)
	$year2="NULL";

$offic_url=$tab_prop["P856"];
if ($offic_url=="")
	$offic_url=$tab_prop["P973"];

$sql="INSERT INTO artworks (qwd,P18,P214,P217,P347,P350,P373,P727,link,P1212,year1,year2,b_date,commons_artist,commons_credit,commons_license,thumb,thumb_h,width_h,large,new_img) VALUES ($item,\"".$tab_prop["P18"]."\",\"".$tab_prop["P214"]."\",\"".$tab_prop["P217"]."\",\"".$tab_prop["P347"]."\",\"".$tab_prop["P350"]."\",\"".$tab_prop["P373"]."\",\"".$tab_prop["P727"]."\",\"".$offic_url."\",\"".$tab_prop["P1212"]."\",$year1,$year2,\"".$b_date."\",\"".$commons_artist."\",\"".$commons_credit."\",\"".$commons_license."\",\"".$thumb."\",\"".$thumb_h."\",$w_thumb_h,\"".$large."\",$new_img)";
$rep=mysql_query($sql);

$sql="SELECT id FROM artworks WHERE qwd=\"$item\"";
$rep=mysql_query($sql);
$row = mysql_fetch_assoc($rep);
$id_artwork=$row['id'];

//1 for artwork item
insert_label_page(1,$item,$id_artwork);

// Other properties
$tab_multi=array(170,31,276,195,136,135,179,180,186,144,361,921,941,1639);	
for ($i=0;$i<count($tab_multi);$i++){
	if ($claims["P".$tab_multi[$i]])
		foreach ($claims["P".$tab_multi[$i]] as $value){
			$val=intval($value["mainsnak"]["datavalue"]["value"]["numeric-id"]);
			/*if (($tab_multi[$i]==195)||($tab_multi[$i]==276)){
				$sql="SELECT id FROM p".$tab_multi[$i]." WHERE qwd=$val";
				$rep=mysql_query($sql);
				if (mysql_num_rows($rep)==0)
					$val=parent_cherche($val);// Looking for uper-classes
			}*/
			$sql="SELECT id FROM p".$tab_multi[$i]." WHERE qwd=$val";
			$rep=mysql_query($sql);
			$newid="";
			$found=false;
			if (mysql_num_rows($rep)==0){
				//Value of property inserted
				$sql="INSERT INTO p".$tab_multi[$i]." (qwd) VALUES ($val)";
				$rep=mysql_query($sql);
				
				$sql="SELECT id FROM p".$tab_multi[$i]." WHERE qwd=$val";
				$rep=mysql_query($sql);
				
				$row = mysql_fetch_assoc($rep);
				$id_prop=$row['id'];
				$newid=$id_prop;
				//Labels of property inserted
				insert_label_page($tab_multi[$i],$val,$id_prop);
				
			}
			else{			
				$row = mysql_fetch_assoc($rep);
				$id_prop=$row['id'];
				$found=true;	
			}
			$insertok=true;
			if (($tab_multi[$i]==195)||($tab_multi[$i]==276)){
				// Looking for uper-classes
				$sql="SELECT id,level FROM p".$tab_multi[$i]." WHERE qwd=$val";
				$rep=mysql_query($sql);

				$level=0;
				if (mysql_num_rows($rep)>0){
					$row = mysql_fetch_assoc($rep);
					$level=$row['level'];
				}
				if ((!$found)||($level!=0))
					parent_cherche($tab_multi[$i],$val,$id_artwork,$newid);
					
				$sql="SELECT id FROM artw_prop WHERE prop=".$tab_multi[$i]." and id_artw=$id_artwork and id_prop=$id_prop";
				$rep=mysql_query($sql);
				if (mysql_num_rows($rep)!=0)
					$insertok=false;
			}
					
			
			if ($insertok){
				$sql="INSERT INTO artw_prop (prop,id_artw,id_prop) VALUES (".$tab_multi[$i].",$id_artwork,$id_prop)";
				//test if ($tab_multi[$i]=="276") echo "\n".$sql;
				$rep=mysql_query($sql);
			}
		}
	else
		if (!(($tab_multi[$i]=="31")||($tab_multi[$i]=="1639")))
			$tab_miss["m".$tab_multi[$i]]=1;
}

// missing props
$sql="UPDATE artworks SET ";
foreach($tab_miss as $key=>$value){
	if ($sql!="UPDATE artworks SET ")
		$sql.=",";
	if ((substr($key,0,1)=="m")&&($key!="mu"))
		$sql.=$key."=".$value;
	else
		$sql.="lb".$key."=".$value;
}
$sql.=" WHERE id=$id_artwork";
$rep=mysql_query($sql);
unset($tab_miss);

	}//it's a file fichier
}//reading files in directory
mysql_close();
closedir($dir);
echo "\ncompilation done";


?>