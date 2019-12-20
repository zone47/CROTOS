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
	echo "   		 <select name=\"p31\" id=\"listp31\" class=\"mode_plus\" onChange=\"document.getElementById('form').submit()\">";
else 
	echo "   		 <select name=\"p31\" id=\"listp31\" onChange=\"document.getElementById('form').submit()\">";
	
?>
    		<option value="" id="tout"><?php echo ucfirst(translate($l,"everything")) ?></option>
<?php 
$p31_list=array("3305213","93184","11060274","125191");
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
	$tabalea=array(23824442,23703565,23700078,22677627,23662327,23699183,23703790,23832075,23893881,23980528,23981663,24069245,23719780,24175986,25253564,25916956,22676062,24566598);
 
	$alea_rand = $tabalea[array_rand($tabalea, 1)];
	$sql="SELECT artworks.qwd,commons_img.thumb FROM artworks, commons_img WHERE artworks.qwd=". $alea_rand." AND commons_img.id=artworks.P18";
	$rep_pres=mysqli_query($link,$sql);
	$data = mysqli_fetch_assoc($rep_pres);
	$img1=str_replace("/200px","/100px",$data['thumb']);	
	$qwd1=$data['qwd'];

	echo "<div id=\"presentation\">";
	echo "<a href=\"/crotos/dwywen/?q=".$qwd1."\" id=\"art_pres\"><img src=\"".$img1."\" width=\"100\" height=\"75\"></a>";
	if ($l=="fr")
		echo "<p><a href=\"/crotos/dwynwen/\"><b>Dwynwen</b></a> est un moteur de recherche et d'affichage d'œuvres d'art de la <a href=\"https://www.library.wales/\"><b>Bibliothèque nationale du pays de Galles</b></a> s'appuyant sur <a href=\"https://www.wikidata.org\"><b>Wikidata</b></a> <img src=\"/crotos/img/wd_ico".$day.".png\" alt=\"\" /> et  <a href=\"https://commons.wikimedia.org/\"><b>Wikimedia Commons</b></a><img src=\"/crotos/img/commons_ico".$day.".png\" alt=\"\" /></p>";
	elseif ($l=="cy")
		echo "<p>Peiriant chwilio ac arddangos yw <a href=\"/crotos/dwynwen/\"><b>Dwynwen</b></a> ar gyfer cynnwys digidol <a href=\"https://www.library.wales/\"><b>Llyfrgell Genedlaethol Cymru</b></a> sy'n cael eu pweru gan <a href=\"https://www.wikidata.org\"><b>Wikidata</b></a> <img src=\"/crotos/img/wd_ico".$day.".png\" alt=\"\" /> a   <a href=\"https://commons.wikimedia.org/\"><b>Wikimedia Commons</b></a><img src=\"/crotos/img/commons_ico".$day.".png\" alt=\"\" /></p>";
	elseif ($l=="mu")
		echo "<p><a href=\"/crotos/dwynwen/\"><b>Houba</b></a> Houba Houba Houba Houba Houba Houba Houba'Houba Houba Houba <a href=\"https://www.library.wales/\"><b>Houba Houba Houba Houba Houba Houba</b></a> Houba'Houba Houba'Houba Houba'Houba Houba <a href=\"https://www.wikidata.org\"><b>Houba</b></a> <img src=\"/crotos/img/wd_ico".$day.".png\" alt=\"\" /> et  <a href=\"https://commons.wikimedia.org/\"><b>Houba Houba</b></a><img src=\"/crotos/img/commons_ico".$day.".png\" alt=\"\" /></p>";
	else
		echo "<p><a href=\"/crotos/dwynwen/\"><b>Dwynwen</b></a> is a search and display engine for visual artworks of the <a href=\"https://www.library.wales/\"><b>National Library of Wales</b></a> powered by <a href=\"https://www.wikidata.org\"><b>Wikidata</b></a> <img src=\"/crotos/img/wd_ico".$day.".png\" alt=\"\" /> and  <a href=\"https://commons.wikimedia.org/\"><b>Wikimedia Commons</b></a><img src=\"/crotos/img/commons_ico".$day.".png\" alt=\"\" /></p>";

	$sql="SELECT count(id) as nbimg FROM artworks WHERE P18!=0";
	$rep_nb=mysqli_query($link,$sql);
	$data = mysqli_fetch_assoc($rep_nb);
	$nb1=number_format(intval($data['nbimg']),0,'',' ');
	$sql="SELECT count(id) as nbimg FROM artworks WHERE hd=1";
	$rep_nb=mysqli_query($link,$sql);
	$data = mysqli_fetch_assoc($rep_nb);
	$nb2=number_format(intval($data['nbimg']),0,'',' ');

	if ($l=="fr")
		echo "<p class=\"pres_detail\">• <b>$nb1 œuvres</b>, dont $nb2 avec <b>image HD</b> <img src=\"/crotos/img/magnifying_ico".$day.".png\" alt=\"\" /></p><p class=\"pres_detail\">• Liens vers <b>site officiel</b> <img src=\"/crotos/img/site_link.png\" alt=\"\" />, <b>Wikipédia</b> <img src=\"/crotos/img/wp_ico".$day.".png\" alt=\"\" /> – <b>multilingue</b> <img src=\"/crotos/img/i18n_ico".$day.".png\" alt=\"\" /> – <b>libre</b> <img src=\"/crotos/img/pd_ico".$day.".png\" alt=\"Domaine public\" /><img src=\"/crotos/img/cc0_ico".$day.".png\" alt=\"CC 0\" /><img src=\"/crotos/img/by_ico".$day.".png\" alt=\"Attribution\" /><img src=\"/crotos/img/sa_ico".$day.".png\" alt=\"Partage dans les mêmes conditions\" /></p>";
	elseif ($l=="cy")
		echo "<p class=\"pres_detail\">• <b>$nb1 gwaith celf</b>, yn cynnwys $nb2 gyda <b>delwedd HD</b> <img src=\"/crotos/img/magnifying_ico".$day.".png\" alt=\"\" /></p><p class=\"pres_detail\">• Dolenni i <b> gwefan swyddogol</b> <img src=\"/crotos/img/site_link.png\" alt=\"\" />, <b>Wicipedia </b> <img src=\"/crotos/img/wp_ico".$day.".png\" alt=\"\" /> – <b>amlieithog</b> <img src=\"/crotos/img/i18n_ico".$day.".png\" alt=\"\" /> – <b>am ddim ac yn agored parth cyhoeddus</b> <img src=\"/crotos/img/pd_ico".$day.".png\" alt=\"Domaine public\" /><img src=\"/crotos/img/cc0_ico".$day.".png\" alt=\"CC 0\" /><img src=\"/crotos/img/by_ico".$day.".png\" alt=\"Attribution\" /><img src=\"/crotos/img/sa_ico".$day.".png\" alt=\"Partage dans les mêmes conditions\" /></p>";	
	elseif ($l=="mu")
		echo "<p class=\"pres_detail\">• <b>$nb1 Houba</b>, Houba $nb2 Houba <b>Houba Houba</b> <img src=\"/crotos/img/magnifying_ico".$day.".png\" alt=\"\" /></p><p class=\"pres_detail\">• Houba Houba <b>Houba Houba Houba</b> <img src=\"/crotos/img/site_link.png\" alt=\"\" />, <b>Houba</b> <img src=\"/crotos/img/wp_ico".$day.".png\" alt=\"\" /> – <b>Houba</b> <img src=\"/crotos/img/i18n_ico".$day.".png\" alt=\"\" /> – <b>Houba</b> <img src=\"/crotos/img/pd_ico".$day.".png\" alt=\"Houba Houba\" /><img src=\"/crotos/img/cc0_ico".$day.".png\" alt=\"Houba\" /><img src=\"/crotos/img/by_ico".$day.".png\" alt=\"Houba\" /><img src=\"/crotos/img/sa_ico".$day.".png\" alt=\"Houba Houba Houba Houba Houba\" /></p>";
	else
		echo "<p class=\"pres_detail\">• <b>$nb1 artworks</b>, including $nb2 with <b>HD image</b> <img src=\"/crotos/img/magnifying_ico".$day.".png\" alt=\"\" /></p><p class=\"pres_detail\">• Links to <b>official website</b> <img src=\"/crotos/img/site_link.png\" alt=\"\" />, <b>Wikipedia</b> <img src=\"/crotos/img/wp_ico".$day.".png\" alt=\"\" /> – <b>multilingual</b> <img src=\"/crotos/img/i18n_ico".$day.".png\" alt=\"\" /> – <b>free and open</b> <img src=\"/crotos/img/pd_ico".$day.".png\" alt=\"Domaine public\" /><img src=\"/crotos/img/cc0_ico".$day.".png\" alt=\"CC 0\" /><img src=\"/crotos/img/by_ico".$day.".png\" alt=\"BY\" /><img src=\"/crotos/img/sa_ico".$day.".png\" alt=\"Share alike\" /></p>";
		
	echo "</div>";
}

