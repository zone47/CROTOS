 <title><?php
foreach($tab_idx as $key=>$value)
	if ($value!="")
		echo txt_prop(0,$value,$l,"normal",0,0)." - ";
if ($q!=""){
	$title="";
	$reptitle=mysqli_query($link,$sql);
	while($titledata = mysqli_fetch_assoc($reptitle)){
		$qwd_art=$titledata['qwd'];
		$id_artw=$titledata['id'];
		$title=label_item($qwd_art,$l);
		$creator=txt_prop($id_artw,170,$l,"normal",false,false);
		if (($title!="")&&($creator!=""))
			$title.=" - ";
		$title.=$creator;
		echo $title;
		echo " - ";
		
	}
}
?>Palladia</title>
	<meta name="description" content="Palladia<?php
foreach($tab_idx as $key=>$value)
	if ($value!="")
		echo " - ".txt_prop(0,$value,$l,"normal",0,0);
if ($title!=""){
	echo " - ".esc_dblq($title);
}
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