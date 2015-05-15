<?php
/* / */
/* Harvest WD */
set_time_limit(360000);
error_reporting(E_ALL ^ E_NOTICE);
include "functions.php";
include "config_harvest.php";

list($g_usec, $g_sec) = explode(" ",microtime());
$t_start_glob=(float)$g_usec + (float)$g_sec;

include $file_harvest;

include $file_harvest_props;

include $file_compilation;

include $file_subclasses;

include $file_nb_labels;

include $file_optimization;

include $file_migr;

list($g2_usec, $g2_sec) = explode(" ",microtime());
$t_end_glob=(float)$g2_usec + (float)$g2_sec;
print "\nGlobal duration: ".gmdate("H:i:s",round ($t_end_glob-$t_start_glob, 1));

?>