if ($mode==1){
	echo "<div id=\"miss_props\">";//<b>".translate($l,"missing")."</b> : ";
	$missing_props=array(1,18,2,170,571,276,180);
	for ($i=0;$i<count($missing_props);$i++){
    	echo "<span><label>".translate($l,strval($missing_props[$i]))."</label> ";
		echo "<input name=\"c".$missing_props[$i]."\" id=\"c".$missing_props[$i]."\" type=\"checkbox\" value=\"1\"";
		if ($tab_check["c".strval($missing_props[$i])]==1)
			echo " checked=\"checked\"";
		echo " onChange=\"document.getElementById('form').submit()\"/>✓";
		if ($missing_props[$i]!=2){
			echo "<input name=\"m".$missing_props[$i]."\" id=\"m".$missing_props[$i]."\" type=\"checkbox\" value=\"1\"";
			if ($tab_miss["m".strval($missing_props[$i])]==1)
				echo " checked=\"checked\"";
			echo " onChange=\"document.getElementById('form').submit()\"/>✗";
		}
		if ($missing_props[$i]!=18)
			echo "&nbsp;&nbsp;&nbsp;&nbsp;</span> ";
		else
			echo "&nbsp;</span> ";
	}
   	echo "<span><label>".translate($l,"Wikipedia")."</label> ";
	echo "<input name=\"cw\" id=\"cw\" type=\"checkbox\" value=\"1\"";
	if ($tab_check["cw"]==1)
		echo " checked=\"checked\"";
	echo " onChange=\"document.getElementById('form').submit()\"/>✓";
	echo "<input name=\"mw\" id=\"mw\" type=\"checkbox\" value=\"1\"";
	if ($tab_miss["mw"]==1)
		echo " checked=\"checked\"";
	echo " onChange=\"document.getElementById('form').submit()\"/>✗";
	echo "&nbsp;&nbsp;&nbsp;&nbsp;</span> ";
	
	echo "</div>";
}
?>   