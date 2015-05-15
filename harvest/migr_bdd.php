<?php
/* / */

$host = '***'; 
$user = '***';
$pass = '***';
$db = '***';
$fic_sql_tmp="crotos_tmp.sql";
$fic_sql="crotos.sql";
$path="***";

$link = mysqli_connect ($host,$user,$pass,$db) or die ('Erreur : '.mysqli_error());

$file = $path."bdd/".$fic_sql_tmp.".zip";
$pathzip=$path."bdd/";
$zip = new ZipArchive;
$res = $zip->open($file);
if ($res === TRUE) {
	$zip->extractTo($pathzip);
	$zip->close();
} else {
	echo "Doh! I couldn't open $path $file";
}

$cmd="mysql --default-character-set=utf8 -h ".$host." -u ".$user." -p".$pass." ".$db." < ".$path."bdd/".$fic_sql_tmp;
exec($cmd);
mysqli_query($link,"ALTER TABLE `artw_prop` ADD INDEX ( `id_artw` )");
mysqli_query($link,"ALTER TABLE `artw_prop` ADD INDEX ( `id_prop` )");
mysqli_query($link,"ALTER TABLE `label_page` ADD INDEX ( `qwd` )");
mysqli_close($link);
echo "updated";

$cmd="mysqldump --default-character-set=utf8 -h ".$host." -u ".$user." -p".$pass." ".$db." > ".$path."bdd/".$fic_sql;
exec($cmd);
$cmd="zip -j ".$path."bdd/".$fic_sql.".zip ".$path."bdd/".$fic_sql;
exec($cmd);

$cmd="cp ".$path."dateupdate.txt ".$path."dateprev.txt"; 
exec($cmd);
$ficdate = fopen("../dateupdate.txt", 'w');
fputs($ficdate,date("j / n / Y"));
fclose($ficdate);

$cmd="rm -f ".$path."lab/artworks/creators/*"; 
exec($cmd);
$cmd="rm -f ".$path."lab/artworks/csv/*"; 
exec($cmd);
$cmd="rm -f ".$path."lab/artworks/csv_tmp/*"; 
exec($cmd);
$cmd="rm -f ".$path."lab/artworks/items/*"; 
exec($cmd);
$cmd="rm -f ".$path."lab/artworks/queries/*"; 
exec($cmd);

$to = "benoit.deshayes@gmail.com"; 
$subject = "Crotos - Mise à jour"; 
$body = "Mise à jour effectuée."; 
mail($to, $subject, $body);

?>