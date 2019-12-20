<?php
//set_time_limit(108000);
//////////// Compilation ///////////////////// 
echo "\nCompilation";
include $file_timer_begin;

$link = mysqli_connect ($host,$user,$pass,$db) or die ('Erreur : '.mysqli_error());
mysqli_query($link,"SET NAMES 'utf8'");

mysqli_query($link,"TRUNCATE `artworks`");
mysqli_query($link,"TRUNCATE `artw_prop`");
mysqli_query($link,"TRUNCATE `label_page`");
mysqli_query($link,"TRUNCATE `p31`");
mysqli_query($link,"TRUNCATE `p135`");
mysqli_query($link,"TRUNCATE `p136`");
mysqli_query($link,"TRUNCATE `p144`");
mysqli_query($link,"TRUNCATE `p170`");
mysqli_query($link,"TRUNCATE `p179`");
mysqli_query($link,"TRUNCATE `p180`");
mysqli_query($link,"TRUNCATE `p186`");
mysqli_query($link,"TRUNCATE `p195`");
mysqli_query($link,"TRUNCATE `p276`");
mysqli_query($link,"TRUNCATE `p361`");
mysqli_query($link,"TRUNCATE `p608`");
mysqli_query($link,"TRUNCATE `p921`");
mysqli_query($link,"TRUNCATE `p941`");
mysqli_query($link,"TRUNCATE `p1433`");
mysqli_query($link,"TRUNCATE `p1639`");
mysqli_query($link,"TRUNCATE `p6216`");
mysqli_query($link,"TRUNCATE `units`");
mysqli_query($link,"ALTER TABLE `commons_img` ADD INDEX(`P18`)");
$cmd="del /Q ".str_replace("/","\\",$fold_crotos)."harvest\\units\\*.*";
exec($cmd);

$tab_lg=array("ar","bn","br","ca","cs","cy","da","de","el","en","eo","es","fa","fi","fr","he","hi","id","it","ja","jv","ko","nb","nl","pa","pl","pt","ru","sw","sv","te","th","tr","uk","vi","zh");

$dirname = $fold_crotos.'harvest/items/';
$dir = opendir($dirname); 
$cpt=0;
while($file = readdir($dir)) {
	 //Test 	 if ($cpt==5) break;  
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
			"m6216"=> 0,// inspired by
			"mw"=> 1,// wikipedia article
			"ar"=> 0,
			"bn"=> 0,
			"br"=> 0,
			"ca"=> 0,
			"cs"=> 0,
            "cy"=> 0,
			"da"=> 0,
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
			"nb"=> 0,
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
		if (($cpt % 1000)==0)
		echo "\n$cpt";

$datafic=file_get_contents($fold_crotos."harvest/items/$item.json",true);
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
    "P625"=> "", // Commons Category
	"P727"=> "", // Europeana ID
	"P856"=> "", // Official website
	"P973"=> "", // described at URL
	"P1212"=> "", // Atlas ID
    "P2043"=> "", // length
    "P2048"=> "", // height
    "P2049"=> "", // width
	"P2108"=> "", // Kunstindex Danmark kunstværk-ID
    "P2386"=> "", // diameter
	"P2610"=> ""  // depth
);

foreach($tab_prop as $key=>$val){
	if ($claims[$key]){
		foreach ($claims[$key] as $value){
			if (($key=="P2043")||($key=="P2048")||($key=="P2049")||($key=="P2386")||($key=="P2610")){
				if ($tab_prop[$key]!="")
					$tab_prop[$key].="|";
				$tab_prop[$key].=str_replace("+","",$value["mainsnak"]["datavalue"]["value"]["amount"]);
				$unit=str_replace("http://www.wikidata.org/entity/Q","",$value["mainsnak"]["datavalue"]["value"]["unit"]);
				if ($unit!="1")
					unit_search($unit);
				$tab_prop[$key].=";".str_replace("http://www.wikidata.org/entity/Q","",$unit);
			}
			else{
				if ($tab_prop[$key]=="")
					$tab_prop[$key]=$value["mainsnak"]["datavalue"]["value"];
				else
					break;
			}
		}
		if ($key!="P18")
			$tab_prop[$key]=esc_dblq($tab_prop[$key]);
	}
}

