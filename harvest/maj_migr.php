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
$db = '***';

// Timer begin
list($g_usec, $g_sec) = explode(" ",microtime());
define ("t_start", (float)$g_usec + (float)$g_sec);

//include "harvest.php";

list($g2_usec, $g2_sec) = explode(" ",microtime());
define ("t_end", (float)$g2_usec + (float)$g2_sec);
print "\n".round (t_end-t_start, 1)." secondes";	
list($g_usec3, $g_sec3) = explode(" ",microtime());
define ("t_start2", (float)$g_usec3 + (float)$g_sec3);

//include "compilation.php";

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
	include "migr.php";

print "\n".round (t_end2-t_start2, 1)." secondes";	
print "\nGlobal : ".round (t_end2-t_start, 1)." secondes";
?>