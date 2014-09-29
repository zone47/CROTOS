<?php
/* / */

$host = '***'; 
$user = '***';
$pass = '***';
$db = '***';
$fic_sql="crotos_tmp.sql";

$link = mysql_connect ($host,$user,$pass) or die ('Erreur : '.mysql_error());
mysql_select_db($db) or die ('Erreur :'.mysql_error());

$cmd="mysql --default-character-set=utf8 -h ".$host." -u ".$user." -p".$pass." ".$db." < /***/crotos/bdd/".$fic_sql;
exec($cmd);
mysql_query("ALTER TABLE `artw_prop` ADD INDEX ( `id_artw` )");
mysql_query("ALTER TABLE `artw_prop` ADD INDEX ( `id_prop` )");
mysql_query("ALTER TABLE `label_page` ADD INDEX ( `qwd` )");
mysql_close();
echo "updated";

$cmd="mysqldump --default-character-set=utf8 -h ".$host." -u ".$user." -p".$pass." ".$db." > /***/crotos/bdd/crotos.sql";
exec($cmd);
$cmd="zip -j /***/crotos.sql.zip /***/crotos.sql";
exec($cmd);

$ficdate = fopen("../datemaj.txt", 'w');
fputs($ficdate,date("j / n / Y"));
fclose($ficdate);

$to = "***@***"; 
$subject = "Crotos - Mise à jour"; 
$body = "Mise à jour effectuée."; 
mail($to, $subject, $body);

?>