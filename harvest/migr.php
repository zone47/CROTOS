<?php
/* / */
/* FTP migration */
$source_file=$fold_crotos."bdd/".$fic_sql;
$fold_subclasses=$fold_crotos."subclasses/";
$cmd=$mysqldump." -uroot crotos_tmp > ".$source_file;
exec($cmd);

$conn_id = ftp_connect($ftp_adresse) or die("Couldn't connect to ftp_server");
$login_result = ftp_login($conn_id, $ftp_log, $ftp_pass);

$msgftp="";
// Connexion
if ((!$conn_id) || (!$login_result)) 
		echo  "FTP connexion fail";
	else 
		echo " FTP connexion ok ";

// Upload

$upload = ftp_put($conn_id, $ftp_fold."bdd/".$fic_sql, $source_file, FTP_BINARY);
$dir = opendir($fold_subclasses); 
while($file = readdir($dir)) 
	if($file != '.' && $file != '..')
		$upload = ftp_put($conn_id,$ftp_fold."subclasses/".$file, $fold_subclasses.$file, FTP_BINARY);

ftp_quit($conn_id);	

include($file_update);

?>