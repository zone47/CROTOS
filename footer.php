<footer <?php if ($num_rows<6) echo "class=\"marge\"" ?>>
	<span class="bl_foot"><a href="https://twitter.com/shona_gon">/* / */</a>&nbsp;&nbsp;–&nbsp;&nbsp;<a href="/dozo/<?php if ($l=="fr") echo "crotos-moteur-de-recherche-sur-les-oeuvres-dart-dans-wikidata"; else echo "crotos-a-project-on-visual-artworks-powered-by-wikidata-and-wikimedia-commons"; ?>" class="externe">info [<?php if ($l=="fr") echo "fr"; else echo "en"; ?>]</a>, <a href="https://github.com/zone47/CROTOS" class="externe">source</a>, <a href="/crotos/bdd/crotos.sql.zip">data</a>&nbsp;&nbsp;–&nbsp;&nbsp;powered by</span><span class="sep"> </span>
    <span class="bl_foot"><a href="http://www.wikidata.org" title="<?php echo translate($l,"Wikidata"); ?>"><img src="/crotos/img/wikidata.png" alt="<?php echo translate($l,"Wikidata"); ?>"/></a>  <a href="http://commons.wikimedia.org" title="<?php echo translate($l,"Commons"); ?>"><img src="/crotos/img/wikimedia-commons.png" alt="<?php echo translate($l,"Commons"); ?>" /></a>  <a href="http://www.semanticpedia.org/" title="Sémantipédia"><img src="/crotos/img/semanticpedia.png" alt="Sémanticpédia" /></a>    <img src="/crotos/img/photographer.png" alt="Photographers" />  al.</span>
    <span class="bl_foot"> and &lt;3</span>
    <div class="update">Last update:
<?php
$fp = fopen ("../dateupdate.txt", "r");
echo fgets ($fp, 255);
fclose ($fp);
?></div>
</footer>