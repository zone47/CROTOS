<?php // utf-8 ç 
/* / */
?>
	<div id="params">
    	<div>
		<label for="lg" id="label_lg" class="paralab"><?php echo translate($l,"language") ?></label>
		<select name="l" id="lg" onChange="document.getElementById('form').submit()">
<?php
for ($i=0;$i<count($lgs);$i++){
	/* Easter egg */if (($lgs[$i]=="mu")&&($l!="mu")) echo "<!-- ";
    echo "			<option value=\"".translate($lgs[$i],"lang_code")."\"";
	if ($l==translate($lgs[$i],"lang_code"))
		 echo " selected=\"selected\"";
	//echo " >".translate($lgs[$i],"lang_code")." - ".translate($lgs[$i],"lg")."</option>\n";	
	echo " >".translate($lgs[$i],"lg")."</option>\n";	
	/* Easter egg */if (($lgs[$i]=="mu")&&($l!="mu")) echo " -->";
}
?>
		</select>
		<input type="hidden" value="<?php  /*if ($p!=1) echo $p;*/  ?>" name="p" id="p" />

		<label for="nb" class="paralab"><?php echo translate($l,"img_page") ?></label>
		<select name="nb" id="nb" onChange="document.getElementById('form').submit()">
        	<option value="10" <?php if ($nb==10) echo "selected=\"selected\""; ?>>10</option>
			<option value="20" <?php if ($nb==20) echo "selected=\"selected\""; ?>>20</option>
    		<option value="40" <?php if ($nb==40) echo "selected=\"selected\""; ?>>40</option>
    		<option value="60" <?php if ($nb==60) echo "selected=\"selected\""; ?>>60</option>
            <option value="100" <?php if ($nb==100) echo "selected=\"selected\""; ?>>100</option>
            <option value="200" <?php if ($nb==200) echo "selected=\"selected\""; ?>>200</option>
            <?php if (($nb!=10)&&($nb!=20)&&($nb!=40)&&($nb!=60)&&($nb!=100)&&($nb!=200))
			echo "<option value=\"$nb\" selected=\"selected\">$nb</option>";
			?>
		</select>
        </div>
		<?php
	echo "<div id=\"contrib\"";
	if ($cosmos)
		echo " style=\"visibility:hidden\"";
	if ($mode==0)
		echo " class=\"mode_plus\"><span>";
	else
		echo "><span>";
		
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
	echo "</div>";

		?>
   		
        <div id="disp">
			<label for="d1"><img src="/crotos/img/<?php if ($disp==0) echo "moon_night.png"; else echo "moon.png"; ?>" alt="moon"/></label><input type="radio" name="disp" value="0" id="d1" <?php if ($disp==0) echo "checked" ?> onChange="document.getElementById('form').submit()"><input type="radio" name="disp" value="1"  id="d2" <?php if ($disp==1) echo "checked" ?> onChange="document.getElementById('form').submit()"><label for="d2"><img src="/crotos/img/<?php if ($disp==0) echo "sun_night.png"; else echo "sun.png"; ?>" alt="sun" /></label>
            
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

<?php
	include "facets.php";
?>