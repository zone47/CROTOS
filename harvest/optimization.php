<?php
/* / */
/* Search optimization */
echo "\nOptimization";
include $file_timer_begin;

$link = mysqli_connect ($host,$user,$pass,$db) or die ('Erreur : '.mysqli_error());
$optimiz = array(
	"p31|3305213",
	"p31|860861",
	"p195|190804",
	"p276|190804",
	"p195|23402",
	"p276|23402"
);

foreach ($optimiz as $item){	
	$prop_val=explode("|",$item);
	$prop=$prop_val[0];
	$value=$prop_val[1];
	
	if ($prop=="p31"){
		$where="($prop.qwd=$value";
		include $fold_crotos."subclasses/".$value.".php";
		for ($i=0;$i<count($tab279);$i++)
			$where.=" OR $prop.qwd=".$tab279[$i];
		$where.=")";
	}
	else
		$where="$prop.qwd=$value";
	
	$sql="select distinct artworks.id as id 
	from $prop, artw_prop, artworks WHERE ".$where." AND artw_prop.id_prop=$prop.id AND artw_prop.prop=".str_replace("p","",$prop)." AND artworks.id=artw_prop.id_artw";
	$rep=mysqli_query($link,$sql);

	$cpt=0;
	while ($data = mysqli_fetch_assoc($rep)){
		$sql="UPDATE artworks SET opt".$prop."_".$value."=1 WHERE id=".$data['id'];
		$update=mysqli_query($link,$sql);
		$cpt++;
	}
	echo "\n $prop $cpt";
}
mysqli_close($link);

echo "\nOptimization done";
include $file_timer_end;
?>