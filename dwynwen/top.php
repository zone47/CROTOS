    <div id="bl_titre">
        <a href="https://www.library.wales/" title="LLGC-NLW" id="lk_img"><img src="/crotos/dwynwen/img/llgc-nlw-logo.png" alt="llgc-nlw" width="108" height="101" id="img_crotos"/></a>
        <h1><a href="/crotos/dwywen/<? if ($new) echo $new_url ?>" title="Dwynwen"><?php /* Easter egg */if ($l=="mu") echo "HOUBA"; else echo "Dwynwen"; ?></a> </h1>
        <div id="search" class="sh1 <?php if (!($cosmos)) echo "search_title" ?>"><?php if (!($cosmos)) echo "<b>"; ?><a href="/crotos/dwynwen/" title="<?php
if ($l=="cy")
	echo "Chwilio";
elseif ($l=="fr")
	echo "Recherche";
elseif ($l=="mu")
	echo "Houba";
else
	echo "Search";
		?></a>"><?php
if ($l=="cy")
	echo "Chwilio";
elseif ($l=="fr")
	echo "Recherche";
elseif ($l=="mu")
	echo "Houba";
else
	echo "Search";
		?></a><?php if (!($cosmos)) echo "</b>"; ?></div>
        <div id="cosmos" class="sh1 <?php if ($cosmos) echo "cosmos_title" ?>"><?php if ($cosmos) echo "<b>"; ?><a href="/crotos/dwynwen/cosmos/" title="Cosmos"><?php
if ($l=="cy")
	echo "Pori";
elseif ($l=="fr")
	echo "Explorer";
elseif ($l=="mu")
	echo "Houba";
else
	echo "Browse";
		?></a><?php if ($cosmos) echo "</b>"; ?></div>
        <div id="callisto" class="sh1"><a href="/crotos/dwynwen/callisto/" title="Callisto"><?php
if ($l=="cy")
	echo "Map";
elseif ($l=="fr")
	echo "Carte";
elseif ($l=="mu")
	echo "Houba";
else
	echo "Map";
?></a></div>
        <!--<div id="lab" class="sh1"><a href="/crotos/lab/" title="Crotos Lab">Lab</a></div>-->
<?php
if ($d!=0){
	$dir = 'updates';
	$files = scandir($dir, 1);
	for ($i=0;$i<count($files);$i++){
		$file=$files[$i];
		if($file==$d.".txt"){
			$rank=$i;
			break;
		}
	}
	$before="";
	if (($rank+1<count($files))&&(intval($d)>20150809))
		$before=str_replace(".txt","",$files[$rank+1]);
	
	$after="";
	if ($rank-1>-1)
		$after=str_replace(".txt","",$files[$rank-1]);
	
		
	$disp_date=substr($d,6,2).".".substr($d,4,2).".".substr($d,0,4);
	echo "<div id=\"new\" class=\"sh1\">";
	if ($before!="")
		echo "<a href=\"../crotos/dwynwen/?d=$before\" class=\"nav_update\">⇐</a> ";
		
	echo $disp_date." <input value=\"$d\" name=\"d\" checked=\"checked\" onchange=\"document.getElementById('form').submit()\" type=\"checkbox\" />";
	if ($after!="")
		echo " <a href=\"../crotos/dwynwen/?d=$after\" class=\"nav_update\">⇒</a>";
	echo "</div>";
	
}
	
?>
        
    </div>