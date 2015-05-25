<div id="facets">
<?php
if (($y1=="-40000")&&($y2=="2015"))		
	echo "   		<div class=\"mode_plus\">";
?>
   		<div id="slider">
            <input type="text" id="amount1" value="<?php echo $y1; ?>" data-index="0" class="sliderValue" name="y1" />
            <div id="slider-range"></div>
            <input type="text" id="amount2" value="<?php echo $y2; ?>" data-index="1" class="sliderValue"  name="y2" />
            <input type="submit" id="ok2" value=" " />
        </div>
<?php
if (($y1=="-40000")&&($y2=="2015"))		
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
$p31_list=array("3305213","860861","93184","11060274","125191","133067","212431","5647631","184296","1473346");
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
if ($mode==1){
	echo "<div id=\"miss_props\">";//<b>".translate($l,"missing")."</b> : ";
	$missing_props=array(1,18,2,170,571,195,276,180);
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
	echo "</div>";
}
?>   