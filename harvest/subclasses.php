<?php
/* / */
/* Harvest sublasses */
//$cmd="rm -f ".$fold_crotos."subclasses/*";
$cmd="del /Q ".$fold_crotos."subclassses/*.*";

$link = mysql_connect ($host,$user,$pass) or die ('Erreur : '.mysql_error());
mysql_select_db($db) or die ('Erreur :'.mysql_error());
$sql="select qwd from p31";
$rep=mysql_query($sql);
while($data = mysql_fetch_assoc($rep)) {
	$p31=$data['qwd'];
	$req="http://wdq.wmflabs.org/api?q=claim[279:%28tree[".$p31."][][279]%29]";
	$res = request($req);
	$responseArray = json_decode($res,true);
	$data="<?php \$tab279=array(";
	foreach ($responseArray["items"] as $key => $value){
		if ($data!="<?php \$tab279=array(")
			$data.=",";
		$data.=$value;
	}
	$data.="); ?>";
	$fic = fopen($fold_crotos."subclassses/".$p31.".php", 'w');
	fputs($fic ,$data);
	fclose($fic );
}
echo "\nsubclasses done";

?>