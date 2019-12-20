<?php
$deb=($p-1)*$nb;
if ($deb<0)
	$deb=0;
	
$sql="SELECT id, qwd, P18, nb, nbimg from p$prop WHERE qwd!=0 ";
switch ($prop){
	case 31:
		$tab_excl=array(15222213,4502142,386724,18593264,838948,478798,618123,10657813,7725634,811979,179700,210272,193893,815241,1640824,811430,15792060,2160811,28886448,13397636,27096235,49848,20937557,8205328,16686448,684740,21029893,570116,47461344,19828370,7328910);
		break;
	case 180:
		$tab_excl=array(729,215627,6581072,6581097,2424752);
		break;
	case 195:
		$tab_excl=array(666063);
		break;
	default:$tab_excl=array();
}
for ($i=0;$i<count($tab_excl);$i++)
	$sql.=" AND qwd!=".$tab_excl[$i];
//if ($prop==195)
//	$sql.=" AND level=0";
$sql.=" AND nbimg>=".$n;
$rep=mysqli_query($link,$sql);
$num_rows=mysqli_num_rows($rep);
if ($rand_sel)
	//$sql.=" ORDER BY RAND() LIMIT 0,$nb ";
	//$sql.=" AND id IN (".gen_id_rand("p$prop",$n,$nb).") LIMIT 0,$nb ";
	$sql.=" AND id IN (".gen_id_rand_sql("p$prop",$nb,$n).") LIMIT 0,$nb ";
else
	$sql.=" ORDER BY nbimg DESC LIMIT ".$deb.", ".$nb;
	//echo $sql;
$rep=mysqli_query($link,$sql);
$num_rows_ec = mysqli_num_rows($rep);

$nbpg=ceil($num_rows/$nb); // number of pages

// navigation link
$liennav="&amp;f=".$prop;

?>