<?php
/* / */
set_time_limit(1200);
error_reporting(E_ALL & ~E_NOTICE);

$host = '***'; 
$user = '***';
$pass = '***';
$db = '***';
$fic_sql_tmp="crotos_tmp.sql";
$fic_sql="crotos.sql";
$path="/***/crotos/llgc-nlw/";

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
$cmd="rm -f ".$path."bdd/".$fic_sql_tmp; 
exec($cmd);

echo "updated";

$cmd="mysqldump --default-character-set=utf8 -h ".$host." -u ".$user." -p".$pass." ".$db." > ".$path."bdd/".$fic_sql;
exec($cmd);

$cmd="zip -j ".$path."bdd/".$fic_sql.".zip ".$path."bdd/".$fic_sql;
exec($cmd);

$cmd="rm -f ".$path."bdd/".$fic_sql; 
exec($cmd);


?>