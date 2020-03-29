<div id="facets">
<?php
if (($y1=="1000")&&($y2=="2020"))		
	echo "   		<div class=\"mode_plus\">";
?>
   		<div id="slider">
            <input type="text" id="amount1" value="<?php echo $y1; ?>" data-index="0" class="sliderValue" name="y1" />
            <div id="slider-range"></div>
            <input type="text" id="amount2" value="<?php echo $y2; ?>" data-index="1" class="sliderValue"  name="y2" />
            <input type="submit" id="ok2" value=" " />
        </div>
<?php
if (($y1=="1000")&&($y2=="2020"))		
	echo "</div>";
?>
<?php
if ($tab_idx["p31"]=="")	
	//echo "   		 <select name=\"p31\" id=\"listp31\" class=\"mode_plus\" onChange=\"document.getElementById('form').submit()\" style=\"visibility:hidden\">";
	echo "   		 <select name=\"p31\" id=\"listp31\" class=\"mode_plus\" onChange=\"document.getElementById('form').submit()\">";
else 
	echo "   		 <select name=\"p31\" id=\"listp31\" onChange=\"document.getElementById('form').submit()\">";
	
?>
    		<option value="" id="tout"><?php echo ucfirst(translate($l,"everything")) ?></option>
<?php 
$p31_list=array("860861","738680","41207","1135567","48634");
//$p31_list=array();
if (($tab_idx["p31"]!="")&&(!(in_array($tab_idx["p31"],$p31_list))))
	echo "    		<option value=\"".$tab_idx["p31"]."\" selected>".ucfirst(label_item($tab_idx["p31"],$l))."</option>\n";
for ($i=0;$i<count($p31_list);$i++){
	$option="    		<option value=\"".$p31_list[$i]."\"";
	if (($tab_idx["p31"]!="")&&($tab_idx["p31"]==$p31_list[$i]))
		$option.=" selected";
	echo $option.">".ucfirst(label_item($p31_list[$i],$l))."</option>\n";
}
?>
		</select>
<?php
$txt_crit="";
foreach($tab_idx as $key=>$value)
	if (($value!="")&&($key!="p31")){
		$txt_crit.="<label for=\"$key\"><span class=\"libelle_criteres\">".translate($l,str_replace("p","",$key))." :</span></label> ";
		$txt_crit.=txt_prop(0,$value,$l,"normal",0,0);
		$txt_crit.="		<input type=\"checkbox\" value=\"$value\" name=\"$key\" id=\"$key\" class=\"crit_sel\" checked=\"checked\"  onChange=\"document.getElementById('form').submit()\"/>";
	}
if ($q!="")
	$txt_crit.="<span class=\"libelle_criteres\">".translate($l,"Wikidata")." :</span> <a href=\"https://www.wikidata.org/wiki/Q".$q."\" class=\"externe\">Q".$q."</a>";
	
if ($txt_crit!="")
	echo "<span class=\"criteres\" >".$txt_crit."</span>";
?>
    </div>
