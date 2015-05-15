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
if ($nbartw>45000){
	echo "\nMigration and update";
	include $file_timer_begin;
	
	$source_file=$fold_crotos."bdd/".$fic_sql;
	$cmd=$mysqldump." -uroot ".$db." > ".$source_file;
	exec($cmd);
	$zip_file=$fold_crotos."bdd/".$fic_sql_zip;
	$cmd=$zip_exe." a -tzip ".$zip_file." ".$source_file;
	exec($cmd);
	
	$conn_id = ftp_connect($ftp_adresse) or die("Couldn't connect to ftp_server");
	$login_result = ftp_login($conn_id, $ftp_log, $ftp_pass);
	
	// Connexion
	if ((!$conn_id) || (!$login_result)) 
			echo  "FTP connexion fail";
		else 
			echo " FTP connexion ok ";
	
	// Upload
	$upload = ftp_put($conn_id, $ftp_fold."bdd/".$fic_sql_zip, $zip_file, FTP_BINARY);
	
	ftp_quit($conn_id);	
	$cmd="del /Q ".str_replace("/","\\",$fold_crotos)."bdd\\".$fic_sql_zip;
	exec($cmd);
	include($file_update);
	
	echo "\nMigration done";
	include $file_timer_end;
}
else 
	echo "\nNot enough data to migrate and update";
?>