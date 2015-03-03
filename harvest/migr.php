<?php
/* / */
/* FTP migration */
$link = mysqli_connect ($host,$user,$pass,$db) or die ('Erreur : '.mysqli_error());
$nbartw =0;
$sql = "SELECT COUNT(id) as id FROM artworks";
$req = mysqli_query($link,$sql);
$data = mysqli_fetch_assoc($req);
$nbartw = $data['id'];
mysqli_close($link);
if ($nbartw>10000){
	echo "\nMigration and update";
	include $file_timer_begin;
	
	$source_file=$fold_crotos."bdd/".$fic_sql;
	$cmd=$mysqldump." -uroot crotos_tmp > ".$source_file;
	//$cmd="mysqldump -h ".$host." -u ".$user." -p".$pass." ".$db." > ".$fold_crotos."bdd/".$fic_sql;
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
	
	ftp_quit($conn_id);	
	
	include($file_update);
	
	echo "\nMigration done";
	include $file_timer_end;
}
else 
	echo "\nNot enough data to migrate and update";
?>