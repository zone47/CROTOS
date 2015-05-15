<div id="facets">
    <div class="fac_list">
<?php
for($i=0;$i<count($tab_facets);$i++){
	if ($tab_facets[$i]!=$prop)
		echo " <a href=\"?f=".$tab_facets[$i]."\" class=\"nav_sec\">".ucfirst(translate($l,$tab_facets[$i]))."</a>";	
	else
		echo " <span class=\"fac_ec\">".ucfirst(translate($l,$tab_facets[$i]))."</span>";
}
?>
<input type="hidden" value="<?php  echo $prop;  ?>" name="f" id="f" />
        <label for="n" id="min"><?php echo ucfirst(translate($l,"minimum")) ?>
        <select name="n" id="n" onChange="document.getElementById('form').submit()" style="vertical-align:baseline">
<?php
    $opt_n=array(1,3,5,10,50,100,500,1000);
    for ($i=0;$i<count($opt_n);$i++){
        $opt_txt="			<option value=\"".$opt_n[$i]."\"";
        if ($opt_n[$i]==$n)
            $opt_txt.=" selected=\"selected\"";
        $opt_txt.=">".$opt_n[$i]."</option>\n";
        echo $opt_txt;
    }
?>
        </select></label>
	</div>


</div>

    