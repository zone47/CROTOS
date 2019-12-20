<?php
error_reporting(E_ALL & ~E_NOTICE);
$host = '***'; 
$user = '***'; 
$pass = '***'; 
$db = 'crotos_llgcnlw';
$fold_crotos="***/crotos/dwynwen/";
$fold_harvest="***/crotos/harvest/";


$file_harvest="harvest.php";
$file_harvest_props=$fold_harvest."harvest_props.php";
$file_compilation="compilation.php";
$file_subclasses="props_sub.php";
$file_nb_labels=$fold_harvest."nb_labels.php";
$file_optimization=$fold_harvest."optimization.php";
$file_migr="migr.php";
$file_timer_begin=$fold_harvest."timer_begin.php";
$file_timer_end=$fold_harvest."timer_end.php";
$file_gen_geo="gen_geo.php";

$mysqldump="C:/xampp/mysql/bin/mysqldump";
$fic_sql="crotos_tmp.sql";
$zip_exe="\"C:/Program Files/7-Zip/7z\"";
$fic_sql_zip="crotos_tmp.sql.zip";


$ftp_adresse="***";
$ftp_log="***";
$ftp_pass="***";
$ftp_fold="/zone47/crotos/dwynwen/";
$file_maj="http://zone47.com/crotos/dwynwen/harvest/migr_bdd.php";
$file_update="http://zone47.com/crotos/dwynwen/harvest/update.php";

?>