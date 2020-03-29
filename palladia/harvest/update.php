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
$path="****";

$link = mysqli_connect ($host,$user,$pass,$db) or die ('Erreur : '.mysqli_error());

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

?>