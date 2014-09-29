<?php // utf-8 ç 
/* / */
?>
<form action="index.php" method="get" id="form"  name="form"  accept-charset="UTF-8">
	<div id="params">
    	<div>
		<label for="lg"><?php echo translate($l,"language") ?></label>
		<select name="l" id="lg">
<?php
for ($i=0;$i<count($lgs);$i++){
	/* Easter egg */if (($lgs[$i]=="mu")&&($l!="mu")) echo "<!-- ";
    echo "			<option value=\"".translate($lgs[$i],"lang_code")."\"";
	if ($l==translate($lgs[$i],"lang_code"))
		 echo " selected=\"selected\"";
	echo " >".translate($lgs[$i],"lang_code")." - ".translate($lgs[$i],"lg")."</option>\n";	
	/* Easter egg */if (($lgs[$i]=="mu")&&($l!="mu")) echo " -->";
}
?>
		</select>
		<input type="hidden" value="<?php  /*if ($p!=1) echo $p;*/  ?>" name="p" id="p" />
<?php
foreach($tab_idx as $key=>$value)
	if (($value!="")&&($key!="p31"))
		echo "		<input type=\"hidden\" value=\"$value\" name=\"$key\" id=\"$key\" />";
?>

		<label for="nb"><?php echo translate($l,"img_page") ?></label>
		<select name="nb" id="nb">
			<option value="10" <?php if ($nb==10) echo "selected=\"selected\""; ?>>10</option>
			<option value="20" <?php if ($nb==20) echo "selected=\"selected\""; ?>>20</option>
    		<option value="40" <?php if ($nb==40) echo "selected=\"selected\""; ?>>40</option>
    		<option value="60" <?php if ($nb==60) echo "selected=\"selected\""; ?>>60</option>
		</select>
        </div>
		<div id="contrib"><span><?php
	if (($l=="ar")||($l=="fa")||($l=="he"))
		echo "←";
	else
		echo "→";
?></span>
        <?php
		$comp="";
		if (isset($_GET['p']))
			if ($_GET['p']!="") 
				$comp="&amp;p=".$_GET['p'];
		if ($mode==0)
    		echo "<a href=\"?".$liennav.$comp."&amp;mode=1\">".translate($l,"contribution")."</a>";
		else
			echo "<a href=\"?".$liennav.$comp."&amp;mode=0\">".translate($l,"read")."</a>";
		?>
   		</div>
	</div>

	<div id="recherche">
        <input type="text" name="s" value="<?php if ($s!="") echo $s ?>" id="topic_title" />
        <input type="submit" value="<?php echo translate($l,"search") ?>" id="ok" />
	</div>

    <div id="res_nav">
<?php
echo $txt_res;
if ($random)
	echo " <span>(".translate($l,"random").")</span>";
 ?>
    </div>

	<div id="facets">
		<span class="criteres"><?php
foreach($tab_idx as $key=>$value)
	if (($value!="")&&($key!="p31")){
		echo txt_prop(0,$value,$l,"normal",0,0);
		echo "			<a href=\"?l=".$l;
		if ($nb!="20") echo "&amp;nb=".$nb;
		foreach($tab_idx as $key2=>$value2)
			if ($value2!="")
				if ($key2!=$key)
					echo "&amp;".$key2."=".$value2;
		foreach($tab_miss as $key2=>$value2)
			if ($value2!="")
				if ($key2!=$key)
					echo "&amp;".$key2."=".$value2;
		if ($s!="") echo "&amp;s=".$s;
		echo "\">";
		echo "<img src=\"img/delete.png\" alt=\"\" width=\"16\" height=\"17\"/>";
		echo "</a>";
	}
?></span>
	<div id="form_facets">
		<select name="p31" id="listp31">
    		<option value="" id="tout"><?php echo ucfirst(translate($l,"everything")) ?></option>
<?php 
$p31_list=array("3305213","860861","93184","11060274","133067","5647631","1473346","184296");
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
    
            <div id="slider">
                <input type="text" id="amount1" value="<?php echo $y1; ?>" data-index="0" class="sliderValue" size="3" name="y1" />
                <div id="slider-range"></div>
                <input type="text" id="amount2" value="<?php echo $y2; ?>" data-index="1" class="sliderValue"  size="3" name="y2" />
                <input type="submit" id="ok2" value=" " />
            </div>
            
        </div>
    </div>

    <?php
if ($mode==1){
	echo "<div id=\"miss_props\">";//<b>".translate($l,"missing")."</b> : ";
	$missing_props=array(1,18,170,571,186,217,195,276);
	for ($i=0;$i<count($missing_props);$i++){
    	echo "<span><label>".translate($l,strval($missing_props[$i]))."</label> ";
		echo "<input name=\"c".$missing_props[$i]."\" id=\"c".$missing_props[$i]."\" type=\"checkbox\" value=\"1\"";
		if ($tab_check["c".strval($missing_props[$i])]==1)
			echo " checked=\"checked\"";
		echo " />✓";
		echo "<input name=\"m".$missing_props[$i]."\" id=\"m".$missing_props[$i]."\" type=\"checkbox\" value=\"1\"";
		if ($tab_miss["m".strval($missing_props[$i])]==1)
			echo " checked=\"checked\"";
		echo " />✗";
		
		echo "&nbsp;&nbsp;&nbsp;&nbsp;</span> ";
	}
	echo "<br/>\n";
	$missing_props=array(136,135,921,144,941,180);
	for ($i=0;$i<count($missing_props);$i++){
    	echo "<span><label>".translate($l,strval($missing_props[$i]))."</label> ";
		echo "<input name=\"c".$missing_props[$i]."\" id=\"c".$missing_props[$i]."\" type=\"checkbox\" value=\"1\"";
		if ($tab_check["c".strval($missing_props[$i])]==1)
			echo " checked=\"checked\"";
		echo " />✓";
		echo "<input name=\"m".$missing_props[$i]."\" id=\"m".$missing_props[$i]."\" type=\"checkbox\" value=\"1\"";
		if ($tab_miss["m".strval($missing_props[$i])]==1)
			echo " checked=\"checked\"";
		echo " />✗";
		
		echo "&nbsp;&nbsp;&nbsp;&nbsp;</span> ";
	}
	echo "<br/>\n";
	$missing_props=array(727,214,350,347,1212,973);
	for ($i=0;$i<count($missing_props);$i++){
    	echo "<span><label>".translate($l,strval($missing_props[$i]))."</label> ";
		echo "<input name=\"c".$missing_props[$i]."\" id=\"c".$missing_props[$i]."\" type=\"checkbox\" value=\"1\"";
		if ($tab_check["c".strval($missing_props[$i])]==1)
			echo " checked=\"checked\"";
		echo " />✓";
		echo "<input name=\"m".$missing_props[$i]."\" id=\"m".$missing_props[$i]."\" type=\"checkbox\" value=\"1\"";
		if ($tab_miss["m".strval($missing_props[$i])]==1)
			echo " checked=\"checked\"";
		echo " />✗";
		
		echo "&nbsp;&nbsp;&nbsp;&nbsp;</span> ";
	}
	echo "</div>";
}
?>   
</form>