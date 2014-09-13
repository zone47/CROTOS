<?php
/* / */

$host = '***'; //Votre host, souvent localhost
$user = '***'; //votre login
$pass = '***'; //Votre mot de passe
$db = '***'; // Le nom de la base de donnee
$fic_sql="crotos_tmp.sql";

$link = mysql_connect ($host,$user,$pass) or die ('Erreur : '.mysql_error());
mysql_select_db($db) or die ('Erreur :'.mysql_error());
mysql_query("TRUNCATE `artworks`");
mysql_query("TRUNCATE `artw_prop`");
mysql_query("TRUNCATE `label_page`");
mysql_query("TRUNCATE `missing`");
mysql_query("TRUNCATE `p31`");
mysql_query("TRUNCATE `p135`");
mysql_query("TRUNCATE `p136`");
mysql_query("TRUNCATE `p144`");
mysql_query("TRUNCATE `p170`");
mysql_query("TRUNCATE `p180`");
mysql_query("TRUNCATE `p186`");
mysql_query("TRUNCATE `p195`");
mysql_query("TRUNCATE `p276`");
mysql_query("TRUNCATE `p921`");
mysql_query("TRUNCATE `p941`");

$cmd="mysql --default-character-set=utf8 -h ".$host." -u ".$user." -p".$pass." ".$db." < /***/bdd/".$fic_sql;
exec($cmd);
mysql_query("ALTER TABLE `artw_prop` ADD INDEX ( `id_artw` )");
mysql_query("ALTER TABLE `artw_prop` ADD INDEX ( `id_prop` )");
mysql_query("ALTER TABLE `label_page` ADD INDEX ( `qwd` )");
mysql_close();
echo "updated";

$cmd="mysqldump --default-character-set=utf8 -h ".$host." -u ".$user." -p".$pass." ".$db." > /***/crotos.sql";
exec($cmd);
$cmd="zip -j /***/crotos/bdd/crotos.sql.zip /***/crotos.sql";
exec($cmd);

$ficdate = fopen("../datemaj.txt", 'w');
fputs($ficdate,date("j / n / Y"));
fclose($ficdate);

$to = "***@***.com"; 
$subject = "Crotos - Mise à jour"; 
$body = "Mise à jour effectuée."; 
mail($to, $subject, $body);

?>