<?php
if ($random){
	if ($disp==0)
		$day="";
	else
		$day="_day";
	$tabalea=array(24520194,24580187,24582095,24628970,24662746,25216237,27717675,27862648,28108996,28545044,29555789,38697897,55655902,55655931,56605689,59576582,62615267);

	$alea_rand = $tabalea[array_rand($tabalea, 1)];
	$sql="SELECT artworks.qwd,commons_img.thumb,commons_img.height,width,commons_img.width FROM artworks, commons_img WHERE artworks.qwd=". $alea_rand." AND commons_img.id=artworks.P18";
	$rep_pres=mysqli_query($link,$sql);
	$data = mysqli_fetch_assoc($rep_pres);
	$new_w=round($data['width']/$data['height']*100);
	$img1=str_replace("/200px","/".$new_w."px",$data['thumb']);	
	$qwd1=$data['qwd'];
	$bl_width=$new_w;
	if ($bl_width<100)
		$bl_width=100;
	?>
<style>
#pres_img{
	left:<?php echo intval($new_w)+19 ?>px;
}
#presentation p{
	margin-left:<?php echo intval($new_w)+19 ?>px;
}
#presentation .pres_detail{
	margin-left:<?php echo intval($new_w)+28 ?>px;
}
</style>
    <?php
	echo "<div id=\"presentation\">";
	echo "<div id=\"pres_img\"><a href=\"/crotos/palladia/?q=".$qwd1."\" id=\"art_pres\"><img src=\"".$img1."\" ></a></div>";
	if ($l=="fr")
		echo "<div id=\"pres_txt\"><p style=\"margin-top:1px;margin-bottom:4px\"><a href=\"/crotos/palladia/\"><b>Palladia</b></a> est un moteur de recherche et d'affichage d'une partie des collections du <a href=\"https://www.saintraymond.toulouse.fr\"><b>Musée Saint-Raymond</b></a> s'appuyant sur <a href=\"https://www.wikidata.org\"><b>Wikidata</b></a> <img src=\"/crotos/img/wd_ico".$day.".png\" alt=\"\" /> et  <a href=\"https://commons.wikimedia.org/\"><b>Wikimedia Commons</b></a><img src=\"/crotos/img/commons_ico".$day.".png\" alt=\"\" /></p>";
	elseif ($l=="mu")
		echo "<div id=\"pres_txt\"><p style=\"margin-top:1px;margin-bottom:4px\"><a href=\"/crotos/palladia/\"><b>Houba</b></a> Houba Houba Houba Houba Houba Houba Houba'Houba Houba Houba <a href=\"https://www.saintraymond.toulouse.fr\"><b>Houba Houba Houba Houba Houba Houba</b></a> Houba'Houba Houba'Houba Houba'Houba Houba <a href=\"https://www.wikidata.org\"><b>Houba</b></a> <img src=\"/crotos/img/wd_ico".$day.".png\" alt=\"\" /> et  <a href=\"https://commons.wikimedia.org/\"><b>Houba Houba</b></a><img src=\"/crotos/img/commons_ico".$day.".png\" alt=\"\" /></p>";
	else
		echo "<div id=\"pres_txt\"><p style=\"margin-top:1px;margin-bottom:4px\"><a href=\"/crotos/palladia/\"><b>Palladia</b></a> is a search and display engine for a part of the collections of the <a href=\"https://www.saintraymond.toulouse.fr\"><b>Musée Saint-Raymond</b></a> powered by <a href=\"https://www.wikidata.org\"><b>Wikidata</b></a> <img src=\"/crotos/img/wd_ico".$day.".png\" alt=\"\" /> and  <a href=\"https://commons.wikimedia.org/\"><b>Wikimedia Commons</b></a><img src=\"/crotos/img/commons_ico".$day.".png\" alt=\"\" /></p>";

	$sql="SELECT count(id) as nbimg FROM artworks WHERE P18!=0";
	$rep_nb=mysqli_query($link,$sql);
	$data = mysqli_fetch_assoc($rep_nb);
	$nb1=number_format(intval($data['nbimg']),0,'',' ');
	$sql="SELECT count(id) as nbimg FROM artworks WHERE hd=1";
	$rep_nb=mysqli_query($link,$sql);
	$data = mysqli_fetch_assoc($rep_nb);
	$nb2=number_format(intval($data['nbimg']),0,'',' ');

	if ($l=="fr")
		echo "<p class=\"pres_detail\" style=\"margin-bottom:1px\">• <b>$nb1 objets</b>, dont $nb2 avec <b>image HD</b> <img src=\"/crotos/img/magnifying_ico".$day.".png\" alt=\"\" /></p><p class=\"pres_detail\">• Liens vers <b>site officiel</b> <img src=\"/crotos/img/site_link.png\" alt=\"\" />, <b>Wikipédia</b> <img src=\"/crotos/img/wp_ico".$day.".png\" alt=\"\" /> – <b>multilingue</b> <img src=\"/crotos/img/i18n_ico".$day.".png\" alt=\"\" /> – <b>libre</b> <img src=\"/crotos/img/pd_ico".$day.".png\" alt=\"Domaine public\" /><img src=\"/crotos/img/cc0_ico".$day.".png\" alt=\"CC 0\" /><img src=\"/crotos/img/by_ico".$day.".png\" alt=\"Attribution\" /><img src=\"/crotos/img/sa_ico".$day.".png\" alt=\"Partage dans les mêmes conditions\" /></p>";
	elseif ($l=="mu")
		echo "<p class=\"pres_detail\" style=\"margin-bottom:1px\">• <b>$nb1 Houba</b>, Houba $nb2 Houba <b>Houba Houba</b> <img src=\"/crotos/img/magnifying_ico".$day.".png\" alt=\"\" /></p><p class=\"pres_detail\">• Houba Houba <b>Houba Houba Houba</b> <img src=\"/crotos/img/site_link.png\" alt=\"\" />, <b>Houba</b> <img src=\"/crotos/img/wp_ico".$day.".png\" alt=\"\" /> – <b>Houba</b> <img src=\"/crotos/img/i18n_ico".$day.".png\" alt=\"\" /> – <b>Houba</b> <img src=\"/crotos/img/pd_ico".$day.".png\" alt=\"Houba Houba\" /><img src=\"/crotos/img/cc0_ico".$day.".png\" alt=\"Houba\" /><img src=\"/crotos/img/by_ico".$day.".png\" alt=\"Houba\" /><img src=\"/crotos/img/sa_ico".$day.".png\" alt=\"Houba Houba Houba Houba Houba\" /></p>";
	else
		echo "<p class=\"pres_detail\" style=\"margin-bottom:1px\">• <b>$nb1 works</b>, including $nb2 with <b>HD image</b> <img src=\"/crotos/img/magnifying_ico".$day.".png\" alt=\"\" /></p><p class=\"pres_detail\">• Links to <b>official website</b> <img src=\"/crotos/img/site_link.png\" alt=\"\" />, <b>Wikipedia</b> <img src=\"/crotos/img/wp_ico".$day.".png\" alt=\"\" /> – <b>multilingual</b> <img src=\"/crotos/img/i18n_ico".$day.".png\" alt=\"\" /> – <b>free and open</b> <img src=\"/crotos/img/pd_ico".$day.".png\" alt=\"Domaine public\" /><img src=\"/crotos/img/cc0_ico".$day.".png\" alt=\"CC 0\" /><img src=\"/crotos/img/by_ico".$day.".png\" alt=\"BY\" /><img src=\"/crotos/img/sa_ico".$day.".png\" alt=\"Share alike\" /></p>";
		
	echo "</div></div>";
}

