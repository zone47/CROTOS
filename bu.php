<?php
/* / */
set_time_limit(600);
$host = 'db543840727.db.1and1.com'; 
$user = 'dbo543840727';
$pass = '47crotos47';
$db = 'db543840727';
$fic_sql="crotos_tmp_bu.sql";

$link = mysqli_connect ($host,$user,$pass,$db) or die ('Erreur : '.mysqli_error());

$cmd="mysql --default-character-set=utf8 -h ".$host." -u ".$user." -p".$pass." ".$db." < /kunden/homepages/42/d110278962/htdocs/zone47/crotos/bdd/".$fic_sql;
exec($cmd);
mysqli_query($link,"ALTER TABLE `artw_prop` ADD INDEX ( `id_artw` )");
mysqli_query($link,"ALTER TABLE `artw_prop` ADD INDEX ( `id_prop` )");
mysqli_query($link,"ALTER TABLE `label_page` ADD INDEX ( `qwd` )");
mysqli_close($link);
echo "updated";

$cmd="mysqldump --default-character-set=utf8 -h ".$host." -u ".$user." -p".$pass." ".$db." > /kunden/homepages/42/d110278962/htdocs/zone47/crotos/bdd/crotos.sql";
exec($cmd);
$cmd="zip -j /kunden/homepages/42/d110278962/htdocs/zone47/crotos/bdd/crotos.sql.zip /kunden/homepages/42/d110278962/htdocs/zone47/crotos/bdd/crotos.sql";
exec($cmd);

/*$ficdate = fopen("../datemaj.txt", 'w');
fputs($ficdate,date("j / n / Y"));
fclose($ficdate);
*/
$to = "benoit.deshayes@gmail.com"; 
$subject = "Crotos - Back up"; 
$body = "Back up"; 
$body = "Mise à jour effect effectuée."; 
mail($to, $subject, $body);

?>