<?php
$deb=($p-1)*$nb;
if ($deb<0)
	$deb=0;
	
$sql="SELECT id, qwd, P18, nb, nbimg from p$prop WHERE qwd!=0 ";
switch ($prop){
	case 31:
		$tab_excl=array(386724,18593264,838948,478798,618123,10657813,7725634,811979,179700,210272,193893,815241,1640824);
		break;
	case 180:
		$tab_excl=array(729,215627,6581072,6581097,2424752);
		break;
	case 195:
		$tab_excl=array(3034552,3591574,700216,812285);
		break;
	default:$tab_excl=array();
}
for ($i=0;$i<count($tab_excl);$i++)
	$sql.=" AND qwd!=".$tab_excl[$i];
if ($prop==195)
	$sql.=" AND level=0";
$sql.=" AND nbimg>=".$n;
$rep=mysqli_query($link,$sql);
$num_rows=mysqli_num_rows($rep);
if ($rand_sel)
	$sql.=" ORDER BY RAND() LIMIT 0,$nb ";
else
	$sql.=" ORDER BY nbimg DESC LIMIT ".$deb.", ".$nb;
$rep=mysqli_query($link,$sql);
$num_rows_ec = mysqli_num_rows($rep);

$nbpg=ceil($num_rows/$nb); // number of pages

// navigation link
$liennav="&amp;f=".$prop;

?>