<?php
/* / */
/* FTP migration */
$link = mysqli_connect ($host,$user,$pass,$db) or die ('Erreur : '.mysqli_error());
$nbartw =0;
$sql = "SELECT COUNT(id) as id FROM artworks";
$req = mysqli_query($link,$sql);
$data = mysqli_fetch_assoc($req);
$nbartw = $data['id'];

if ($nbartw>10000){
    mysqli_query($link,"ALTER TABLE `artw_prop` ADD INDEX ( `id_artw` )");
    mysqli_query($link,"ALTER TABLE `artw_prop` ADD INDEX ( `id_prop` )");
    mysqli_query($link,"ALTER TABLE `label_page` ADD INDEX ( `qwd` )");
    mysqli_query($link,"ALTER TABLE `label_page` ADD INDEX(`label`)");
    $sql2 = "SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES
        WHERE TABLE_SCHEMA = '".$db."'"; 
     //   AND ENGINE = 'MyISAM'";
    $rs = mysqli_query($link,$sql2);
    while($row = mysqli_fetch_array($rs))
    {
        $tbl = $row[0];
        $sql = "ALTER TABLE `$tbl` ENGINE=MyISAM";
        mysqli_query($link,$sql);
    }

	echo "\nMigration and update";
	include $file_timer_begin;
	
	$source_file=$fold_crotos."bdd/".$fic_sql;
	$cmd=$mysqldump." -uroot ".$db." > ".$source_file;
	exec($cmd);
    
    mysqli_query($link,"ALTER TABLE `artw_prop` DROP INDEX id_artw");
    mysqli_query($link,"ALTER TABLE `artw_prop` DROP INDEX id_prop");
    mysqli_query($link,"ALTER TABLE `label_page` DROP INDEX qwd");
    mysqli_query($link,"ALTER TABLE `label_page` DROP INDEX label");
    $sql2 = "SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES
        WHERE TABLE_SCHEMA = '".$db."'"; 
     //   AND ENGINE = 'MyISAM'";
    $rs = mysqli_query($link,$sql2);
    while($row = mysqli_fetch_array($rs))
    {
        $tbl = $row[0];
        $sql = "ALTER TABLE `$tbl` ENGINE=INNODB";
        mysqli_query($link,$sql);
    }
    
    
	$zip_file=$fold_crotos."bdd/".$fic_sql_zip;
	$cmd=$zip_exe." a -tzip ".$zip_file." ".$source_file;
	exec($cmd);
	
	$conn_id = ftp_connect($ftp_adresse) or die("Couldn't connect to ftp_server");
	$login_result = ftp_login($conn_id, $ftp_log, $ftp_pass);
	ftp_pasv($conn_id, true);
	// Connexion
	if ((!$conn_id) || (!$login_result)) 
			echo  "FTP connexion fail";
		else 
			echo " FTP connexion ok ";
	
	// Upload
    ftp_pasv($conn_id, true);
	$upload = ftp_put($conn_id, $ftp_fold."bdd/".$fic_sql_zip, $zip_file, FTP_BINARY);
	
	ftp_quit($conn_id);	
	$cmd="del /Q ".str_replace("/","\\",$fold_crotos)."bdd\\".$fic_sql_zip;
	exec($cmd);
	include($file_maj);
	include($file_update);
	
	echo "\nMigration done";
	include $file_timer_end;
}
else 
	echo "\nNot enough data to migrate and update";
mysqli_close($link);
?>