<?php
/* / */
/* Search for subs P279 and part of P361 for indexes */
echo "\nSubs and parts of";
include $file_timer_begin;

$link = mysqli_connect ($host,$user,$pass,$db) or die ('Erreur : '.mysqli_error());
$tab_props=array(31,135,136,144,170,179,180,186,195,276,921,941);
for ($i=0;$i<count($tab_props);$i++){
//for ($i=0;$i<1;$i++){
	$prop=$tab_props[$i];
	$sql="SELECT id, qwd, P18 from p$prop";
	$rep=mysqli_query($link,$sql);
	$sub_search=true;
	//$cpt=0;
	while($data = mysqli_fetch_assoc($rep)) {
		/*$cpt++;
		if ($cpt==11)
			break;*/
		$id_prop=$data['id'];
		$qwd=$data['qwd'];
		if (($prop==31)||($prop==136)||($prop==186))
			$req="http://wdq.wmflabs.org/api?q=claim[279:%28tree[".$qwd."][][279]%29]";
		elseif (($prop==195)&&($qwd==3044747))
			$req="http://wdq.wmflabs.org/api?q=claim[361:%28tree[".$qwd."][][361]%29]";
		/*elseif ($prop==276)
			$req="http://wdq.wmflabs.org/api?q=claim[276:%28tree[".$qwd."][][276]%29,361:%28tree[".$qwd."][][361]%29]";*/
		elseif (($prop==135)||($prop==144)||($prop==180)||($prop==921)||($prop==941))
			$req="http://wdq.wmflabs.org/api?q=claim[279:%28tree[".$qwd."][][279]%29,361:%28tree[".$qwd."][][361]%29]";
		else 
			$sub_search=false;
		$sub_query="";
		if ($sub_search){
			$res = request($req);
			$responseArray = json_decode($res,true);
			foreach ($responseArray["items"] as $key => $value){
				$sql="SELECT id from p$prop WHERE qwd=".$value;
				$rep2=mysqli_query($link,$sql);
				if (mysqli_num_rows($rep2)>0){
					$row = mysqli_fetch_assoc($rep2);
					$id_sub=$row['id'];	
					$rep2=mysqli_query($link,"INSERT INTO prop_sub (prop,id_prop,id_sub) VALUES (".$prop.",".$id_prop.",".$id_sub.") ");
					$sub_query.=" OR id_prop=".$id_sub;
				}
			}
		}
		//Pour chaque on fait recherche de 279 ou 461 selon la propriété
		//on le lie à la propriété (test si trop long on ne lie pas)
		$sql="SELECT count(distinct id_artw) as total from artw_prop  WHERE prop=$prop and (id_prop=".$id_prop.$sub_query.")";
		$rep2=mysqli_query($link,$sql);
		$data2=mysqli_fetch_assoc($rep2);
		$nbartworks=$data2['total'];
		
		$sql="SELECT count(distinct artworks.id) as total from artworks, artw_prop  WHERE artworks.id=artw_prop.id_artw and  artworks.P18!=0 and artw_prop.prop=$prop and (id_prop=".$id_prop.$sub_query.")";
		$rep2=mysqli_query($link,$sql);
		$data2=mysqli_fetch_assoc($rep2);
		$nbimg=$data2['total'];
		
		$sql="UPDATE p$prop SET nb=".$nbartworks.", nbimg=".$nbimg." WHERE id=".$id_prop;
		mysqli_query($link,$sql);
		
		
	}
}
mysqli_close($link);

echo "\nSubs and parts of done";
include $file_timer_end;
?>