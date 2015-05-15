<?php
if ($num_rows>1)
	echo "<div id=\"contenu\" class=\"yoxview\" >";
else
	echo "<div id=\"contenu\" class=\"yoxview contentsolo\" >";	
?>
<?php
$cpt=0;
while($data = mysqli_fetch_assoc($rep)) {
	$content="";
	$cpt++;
	$id_artw=$data['id'];
	$qwd_art=$data['qwd'];
	$inv=$data['P217'];
	$described_link=$data['link'];
	$titre="";
	$titre=label_item($qwd_art,$l);
	$trunc_title=truncate($titre);
	$trunk=false;
	if ($titre!=$trunc_title)
		$trunk=true;
	$alias=alias_item($qwd_art,$l);
	$creator=txt_prop($id_artw,170,$l,"creator",false);
	$pageWP=page_item($qwd_art,$l);
	$lgWP="";
	$pos=strpos($pageWP,"|");
	if ($pos){
		$lgWP=substr($pageWP,0,$pos);
		$pageWP=substr($pageWP,$pos+1,strlen($pageWP));
	}
	
	$coll0=val_0($id_artw,195,$l);
	$location0=val_0($id_artw,275,$l);

	$coll_or_loc=$coll0;
	if ($coll0=="")
		$coll_or_loc=$location0;
	
	$location=txt_prop($id_artw,276,$l);
	$collection=txt_prop($id_artw,195,$l);
	
	$loc_link=local_link($id_artw,195,$l);
	if ($loc_link=="")
		$loc_link=local_link($id_artw,276,$l);
	
	$type=txt_prop($id_artw,31,$l,"normal",0);
	$material=txt_prop($id_artw,186,$l);
	$series=txt_prop($id_artw,179,$l);
	$partof=txt_prop($id_artw,361,$l);
	$mouvement=txt_prop($id_artw,135,$l);
	$genre=txt_prop($id_artw,136,$l);
	$depicts=txt_prop($id_artw,180,$l);
	$based=txt_prop($id_artw,144,$l);
	$subject=txt_prop($id_artw,921,$l);
	$inspired=txt_prop($id_artw,941,$l);
	$pendant=txt_prop($id_artw,1639,$l);
	
	$p18=$data['P18'];
	
	$commons_artist="";
	$commons_credit="";
	$license="";
	$thumb="";
	$thumb_h="";
	$width_h=0;
	$large="";
	if ($p18!=0){
		$sql="select * from commons_img where id=".$p18;
		$rep18=mysqli_query($link,$sql);
		if (mysqli_num_rows($rep18)!=0){
			$data_p18 = mysqli_fetch_assoc($rep18);
			$p18_str=$data_p18['P18'];
			$commons_artist=$data_p18['commons_artist'];
			$commons_credit=$data_p18['commons_credit'];
			$license=$data_p18['commons_license'];
			$thumb_h=$data_p18['thumb_h'];
			$width_h=$data_p18['width_h'];
			$large=$data_p18['large'];
			// Hack to move to compilation
			if ((substr ($thumb_h,-3)=="tif")||(substr ($thumb_h,-3)=="iff")){
				$width=$data_p18['width'];
				$thumb_h=str_replace("tif/","tif/lossy-page1-",$thumb_h).".jpg";
				$large=str_replace($width_h,$width,$thumb_h);
			}
		}
	}
	
	if (intval($width_h)<201)
		$width_item=202;
	else
		$width_item=intval($width_h)+2;	
	
	if ($num_rows>1){
		$content.="	<div style=\"width:".$width_item."px\" class=\"item\" data-width=\"".$width_item."px\" >\n";
	}
	else{ 
		if ($large!=""){
			$width_big_img=0;
			
			if (strpos($large,"commons/thumb")){
				preg_match('#/[0-9]*px-#',$large,$matches);
				if ($matches)
					$width_big_img=intval(str_replace("/","",str_replace("px-","",$matches[0])));
			}
			else{
				$sql="select width from commons_img where id=".$p18;
				$rep_img=mysqli_query($link,$sql);
				$num_rows = mysqli_num_rows($rep_img);
				if ($num_rows!=0){
					$data_img = mysqli_fetch_assoc($rep_img);
					$width_big_img=intval($data_img['width']);
				}
				
			}
			if ($width_big_img<348)
				$width_big_img=348;
			$width_big_img+=2;	

			$content.="	<div class=\"item solo\" style=\"width:100%;max-width:".$width_big_img."px;\" data-width=\"".$width_item."px\">\n";
		}
		else{
			$content.="	<div class=\"item solo\" style=\"width:".$width_item."px\" data-width=\"".$width_item."px\">\n";
		}
	}
		
	if ($num_rows>1)
		$content.="		<div class=\"thumb multiimg\"><div>";
	else
		$content.="		<div class=\"thumb soloimg\"><div>";
	if ($thumb_h!=""){
		if ($license!=""){
			if ($license=="pd")
				$license=ucfirst(translate($l,"pd"));
			else{
				switch ($license){
					case "cc0":
						$license="<a href=\"http://creativecommons.org/publicdomain/zero/1.0/\" class=\"externe\"><b>cc0</b></a>";
						break;
					case "GFDL-1.2":
						$license="<a href=\"https://commons.wikimedia.org/wiki/Commons:GNU_Free_Documentation_License,_version_1.2\" class=\"externe\"><b>GFDL-1.2</b></a>";
						break;
					case "FAL":
						$license="<a href=\"https://commons.wikimedia.org/wiki/Commons:Free_Art_License_1.3\" class=\"externe\"><b>Free Art License</b></a>";
						break;
					default:
						if ((substr($license,3,5)=="by-sa")||(substr($license,3,5)=="by-nc")){
							if (strlen($license)==12)
								$license="<a href=\"http://creativecommons.org/licenses/".substr($license,3,5)."/".substr($license,9,3)."/deed.".$l."\" class=\"externe\"><b>".$license."</b></a>";
							elseif (strlen($license)==15)
								$license="<a href=\"http://creativecommons.org/licenses/".substr($license,3,5)."/".substr($license,9,3)."/".substr($license,13,2)."/deed.".$l."\" class=\"externe\"><b>".$license."</b></a>";
						}
						elseif ((substr($license,3,2)=="by")||(substr($license,3,2)=="sa")){
							if (strlen($license)==9)
								$license="<a href=\"http://creativecommons.org/licenses/".substr($license,3,2)."/".substr($license,6,3)."/deed.".$l."\" class=\"externe\"><b>".$license."</b></a>";
							elseif (strlen($license)==12)
								$license="<a href=\"http://creativecommons.org/licenses/".substr($license,3,2)."/".substr($license,6,3)."/".substr($license,10,2)."/deed.".$l."\" class=\"externe\"><b>".$license."</b></a>";
						}
				}
			}
			$license=esc_dblq(htmlentities($license));
		}
		$commons_artist = esc_dblq(htmlentities(preg_replace("/<\/?table[^>]*\>/i", "",preg_replace("/<\/?div[^>]*\>/i", "",$commons_artist))));
		$commons_link="http://commons.wikimedia.org/wiki/File:".htmlentities(str_replace("?","%3F",str_replace(" ","_",$p18_str)));
		$commons_credit = esc_dblq(htmlentities(preg_replace("/<img[^>]+\>/i", "",preg_replace("/<\/?td[^>]*\>/i", "",preg_replace("/<\/?tr[^>]*\>/i", "",preg_replace("/<\/?table[^>]*\>/i", "",preg_replace("/<\/?li[^>]*\>/i", "", preg_replace("/<\/?ul[^>]*\>/i", "", preg_replace("/<\/?hr[^>]*\>/i", "", preg_replace("/<\/?p[^>]*\>/i", "", preg_replace("/<\/?div[^>]*\>/i", "", $commons_credit)))))))))));
		$credits=$commons_artist;
		if (($credits!="")&&($license!=""))
			$credits.=" | ";
		$credits.=$license;
		if (($credits!="")&&($commons_credit!=""))
			$credits.=" | ";
		$credits.=$commons_credit;
		if ($num_rows>1)
			$content.="<a href=\"".$commons_link."\" data-file=\"".esc_dblq($large)."\" data-commons=\"".$commons_link."\" class=\"yox\" id=\"link$cpt\"><img src=\"".esc_dblq($thumb_h)."\" alt=\"".esc_dblq($titre)."\" data-img=\"".esc_dblq($thumb_h)."\" data-credit=\"&lt;b&gt;".esc_dblq($titre)."&lt;/b&gt;&lt;br /&gt;".$credits."\"/></a>";
		else
			$content.="<a href=\"".$commons_link."\" data-file=\"".esc_dblq($large)."\" data-commons=\"".$commons_link."\" class=\"linksolo\" id=\"link$cpt\"><img src=\"".esc_dblq($large)."\" alt=\"".esc_dblq($titre)."\" data-img=\"".esc_dblq($thumb_h)."\" data-credit=\"&lt;b&gt;".esc_dblq($titre)."&lt;/b&gt;&lt;br /&gt;".$credits."\"/></a>";
	}
	else{
		if ($disp==0)
			$content.="<img src=\"img/no_image2.png\" alt=\"\" width=\"200\" height=\"240\">";
		else
			$content.="<img src=\"img/no_image_day.png\" alt=\"\" width=\"200\" height=\"240\" class=\"no_img\">";
	}
	$content.="\n		</div></div>";
		
	$content.="\n		<div class=\"cartel\">";

	
	$content.="\n			<div class=\"entete\"><span>".$trunc_title."</span>";
	if ($creator!="")
		$content.="<br/>".$creator;
	if ($coll_or_loc!=""){
		if ($creator!="")
			$content.=" - ";
		else
			$content.="<br />";
		$content.=$coll_or_loc;
	}
	$content.="\n			</div>";	
	
	$content.="\n			<div class=\"btn_notice\">";
	if ($disp==0)
		$content.="<img id=\"iconot$cpt\" src=\"img/arrow_down.png\" alt=\"notice\" class=\"lien_notice\">\n";
	else
		$content.="<img id=\"iconot$cpt\" src=\"img/arrow_down_day.png\" alt=\"notice\" class=\"lien_notice\">\n";
	$content.="			</div>";

	$content.="\n			<div class=\"act_not\">";
	$uri_link="https://www.wikidata.org/wiki/Q".$qwd_art;
	$content.="<a href=\"".$uri_link."\" title=\"".translate($l,"Wikidata")."\">";
	if ($disp==0)
		$content.="<img src=\"img/wd_ico.png\" alt=\"\"/>";
	else
		$content.="<img src=\"img/wd_ico_day.png\" alt=\"\"/>";
	$content.="</a>";
	if ($thumb_h!=""){
		if ($disp==0)
			$content.="	<a href=\"".$commons_link."\" title=\"".translate($l,"Commons")."\"><img src=\"img/commons_ico.png\" alt=\"\"/></a>";
		else
			$content.="	<a href=\"".$commons_link."\" title=\"".translate($l,"Commons")."\"><img src=\"img/commons_ico_day.png\" alt=\"\"/></a>";
	}
	if ($described_link!="")
		$content.="	<a href=\"".$described_link."\" title=\"".translate($l,"973")."\"><img src=\"img/site_link.png\" alt=\"\"/></a>";
	if ($pageWP!=""){
		$content.=" <a href=\"https://";
		if ($lgWP!="")
			$content.=$lgWP;
		else 
			$content.=$l;
		$content.=".wikipedia.org/wiki/".str_replace(" ","_",str_replace("\"","",$pageWP))."\">";
		if ($disp==0)
			$content.="<img src=\"img/wp_ico.png\" alt=\"\" /></a>";
		else
			$content.="<img src=\"img/wp_ico_day.png\" alt=\"\" /></a>";
		
		if ($lgWP!="")
			$content.=" <a href=\"https://".$lgWP.".wikipedia.org/wiki/".str_replace(" ","_",str_replace("\"","",$pageWP))."\" class=\"lgWP\" title=\"".translate($l,"Wikipedia")."\">".$lgWP."</a>";
	}

	$content.="\n			</div>";
	$content.="\n			<div id=\"notice$cpt\" class=\"notice\">";
	
	if ($trunk!="")
		$content.="\n<p><b>".$titre."</b></p>";	
	if ($alias!="")
		$content.="\n<p><span class=\"libelle\">".translate($l,"alias")."</span>&nbsp;:<br/>".$alias."</p>";	
		
	if ((!(is_null($data['year2'])))||($data['year1']!=$data['year2'])){
		$content.="\n<p class=\"start_cartel\">";
		if ($data['b_date']==1)
			$content.="~&nbsp;&nbsp;";
		if (!(is_null($data['year1'])))
			$content.=$data['year1'];
		if ((!(is_null($data['year2'])))&&($data['year1']!=$data['year2']))
			$content.=" / ".$data['year2'];
		$content.="</p>";
		$content.="<p>";
	}
	else
		$content.="\n<p class=\"start_cartel\">";

	if ($pendant!="")
		$content.="\n<p>".$pendant."</p>";		
	$content.=$type."</p>";
	if ($material!="")
		$content.="\n<p>".$material."</p>";
	if ($inv!="")
		$content.="\n<p><span class=\"libelle\">".translate($l,"217")."</span>&nbsp;: ".$inv."</p>";
	if ($collection!="")
		$content.="\n<p>".$collection."</p>";
	if ($series!="")
		$content.="\n<p>".$series."</p>";
	if ($partof!="")
		$content.="\n<p>".$partof."</p>";
	if ($location!="")
		$content.="\n<p>".$location."</p>";
		
	if ($mouvement!="")
		$content.="\n<p>".$mouvement."</p>";
	if ($genre!="")
		$content.="\n<p>".$genre."</p>";
	if ($based!="")
		$content.="\n<p>".$based."</p>";
	if ($subject!="")
		$content.="\n<p>".$subject."</p>";
	if ($inspired!="")
		$content.="\n<p>".$inspired."</p>";
	if ($depicts!="")
		$content.="\n<p>".$depicts."</p>";
	
	$content.="\n				<div class=\"liens\">";
	if ($loc_link!="")
		$content.="\n<p>".$loc_link."</p>";
	if ($data['P727']!="")
		$content.="<p><a href=\"http://europeana.eu/portal/record/".$data['P727'].".html\"><img src=\"img/europeana.png\" alt=\"Europeana\"/></a> <a href=\"http://europeana.eu/portal/record/".$data['P727'].".html\" class=\"externe\">".translate($l,"Europeana")."</a></p>";
	if ($data['P214']!="")
		$content.="<p><a href=\"http://viaf.org/viaf/".$data['P214']."/\"><img src=\"img/viaf.png\" alt=\"VIAF\"/></a> <a href=\"http://viaf.org/viaf/".$data['P214']."/\" class=\"externe\">".translate($l,"VIAF")."</a></p>";
	if ($data['P350']!="")
		$content.="<p><a href=\"https://rkd.nl/nl/explore/images/".$data['P350']."\"><img src=\"img/rkd.png\" alt=\"RKD Images\"/></a> <a href=\"https://rkd.nl/nl/explore/images/".$data['P350']."\" class=\"externe\">".translate($l,"RKDimages")."</a></p>";
	if ($data['P347']!="")
		$content.="<p><a href=\"http://www.culture.gouv.fr/public/mistral/joconde_fr?ACTION=CHERCHER&amp;FIELD_1=REF&amp;VALUE_1=".$data['P347']."\"><img src=\"img/joconde.png\" alt=\"Joconde\"/></a> <a href=\"http://www.culture.gouv.fr/public/mistral/joconde_fr?ACTION=CHERCHER&amp;FIELD_1=REF&amp;VALUE_1=".$data['P347']."\" class=\"externe\">".translate($l,"Joconde")."</a></p>";
	if ($data['P1212']!="")
		$content.="<p><a href=\"http://cartelfr.louvre.fr/cartelfr/visite?srv=car_not_frame&idNotice=".$data['P1212']."\"><img src=\"img/atlas.png\" alt=\"ATLAS\"/></a> <a href=\"http://cartelfr.louvre.fr/cartelfr/visite?srv=car_not_frame&idNotice=".$data['P1212']."\" class=\"externe\">".translate($l,"Atlas")."</a></p>";
	if ($data['P373']!="")
		$content.="<p><a href=\"https://commons.wikimedia.org/wiki/Category:".str_replace(" ","_",$data['P373'])."\"><img src=\"img/commons.png\" alt=\"Commons\"/></a> <a href=\"https://commons.wikimedia.org/wiki/Category:".str_replace(" ","_",$data['P373'])."\" class=\"externe\">".translate($l,"CommonsCat")."</a></p>";

	$url="http://tools.wmflabs.org/reasonator/?lang=".$l."&amp;q=".$qwd_art;	
	$content.="\n<p> <a href=\"".$url."\"><img src=\"img/reasonator.png\" alt=\"Reasonator\"/></a> <a href=\"".$url."\" class=\"externe\">".translate($l,"reasonator")."</a></p>";
	
	$url="http://zone47.com/crotos/?q=".$qwd_art;	
	$content.="\n<p> <a href=\"".$url."\"><img src=\"img/crotos.png\" alt=\"CROTOS\"/></a> <a href=\"".$url."\">crotos/?q=".$qwd_art."</a></p>";

	$content.="\n				</div>";
	if (($num_rows==1)&&($credits!="")){
		$content.="\n				<div class=\"img_info\">";
		$content.="\n					<div class=\"credit_img\">";
		$content.="\n					<a href=\"".$commons_link."\"><img src=\"img/commons_gray.png\" alt=\"\"></a>";
		$content.="\n					</div>";
		$content.="\n					<div class=\"credit_txt\">";
		$content.=html_entity_decode($credits);
		$content.="\n					</div>";
		
		$content.="\n				</div>";
	}
	
	$content.="\n			</div>";

	$content.="\n		</div>";
	$content.="\n	</div>";
	if ($num_rows>1)
		$content.="\n	<script>document.getElementById('notice$cpt').style.display = 'none';</script>\n";
	
	echo $content;	
}

?>
</div>