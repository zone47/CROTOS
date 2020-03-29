<footer <?php if ($num_rows<6) echo "class=\"marge\"" ?>>
	<span class="bl_foot"><a href="https://twitter.com/shona_gon">/* / */</a>, <a href="https://twitter.com/christelmolinie">C.M.</a>&nbsp;&nbsp;–&nbsp;&nbsp;<!--<a href="https://blog.wikimedia.org.uk/2019/12/leveraging-open-data-at-the-national-library-of-wales/" class="externe">info [<?php if ($l=="fr") echo "fr"; else echo "en"; ?>]</a>--><a href="https://github.com/zone47/CROTOS" class="externe">source</a>, <a href="/crotos/palladia/bdd/palladia.sql.zip">data</a><a href="http://creativecommons.org/publicdomain/zero/1.0/" title="CC0 1.0 Universal"><img src="/crotos/img/licence/CC-0-icon.png" alt="CC0 1.0 Universal" class="licence"></a>&nbsp;–&nbsp; powered by</span><span class="sep"> </span>
    <span class="bl_foot"><a href="http://www.wikidata.org" title="<?php echo translate($l,"Wikidata"); ?>"><img src="/crotos/img/wikidata.png" alt="<?php echo translate($l,"Wikidata"); ?>"/></a>  <a href="http://commons.wikimedia.org" title="<?php echo translate($l,"Commons"); ?>"><img src="/crotos/img/wikimedia-commons.png" alt="<?php echo translate($l,"Commons"); ?>" /></a>  <img src="/crotos/img/semanticpedia.png" alt="Sémanticpédia" />    <img src="/crotos/img/photographer.png" alt="Photographers" />  al.</span>
    <span class="bl_foot"> and &lt;3</span>
    <div class="update"><?php
$sql="SELECT count(id) as nbimg FROM artworks WHERE P18!=0";
$rep=mysqli_query($link,$sql);
$data = mysqli_fetch_assoc($rep);
echo number_format(intval($data['nbimg']),0,'',' ');
if ($l=="fr")
	echo " objets avec image";
else
	echo " works with image";
?>
 <a href="https://creativecommons.org/publicdomain/mark/1.0/" title="Public Domain Mark 1.0"><img src="/crotos/img/licence/PDM-icon.png" alt="Public Domain Mark 1.0" class="licence"></a>↔<a href="https://creativecommons.org/licenses/by-sa/4.0/" title="CC Attribution-ShareAlike"><img src="/crotos/img/licence/CC-BY-SA-icon.png" alt="CC Attribution-ShareAlike" class="licence"></a>– <?php 
 
$fp = fopen ($fold_crotos."dateupdate.txt", "r");
$update=fgets ($fp, 255);
/*if ($l=="fr")
	echo "<a href=\"/crotos/lab/updates.php\">Dernière mise à jour</a> : ";
else
	echo "<a href=\"/crotos/lab/updates.php\">Last update</a>: ";*/
if ($l=="fr")
	echo "Dernière mise à jour : ";
else
	echo "Last update: ";
echo substr($update, 6, 2)." / ".substr($update, 4, 2)." / ".substr($update, 0, 4);

fclose ($fp);
?></div>
</footer>