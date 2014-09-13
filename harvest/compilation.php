<?php
//set_time_limit(108000);
//////////// Compilation ///////////////////// 
$link = mysql_connect ($host,$user,$pass) or die ('Erreur : '.mysql_error());
mysql_select_db($db) or die ('Erreur :'.mysql_error());
mysql_query("SET NAMES 'utf8'");

mysql_query("TRUNCATE `artworks`");
mysql_query("TRUNCATE `artw_prop`");
mysql_query("TRUNCATE `label_page`");
mysql_query("TRUNCATE `missing`");
mysql_query("TRUNCATE `p31`");
mysql_query("TRUNCATE `p135`");
mysql_query("TRUNCATE `p136`");
mysql_query("TRUNCATE `p144`");
mysql_query("TRUNCATE `p170`");
mysql_query("TRUNCATE `p180`");
mysql_query("TRUNCATE `p186`");
mysql_query("TRUNCATE `p195`");
mysql_query("TRUNCATE `p276`");
mysql_query("TRUNCATE `p921`");
mysql_query("TRUNCATE `p941`");

$tab_lg=array("ar","bn","br","ca","cs","de","el","en","eo","es","fa","fi","fr","he","hi","id","it","ja","jv","ko","nl","pa","pl","pt","ru","sw","sv","te","th","tr","uk","vi","zh");

$dirname = '/***/crotos/harvest/items/';
$dir = opendir($dirname); 
$cpt=0;
while($file = readdir($dir)) {
	//if ($cpt==5) break;
	if($file != '.' && $file != '..' && !is_dir($dirname.$file)){
		$item=str_replace(".json","",$file);
		$cpt++;
		
		$tab_miss = array(
			"m18"=> 0,// image
			"m170"=> 0,// creator
			"m571"=> 0,// creator
			"m186"=> 0,// material
			"m195"=> 0,// collection
			"m276"=> 0,// location
			"m180"=> 0,// depicts
			"m136"=> 0,// genre
			"m135"=> 0,// movement
			"m347"=> 0,// Joconde ID
			"m217"=> 0,// inventory number
			"m144"=> 0,// based on
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

//P18 Image
$P18="";
if ($claims["P18"])
	foreach ($claims["P18"] as $value){
		if ($P18=="")
		   $P18=$value["mainsnak"]["datavalue"]["value"];
		else
		 	break;
	}
else
	$tab_miss["m18"]=1;
	
//P373 Commons Category
$P373="";
if ($claims["P373"])
	foreach ($claims["P373"] as $value){
		if ($P373=="")
		   $P373=$value["mainsnak"]["datavalue"]["value"];
		else
		 	break;
	}
	
//P217 Inventory number
$P217="";
if ($claims["P217"])
	foreach ($claims["P217"] as $value){
		if ($P217=="")
		   $P217=$value["mainsnak"]["datavalue"]["value"];
		else
		 	break;
	}
else
	$tab_miss["m217"]=1;
	
//P347 Joconde ID
$P347="";
if ($claims["P347"])
	foreach ($claims["P347"] as $value){
		if ($P347=="")
		   $P347=$value["mainsnak"]["datavalue"]["value"];
		else
		 	break;
	}
else
	$tab_miss["m347"]=1;
	
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

if ($year1!=NULL){
	if ($year2==0)
		$year2=-1;
	}
else
	$tab_miss["m571"]=1;  
	
if (($year1==1)&&($precision<9))
		$year2=10*floor($year2/10);

$P18=esc_dblquote($P18);
$P217=esc_dblquote($P217);
$P347=esc_dblquote($P347);
$P373=esc_dblquote($P373);
if ($year1!=NULL)
	$sql="INSERT INTO artworks (qwd,P18,P217,P347,P373,year1,year2,b_date) VALUES ($item,\"$P18\",\"$P217\",\"$P347\",\"$P373\",$year1,$year2,$b_date)";
else
	$sql="INSERT INTO artworks (qwd,P18,P217,P347,P373) VALUES ($item,\"$P18\",\"$P217\",\"$P347\",\"$P373\")";
$rep=mysql_query($sql);
$sql="SELECT id FROM artworks WHERE qwd=\"$item\"";
$rep=mysql_query($sql);
$row = mysql_fetch_assoc($rep);
$id_artwork=$row['id'];

//1 for artwork item
insert_label_page(1,$item,$id_artwork);

// Other properties
$tab_multi=array(170,31,276,195,136,135,180,186,144,921,941);	
for ($i=0;$i<count($tab_multi);$i++){
	if ($claims["P".$tab_multi[$i]])
		foreach ($claims["P".$tab_multi[$i]] as $value){
			$val=intval($value["mainsnak"]["datavalue"]["value"]["numeric-id"]);
			if (($tab_multi[$i]==195)||($tab_multi[$i]==276)){
				$sql="SELECT id FROM p".$tab_multi[$i]." WHERE qwd=$val";
				$rep=mysql_query($sql);
				if (mysql_num_rows($rep)==0)
					$val=parent_cherche($val);
			}
			$sql="SELECT id FROM p".$tab_multi[$i]." WHERE qwd=$val";
			$rep=mysql_query($sql);
			if (mysql_num_rows($rep)==0){
				//Value of property inserted
				$sql="INSERT INTO p".$tab_multi[$i]." (qwd) VALUES ($val)";
				$rep=mysql_query($sql);
				
				$sql="SELECT id FROM p".$tab_multi[$i]." WHERE qwd=$val";
				$rep=mysql_query($sql);
				
				$row = mysql_fetch_assoc($rep);
				$id_prop=$row['id'];
				//Labels of property inserted
				insert_label_page($tab_multi[$i],$val,$id_prop);
				
			}
			else{			
				$row = mysql_fetch_assoc($rep);
				$id_prop=$row['id'];	
			}
			
			$sql="INSERT INTO artw_prop (prop,id_artw,id_prop) VALUES (".$tab_multi[$i].",$id_artwork,$id_prop)";
			$rep=mysql_query($sql);
		}
	else
		if ($tab_multi[$i]!="31")
			$tab_miss["m".$tab_multi[$i]]=1;
}

// missing props
$cols="";
$values="";
foreach($tab_miss as $key=>$value){
	$cols.=",".$key;
	$values.=",".$value;
}
$sql="INSERT INTO missing (ident".$cols.") VALUES ($id_artwork".$values.")";
$rep=mysql_query($sql);
unset($tab_miss);

	}//it's a file fichier
}//reading files in directory
mysql_close();
closedir($dir);
echo "\ncompilation done";


?>