$new_img=0;
$p18=0;
$hd=0;
if ($tab_prop["P18"]!=""){
	$img_exists=false;
	$sql="SELECT id FROM commons_img WHERE P18 = _utf8 \"".esc_dblq($tab_prop["P18"])."\" collate utf8_bin";
	$rep=mysqli_query($link,$sql);
	if (mysqli_num_rows($rep)!=0)
		$img_exists=true;		
	$p18=id_commons($tab_prop["P18"]);
	
	if ($p18!=0){
		$sql="SELECT width,height FROM commons_img WHERE id=".$p18;
		$rep=mysqli_query($link,$sql);
		if (mysqli_num_rows($rep)!=0){
			$row = mysqli_fetch_assoc($rep);
			if (($row['width']>=2000)||($row['height']>=2000))
				$hd=1;
		}
	}
	$id_artwork=$row['id'];
	
	if (($p18!=0)and(!($img_exists)))
		$new_img=1;
}
$lat="";
$lon="";
if ($tab_prop["P625"]!=""){
	$lat=$tab_prop["P625"]["latitude"];
    $lon=$tab_prop["P625"]["longitude"];
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
            if (intval($after)!=0){
                $gap=9-intval($precision);
				switch ($gap) {
					case 0:
						$coef=1;
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
			/*if (($b_date==0)){
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
				$date_tmp2=$date_tmp;*/
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
	$offic_url=urlext_search_dwynwen($item);
$link2=link2_search($item);

$publi_crea=0;
$publi_img=0;

$sql="SELECT id,crea,img FROM publi WHERE qwd=$item";
$rep=mysqli_query($link,$sql);
if (mysqli_num_rows($rep)!=0){
	$row = mysqli_fetch_assoc($rep);
	$publi_crea=$row['crea'];
	$publi_img=$row['img'];
	if (($row['img']==0)&&($new_img==1)){
		mysqli_query($link,"UPDATE publi SET img=1 WHERE id=".$row['id']);
		$publi_img=1;
	}
}
else{
	mysqli_query($link,"INSERT INTO publi (qwd,crea,img) VALUES ($item,1,$new_img)");
	$publi_crea=1;
	$publi_img=$new_img;
}

$sql="INSERT INTO artworks (qwd,p18,hd,p214,p217,p347,p350,p373,p727,link,link2,p1212,p2108,p2043,p2048,p2049,p2386,p2610,year1,year2,b_date,crea,img,lat,lon) VALUES ($item,".$p18.",$hd,\"".$tab_prop["P214"]."\",\"".$tab_prop["P217"]."\",\"".$tab_prop["P347"]."\",\"".$tab_prop["P350"]."\",\"".$tab_prop["P373"]."\",\"".$tab_prop["P727"]."\",\"".$offic_url."\",\"".$link2."\",\"".$tab_prop["P1212"]."\",\"".$tab_prop["P2108"]."\",\"".$tab_prop["P2043"]."\",\"".$tab_prop["P2048"]."\",\"".$tab_prop["P2049"]."\",\"".$tab_prop["P2386"]."\",\"".$tab_prop["P2610"]."\",$year1,$year2,\"".$b_date."\",".$publi_crea.",".$publi_img.",\"".$lat."\",\"".$lon."\")";
$rep=mysqli_query($link,$sql);

mysqli_query($link,"UPDATE publi SET del=1 WHERE qwd=$item");

$sql="SELECT id FROM artworks WHERE qwd=\"$item\"";
$rep=mysqli_query($link,$sql);
$row = mysqli_fetch_assoc($rep);
$id_artwork=$row['id'];

// 1abels for artwork item
insert_label_page(1,$item,$id_artwork);

// Other properties
$tab_multi=array(31,135,136,144,170,179,180,186,195,276,361,608,921,941,1433,1639,6216);	
for ($i=0;$i<count($tab_multi);$i++){
	if ($claims["P".$tab_multi[$i]])
		foreach ($claims["P".$tab_multi[$i]] as $value){
			$alive=true;
			if (($tab_multi[$i]==195)||($tab_multi[$i]==276))
				if (isset($value["qualifiers"]["P582"]))
					$alive=false;
			if ($alive){		
				$val=intval($value["mainsnak"]["datavalue"]["value"]["numeric-id"]);
				$sql="SELECT id FROM p".$tab_multi[$i]." WHERE qwd=$val";
				$rep=mysqli_query($link,$sql);
				$newid="";
				$found=false;
				if (mysqli_num_rows($rep)==0){
					//Value of property inserted
					/*$p18_str=img_qwd($val);
					if ($p18_str!="")
						$p18=id_commons($p18_str);
					else*/
						$p18=0;
						
					$sql="INSERT INTO p".$tab_multi[$i]." (qwd,P18) VALUES ($val,".$p18.")";
					$rep=mysqli_query($link,$sql);
					
					$sql="SELECT id FROM p".$tab_multi[$i]." WHERE qwd=$val";
					$rep=mysqli_query($link,$sql);
					
					$row = mysqli_fetch_assoc($rep);
					$id_prop=$row['id'];
					$newid=$id_prop;
					//Labels of property inserted
					insert_label_page($tab_multi[$i],$val,$id_prop);
					
				}
				else{			
					$row = mysqli_fetch_assoc($rep);
					$id_prop=$row['id'];
					$found=true;	
				}
				$insertok=true;
				if (($tab_multi[$i]==195)||($tab_multi[$i]==276)){
					// Looking for uper-classes
					$sql="SELECT id,level FROM p".$tab_multi[$i]." WHERE qwd=$val";
					$rep=mysqli_query($link,$sql);
	
					$level=0;
					if (mysqli_num_rows($rep)>0){
						$row = mysqli_fetch_assoc($rep);
						$level=$row['level'];
					}
					if ((!$found)||($level!=0))
						parent_cherche($tab_multi[$i],$val,$id_artwork,$newid);
						
					$sql="SELECT id FROM artw_prop WHERE prop=".$tab_multi[$i]." and id_artw=$id_artwork and id_prop=$id_prop";
					$rep=mysqli_query($link,$sql);
					if (mysqli_num_rows($rep)!=0)
						$insertok=false;
				}
				
				if ($insertok){
					$sql="INSERT INTO artw_prop (prop,id_artw,id_prop) VALUES (".$tab_multi[$i].",$id_artwork,$id_prop)";
					$rep=mysqli_query($link,$sql);
				}
			}
		}
	else
		if (!(($tab_multi[$i]=="31")||($tab_multi[$i]=="608")||($tab_multi[$i]=="1433")||($tab_multi[$i]=="1639")))
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
$rep=mysqli_query($link,$sql);
unset($tab_miss);

	}//it's a file
}//reading files in directory
closedir($dir);

mysqli_query($link,"ALTER TABLE commons_img DROP INDEX P18");

// maj date publi
$newdate=date("Ymd");
mysqli_query($link,"UPDATE artworks SET crea=$newdate WHERE crea=1");
mysqli_query($link,"UPDATE artworks SET img=$newdate WHERE img=1");
mysqli_query($link,"UPDATE publi SET crea=$newdate WHERE crea=1");
mysqli_query($link,"UPDATE publi SET img=$newdate WHERE img=1");
mysqli_query($link,"UPDATE publi SET del=$newdate WHERE del=0");
mysqli_query($link,"UPDATE publi SET del=0 WHERE del=1");

mysqli_close($link);

echo "\nCompilation done";
include $file_timer_end;
?>