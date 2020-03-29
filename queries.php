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
	$stopwords=array("a", "an", "and", "in", "of", "on", "or", "so", "the", "to", "up","à","au", "de", "de", "des", "du", "en", "et", "la", "le", "les", "ou","un","une");
	for ($i=0;$i<count($tab_keywords);$i++){
		for ($j=0;$j<count($stopwords);$j++){
			if ($tab_keywords[$i]==$stopwords[$j])
				array_splice($tab_keywords,$i,1);
			
		}
	}
	for ($i=0;$i<count($tab_keywords);$i++){
		if (($tab_keywords[$i]!="")&&(strlen($tab_keywords[$i])>2)){
			$search_query=true;
			$sql_s="
			(
			SELECT distinct artworks.id as id
			FROM label_page, artw_prop, artworks
			WHERE label_page.prop !=1
			AND label_page.label LIKE \"%".$tab_keywords[$i]."%\"
			AND label_page.id_art_or_prop = artw_prop.id_prop
			AND label_page.prop = artw_prop.prop
			AND artw_prop.id_artw = artworks.id";
			if ($mode==0) $sql_s.=" AND artworks.P18!=0";
			$sql_s.=")
			UNION (
			
			SELECT distinct artworks.id as id
			FROM label_page, artworks
			WHERE label_page.label LIKE \"%".$tab_keywords[$i]."%\"
			AND label_page.id_art_or_prop = artworks.id
			AND label_page.prop =1";
			if ($mode==0) $sql_s.=" AND artworks.P18!=0";
			$sql_s.=")";

			$rep_s=mysqli_query($link,$sql_s);
			$new_s="";
			while($data_s = mysqli_fetch_assoc($rep_s)) {
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
$optimiz=array();
foreach($tab_idx as $key=>$value){
	if ($value!=""){
		//Optimization
		$optimization=false;
		if (($key=="p31")&&($value=="3305213")){
			$optimiz[]="p31_3305213";
			$optimization=true;
		}
		if (($key=="p31")&&($value=="860861")){
			$optimiz[]="p31_860861";
			$optimization=true;
		}
		if (($key=="p195")&&($value=="23402")){
			$optimiz[]="p195_23402";
			$optimization=true;
		}
		if (($key=="p276")&&($value=="23402")){
			$optimiz[]="p276_23402";
			$optimization=true;
		}
		if (($key=="p195")&&($value=="190804")){
			$optimiz[]="p195_190804";
			$optimization=true;
		}
		if (($key=="p276")&&($value=="190804")){
			$optimiz[]="p276_190804";
			$optimization=true;
		}
		if (!$optimization){
			$search_query=true;
			$where="(".$key.".qwd=".$value;

			$sql_sub="SELECT id_sub FROM prop_sub, ".$key." WHERE prop_sub.prop=".str_replace("p","",$key)." AND prop_sub.id_prop=".$key.".id AND ".$key.".qwd=".$value;
			$rep_sub=mysqli_query($link,$sql_sub);
			while($data = mysqli_fetch_assoc($rep_sub))
				$where.=" OR ".$key.".id=".$data['id_sub'];
			$where.=")";
			
			$sql_s="select distinct artworks.id as id 
			from ".$key.", artw_prop, artworks WHERE ".$where." AND artw_prop.id_prop=".$key.".id AND artw_prop.prop=".str_replace("p","",$key)." AND artworks.id=artw_prop.id_artw";
			if ($mode==0) $sql_s.=" AND artworks.P18!=0";
			$rep_s=mysqli_query($link,$sql_s);
			$new_s="";
			while($data_s = mysqli_fetch_assoc($rep_s)) {
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
}
$optimization=false;
if (count($optimiz)>0)
	$optimization=true;
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
$search_date=false;
if (!(($y1==-40000)&&($y2==2016)))
	$search_date=true;
$search_publi=false;
if ($d!=0)
	$search_publi=true;
if (($search_query)||($optimization)||($search_date)||($search_publi)||($check_query)){
	if (($search_query)&&(!(count($res_s)>0)))
		$sql="SELECT * from artworks WHERE id=0";
	else{
		$sql="SELECT * from artworks WHERE ";
		$sql_c="";
		if (count($res_s)>0)
			$sql_c.=" id IN ( ";
		for ($i=0;$i<count	($res_s);$i++){
			/*if ($sql_c!="")
				$sql_c.=" OR ";
			else
				$sql_c.="(";
			$sql_c.="id=".$res_s[$i];*/
			if ($i!=0)
				$sql_c.=",";
			$sql_c.=$res_s[$i];
		}
		if (count($res_s)>0)
			$sql_c.=")";
		/*if ($sql_c!="")
			$sql_c.=")";*/
		for ($i=0;$i<count($optimiz);$i++){
			if ($sql_c!="")
				$sql_c.=" AND ";
			$sql_c.=" opt".$optimiz[$i]."=1";
		}
		if ($search_date){
			if ($sql_c!="")
				$sql_c.=" AND ";
			$sql_c.=" year1>=$y1 AND year2<=$y2 ";
		}
		if ($search_publi){
			if ($sql_c!="")
				$sql_c.=" AND ";
			$sql_c.="(crea=$d OR img=$d) ";
		}
		if ($check_query){
			foreach($tab_check as $key=>$value){
				if ($value!=""){
					switch($key){
						case "c1":
							if ($sql_c!="") $sql_c.=" AND";
							$sql_c.=" lb$l=0";
							break;
						case "c2":
							if ($sql_c!="") $sql_c.=" AND";
							$sql_c.=" hd=1";
							break;
						case "c571":
							if ($sql_c!="") $sql_c.=" AND";
							$sql_c.=" year1 IS NOT NULL";
							break;
						case "c18":
						case "c214":
						case "c217":
						case "c347":
						case "c350":
						case "c373":
						case "c727":
						case "c973":
						case "c1212":
							$key=str_replace("c","P", $key);
							if ($sql_c!="") $sql_c.=" AND";
							$sql_c.=" $key!=''";
							break;
						default:
							$key=str_replace("c","m", $key);
							if ($sql_c!="") $sql_c.=" AND";
							$sql_c.=" $key=0";
					}
				}
			}
			foreach($tab_miss as $key=>$value){
				if ($value!=""){
					switch($key){
						case "m1":
							if ($sql_c!="") $sql_c.=" AND";
							$sql_c.=" lb$l=1";
							break;
						case "m571":
							if ($sql_c!="") $sql_c.=" AND";
							$sql_c.=" year1 IS NULL";
							break;
						case "m18":
						case "m214":
						case "m217":
						case "m347":
						case "m350":
						case "m373":
						case "m727":
						case "m973":
						case "m1212":
							$key=str_replace("m","P", $key);
							if ($sql_c!="") $sql_c.=" AND";
							$sql_c.=" $key=''";
							break;
						default:
							if ($sql_c!="") $sql_c.=" AND";
							$sql_c.=" $key=1";
					}
				}
			}
		}
		$sql.=$sql_c;
		if ($mode==0) $sql.=" AND artworks.P18!=0";
	}
}
//echo $sql;
if ($sql!=""){
	$repnb=mysqli_query($link,$sql);
	$num_rows = mysqli_num_rows($repnb);
	if ($num_rows<=$nb)
		$rand_sel=false;
	if ($rand_sel)
		$sql.=" ORDER BY RAND() LIMIT 0,$nb  ";
		//$sql.=" AND id IN (".gen_id_rand($nb).")";
	else
		$sql.=" ORDER BY ISNULL(year1), year1";
}
else
	if (isset($_GET['p'])){
		if ($_GET['p']!=""){
			$sql="SELECT * from artworks";
			if ($mode==0) $sql.="  WHERE artworks.P18!=0 AND year1 IS NOT NULL";
			else {
				if ($check_query){
					/*$sql.="  WHERE ";
					for ($i=0;$i<count	($res_s);$i++){
						if ($i!=0)
							$sql.=" OR ";
						$sql.="id=".$res_s[$i];
					}*/
					$sql.=" WHERE id IN ( ";
					for ($i=0;$i<count($res_s);$i++){
						if ($i!=0)
							$sql.=",";
						$sql.=$res_s[$i];
					}
					$sql.=" ) ";
				}
			}
			$sql.=" ORDER BY ISNULL(year1), year1";
			$repnb=mysqli_query($link,$sql);
			$num_rows = mysqli_num_rows($repnb);
		}
		else
			$random=true;
	}
	else
		$random=true;
if ($q!=""){
	$random=false;
	$sql="SELECT * from artworks WHERE qwd=$q";
}
if ($random){
	/*$sql="SELECT * from artworks";
	if ($mode==0)
		$sql.="  WHERE artworks.P18!=0";
	else {
		if ($check_query){
			$sql.="  WHERE ";
			for ($i=0;$i<count	($res_s);$i++){
				if ($i!=0)
					$sql.=" OR ";
				$sql.="id=".$res_s[$i];
			}
		}
	}*/
	$sql="SELECT * from artworks WHERE artworks.P18!=0 ";
	if ($check_query){
		/*$sql.=" AND ( ";
		for ($i=0;$i<count($res_s);$i++){
			if ($i!=0)
				$sql.=" OR ";
			$sql.="id=".$res_s[$i];
		}*/
		$sql.=" AND id IN ( ";
		for ($i=0;$i<count($res_s);$i++){
			if ($i!=0)
				$sql.=",";
			$sql.=$res_s[$i];
		}
		$sql.=" ) ";
	}
	$sql.=" AND id IN (".gen_id_rand("artworks",$nb).")";
	//$sql.=" ORDER BY RAND() LIMIT 0,$nb  ";
	$num_rows =$nb;
}
else {
	if ((!$rand_sel)&&($num_rows>$nb))
		$sql.=" LIMIT ".$deb.", ".$nb;
}
//echo "<!-- $sql -->";
$rep=mysqli_query($link,$sql);
$num_rows_ec = mysqli_num_rows($rep);
?>