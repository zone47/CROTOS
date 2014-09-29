<?php
/* / */
/* Search optimization */

$link = mysql_connect ($host,$user,$pass) or die ('Erreur : '.mysql_error());
mysql_select_db($db) or die ('Erreur :'.mysql_error());
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
	$rep=mysql_query($sql);

	$cpt=0;
	while ($data = mysql_fetch_assoc($rep)){
		$sql="UPDATE artworks SET opt".$prop."_".$value."=1 WHERE id=".$data['id'];
		$update=mysql_query($sql);
		$cpt++;
	}
	echo "\n $prop $cpt";
}
echo "\noptimization done";

?>