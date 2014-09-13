<?php 
// Search queries. Could be improved
$sql="";
$res_s=array();
$prim_query=true;
$check_query=false;
$search_query=false;
// Timer begin
//list($g_usec, $g_sec) = explode(" ",microtime());
//define ("t_start", (float)$g_usec + (float)$g_sec);
if ($s!=""){	
	$tab_keywords=explode(" ",$s);
	for ($i=0;$i<count($tab_keywords);$i++){
		if ($tab_keywords[$i]!=""){
			$search_query=true;
			if (preg_match("/.[\-0123456789]/",$tab_keywords[$i])){ 
				$dates=explode("-",$tab_keywords[$i]);
				$year1=0;				$year1_neg=false;
				$year2=0;
				$year2_neg=false;
				for ($j=0;$j<count($dates);$j++){
					if ($dates[$j]==""){
						if($year1==0)
							$year1_neg=true;
						else
							$year2_neg=true;				
					}
					else{
						if($year1==0)
							$year1=$dates[$j];
						else
							$year2=$dates[$j];
					}
				}
				if ($year1_neg)
					$year1=-$year1;
				if ($year2_neg)
					$year2=-$year2;
				if ($year2==0)
					$year2=$year1;
				$sql_s="
				SELECT distinct  id
				FROM artworks
				WHERE year1>=$year1 
				AND year2<=$year2";
				if ($mode==0) $sql_s.=" AND P18<>''";
			}
			else{
				$sql_s="
				(
				SELECT distinct artworks.id as id
				FROM label_page, artw_prop, artworks
				WHERE label_page.prop !=1
				AND label_page.label LIKE \"%".$tab_keywords[$i]."%\"
				AND label_page.id_art_or_prop = artw_prop.id_prop
				AND label_page.prop = artw_prop.prop
				AND artw_prop.id_artw = artworks.id";
				if ($mode==0) $sql_s.=" AND artworks.P18<>''";
				$sql_s.=")
				UNION (
				
				SELECT distinct artworks.id as id
				FROM label_page, artworks
				WHERE label_page.label LIKE \"%".$tab_keywords[$i]."%\"
				AND label_page.id_art_or_prop = artworks.id
				AND label_page.prop =1";
				if ($mode==0) $sql_s.=" AND artworks.P18<>''";
				$sql_s.=")";
			}
			//echo $sql_s;
			$rep_s=mysql_query($sql_s);
			$new_s="";
			while($data_s = mysql_fetch_assoc($rep_s)) {
				if ($prim_query){
					$res_s[]=$data_s['id'];
					if ($new_s!="")
						$new_s.=";";
					$new_s.=$data_s['id'];
				}
				else
					if (in_array ($data_s['id'],$res_s)){
						if ($new_s!="")
							$new_s.=";";
						$new_s.=$data_s['id'];
					}
			}
			unset($res_s);
			$res_s=array();
			if ($prim_query)
				$prim_query=false;
			if ($new_s!="")
				$res_s=explode(";",$new_s);
			else
				break;
		}
	}
}
// Timer end and print
//list($g2_usec, $g2_sec) = explode(" ",microtime());
//define ("t_end", (float)$g2_usec + (float)$g2_sec);
//print "<br>".round (t_end-t_start, 1)." secondes";
foreach($tab_idx as $key=>$value){
	if ($value!=""){
		$search_query=true;
		if ($key=="p31"){//case of subclasses of types
			switch ($value){
				case "3305213":
					$where="(p31.qwd=3305213 OR p31.qwd=134194 OR p31.qwd=219423 OR p31.qwd=2026188)";
					break;
				case "1278452":
					$where="(p31.qwd=1278452 OR p31.qwd=79218 OR p31.qwd=475476)";
					break;
				case "860861":
					$where="(p31.qwd=860861 OR p31.qwd=14562306 OR p31.qwd=179700 OR p31.qwd=241045)";
					break;
				case "179700":
					$where="(p31.qwd=179700 OR p31.qwd=241045)";
					break;
				default:
					$where="p31.qwd=".$value;
			}
		}
		else
			$where=$key.".qwd=".$value;
		$sql_s="select distinct artworks.id as id 
		from ".$key.", artw_prop, artworks WHERE ".$where." AND artw_prop.id_prop=".$key.".id AND artw_prop.prop=".str_replace("p","",$key)." AND artworks.id=artw_prop.id_artw";
		if ($mode==0) $sql_s.=" AND P18<>''";
		$rep_s=mysql_query($sql_s);
		$new_s="";
		while($data_s = mysql_fetch_assoc($rep_s)) {
			if ($prim_query){
				$res_s[]=$data_s['id'];
				if ($new_s!="")
						$new_s.=";";
					$new_s.=$data_s['id'];
			}
			else
				if (in_array ($data_s['id'],$res_s)){
					if ($new_s!="")
						$new_s.=";";
					$new_s.=$data_s['id'];
				}
		}
		unset($res_s);
		$res_s=array();
		if ($new_s!="")
			$res_s=explode(";",$new_s);
		else
			break;
		if ($prim_query)
			$prim_query=false;
	}
}
foreach($tab_miss as $key=>$value){
	if ($value!=""){
		$check_query=true;
		break;
	}
}
foreach($tab_check as $key=>$value){
	if ($value!=""){
		$check_query=true;
		break;
	}
}
if ($check_query){
	$sql_c="";
	foreach($tab_check as $key=>$value){
		if ($value!=""){
			if (($key!="c1")){
				$key=str_replace("c","m", $key);
				if ($sql_c!="")
					$sql_c.=" AND";
				$sql_c.=" $key=0";
			}
			else{
				if ($sql_c!="")
					$sql_c.=" AND";
				$sql_c.=" $l=0";
			}
		}
	}
	foreach($tab_miss as $key=>$value){
		if ($value!=""){
			if (($key!="m1")){
				if ($sql_c!="")
					$sql_c.=" AND";
				$sql_c.=" $key=1";
			}
			else{
				if ($sql_c!="")
					$sql_c.=" AND";
				$sql_c.=" $l=1";
			}
		}
	}
	$sql_c="SELECT ident as id FROM missing WHERE ".$sql_c;
	$rep_s=mysql_query($sql_c);
	$new_s="";
	while($data_s = mysql_fetch_assoc($rep_s)) {
		if ($prim_query){
			$res_s[]=$data_s['id'];
			if ($new_s!="")
					$new_s.=";";
				$new_s.=$data_s['id'];
		}
		else
			if (in_array ($data_s['id'],$res_s)){
				if ($new_s!="")
					$new_s.=";";
				$new_s.=$data_s['id'];
			}
	}
	unset($res_s);
	$res_s=array();
	if ($new_s!="")
		$res_s=explode(";",$new_s);
	if ($prim_query)
		$prim_query=false;
}
	
if ($search_query){
	if (count($res_s)>0){
		$sql="SELECT * from artworks WHERE ";
		for ($i=0;$i<count	($res_s);$i++){
			if ($i!=0)
				$sql.=" OR ";
			$sql.="id=".$res_s[$i];
		}
		if ($mode==0) $sql.=" AND P18<>''";
	}
	else
		$sql="SELECT * from artworks WHERE id=0";
}


if ($sql!="")
	$sql.=" ORDER BY ISNULL(year1), year1";
else
	if (isset($_GET['p'])){
		if ($_GET['p']!=""){
			$sql="SELECT * from artworks";
			if ($mode==0) $sql.="  WHERE P18<>'' AND year1 IS NOT NULL";
			else {
				if ($check_query){
					$sql.="  WHERE ";
					for ($i=0;$i<count	($res_s);$i++){
						if ($i!=0)
							$sql.=" OR ";
						$sql.="id=".$res_s[$i];
					}
				}
			}
			$sql.=" ORDER BY ISNULL(year1), year1";
		}
		else
			$random=true;
	}
	else
		$random=true;

if ($random){
	$sql="SELECT * from artworks";
	if ($mode==0) $sql.="  WHERE P18<>''";
	else {
		if ($check_query){
			$sql.="  WHERE ";
			for ($i=0;$i<count	($res_s);$i++){
				if ($i!=0)
					$sql.=" OR ";
				$sql.="id=".$res_s[$i];
			}
		}
	}
	$sql.=" ORDER BY RAND() LIMIT 0,$nb  ";
	$num_rows =$nb;
}
else {
	$repnb=mysql_query($sql);
	$num_rows = mysql_num_rows($repnb);
	$sql.=" LIMIT ".$deb.", ".$nb;
}
$rep=mysql_query($sql);
$num_rows_ec = mysql_num_rows($rep);
?>