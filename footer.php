<footer <?php if ($num_rows<6) echo "class=\"marge\"" ?>>
	<span class="bl_foot"><a href="https://twitter.com/shona_gon">/* / */</a>&nbsp;&nbsp;–&nbsp;&nbsp;<a href="/dozo/<?php if ($l=="fr") echo "crotos-moteur-de-recherche-sur-les-oeuvres-dart-dans-wikidata"; else echo "crotos-a-project-on-visual-artworks-powered-by-wikidata-and-wikimedia-commons"; ?>" class="externe">info [<?php if ($l=="fr") echo "fr"; else echo "en"; ?>]</a>, <a href="https://github.com/zone47/CROTOS" class="externe">source</a>, <a href="/crotos/bdd/crotos.sql.zip">data</a>&nbsp;&nbsp;–&nbsp;&nbsp;powered by</span><span class="sep"> </span>
    <span class="bl_foot"><a href="http://www.wikidata.org" title="<?php echo translate($l,"Wikidata"); ?>"><img src="/crotos/img/wikidata.png" alt="<?php echo translate($l,"Wikidata"); ?>"/></a>  <a href="http://commons.wikimedia.org" title="<?php echo translate($l,"Commons"); ?>"><img src="/crotos/img/wikimedia-commons.png" alt="<?php echo translate($l,"Commons"); ?>" /></a>  <a href="http://www.semanticpedia.org/" title="Sémanticpédia"><img src="/crotos/img/semanticpedia.png" alt="Sémanticpédia" /></a>    <img src="/crotos/img/photographer.png" alt="Photographers" />  al.</span>
    <span class="bl_foot"> and &lt;3</span>
    <div class="update"><?php
$sql="SELECT count(id) as nbartworks FROM artworks";
$rep=mysqli_query($link,$sql);
$data = mysqli_fetch_assoc($rep);
echo number_format(intval($data['nbartworks']),0,'',' ');
if ($l=="fr")
	echo " œuvres d'art – ";
else
	echo " artworks – ";
$sql="SELECT count(id) as nbimg FROM artworks WHERE P18!=0";
$rep=mysqli_query($link,$sql);
$data = mysqli_fetch_assoc($rep);
echo number_format(intval($data['nbimg']),0,'',' ');
if ($l=="fr")
	echo " avec ";
else
	echo " with – ";
echo " image – ";

$fp = fopen ($fold_crotos."dateupdate.txt", "r");
$update=fgets ($fp, 255);
echo "<a href=\"lab/updates.php\">Last update</a>: ".substr($update, 6, 2)." / ".substr($update, 4, 2)." / ".substr($update, 0, 4);

fclose ($fp);
?></div>
</footer>