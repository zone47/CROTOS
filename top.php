    <div id="bl_titre">
        <a href="/crotos/<? if ($new) echo $new_url ?>" title="Crotos" id="lk_img"><img src="/crotos/img/crotos.jpg" alt="CROTOS" width="108" height="120" id="img_crotos"/></a>
        <h1><a href="/crotos/<? if ($new) echo $new_url ?>" title="Crotos"><?php /* Easter egg */if ($l=="mu") echo "HOUBA"; else echo "Crotos"; ?></a></h1>
        <div id="cosmos" class="sh1 <?php if ($cosmos) echo "cosmos_title" ?>"><?php if ($cosmos) echo "<b>"; ?><a href="/crotos/cosmos/" title="Cosmos">Cosmos</a><?php if ($cosmos) echo "</b>"; ?></div>
        <div id="lab" class="sh1"><a href="/crotos/lab/" title="Crotos Lab">Lab</a></div>
<?php
if ($d!=0){
	$fp = fopen ($fold_crotos."dateupdate.txt", "r");
	$udpate=fgets ($fp, 255);
	fclose ($fp);
	
	/*if ($d==intval($udpate))
		$disp_date="New";
	else*/
		$disp_date=substr($d,6,2).".".substr($d,4,2).".".substr($d,0,4);
	echo "<div id=\"new\" class=\"sh1\">".$disp_date." <input value=\"$d\" name=\"d\" checked=\"checked\" onchange=\"document.getElementById('form').submit()\" type=\"checkbox\" /></div>";
	
}
	
?>
        
    </div>