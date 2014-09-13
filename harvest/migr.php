<?php
/* / */
/* Harvest WD */
$fic_sql="crotos_tmp.sql";
$dossier="/***/bdd/";
$source_file=$dossier.$fic_sql;
$cmd="mysqldump -h ".$host." -u ".$user." -p".$pass." ".$db." > /***/bdd/".$fic_sql;
exec($cmd);

// Création de la connexion
$conn_id = ftp_connect("***") or die("Couldn't connect to ftp_server");

// Authentification avec nom de compte et mot de passe
$login_result = ftp_login($conn_id, "***", "***");

$msgftp="";
// Vérification de la connexion
if ((!$conn_id) || (!$login_result)) {
		$msgftp.="La connexion FTP a échoué!";
		die;
	}
	else 
	echo " ok ftp ";
// Téléchargement d'un fichier.
//echo $ftp_dossier.$name.".sql<br>".$source_file.".sql<br>;
$upload = ftp_put($conn_id, "/***/bdd/".$fic_sql, $source_file, FTP_BINARY);


// Vérification de téléchargement
if (!$upload) {
		$msgftp.="Le transfert par FTP a échoué!";
	} else {
		$msgftp.="Téléchargement de $source_file sur $ftp_adresse ";
	}

// Fermeture de la connexion FTP.
ftp_quit($conn_id);	

include('***');

?>