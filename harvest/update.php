<?php
/* / */
set_time_limit(600);
error_reporting(E_ALL & ~E_NOTICE);

$host = 'db543840727.db.1and1.com'; 
$user = 'dbo543840727';
$pass = '47cROTOs47CrotoS';
$db = 'db543840727';
$fic_sql_tmp="crotos_tmp.sql";
$fic_sql="crotos.sql";
$path="/kunden/homepages/42/d110278962/htdocs/zone47/crotos/";

$link = mysqli_connect ($host,$user,$pass,$db) or die ('Erreur : '.mysqli_error());
/*
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

echo "updated";

$cmd="mysqldump --default-character-set=utf8 -h ".$host." -u ".$user." -p".$pass." ".$db." > ".$path."bdd/".$fic_sql;
exec($cmd);

$cmd="zip -j ".$path."bdd/".$fic_sql.".zip ".$path."bdd/".$fic_sql;
exec($cmd);
*/
$ficdate = fopen("../dateupdate.txt", 'w');
$res=mysqli_query($link,"select max(crea) as crea from publi");
$data = mysqli_fetch_assoc($res);
$recent=$data['crea'];
fputs($ficdate,$recent);
fclose($ficdate);
$ficupdate = fopen("../updates/$recent.txt", 'w');

$res=mysqli_query($link,"select count(crea) as crea from artworks WHERE crea=$recent");
$data = mysqli_fetch_assoc($res);
$new_crea=$data['crea'];

$res=mysqli_query($link,"select count(img) as img from artworks WHERE img=$recent");
$data = mysqli_fetch_assoc($res);
$new_img=$data['img'];

$res=mysqli_query($link,"select count(del) as del from publi WHERE del=$recent");
$data = mysqli_fetch_assoc($res);
$del=$data['del'];

$res=mysqli_query($link,"select count(id) as id from artworks");
$data = mysqli_fetch_assoc($res);
$crea=$data['id'];

$res=mysqli_query($link,"select count(id) as id from artworks WHERE P18!=0");
$data = mysqli_fetch_assoc($res);
$img=$data['id'];

fputs($ficupdate,$new_crea."|".$new_img."|".$del."|".$crea."|".$img);
fclose($ficupdate);

mysqli_close($link);


/*
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
mail($to, $subject, $body);*/

?>