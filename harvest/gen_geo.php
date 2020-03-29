<?php
/* / */
set_time_limit(360000);
error_reporting(E_ALL & ~E_NOTICE);

//include $fold_crotos."init.php";
$link = mysqli_connect ($host,$user,$pass,$db) or die ('Erreur : '.mysqli_error());
include $fold_harvest."functions_geo.php";
include $file_timer_begin;
mysqli_query($link,"ALTER TABLE `artw_prop` ADD INDEX ( `id_artw` )");
mysqli_query($link,"ALTER TABLE `artw_prop` ADD INDEX ( `id_prop` )");
mysqli_query($link,"ALTER TABLE `label_page` ADD INDEX ( `qwd` )");

$lgs=array("ar","bn","br","ca","cs","cy","da","de","el","en","eo","es","eu","fa","fi","fr","he","hi","id","it","ja","jv","ko","mu","nb","nl","pa","pl","pt","ru","sw","sv","te","th","tr","uk","vi","zh");
//$lgs=array("tr","uk","vi","zh");
for ($i=0;$i<count($lgs);$i++){
$l=$lgs[$i];
$filename="museums_".$l.".geojson";
$file=$fold_crotos."geo/".$filename;
$ficgeo = fopen($file, 'w');
//$file="\xEF\xBB\xBF".$file;
fputs($ficgeo,"{
\"type\": \"FeatureCollection\",
                                                                                
\"features\": [");

$sql="SELECT id, qwd, site, P18, nb, nbimg, lat, lon from p195 WHERE qwd!=0 AND level=0 AND lat!='' AND nbimg!=0";
mysqli_query($link,"SET NAMES 'utf8'");
$rep=mysqli_query($link,$sql);
$cpt=0;
$txt="";
while($data = mysqli_fetch_assoc($rep)) {
	//if ($cpt!=0) fputs($ficgeo,",\n");
	if ($cpt!=0) $txt.=",\n";
	$lb=json_encode(label_item($data["qwd"],$l));
	if ($lg=="mu") $lb="Houba";
	$p18="";
	$thumb_h="";
	if ($data["P18"]!=0){
		$sql="select P18,thumb_h from commons_img where id=".$data["P18"];
		$rep18=mysqli_query($link,$sql);
		if (mysqli_num_rows($rep18)!=0){
			$data_p18 = mysqli_fetch_assoc($rep18);
			$p18=$data_p18["P18"];
			$thumb_h=$data_p18['thumb_h'];
			$thumb_h=str_replace("http://upload.wikimedia.org/wikipedia/commons/","",$thumb_h);
			if ((substr ($thumb_h,-3)=="tif")||(substr ($thumb_h,-3)=="iff"))
				$thumb_h=str_replace("tif/","tif/lossy-page1-",$thumb_h).".jpg";
		}
	}
	$p18=json_encode($p18);
	$thumb_h=json_encode($thumb_h);
	$site=json_encode($data["site"]);
	if ($lg=="mu") $site=json_encode("http://www.houba.com/");
	$txt.="{\"type\":\"Feature\",\"id\":".$cpt.",\"properties\":{\"q\":".$data["qwd"].",\"l\":".$lb.",\"n\": \"".$data["nbimg"]."\",\"i\":".$p18.",\"t\":".$thumb_h.",\"u\":".$site."},\"geometry\":{\"type\":\"Point\",\"coordinates\":[".$data["lon"].",".$data["lat"]."]}}";
	$cpt++;
}
fputs($ficgeo,$txt);
fputs($ficgeo,"]
}");
fclose($ficgeo);

$conn_id = ftp_connect($ftp_adresse) or die("Couldn't connect to ftp_server");
$login_result = ftp_login($conn_id, $ftp_log, $ftp_pass);
ftp_pasv($conn_id, true);
$upload = ftp_put($conn_id, $ftp_fold."geo/".$filename, $file, FTP_BINARY);
ftp_quit($conn_id);	

$filename="depicts_".$l.".geojson";
$file=$fold_crotos."geo/".$filename;
$ficgeo = fopen($file, 'w');
//$file="\xEF\xBB\xBF".$file;
fputs($ficgeo,"{
\"type\": \"FeatureCollection\",
                                                                                
\"features\": [");

$sql="SELECT id, qwd, nb, nbimg, lat, lon from p180 WHERE qwd!=0 AND lat!='' AND nbimg!=0";
mysqli_query($link,"SET NAMES 'utf8'");
$rep=mysqli_query($link,$sql);
$cpt=0;
$txt="";
while($data = mysqli_fetch_assoc($rep)) {
	//if ($cpt!=0) fputs($ficgeo,",\n");
	if ($cpt!=0) $txt.=",\n";
	$lb=json_encode(label_item($data["qwd"],$l));
	if ($lg=="mu") $lb="Houba";
	$p18="";
	$thumb_h="";
	
	$sql="select commons_img.P18 as P18,commons_img.thumb_h as thumb_h from artw_prop,artworks, commons_img WHERE artw_prop.id_prop=".$data['id']." AND artw_prop.prop=180 AND artw_prop.id_artw=artworks.id AND artworks.P18=commons_img.id order by rand() limit 1";
	$rep18=mysqli_query($link,$sql);
	if (mysqli_num_rows($rep18)!=0){
		$data_p18 = mysqli_fetch_assoc($rep18);
		$p18=$data_p18["P18"];
		$thumb_h=$data_p18['thumb_h'];
		$thumb_h=str_replace("http://upload.wikimedia.org/wikipedia/commons/","",$thumb_h);
		if ((substr ($thumb_h,-3)=="tif")||(substr ($thumb_h,-3)=="iff"))
			$thumb_h=str_replace("tif/","tif/lossy-page1-",$thumb_h).".jpg";
	}

	$p18=json_encode($p18);
	$thumb_h=json_encode($thumb_h);
	$site=json_encode($data["site"]);
	$txt.="{\"type\":\"Feature\",\"id\":".$cpt.",\"properties\":{\"q\":".$data["qwd"].",\"l\":".$lb.",\"n\": \"".$data["nbimg"]."\",\"i\":".$p18.",\"t\":".$thumb_h."},\"geometry\":{\"type\":\"Point\",\"coordinates\":[".$data["lon"].",".$data["lat"]."]}}";
	
	$cpt++;
}
fputs($ficgeo,$txt);
fputs($ficgeo,"]
}");
fclose($ficgeo);
$conn_id = ftp_connect($ftp_adresse) or die("Couldn't connect to ftp_server");
$login_result = ftp_login($conn_id, $ftp_log, $ftp_pass);
ftp_pasv($conn_id, true);
$upload = ftp_put($conn_id, $ftp_fold."geo/".$filename, $file, FTP_BINARY);
ftp_quit($conn_id);	

$filename="artworks_".$l.".geojson";
$file=$fold_crotos."geo/".$filename;
$ficgeo = fopen($file, 'w');
//$file="\xEF\xBB\xBF".$file;
fputs($ficgeo,"{
\"type\": \"FeatureCollection\",
                                                                                
\"features\": [");


$sql="SELECT id, qwd, P18, lat, lon from artworks WHERE qwd!=0 AND lat!='' AND P18!=0";
mysqli_query($link,"SET NAMES 'utf8'");
$rep=mysqli_query($link,$sql);
$cpt=0;
$txt="";
while($data = mysqli_fetch_assoc($rep)) {
	//if ($cpt!=0) fputs($ficgeo,",\n");
	if ($cpt!=0) $txt.=",\n";
	$lb=json_encode(label_item($data["qwd"],$l));
	if ($lg=="mu") $lb="Houba";
	$p18="";
	$thumb_h="";
	if ($data["P18"]!=0){
		$sql="select P18,thumb_h from commons_img where id=".$data["P18"];
		$rep18=mysqli_query($link,$sql);
		if (mysqli_num_rows($rep18)!=0){
			$data_p18 = mysqli_fetch_assoc($rep18);
			$p18=$data_p18["P18"];
			$thumb_h=$data_p18['thumb_h'];
			$thumb_h=str_replace("http://upload.wikimedia.org/wikipedia/commons/","",$thumb_h);
			if ((substr ($thumb_h,-3)=="tif")||(substr ($thumb_h,-3)=="iff"))
				$thumb_h=str_replace("tif/","tif/lossy-page1-",$thumb_h).".jpg";
		}
	}
	$p18=json_encode($p18);
	$thumb_h=json_encode($thumb_h);
	$crea=txt_prop($data["id"],170,$l,"",false);
	if ($lg=="mu") $crea="Houba";
	//$crea=json_encode(str_replace("?p170","/crotos/?p170",str_replace(" class=\\\"lien_aut\\\"","",$crea)));
	$crea=json_encode(str_replace("?p170","/crotos/?p170",$crea));
	$txt.="{\"type\":\"Feature\",\"id\":".$cpt.",\"properties\":{\"q\":".$data["qwd"].",\"l\":".$lb.",\"n\": \"".$data["nbimg"]."\",\"i\":".$p18.",\"t\":".$thumb_h.",\"c\":".$crea."},\"geometry\":{\"type\":\"Point\",\"coordinates\":[".$data["lon"].",".$data["lat"]."]}}";
	$cpt++;
}
fputs($ficgeo,$txt);
fputs($ficgeo,"]
}");
fclose($ficgeo);
$conn_id = ftp_connect($ftp_adresse) or die("Couldn't connect to ftp_server");
$login_result = ftp_login($conn_id, $ftp_log, $ftp_pass);
ftp_pasv($conn_id, true);
$upload = ftp_put($conn_id, $ftp_fold."geo/".$filename, $file, FTP_BINARY);
ftp_quit($conn_id);	
}
mysqli_query($link,"ALTER TABLE `artw_prop` DROP INDEX id_artw");
mysqli_query($link,"ALTER TABLE `artw_prop` DROP INDEX id_prop");
mysqli_query($link,"ALTER TABLE `label_page` DROP INDEX qwd");
include $file_timer_end;

mysqli_close($link);
echo "gen geo done";
?>