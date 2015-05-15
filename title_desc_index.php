 <title><?php
foreach($tab_idx as $key=>$value)
	if ($value!="")
		echo txt_prop(0,$value,$l,"normal",0,0)." - ";
?>Crotos</title>
	<meta name="description" content="Crotos<?php
foreach($tab_idx as $key=>$value)
	if ($value!="")
		echo " - ".txt_prop(0,$value,$l,"normal",0,0);
$txt_res="";
if ($num_rows<2)
	$txt_res.=$num_rows." ".mb_ucfirst(translate($l,"result"));
elseif ($num_rows<=$nb)
	$txt_res.=$num_rows_ec." ".mb_ucfirst(translate($l,"results"));
else {
	if ($rand_sel)
		$txt_res.=$num_rows." ".mb_ucfirst(translate($l,"results"));
	else
		$txt_res.=mb_ucfirst(translate($l,"results"))." ".($deb+1)." - ".($deb+$num_rows_ec)." ".translate($l,"of")." ".$num_rows;
}
echo " - ".$txt_res;
?>" />