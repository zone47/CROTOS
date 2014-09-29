<?php
/* / */
/* Harvest WD */
set_time_limit(360000);
error_reporting(E_ALL ^ E_NOTICE);
include "functions.php";

//base temp
$host = '***'; 
$user = '***'; 
$pass = '***'; 
$db = 'crotos_tmp';
$fold_crotos="***/crotos/";

$file_harvest="harvest.php";
$file_compilation="compilation.php";
$file_subclasses="subclasses.php";
$file_optimization="optimization.php";
$file_migr="migr.php";

$mysqldump="***/mysql/bin/mysqldump";
$fic_sql="crotos_tmp.sql";

$ftp_adresse="***";
$ftp_log="***";
$ftp_pass="***";
$ftp_fold="***";
$file_update="***/migr_bdd.php";

// Timer begin
list($g_usec, $g_sec) = explode(" ",microtime());
define ("t_start", (float)$g_usec + (float)$g_sec);

include $file_harvest;

list($g2_usec, $g2_sec) = explode(" ",microtime());
define ("t_end", (float)$g2_usec + (float)$g2_sec);
print "\n".round (t_end-t_start, 1)." secondes";	
list($g_usec3, $g_sec3) = explode(" ",microtime());
define ("t_start2", (float)$g_usec3 + (float)$g_sec3);

include $file_compilation;

include $file_subclasses;

include $file_optimization;

list($g2_usec4, $g2_sec4) = explode(" ",microtime());
define ("t_end2", (float)$g2_usec4 + (float)$g2_sec4);

$link = mysql_connect ($host,$user,$pass) or die ('Erreur : '.mysql_error());
mysql_select_db($db) or die ('Erreur :'.mysql_error());
$nbartw =0;
$sql = "SELECT COUNT(id) as id FROM artworks";
$req = mysql_query($sql);
$data = mysql_fetch_assoc($req);
$nbartw = $data['id'];
mysql_close();
if ($nbartw>10000)
	include $file_migr;

print "\n".round (t_end2-t_start2, 1)." secondes";	
print "\nGlobal : ".round (t_end2-t_start, 1)." secondes";
exec('sudo reboot');
?>