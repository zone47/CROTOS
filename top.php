    <div id="bl_titre">
        <a href="/crotos/<? if ($new) echo $new_url ?>" title="Crotos" id="lk_img"><img src="/crotos/img/crotos.jpg" alt="CROTOS" width="108" height="120" id="img_crotos"/></a>
        <h1><a href="/crotos/<? if ($new) echo $new_url ?>" title="Crotos"><?php /* Easter egg */if ($l=="mu") echo "HOUBA"; else echo "Crotos"; ?></a> </h1>
        <div id="cosmos" class="sh1 <?php if ($cosmos) echo "cosmos_title" ?>"><?php if ($cosmos) echo "<b>"; ?><a href="/crotos/cosmos/" title="Cosmos">Cosmos</a><?php if ($cosmos) echo "</b>"; ?></div>
        <div id="callisto" class="sh1"><a href="/crotos/callisto/" title="Callisto">Callisto</a></div>
        <div id="lab" class="sh1"><a href="/crotos/lab/" title="Crotos Lab">Lab</a></div>
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
		echo "<a href=\"../crotos/?d=$before\" class=\"nav_update\">⇐</a> ";
		
	echo $disp_date." <input value=\"$d\" name=\"d\" checked=\"checked\" onchange=\"document.getElementById('form').submit()\" type=\"checkbox\" />";
	if ($after!="")
		echo " <a href=\"../crotos/?d=$after\" class=\"nav_update\">⇒</a>";
	echo "</div>";
	
}
	
?>
        
    </div>