<?php

if ($num_rows>1)
	$multi_res=true;
else
	$multi_res=false;
if ($multi_res)
	echo "<div id=\"contenu\" class=\"yoxview\" >";
else
	echo "<div id=\"contenu\" class=\"yoxview contentsolo\" >";	

$cpt=0;
while($data = mysqli_fetch_assoc($rep)) {
	$content="";
	$cpt++;
	$id_artw=$data['id'];
	$qwd_art=$data['qwd'];
	$hd=$data['hd'];
	$inv=$data['P217'];
	$described_link=$data['link'];
	$link2=$data['link2'];
	/* Dwynwen 
	if ($described_link==""){
		if ($data['P727']!="")
			$described_link="https://europeana.eu/portal/record/".$data['P727'].".html";
		if ($data['P350']!="")
			$described_link="https://rkd.nl/nl/explore/images/".$data['P350'];
		if ($data['P2108']!="")
			$described_link="https://www.kulturarv.dk/kid/VisVaerk.do?vaerkId=".$data['P2108'];
		if ($data['P347']!="")
			$described_link="http://www.culture.gouv.fr/public/mistral/joconde_fr?ACTION=CHERCHER&amp;FIELD_1=REF&amp;VALUE_1=".$data['P347'];
		if ($data['P1212']!="")
			$described_link="http://cartelfr.louvre.fr/cartelfr/visite?srv=car_not_frame&idNotice=".$data['P1212'];
	}*/
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
	
	
	
	// Dwynwen
	//$location0=val_0($id_artw,276,$l);
	$location0="";
	$location="";

	//$location=txt_prop($id_artw,276,$l);
	if (test_coll($id_artw,195)){
		$collection=txt_prop_dwynwen($id_artw,195,$l);
		$coll0=val_0_dwynwen($id_artw,195,$l);
	}
	else{
		$collection="";
		$coll0="";
	}
	$coll_or_loc=$coll0;
	
	// Dwynwen	
	$loc_link="";
	/*$loc_link=local_link($id_artw,195,$l);
	if ($loc_link=="")
		$loc_link=local_link($id_artw,276,$l);*/
	

	
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
	$publi=txt_prop($id_artw,1433,$l);
	$exhibition=txt_prop($id_artw,608,$l);
	$dimensions = array(
		"length"=> dimension($data['P2043'],2043,$l),
		"height"=> dimension($data['P2048'],2048,$l),
		"width"=> dimension($data['P2049'],2049,$l),
		"diameter"=> dimension($data['P2386'],2386,$l),
		"depth"=> dimension($data['P2610'],2610,$l)
	);
	$status=txt_prop($id_artw,6216,$l,"normal",1,0);
	//echo "+".$status;
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
			$width=$data_p18['width'];
			$height=$data_p18['height'];
			// Hack to move to compilation
			if ((substr ($thumb_h,-3)=="tif")||(substr ($thumb_h,-3)=="iff")){
				$thumb_h=str_replace("tif/","tif/lossy-page1-",$thumb_h).".jpg";
				$large=str_replace($width_h,$width,$thumb_h);
			}
		}
	}
	
	if (intval($width_h)<201)
		$width_item=202;
	else
		$width_item=intval($width_h)+2;	
	
	if ($multi_res){
		$content.="	<div style=\"width:".$width_item."px\" class=\"item\" data-width=\"".$width_item."px\" id=\"item".$cpt."\">\n";
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
		
	// Cartel
	$cartel="\n		<div class=\"cartel\">";
	
	$cartel.="\n			<div class=\"entete\"><span>".$trunc_title."</span>";
	$yox_cartel="<b>".$titre."</b>";
	if ($creator!=""){
		$cartel.="<br/>".$creator;
		$yox_cartel.=", ".$creator;
	}
	$date="";
	if ((!(is_null($data['year2'])))||($data['year1']!=$data['year2'])){
		// tmp; pb avec circa et after+1
		$date1=intval($data['year1']);
		$date2=intval($data['year2']);
		if (($date2-$date1)==1)
			$date.="~".$data['year1'];
		else{
			if ($data['b_date']==1)
				$date.="~";
			if (!(is_null($data['year1'])))
				$date.=$data['year1'];
			if ((!(is_null($data['year2'])))&&($data['year1']!=$data['year2'])) 
				$date.="-".$data['year2'];
		}
	}
	if ($date!="")
		$yox_cartel.=", ".$date;
	
	if ($coll_or_loc!=""){
		if ($creator!="")
			$cartel.=" - ";
		else
			$cartel.="<br />";
		$yox_cartel.=" - ".$coll_or_loc;	
		if ($inv!="")
			$yox_cartel.=" <span class=\"yox_inv\"> (".$inv.")</span>";
		$cartel.=$coll_or_loc;
	}

	$cartel.="\n			</div>";	
	
	$cartel.="\n			<div class=\"btn_notice\">";
	if ($disp==0)
		$cartel.="<img id=\"iconot$cpt\" src=\"../img/arrow_down.png\" alt=\"notice\" class=\"lien_notice\" onclick=\"disp_notice(this)\">\n";
	else
		$cartel.="<img id=\"iconot$cpt\" src=\"../img/arrow_down_day.png\" alt=\"notice\" class=\"lien_notice\" onclick=\"disp_notice(this)\">\n";
	$cartel.="			</div>";

	$cartel.="\n			<div class=\"act_not\">";
	$uri_link="https://www.wikidata.org/wiki/Q".$qwd_art;
	$cartel.="<a href=\"".$uri_link."\" title=\"".translate($l,"Wikidata")."\">";
	if ($disp==0)
		$cartel.="<img src=\"../img/wd_ico.png\" alt=\"\"/>";
	else
		$cartel.="<img src=\"../img/wd_ico_day.png\" alt=\"\"/>";
	$cartel.="</a>";
	$yox_links="<div class=\"yox_links\"><a href=\"".$uri_link."\" title=\"".translate($l,"Wikidata")."\"><img src=\"../img/wd_ico.png\" alt=\"\"/></a>";
	
	$commons_link="";
	if ($thumb_h!=""){
		$commons_link="https://commons.wikimedia.org/wiki/File:".htmlentities(str_replace("?","%3F",str_replace(" ","_",$p18_str)), ENT_QUOTES, "UTF-8");
		if ($disp==0)
			$cartel.="	<a href=\"".$commons_link."\" title=\"".translate($l,"Commons")." – ".$width." × ".$height."&nbsp;".translate($l,"px")."\"><img src=\"../img/commons_ico.png\" alt=\"\"/></a>";
		else
			$cartel.="	<a href=\"".$commons_link."\" title=\"".translate($l,"Commons")." – ".$width." × ".$height."&nbsp;".translate($l,"px")."\"><img src=\"../img/commons_ico_day.png\" alt=\"\"/></a>";
		if ($hd==1){
			$hd_link="https://tools.wmflabs.org/zoomviewer/index.php?f=".htmlentities(str_replace("?","%3F",str_replace(" ","_",$p18_str)), ENT_QUOTES, "UTF-8");
			if ($disp==0)
				$cartel.="	<a href=\"".$hd_link."\" target=\"_blank\" title=\"Zoom HD\"><img src=\"../img/magnifying_ico.png\" alt=\"\"/></a>";
			else
				$cartel.="	<a href=\"".$hd_link."\" target=\"_blank\" title=\"Zoom HD\"><img src=\"../img/magnifying_ico_day.png\" alt=\"\"/></a>";
			$yox_links.=" <a href=\"".$hd_link."\" title=\"Zoom HD\" target=\"_blank\"><img src=\"../img/magnifying_ico.png\" alt=\"\"/></a>";
		}
	}
	else{
		$dp=test_dp($id_artw);
		if (!$dp){
			$cartel.="	<img src=\"../img/no_commons_ico.png\" ";
			if ($l=="fr")
				$cartel.="alt=\"A priori, pas dans le domaine public\" title=\"A priori, pas dans le domaine public\"";
			else
				$cartel.="alt=\"A priori, not in Public Domain\" title=\"A priori, not in Public Domain\"";
			$cartel.="/>";
		}
	}
	if ($described_link!=""){
		$cartel.=" <a href=\"".$described_link."\" title=\"".translate($l,"973")."\"><img src=\"../img/site_link.png\" alt=\"\"/></a>";
		$yox_links.=" <a href=\"".$described_link."\" title=\"".translate($l,"973")."\"><img src=\"../img/site_link.png\" alt=\"\"/></a>";
	}
	
	if ($pageWP!=""){
		$pageWP_link=" <a href=\"https://";
		if ($lgWP!="")
			$pageWP_link.=$lgWP;
		else 
			$pageWP_link.=$l;
		$pageWP_link.=".wikipedia.org/wiki/".str_replace(" ","_",str_replace("\"","",$pageWP))."\"  title=\"".translate($l,"Wikipedia")."\">";
		$yox_links.=$pageWP_link;
		if ($disp==0)
			$pageWP_link.="<img src=\"../img/wp_ico.png\" alt=\"\" /></a>";
		else
			$pageWP_link.="<img src=\"../img/wp_ico_day.png\" alt=\"\" /></a>";
		$yox_links.="<img src=\"../img/wp_ico.png\" alt=\"\" /></a>";
		
		if ($lgWP!=""){
			$pageWP_link.=" <a href=\"https://".$lgWP.".wikipedia.org/wiki/".str_replace(" ","_",str_replace("\"","",$pageWP))."\" class=\"lgWP\" title=\"".translate($l,"Wikipedia")."\">".$lgWP."</a>";
			$yox_links.=" <a href=\"https://".$lgWP.".wikipedia.org/wiki/".str_replace(" ","_",str_replace("\"","",$pageWP))."\" class=\"lgWP\" title=\"".translate($l,"Wikipedia")."\">".$lgWP."</a>";
		}
		$cartel.=$pageWP_link;

	}
	$url="/crotos/dwynwen/?q=".$qwd_art;	
	/*if ($disp==0)
		$cartel.="\n <a href=\"".$url."\" title=\"Crotos\"><img src=\"img/dwynwen_ico.png\" alt=\"Crotos\" title=\"Crotos\"/></a>";
	else
		$cartel.="\n <a href=\"".$url."\" title=\"Crotos\"><img src=\"img/dwynwen_ico.png\" alt=\"Crotos\" title=\"Crotos\"/></a>";*/
	
	$cartel_links="\n				<div class=\"liens\">";
	if ($described_link!=""){
		$cartel_links.="<p> <a href=\"".$described_link."\" title=\"".translate($l,"973")."\"><img src=\"img/llgc-nlw-24.png\" alt=\"\"/></a> <a href=\"".$described_link."\" title=\"Llyfrgell Genedlaethol Cymru - National Library of Wales\" class=\"externe\">LLGC-NLW</a></p>";
		$yox_links.=" <a href=\"".$described_link."\" title=\"Llyfrgell Genedlaethol Cymru - National Library of Wales\"><img src=\"img/llgc-nlw-22.png\" alt=\"\"/></a>";
	}
	if ($link2!="")
		$cartel_links.="<p> <a href=\"".$link2."\" title=\"".translate($l,"973")."\">".$link2."</a></p>";
	if ($loc_link!="")
		$cartel_links.="\n<p>".$loc_link."</p>";
	if ($data['P727']!=""){
		$cartel_links.="<p><a href=\"http://europeana.eu/portal/record/".$data['P727'].".html\"><img src=\"../img/europeana.png\" alt=\"Europeana\"/></a> <a href=\"http://europeana.eu/portal/record/".$data['P727'].".html\" class=\"externe\">".translate($l,"Europeana")."</a></p>";
		$yox_links.=" <a href=\"https://europeana.eu/portal/record/".$data['P727'].".html\" title=\"".translate($l,"Europeana")."\"><img src=\"../img/europeana_ico.png\" alt=\"Europeana\"/></a>";
	}
	if ($data['P214']!=""){
		$cartel_links.="<p><a href=\"http://viaf.org/viaf/".$data['P214']."/\"><img src=\"../img/viaf.png\" alt=\"VIAF\"/></a> <a href=\"http://viaf.org/viaf/".$data['P214']."/\" class=\"externe\">".translate($l,"VIAF")."</a></p>";
		$yox_links.=" <a href=\"http://viaf.org/viaf/".$data['P214']."/\" title=\"".translate($l,"VIAF")."\"><img src=\"../img/viaf_ico.png\" alt=\"VIAF\"/></a>";
	}
	if ($data['P350']!=""){
		$cartel_links.="<p><a href=\"https://rkd.nl/nl/explore/images/".$data['P350']."\"><img src=\"../img/rkd.png\" alt=\"RKD Images\"/></a> <a href=\"https://rkd.nl/nl/explore/images/".$data['P350']."\" class=\"externe\">".translate($l,"RKDimages")."</a></p>";
		$yox_links.=" <a href=\"https://rkd.nl/nl/explore/images/".$data['P350']."\" title=\"".translate($l,"RKDimages")."\"><img src=\"../img/rkd_ico.png\" alt=\"RKD Images\"/></a>";
	}
	if ($data['P347']!=""){
		$cartel_links.="<p><a href=\"http://www.culture.gouv.fr/public/mistral/joconde_fr?ACTION=CHERCHER&amp;FIELD_1=REF&amp;VALUE_1=".$data['P347']."\"><img src=\"../img/joconde.png\" alt=\"Joconde\"/></a> <a href=\"http://www.culture.gouv.fr/public/mistral/joconde_fr?ACTION=CHERCHER&amp;FIELD_1=REF&amp;VALUE_1=".$data['P347']."\" class=\"externe\">".translate($l,"Joconde")."</a></p>";
		$yox_links.=" <a href=\"http://www.culture.gouv.fr/public/mistral/joconde_fr?ACTION=CHERCHER&amp;FIELD_1=REF&amp;VALUE_1=".$data['P347']."\" title=\"".translate($l,"Joconde")."\"><img src=\"../img/joconde_ico.png\" alt=\"Joconde\"/></a>";
	}
	if ($data['P1212']!=""){
		$cartel_links.="<p><a href=\"http://cartelfr.louvre.fr/cartelfr/visite?srv=car_not_frame&idNotice=".$data['P1212']."\"><img src=\"../img/atlas.png\" alt=\"ATLAS\"/></a> <a href=\"http://cartelfr.louvre.fr/cartelfr/visite?srv=car_not_frame&idNotice=".$data['P1212']."\" class=\"externe\">".translate($l,"Atlas")."</a></p>";
		$yox_links.=" <a href=\"http://cartelfr.louvre.fr/cartelfr/visite?srv=car_not_frame&idNotice=".$data['P1212']."\" title=\"".translate($l,"Atlas")."\"><img src=\"../img/atlas_ico.png\" alt=\"ATLAS\"/></a>";
	}
	if ($data['P2108']!=""){
		$cartel_links.="<p><a href=\"https://www.kulturarv.dk/kid/VisVaerk.do?vaerkId=".$data['P2108']."\"><img src=\"../img/kid_ico.png\" alt=\"KID\"/></a> <a href=\"https://www.kulturarv.dk/kid/VisVaerk.do?vaerkId=".$data['P2108']."\" class=\"externe\">".translate($l,"KID")."</a></p>";
		$yox_links.=" <a href=\"https://www.kulturarv.dk/kid/VisVaerk.do?vaerkId=".$data['P2108']."\" title=\"".translate($l,"KID")."\"><img src=\"../img/kid_ico_small.png\" alt=\"KID\"/></a>";
	}
	if ($data['P373']!=""){
		$cartel_links.="<p><a href=\"https://commons.wikimedia.org/wiki/Category:".str_replace(" ","_",$data['P373'])."\"><img src=\"../img/commons.png\" alt=\"Commons\"/></a> <a href=\"https://commons.wikimedia.org/wiki/Category:".str_replace(" ","_",$data['P373'])."\" class=\"externe\">".translate($l,"CommonsCat")."</a></p>";
		$yox_links.=" <a href=\"https://commons.wikimedia.org/wiki/Category:".str_replace(" ","_",$data['P373'])."\" title=\"".translate($l,"CommonsCat")."\"><img src=\"../img/commons_ico.png\" alt=\"Commons\"/></a>";
	}

	$url="http://tools.wmflabs.org/reasonator/?lang=".$l."&amp;q=".$qwd_art;	
	$cartel_links.="\n<p> <a href=\"".$url."\"><img src=\"../img/reasonator.png\" alt=\"Reasonator\"/></a> <a href=\"".$url."\" class=\"externe\">".translate($l,"reasonator")."</a></p>";
	
	$url="/crotos/dwynwen/?q=".$qwd_art; 
	//$cartel_links.="\n<p> <a href=\"".$url."\"><img src=\"img/dwynwen.png\" alt=\"Dwynwen\"/></a> <a href=\"".$url."\">dwynwen/?q=".$qwd_art."</a></p>";
	//$yox_links.=" <a href=\"".$url."\" title=\"Crotos URL\"><img src=\"img/dwynwen_ico.png\" alt=\"Dwynwen\"/></a>";
	
	$cartel_links.="\n				</div>";
	
	$yox_links.="</div>";
	$cartel.="\n			</div>";
	$cartel.="\n			<div id=\"notice$cpt\" class=\"notice\">";
	$yox_cartel.="<div id=\"yox_cartel\">".$yox_links;
	//$yox_cartel.="<span style=\"font-size:0.95em\"\>";
	if ($trunk!="")
		$cartel.="\n<p><b>".$titre."</b></p>";	
	if ($alias!=""){
		$cartel.="\n<p><span class=\"libelle\">".translate($l,"alias")."</span>&nbsp;:<br/>".$alias."</p>";	
		$yox_cartel.="<span class=\"libelle\">".translate($l,"alias")."</span>&nbsp;: ".$alias."&nbsp;&nbsp;|&nbsp;&nbsp;";
	}

	$cartel.="\n<p class=\"start_cartel\">";
			
	if ($date!=""){
		$cartel.=$date;
		$cartel.="</p>";
		$cartel.="<p>";
	}
	
	if ($pendant!=""){
		$cartel.="\n<p>".$pendant."</p>";		
		$yox_cartel.=$pendant."&nbsp;&nbsp;|&nbsp;&nbsp;";
	}
	$cartel.=$type."</p>";
	$yox_cartel.=$type;
	if ($material!="")
		$cartel.="\n<p>".$material."</p>";
	$txtdim="";
	foreach($dimensions as $dim){
		if ($dim!=""){
			if ($txtdim !="")
				$txtdim.=" – ";
			$txtdim.=$dim;
		}
	}
	if ($txtdim!="")
		$cartel.="\n<p>".$txtdim."</p>";
	if ($txtdim!="")
		$yox_cartel.="&nbsp;&nbsp;|&nbsp;&nbsp;".$txtdim;
	if ($inv!="")
		$cartel.="\n<p><span class=\"libelle\">".translate($l,"217")."</span>&nbsp;: ".$inv."</p>";
	if ($collection!="")
		$cartel.="\n<p>".$collection."</p>";
	if ($series!=""){
		$cartel.="\n<p>".$series."</p>";
		$yox_cartel.="&nbsp;&nbsp;|&nbsp;&nbsp;".$series;
	}
	if ($partof!=""){
		$cartel.="\n<p>".$partof."</p>";
		$yox_cartel.="&nbsp;&nbsp;|&nbsp;&nbsp;".$partof;
	}
	if ($location!="")
		$cartel.="\n<p>".$location."</p>";
	if ($publi!="")
		$cartel.="\n<p>".$publi."</p>";
	if ($status!="")
		$cartel.="\n<p>".$status."</p>";	
		
	if ($exhibition!=""){
		$cartel.="\n<p>".$exhibition."</p>";		
		$yox_cartel.="&nbsp;&nbsp;|&nbsp;&nbsp;".$exhibition;
	}	
	if ($mouvement!=""){
		$cartel.="\n<p>".$mouvement."</p>";
		$yox_cartel.="&nbsp;&nbsp;|&nbsp;&nbsp;".$mouvement;
	}
	if ($genre!=""){
		$cartel.="\n<p>".$genre."</p>";
		$yox_cartel.="&nbsp;&nbsp;|&nbsp;&nbsp;".$genre;
	}
	if ($based!=""){
		$cartel.="\n<p>".$based."</p>";
		$yox_cartel.="&nbsp;&nbsp;|&nbsp;&nbsp;".$based;
	}
	if ($subject!=""){
		$cartel.="\n<p>".$subject."</p>";
		$yox_cartel.="&nbsp;&nbsp;|&nbsp;&nbsp;".$subject;
	}
	if ($inspired!=""){
		$cartel.="\n<p>".$inspired."</p>";
		$yox_cartel.="&nbsp;&nbsp;|&nbsp;&nbsp;".$inspired;
	}
	if ($depicts!=""){
		$cartel.="\n<p>".$depicts."</p>";
		$yox_cartel.="&nbsp;&nbsp;|&nbsp;&nbsp;".$depicts;
	}
	$yox_cartel.="</span></div>";
	
	$cartel.=$cartel_links;
	
	if ($multi_res)
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
			//echo "<!-- $license -->";
			$li=$license;
			$license = esc_dblq(htmlentities($li, ENT_QUOTES, "UTF-8"));
		}
		//echo "<!-- $commons_artist-->";
		/*$ca=preg_replace("/<p[^>]+\>/i","",preg_replace("/<\/?img[^>]*\>/i", "",preg_replace("/<\/?ul[^>]*\>/i", "",preg_replace("/<\/?li[^>]*\>/i", "",preg_replace("/<\/?table[^>]*\>/i", "",preg_replace("/<\/?div[^>]*\>/i", "",$commons_artist))))));*/
		$cf="<a href=\"".$commons_link."\" title=\"".translate($l,"Commons")." – ".$width." × ".$height."&nbsp;".translate($l,"px")."\" class=\"commons_link\">".translate($l,"Commons")." – ".$width." × ".$height."&nbsp;".translate($l,"px")."</a><br/>";
		$ca=del_html($commons_artist);
		$cc=del_html($commons_credit);

		$cred=$cf.$ca;
		if (($ca!="")&&($li!=""))
			$cred.="&nbsp;&nbsp;|&nbsp;&nbsp;";
		$cred.=$li;
		if ((($ca!="")&&($cc!=""))||(($li!="")&&($cc!="")))
			$cred.="&nbsp;&nbsp;|&nbsp;&nbsp;";
		$cred.=$cc;
		
		$commons_file = esc_dblq(htmlentities($cf, ENT_QUOTES, "UTF-8"));
		$commons_artist = esc_dblq(htmlentities($ca, ENT_QUOTES, "UTF-8"));
		$commons_credit = esc_dblq(htmlentities($cc, ENT_QUOTES, "UTF-8"));
				
		$credits=$commons_file.$commons_artist;
		if (($commons_artist!="")&&($license!=""))
			$credits.="&nbsp;&nbsp;|&nbsp;&nbsp;";
		$credits.=$license;
		if ((($credits!="")&&($commons_credit!=""))||(($license!="")&&($commons_credit!="")))
			$credits.="&nbsp;&nbsp;|&nbsp;&nbsp;";
		$credits.=$commons_credit;
		if ($multi_res)
			$content.="<a href=\"".$commons_link."\" data-file=\"".esc_dblq($large)."\" data-commons=\"".$commons_link."\" class=\"yox\" id=\"link$cpt\" onclick=\"return wait();\"><img src=\"".esc_dblq($thumb_h)."\" alt=\"".esc_dblq($titre)."\" data-img=\"".esc_dblq($thumb_h)."\" data-credit=\"".$credits."\" data-notice=\"".esc_dblq(htmlentities($yox_cartel, ENT_QUOTES, "UTF-8"))."\"/></a>";
		else
			$content.="<a href=\"".$commons_link."\" data-commons=\"".$commons_link."\" class=\"linksolo\" id=\"link$cpt\" onclick=\"return wait();\"><img src=\"".esc_dblq($large)."\" alt=\"".esc_dblq($titre)."\" data-img=\"".esc_dblq($thumb_h)."\" data-credit=\"".$credits."\" data-notice=\"".esc_dblq(htmlentities($yox_cartel, ENT_QUOTES, "UTF-8"))."\"/></a>";
	}
	else{
		if ($disp==0)
			$content.="<img src=\"../img/no_image2.png\" alt=\"\" width=\"200\" height=\"240\">";
		else
			$content.="<img src=\"../img/no_image_day.png\" alt=\"\" width=\"200\" height=\"240\" class=\"no_img\">";
	}
	$content.="\n		</div></div>";
	
	//echo "++++$num_rows++++++$credits+++";
	if ((!$multi_res)&&($credits!="")){
		
		$cartel.="\n				<div class=\"img_info\">";
		$cartel.="\n					<div class=\"credit_img\">";
		$cartel.="\n					<a href=\"".$commons_link."\"><img src=\"../img/commons_gray.png\" alt=\"\"></a>";
		$cartel.="\n					</div>";
		$cartel.="\n					<div class=\"credit_txt\">";
		$cartel.=$cred;
		//$cartel.=html_entity_decode(str_replace("&nbsp;"," ",$credits));
		$cartel.="\n					</div>";
		
		$cartel.="\n				</div>";
	}
	
	$cartel.="\n			</div>";
	$cartel.="\n		</div>";
	// Cartel - end
		
	$content.=$cartel;
	
	$content.="\n	</div>";
	if ($multi_res)
		/* //$content.="\n	<script>document.getElementById('notice$cpt').style.display = 'none';</script>\n"; */
        $content.="\n	<script>initdiv($cpt);</script>\n";
	
	echo $content;	
}

?>
</div>