if (($mode==1)||($b==1)){
	echo "<div id=\"miss_props\">";//<b>".translate($l,"missing")."</b> : ";
	//$missing_props=array(1,18,2,170,571,276,180);
	if ($mode==1)
		$missing_props=array(4896,1684,18,2,276,571,180,189,2596);
	else
		$missing_props=array(4896,1684);
	for ($i=0;$i<count($missing_props);$i++){
    	echo "<span><label>".translate($l,strval($missing_props[$i]))."</label> ";
		echo "<input name=\"c".$missing_props[$i]."\" id=\"c".$missing_props[$i]."\" type=\"checkbox\" value=\"1\"";
		if ($tab_check["c".strval($missing_props[$i])]==1)
			echo " checked=\"checked\"";
		echo " onChange=\"document.getElementById('form').submit()\"/>✓";
		if (($missing_props[$i]!=2)&&($missing_props[$i]!=4896)&&($missing_props[$i]!=1684)){
			echo "<input name=\"m".$missing_props[$i]."\" id=\"m".$missing_props[$i]."\" type=\"checkbox\" value=\"1\"";
			if ($tab_miss["m".strval($missing_props[$i])]==1)
				echo " checked=\"checked\"";
			echo " onChange=\"document.getElementById('form').submit()\"/>✗";
		}
		if ($missing_props[$i]!=18)
			echo "&nbsp;&nbsp;&nbsp;&nbsp;</span> ";
		else
			echo "&nbsp;</span> ";
		if ($missing_props[$i]==1684){
			echo "<span><label>".translate($l,"Wikipedia")."</label> ";
			echo "<input name=\"cw\" id=\"cw\" type=\"checkbox\" value=\"1\"";
			if ($tab_check["cw"]==1)
				echo " checked=\"checked\"";
			echo " onChange=\"document.getElementById('form').submit()\"/>✓";
			echo "&nbsp;&nbsp;&nbsp;&nbsp;</span> ";
		}
	}
   	/*echo "<input name=\"mw\" id=\"mw\" type=\"checkbox\" value=\"1\"";
	if ($tab_miss["mw"]==1)
		echo " checked=\"checked\"";
	echo " onChange=\"document.getElementById('form').submit()\"/>✗";
	echo "&nbsp;&nbsp;&nbsp;&nbsp;</span> ";*/
	if ($b==1)
		echo "<input name=\"b\" type=\"hidden\" value=\"1\"/>";
	echo "</div>";
}
?>   