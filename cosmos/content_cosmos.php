<div id="contenu">
<?php
while($data = mysqli_fetch_assoc($rep)) {
	$id_prop=$data['id'];
	$nbartworks=$data['nb'];
	$nbimg=$data['nbimg'];
	$qwd=$data['qwd'];
	$lb=label_item($qwd,$l);
	$where="(artw_prop.id_prop=".$id_prop;

	$sql_sub="SELECT id_sub FROM prop_sub WHERE prop=".$prop." AND id_prop=".$id_prop;
	$rep_sub=mysqli_query($link,$sql_sub);
	while($data_sub = mysqli_fetch_assoc($rep_sub))
		$where.=" OR artw_prop.id_prop=".$data_sub['id_sub'];
	$where.=")";
	$sql="select artworks.qwd, commons_img.P18,commons_img.width,commons_img.height from artw_prop,artworks, commons_img WHERE ".$where." AND artw_prop.prop=".$prop." AND artw_prop.id_artw=artworks.id AND artworks.P18=commons_img.id";
	$rep_img=mysqli_query($link,$sql." AND commons_img.height>239 AND commons_img.width>319 order by rand() limit 1");
	$nb_img= mysqli_num_rows($rep_img);
	$img="http://www.zone47.com/crotos/img/no_image2.png";
	$qwd_img="";
	if ($nb_img!=0)
		$data_img = mysqli_fetch_assoc($rep_img);
	else{
		$rep_img=mysqli_query($link,$sql." order by rand() limit 1");
		$nb_img= mysqli_num_rows($rep_img);
		if ($nb_img!=0)
			$data_img = mysqli_fetch_assoc($rep_img);
	}
	if ($nb_img!=0){	
		$qwd_img=$data_img['qwd'];
		$img=str_replace(" ","_",$data_img['P18']);
		$digest = md5($img);
		$folder = $digest[0] . '/' . $digest[0] . $digest[1] . '/' . urlencode($img);
		//$w_thumb=floor(intval($data2['width'])/intval($data2['height'])*$h_thumb);
		$h_thumb=240;
		$w_thumb=floor(intval($data_img['width'])/intval($data_img['height'])*$h_thumb);
		if ($w_thumb<350)
			$w_thumb=350;
		$img="http://upload.wikimedia.org/wikipedia/commons/thumb/" . $folder."/".$w_thumb."px-". urlencode($img);
		if ($w_thumb>=$data_img['width'])			
			$img="http://upload.wikimedia.org/wikipedia/commons/" . $folder;
	}
	$link_facet="../?p$prop=$qwd";
	if ($mode==1)
		$link_facet.= "&c18=1";
	$facet="<div onClick=\"window.location.href='".$link_facet."'\" class=\"facet item\" style=\"background-image:url('".$img."')\"><div class=\"bandeau\"><div><a href=\"".$link_facet."\" class=\"link_facet\">$lb</a><br/><span class=\"nbimg\">$nbimg <span class=\"nbres\">";
	if ($nbimg<2)
		$facet.=translate($l,"result");
	else
		$facet.=translate($l,"results");
	$facet.="</span></span></div></div>";
	if ($qwd_img!="")
		$facet.="<a href=\"../?q=".$qwd_img."\" class=\"link_qwd\">Q".$qwd_img."</a>";
	$facet.="</div>\n";
	echo $facet;
}
?>
</div>