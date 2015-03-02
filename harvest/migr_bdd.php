<?php
/* / */

$host = '***'; 
$user = '***';
$pass = '***';
$db = '***';
$fic_sql="crotos_tmp.sql";

$link = mysqli_connect ($host,$user,$pass,$db) or die ('Erreur : '.mysqli_error());

$cmd="mysql --default-character-set=utf8 -h ".$host." -u ".$user." -p".$pass." ".$db." < /***/crotos/bdd/".$fic_sql;
exec($cmd);
mysqli_query($link,"ALTER TABLE `artw_prop` ADD INDEX ( `id_artw` )");
mysqli_query($link,"ALTER TABLE `artw_prop` ADD INDEX ( `id_prop` )");
mysqli_query($link,"ALTER TABLE `label_page` ADD INDEX ( `qwd` )");
mysqli_close($link);
echo "updated";

$cmd="mysqldump --default-character-set=utf8 -h ".$host." -u ".$user." -p".$pass." ".$db." > /***/crotos/bdd/crotos.sql";
exec($cmd);
$cmd="zip -j /***/crotos/bdd/crotos.sql.zip /***/crotos/bdd/crotos.sql";
exec($cmd);

$cmd="cp /***/crotos/dateupdate.txt /***/crotos/dateprev.txt"; 
exec($cmd);
$ficdate = fopen("../dateupdate.txt", 'w');
fputs($ficdate,date("j / n / Y"));
fclose($ficdate);

$to = "***"; 
$subject = "Crotos - Mise à jour"; 
$body = "Mise à jour effectuée."; 
mail($to, $subject, $body);

?>