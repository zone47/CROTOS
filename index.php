<?php
/* / */
include "config.php";
include "init.php";
include "traduction.php";
include "functions.php";

$link = mysqli_connect ($host,$user,$pass,$db) or die ('Erreur : '.mysqli_error());
mysqli_query($link,"SET NAMES 'utf8'");

$deb=($p-1)*$nb;

// Timer begin
list($g_usec, $g_sec) = explode(" ",microtime());
define ("t_start", (float)$g_usec + (float)$g_sec);

//Search queries
if ($new)
	include "queries_new.php";
else
	include "queries.php";

if ($p!=1) // hack if $p indicated out of range because of $nb parameter
	if (($p-1)*$nb>$num_rows){
		$new_url="http://www.zone47.com/crotos/?";
		foreach($_GET as $key => $value) 
			if ($key!="p")
				$new_url.="&".$key."=".$value;
		header("Location:".$new_url);
	}
	
$nbpg=ceil($num_rows/$nb); // number of pages

//Suggests if home or random choice
if (($random)||($num_rows==0)){
	$alea_prop=array(170,135,136,180,144,921);
	$rand_keys = array_rand($alea_prop, 2);
	$alea_bar="";
	for ($i=0;$i<2;$i++){
		$alea_item=$alea_prop[$rand_keys[$i]];
		$sql_r="SELECT label,qwd FROM label_page WHERE type='1' AND prop=".$alea_item." AND lg='$l' AND label !='' ORDER BY RAND() LIMIT 0,1";
		$rep_r=mysqli_query($link,$sql_r);
		if (mysqli_num_rows($rep_r)!=0){
			$data_r = mysqli_fetch_assoc($rep_r);
			if ($alea_bar!="")
				$alea_bar.=", ";
			$alea_bar.="<a href=\"?p".$alea_item."=".$data_r['qwd']."\">".$data_r['label']."</a>";	
		}
	}
	$alea_bar=translate($l,"suggest")." : ".$alea_bar;
}

// navigation link
$liennav="";
if ($nb!="20") $liennav.="&amp;nb=".$nb;
foreach($tab_idx as $key=>$value)
	if ($value!="")
		$liennav.="&amp;$key=".$value;
foreach($tab_miss as $key=>$value)
	if ($value!="")
		$liennav.="&amp;$key=".$value;
foreach($tab_check as $key=>$value)
	if ($value!="")
		$liennav.="&amp;$key=".$value;
if ($s!="") $liennav.="&amp;s=".$s;
if ($y1!="") $liennav.="&amp;y1=".$y1;
if ($y2!="") $liennav.="&amp;y2=".$y2;

include "text_nav.php";
?><!doctype html>
<html lang="<?php echo $l ?>">
<head>
	<meta charset="utf-8">
    <meta name="author" content="/* / */" />
    <title><?php
foreach($tab_idx as $key=>$value)
	if ($value!="")
		echo txt_prop(0,$value,$l,"normal",0,0)." - ";
?>Crotos</title>
	<meta name="description" content="Crotos<?php
foreach($tab_idx as $key=>$value)
	if ($value!="")
		echo " - ".txt_prop(0,$value,$l,"normal",0,0);
$txt_res="";
if ($num_rows<2)
	$txt_res.=$num_rows." ".mb_ucfirst(translate($l,"result"));
elseif ($num_rows<=$nb)
	$txt_res.=$num_rows_ec." ".mb_ucfirst(translate($l,"results"));
else {
	$txt_res.=mb_ucfirst(translate($l,"results"))." ".($deb+1)." - ".($deb+$num_rows_ec)." ".translate($l,"of")." ".$num_rows;
}
echo " - ".$txt_res;
?>" />
    <link rel="icon" href="favicon.ico" />
<?php
    if ($disp==0)
		echo "<link rel=\"stylesheet\" href=\"css/night.css\">";
	else
		echo "<link rel=\"stylesheet\" href=\"css/day.css\">";
?>
    <link rel="stylesheet" href="css/styles.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" media="screen and (max-width: 945px)" href="css/medium.css" type="text/css" />
    <link rel="stylesheet" media="screen and (max-width: 700px)" href="css/small.css" type="text/css" />
    <link rel="stylesheet" href="css/jquery-ui.css">
	<script src="js/jquery.js"></script>
	<script src="js/jquery-ui.min.js"></script>
    <script src="js/imagesloaded.pkgd.min.js"></script>
    <script src="js/yoxview/yoxview-init.js"></script>
    <script>
function loadSprite(id_link,src, callback) { 
	var sprite = new Image();
	sprite.onload = callback;
	sprite.src = src; 
	$("#"+id_link).children("img").attr("src",src);

}
function img_magnify(){ 
	$(".yox,.solo").each(function( index ) {
		id_link=$(this).attr('id');
		fic=$("#"+id_link).attr("data-file");
		loadSprite(id_link,fic, function() {});
	});
}
function init_display(){ 
	if ($(window).width()>=680){
		$(".yoxview").yoxview({
			linkToOriginalContext:true,
			cacheImagesInBackground:true,
			renderInfoPin:false,
			<?php 
			echo "close_popin:\"".translate($l,"close")."\"";
			if (($l=="ar")||($l=="fa")||($l=="he"))
				echo ",
			isRTL:true";
			?>
		});
		$( ".item" ).each(function( index ) {
			if (!($(this).hasClass("solo")))
				$(this).css("width",$(this).attr("data-width"));
		});
		$( ".thumb a" ).each(function( index ) {
			this.href=$(this).attr("data-commons");
			if (!($(this).hasClass("linksolo")))
				$(this).children("img").attr("src",$(this).children("img").attr("data-img"));
		});
	}
	else{
		$(".item").css("width","100%");
		$( ".thumb a" ).each(function( index ) {
			this.href=$(this).attr("data-file");
		});
	}
}
$(document).ready(function() {
<?php if ($num_rows>1){ ?>
	function preload(arrayOfImages) { $(arrayOfImages).each(function () { $('<img />').attr('src',this).appendTo('body').css('display','none'); }); }
	preload(['img/arrow_down.png','img/arrow_down_day.png','img/magnifying_on.png']);
<?php }
else{  
	if ($disp==0)
		echo "$(\"#iconot1\").attr(\"src\",\"img/arrow_up.png\");";
	else
		echo "$(\"#iconot1\").attr(\"src\",\"img/arrow_up_day.png\");";
}
?>	
	$('#lg,#nb,#d1,#d2,#listp31,#miss_props input').change(function() {
		$('#form').submit();
	});
	$(".lien_notice").click(function() {
		var id_not = "#notice"+this.id.replace("iconot","");
		if($(id_not).css('display') == 'none'){
			$(id_not).css({ display: "block" });
<?php
if ($disp==0)
	echo "			$(\"#\"+this.id).attr(\"src\",\"img/arrow_up.png\");";
else
	echo "			$(\"#\"+this.id).attr(\"src\",\"img/arrow_up_day.png\");";
?>
		}
		else{
			$(id_not).css({ display: "none" });
<?php
if ($disp==0)
	echo "			$(\"#\"+this.id).attr(\"src\",\"img/arrow_down.png\");";
else
	echo "			$(\"#\"+this.id).attr(\"src\",\"img/arrow_down_day.png\");";
?>
		}
	});

	var trueValues = [-40000,-30000,-20000,-10000,-8000,-6000,-4000,-3500,-3000,-2500,-2000,-1800,-1600,-1400,-1200,-1000,-800,-700,-600,-500,-400,-300,-200,-100,1,100,200,300,400,500,600,700,800,900,1000,1050,1100,1150,1200,1250,1300,1350,1400,1420,1440,1460,1480,1490,1500,1510,1520,1530,1540,1550,1560,1570,1580,1590,1600,1610,1620,1630,1640,1650,1660,1670,1680,1690,1700,1710,1720,1730,1740,1750,1760,1770,1780,1790,1800,1810,1820,1830,1840,1850,1860,1870,1880,1890,1900,1910,1920,1930,1940,1950,1960,1970,1980,1990,2000,2010,2014];
	var values =[ 0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42,43,44,45,46,47,48,49,50,51,52,53,54,55,56,57,58,59,60,61,62,63,64,65,66,67,68,69,70,71,72,73,74,75,76,77,78,79,80,81,82,83,84,85,86,87,88,89,90,91,92,93,94,95,96,97,98,99,100];
	var slider =$("#slider-range").slider({
		range: true,
        min:0,
        max:100,
<?php
$pos_y1=0;
$pos_y2=100;
if (($y1!=-40000)||($y2!=2014))
	$date_pos=array(0 => -40000,1 => -30000,2 => -20000,3 => -10000,4 => -8000,5 => -6000,6 => -4000,7 => -3500,8 => -3000,9 => -2500,10 => -2000,11 => -1800,12 => -1600,13 => -1400,14 => -1200,15 => -1000,16 => -800,17 => -700,18 => -600,19 => -500,20 => -400,21 => -300,22 => -200,23 => -100,24 => 1,25 => 100,26 => 200,27 => 300,28 => 400,29 => 500,30 => 600,31 => 700,32 => 800,33 => 900,34 => 1000,35 => 1050,36 => 1100,37 => 1150,38 => 1200,39 => 1250,40 => 1300,41 => 1350,42 => 1400,43 => 1420,44 => 1440,45 => 1460,46 => 1480,47 => 1490,48 => 1500,49 => 1510,50 => 1520,51 => 1530,52 => 1540,53 => 1550,54 => 1560,55 => 1570,56 => 1580,57 => 1590,58 => 1600,59 => 1610,60 => 1620,61 => 1630,62 => 1640,63 => 1650,64 => 1660,65 => 1670,66 => 1680,67 => 1690,68 => 1700,69 => 1710,70 => 1720,71 => 1730,72 => 1740,73 => 1750,74 => 1760,75 => 1770,76 => 1780,77 => 1790,78 => 1800,79 => 1810,80 => 1820,81 => 1830,82 => 1840,83 => 1850,84 => 1860,85 => 1870,86 => 1880,87 => 1890,88 => 1900,89 => 1910,90 => 1920,91 => 1930,92 => 1940,93 => 1950,94 => 1960,95 => 1970,96 => 1980,97 => 1990,98 => 2000,99 => 2010,100 => 2014);
if ($y1!=-40000){
	for ($i=100;$i>-1;$i--){
		if ($y1>=$date_pos[$i]){
			$pos_y1=$i;	
			break;
		}
	}
}
else
	$pos_y1=0;
if ($y2!=2014){
	for ($i=0;$i<101;$i++){
		if ($y2<=$date_pos[$i]){
			$pos_y2=$i;	
			break;
		}
	}
}
else
	$pos_y2=100;
?>
        values: [<?php echo "$pos_y1,$pos_y2" ?>],
        slide: function(event, ui) {
			var includeLeft = event.keyCode != $.ui.keyCode.RIGHT;
            var includeRight = event.keyCode != $.ui.keyCode.LEFT;
            var value = findNearest(includeLeft, includeRight, ui.value);
            if (ui.value == ui.values[0])
                slider.slider('values', 0, value);
            else
                slider.slider('values', 1, value);
			$("#amount1" ).val(getRealValue(slider.slider('values', 0)));
			$("#amount2" ).val(getRealValue(slider.slider('values', 1)));
            return false;
			
        }
    });
	function findNearest(includeLeft, includeRight, value) {
        var nearest = null;
        var diff = null;
        for (var i = 0; i < values.length; i++) {
            if ((includeLeft && values[i] <= value) || (includeRight && values[i] >= value)) {
                var newDiff = Math.abs(value - values[i]);
                if (diff == null || newDiff < diff) {
                    nearest = values[i];
                    diff = newDiff;
                }
            }
        }
        return nearest;
    }
	function idxdate(sens,date) {
		if (sens==0){
			pos=0;
			for (var i = 0; i < 100; i++) {
	            if (date>=trueValues[i]) 
					pos=i;
				else
					break;
        	}
		}
		else{
			pos=100;
			for (var i = 100; i > -1; i--) {
	            if (date<=trueValues[i]) 
					pos=i;
				else
					break;
        	}
		}
        return pos;
    }
	function getRealValue(sliderValue) {
        for (var i = 0; i < values.length; i++) {
            if (values[i] >= sliderValue) {
                return trueValues[i];
            }
        }
        return 0;
    }
    $("input.sliderValue").change(function() {
        var $this = $(this);
		pos=idxdate($this.data("index"),$this.val());
        $("#slider-range").slider("values", $this.data("index"),pos);
    });

	init_display();
});
$(window).bind('resize', function(e)
{
	init_display();
	if ($(window).width()<=680){
		img_magnify();
	}
});
$(window).load(function() {
	if ($(window).width()<=680){
		img_magnify();
	}
});
</script>
    <style><?php
	if (($l=="ar")||($l=="fa")||($l=="he"))
    	echo "
.yoxview_imgPanel{direction: rtl;}
#yoxview{text-align:right}";
	
    ?></style>
</head>
<?php
	if (($l=="ar")||($l=="fa")||($l=="he"))
		echo "<body id=\"lg_rtl\" >\n";
	else
		echo "<body>\n";
?>
	<div id="global">
<nav>
	<ul id="access">
		<li><a href="#contenu"><?php echo translate($l,"content") ?></a></li>
    	<li><a href="#topic_title"><?php echo translate($l,"search") ?></a></li>
	</ul>
</nav>
<div id="entete">
    <div id="bl_titre">
        <a href="/crotos/" title="Crotos"><img src="img/crotos.jpg" alt="CROTOS" width="108" height="120" id="img_crotos"/></a>
        <a href="/crotos/" title="Crotos"><h1><?php /* Easter egg */if ($l=="mu") echo "HOUBA"; else echo "Crotos"; ?></h1></a>
    </div>
	<?php include "form.php" ?>    
</div>

<div class="nav navhaut">
    <div>
		<span class="nav_list"><?php  echo $txtnav; ?></span>
        <?php
        if (($random)||($num_rows==0))
			echo "<span class=\"alea\">".$alea_bar."</span>";
		?>
	</div>
</div>

<?php
if ($num_rows>1)
	echo "<div id=\"contenu\" class=\"yoxview\" >";
else
	echo "<div id=\"contenu\" class=\"yoxview contentsolo\" >";	
?>
<?php
$cpt=0;
while($data = mysqli_fetch_assoc($rep)) {
	$content="";
	$cpt++;
	$id_artw=$data['id'];
	$qwd_art=$data['qwd'];
	$inv=$data['P217'];
	$described_link=$data['link'];
	$titre="";
	$titre=label_item($qwd_art,$l);
	$trunc_title=truncate($titre);
	$trunk=false;
	if ($titre!=$trunc_title)
		$trunk=true;
	$alias=alias_item($qwd_art,$l);
	$creator=txt_prop($id_artw,170,$l,"creator",false);
	$pageWP=page_item($qwd_art,$l);
	$lgWP="";
	$pos=strpos($pageWP,"|");
	if ($pos){
		$lgWP=substr($pageWP,0,$pos);
		$pageWP=substr($pageWP,$pos+1,strlen($pageWP));
	}
	
	$coll0=val_0($id_artw,195,$l);
	$location0=val_0($id_artw,275,$l);

	$coll_or_loc=$coll0;
	if ($coll0=="")
		$coll_or_loc=$location0;
	
	$location=txt_prop($id_artw,276,$l);
	$collection=txt_prop($id_artw,195,$l);
	
	$loc_link=local_link($id_artw,195,$l);
	if ($loc_link=="")
		$loc_link=local_link($id_artw,276,$l);
	
	$type=txt_prop($id_artw,31,$l,"normal",0);
	$material=txt_prop($id_artw,186,$l);
	$series=txt_prop($id_artw,179,$l);
	$partof=txt_prop($id_artw,361,$l);
	$mouvement=txt_prop($id_artw,135,$l);
	$genre=txt_prop($id_artw,136,$l);
	$depicts=txt_prop($id_artw,180,$l);
	$based=txt_prop($id_artw,144,$l);
	$subject=txt_prop($id_artw,921,$l);
	$inspired=txt_prop($id_artw,941,$l);
	$pendant=txt_prop($id_artw,1639,$l);
	
	$p18=$data['P18'];
	
	$commons_artist="";
	$commons_credit="";
	$license="";
	$thumb="";
	$thumb_h="";
	$width_h=0;
	$large="";
	if ($p18!=0){
		$sql="select * from commons_img where id=".$p18;
		$rep18=mysqli_query($link,$sql);
		if (mysqli_num_rows($rep18)!=0){
			$data_p18 = mysqli_fetch_assoc($rep18);
			$p18_str=$data_p18['P18'];
			$commons_artist=$data_p18['commons_artist'];
			$commons_credit=$data_p18['commons_credit'];
			$license=$data_p18['commons_license'];
			$thumb_h=$data_p18['thumb_h'];
			$width_h=$data_p18['width_h'];
			$large=$data_p18['large'];
			// Hack to move to compilation
			if ((substr ($thumb_h,-3)=="tif")||(substr ($thumb_h,-3)=="iff")){
				$width=$data_p18['width'];
				$thumb_h=str_replace("tif/","tif/lossy-page1-",$thumb_h).".jpg";
				$large=str_replace($width_h,$width,$thumb_h);
			}
		}
	}
	
	if (intval($width_h)<201)
		$width_item=202;
	else
		$width_item=intval($width_h)+2;	
	
	if ($num_rows>1){
		$content.="	<div style=\"width:".$width_item."px\" class=\"item\" data-width=\"".$width_item."px\" >\n";
	}
	else{ 
		if ($large!=""){
			$width_big_img=0;
			
			if (strpos($large,"commons/thumb")){
				preg_match('#/[0-9]*px-#',$large,$matches);
				if ($matches)
					$width_big_img=intval(str_replace("/","",str_replace("px-","",$matches[0])));
			}
			else{
				$sql="select width from commons_img where id=".$p18;
				$rep_img=mysqli_query($link,$sql);
				$num_rows = mysqli_num_rows($rep_img);
				if ($num_rows!=0){
					$data_img = mysqli_fetch_assoc($rep_img);
					$width_big_img=intval($data_img['width']);
				}
				
			}
			if ($width_big_img<348)
				$width_big_img=348;
			$width_big_img+=2;	

			$content.="	<div class=\"item solo\" style=\"width:100%;max-width:".$width_big_img."px;\" data-width=\"".$width_item."px\">\n";
		}
		else{
			$content.="	<div class=\"item solo\" style=\"width:".$width_item."px\" data-width=\"".$width_item."px\">\n";
		}
	}
		
	if ($num_rows>1)
		$content.="		<div class=\"thumb multiimg\"><div>";
	else
		$content.="		<div class=\"thumb soloimg\"><div>";
	if ($thumb_h!=""){
		if ($license!=""){
			if ($license=="pd")
				$license=ucfirst(translate($l,"pd"));
			else{
				switch ($license){
					case "cc0":
						$license="<a href=\"http://creativecommons.org/publicdomain/zero/1.0/\" class=\"externe\"><b>cc0</b></a>";
						break;
					case "GFDL-1.2":
						$license="<a href=\"https://commons.wikimedia.org/wiki/Commons:GNU_Free_Documentation_License,_version_1.2\" class=\"externe\"><b>GFDL-1.2</b></a>";
						break;
					case "FAL":
						$license="<a href=\"https://commons.wikimedia.org/wiki/Commons:Free_Art_License_1.3\" class=\"externe\"><b>Free Art License</b></a>";
						break;
					default:
						if ((substr($license,3,5)=="by-sa")||(substr($license,3,5)=="by-nc")){
							if (strlen($license)==12)
								$license="<a href=\"http://creativecommons.org/licenses/".substr($license,3,5)."/".substr($license,9,3)."/deed.".$l."\" class=\"externe\"><b>".$license."</b></a>";
							elseif (strlen($license)==15)
								$license="<a href=\"http://creativecommons.org/licenses/".substr($license,3,5)."/".substr($license,9,3)."/".substr($license,13,2)."/deed.".$l."\" class=\"externe\"><b>".$license."</b></a>";
						}
						elseif ((substr($license,3,2)=="by")||(substr($license,3,2)=="sa")){
							if (strlen($license)==9)
								$license="<a href=\"http://creativecommons.org/licenses/".substr($license,3,2)."/".substr($license,6,3)."/deed.".$l."\" class=\"externe\"><b>".$license."</b></a>";
							elseif (strlen($license)==12)
								$license="<a href=\"http://creativecommons.org/licenses/".substr($license,3,2)."/".substr($license,6,3)."/".substr($license,10,2)."/deed.".$l."\" class=\"externe\"><b>".$license."</b></a>";
						}
				}
			}
			$license=esc_dblq(htmlentities($license));
		}
		$commons_artist = esc_dblq(htmlentities(preg_replace("/<\/?div[^>]*\>/i", "",$commons_artist)));
		$commons_link="http://commons.wikimedia.org/wiki/File:".htmlentities(str_replace("?","%3F",str_replace(" ","_",$p18_str)));
		$commons_credit = esc_dblq(htmlentities(preg_replace("/<img[^>]+\>/i", "",preg_replace("/<\/?td[^>]*\>/i", "",preg_replace("/<\/?tr[^>]*\>/i", "",preg_replace("/<\/?table[^>]*\>/i", "",preg_replace("/<\/?li[^>]*\>/i", "", preg_replace("/<\/?ul[^>]*\>/i", "", preg_replace("/<\/?hr[^>]*\>/i", "", preg_replace("/<\/?p[^>]*\>/i", "", preg_replace("/<\/?div[^>]*\>/i", "", $commons_credit)))))))))));
		$credits=$commons_artist;
		if (($credits!="")&&($license!=""))
			$credits.=" | ";
		$credits.=$license;
		if (($credits!="")&&($commons_credit!=""))
			$credits.=" | ";
		$credits.=$commons_credit;
		if ($num_rows>1)
			$content.="<a href=\"".$commons_link."\" data-file=\"".esc_dblq($large)."\" data-commons=\"".$commons_link."\" class=\"yox\" id=\"link$cpt\"><img src=\"".esc_dblq($thumb_h)."\" alt=\"".esc_dblq($titre)."\" data-img=\"".esc_dblq($thumb_h)."\" data-credit=\"&lt;b&gt;".esc_dblq($titre)."&lt;/b&gt;&lt;br /&gt;".$credits."\"/></a>";
		else
			$content.="<a href=\"".$commons_link."\" data-file=\"".esc_dblq($large)."\" data-commons=\"".$commons_link."\" class=\"linksolo\" id=\"link$cpt\"><img src=\"".esc_dblq($large)."\" alt=\"".esc_dblq($titre)."\" data-img=\"".esc_dblq($thumb_h)."\" data-credit=\"&lt;b&gt;".esc_dblq($titre)."&lt;/b&gt;&lt;br /&gt;".$credits."\"/></a>";
	}
	else{
		if ($disp==0)
			$content.="<img src=\"img/no_image2.png\" alt=\"\" width=\"200\" height=\"240\">";
		else
			$content.="<img src=\"img/no_image_day.png\" alt=\"\" width=\"200\" height=\"240\">";
	}
	$content.="\n		</div></div>";
		
	$content.="\n		<div class=\"cartel\">";

	
	$content.="\n			<div class=\"entete\"><span>".$trunc_title."</span>";
	if ($creator!="")
		$content.="<br/>".$creator;
	if ($coll_or_loc!=""){
		if ($creator!="")
			$content.=" - ";
		else
			$content.="<br />";
		$content.=$coll_or_loc;
	}
	$content.="\n			</div>";	
	
	$content.="\n			<div class=\"btn_notice\">";
	if ($disp==0)
		$content.="<img id=\"iconot$cpt\" src=\"img/arrow_down.png\" alt=\"notice\" class=\"lien_notice\">\n";
	else
		$content.="<img id=\"iconot$cpt\" src=\"img/arrow_down_day.png\" alt=\"notice\" class=\"lien_notice\">\n";
	$content.="			</div>";

	$content.="\n			<div class=\"act_not\">";
	$uri_link="https://www.wikidata.org/wiki/Q".$qwd_art;
	$content.="<a href=\"".$uri_link."\" title=\"".translate($l,"Wikidata")."\">";
	if ($disp==0)
		$content.="<img src=\"img/wd_ico.png\" alt=\"\"/>";
	else
		$content.="<img src=\"img/wd_ico_day.png\" alt=\"\"/>";
	$content.="</a>";
	if ($thumb_h!=""){
		if ($disp==0)
			$content.="	<a href=\"".$commons_link."\" title=\"".translate($l,"Commons")."\"><img src=\"img/commons_ico.png\" alt=\"\"/></a>";
		else
			$content.="	<a href=\"".$commons_link."\" title=\"".translate($l,"Commons")."\"><img src=\"img/commons_ico_day.png\" alt=\"\"/></a>";
	}
	if ($described_link!="")
		$content.="	<a href=\"".$described_link."\" title=\"".translate($l,"973")."\"><img src=\"img/site_link.png\" alt=\"\"/></a>";
	if ($pageWP!=""){
		$content.=" <a href=\"https://";
		if ($lgWP!="")
			$content.=$lgWP;
		else 
			$content.=$l;
		$content.=".wikipedia.org/wiki/".str_replace(" ","_",str_replace("\"","",$pageWP))."\">";
		if ($disp==0)
			$content.="<img src=\"img/wp_ico.png\" alt=\"\" /></a>";
		else
			$content.="<img src=\"img/wp_ico_day.png\" alt=\"\" /></a>";
		
		if ($lgWP!="")
			$content.=" <a href=\"https://".$lgWP.".wikipedia.org/wiki/".str_replace(" ","_",str_replace("\"","",$pageWP))."\" class=\"lgWP\" title=\"".translate($l,"Wikipedia")."\">".$lgWP."</a>";
	}

	$content.="\n			</div>";
	$content.="\n			<div id=\"notice$cpt\" class=\"notice\">";
	
	if ($trunk!="")
		$content.="\n<p><b>".$titre."</b></p>";	
	if ($alias!="")
		$content.="\n<p><span class=\"libelle\">".translate($l,"alias")."</span>&nbsp;:<br/>".$alias."</p>";	
		
	if ((!(is_null($data['year2'])))||($data['year1']!=$data['year2'])){
		$content.="\n<p class=\"start_cartel\">";
		if ($data['b_date']==1)
			$content.="~&nbsp;&nbsp;";
		if (!(is_null($data['year1'])))
			$content.=$data['year1'];
		if ((!(is_null($data['year2'])))&&($data['year1']!=$data['year2']))
			$content.=" / ".$data['year2'];
		$content.="</p>";
		$content.="<p>";
	}
	else
		$content.="\n<p class=\"start_cartel\">";

	if ($pendant!="")
		$content.="\n<p>".$pendant."</p>";		
	$content.=$type."</p>";
	if ($material!="")
		$content.="\n<p>".$material."</p>";
	if ($inv!="")
		$content.="\n<p><span class=\"libelle\">".translate($l,"217")."</span>&nbsp;: ".$inv."</p>";
	if ($collection!="")
		$content.="\n<p>".$collection."</p>";
	if ($series!="")
		$content.="\n<p>".$series."</p>";
	if ($partof!="")
		$content.="\n<p>".$partof."</p>";
	if ($location!="")
		$content.="\n<p>".$location."</p>";
		
	if ($mouvement!="")
		$content.="\n<p>".$mouvement."</p>";
	if ($genre!="")
		$content.="\n<p>".$genre."</p>";
	if ($based!="")
		$content.="\n<p>".$based."</p>";
	if ($subject!="")
		$content.="\n<p>".$subject."</p>";
	if ($inspired!="")
		$content.="\n<p>".$inspired."</p>";
	if ($depicts!="")
		$content.="\n<p>".$depicts."</p>";
	
	$content.="\n				<div class=\"liens\">";
	if ($loc_link!="")
		$content.="\n<p>".$loc_link."</p>";
	if ($data['P727']!="")
		$content.="<p><a href=\"http://europeana.eu/portal/record/".$data['P727'].".html\"><img src=\"img/europeana.png\" alt=\"Europeana\"/></a> <a href=\"http://europeana.eu/portal/record/".$data['P727'].".html\" class=\"externe\">".translate($l,"Europeana")."</a></p>";
	if ($data['P214']!="")
		$content.="<p><a href=\"http://viaf.org/viaf/".$data['P214']."/\"><img src=\"img/viaf.png\" alt=\"VIAF\"/></a> <a href=\"http://viaf.org/viaf/".$data['P214']."/\" class=\"externe\">".translate($l,"VIAF")."</a></p>";
	if ($data['P350']!="")
		$content.="<p><a href=\"https://rkd.nl/nl/explore/images/".$data['P350']."\"><img src=\"img/rkd.png\" alt=\"RKD Images\"/></a> <a href=\"https://rkd.nl/nl/explore/images/".$data['P350']."\" class=\"externe\">".translate($l,"RKDimages")."</a></p>";
	if ($data['P347']!="")
		$content.="<p><a href=\"http://www.culture.gouv.fr/public/mistral/joconde_fr?ACTION=CHERCHER&amp;FIELD_1=REF&amp;VALUE_1=".$data['P347']."\"><img src=\"img/joconde.png\" alt=\"Joconde\"/></a> <a href=\"http://www.culture.gouv.fr/public/mistral/joconde_fr?ACTION=CHERCHER&amp;FIELD_1=REF&amp;VALUE_1=".$data['P347']."\" class=\"externe\">".translate($l,"Joconde")."</a></p>";
	if ($data['P1212']!="")
		$content.="<p><a href=\"http://cartelfr.louvre.fr/cartelfr/visite?srv=car_not_frame&idNotice=".$data['P1212']."\"><img src=\"img/atlas.png\" alt=\"ATLAS\"/></a> <a href=\"http://cartelfr.louvre.fr/cartelfr/visite?srv=car_not_frame&idNotice=".$data['P1212']."\" class=\"externe\">".translate($l,"Atlas")."</a></p>";
	if ($data['P373']!="")
		$content.="<p><a href=\"https://commons.wikimedia.org/wiki/Category:".str_replace(" ","_",$data['P373'])."\"><img src=\"img/commons.png\" alt=\"Commons\"/></a> <a href=\"https://commons.wikimedia.org/wiki/Category:".str_replace(" ","_",$data['P373'])."\" class=\"externe\">".translate($l,"CommonsCat")."</a></p>";

	$url="http://tools.wmflabs.org/reasonator/?lang=".$l."&amp;q=".$qwd_art;	
	$content.="\n<p> <a href=\"".$url."\"><img src=\"img/reasonator.png\" alt=\"Reasonator\"/></a> <a href=\"".$url."\" class=\"externe\">".translate($l,"reasonator")."</a></p>";
	
	$url="http://zone47.com/crotos/?q=".$qwd_art;	
	$content.="\n<p> <a href=\"".$url."\"><img src=\"img/crotos.png\" alt=\"CROTOS\"/></a> <a href=\"".$url."\">crotos/?q=".$qwd_art."</a></p>";

	$content.="\n				</div>";
	if (($num_rows==1)&&($credits!="")){
		$content.="\n				<div class=\"img_info\">";
		$content.="\n					<div class=\"credit_img\">";
		$content.="\n					<a href=\"".$commons_link."\"><img src=\"img/commons_gray.png\" alt=\"\"></a>";
		$content.="\n					</div>";
		$content.="\n					<div class=\"credit_txt\">";
		$content.=html_entity_decode($credits);
		$content.="\n					</div>";
		
		$content.="\n				</div>";
	}
	
	$content.="\n			</div>";

	$content.="\n		</div>";
	$content.="\n	</div>";
	if ($num_rows>1)
		$content.="\n	<script>document.getElementById('notice$cpt').style.display = 'none';</script>\n";
	
	echo $content;	
}
mysqli_close($link);

?>
</div>
<div class="nav navbas">
<?php
if ($num_rows>5){
	echo "<span class=\"nav_list\">";
	echo $txtnav;
	echo "</span>";
}
?>
</div>
<div id="timer"><?php
// Timer end and print
list($g2_usec, $g2_sec) = explode(" ",microtime());
define ("t_end", (float)$g2_usec + (float)$g2_sec);
 print round (t_end-t_start, 1)." secondes"; ?>
</div>
<footer <?php if ($num_rows<6) echo "class=\"marge\"" ?>>
	<span class="bl_foot">by <a href="https://twitter.com/shona_gon">/* / */</a>&nbsp;&nbsp; (<a href="/dozo/crotos-moteur-de-recherche-sur-les-oeuvres-dart-dans-wikidata" class="externe">info [fr]</a>, <a href="lab/">Lab</a> , <a href="https://github.com/zone47/CROTOS" class="externe">source</a>, <a href="bdd/crotos.sql.zip">data</a> )&nbsp;&nbsp; with</span><span class="sep"> </span>
    <span class="bl_foot"><a href="http://www.wikidata.org" title="<?php echo translate($l,"Wikidata"); ?>"><img src="img/wikidata.png" alt="<?php echo translate($l,"Wikidata"); ?>"/></a>  <a href="http://commons.wikimedia.org" title="<?php echo translate($l,"Commons"); ?>"><img src="img/wikimedia-commons.png" alt="<?php echo translate($l,"Commons"); ?>" /></a>  <a href="http://www.semanticpedia.org/" title="Sémantipédia"><img src="img/semanticpedia.png" alt="Sémanticpédia" /></a>    <img src="img/photographer.png" alt="Photographers" />  al.</span>
    <span class="bl_foot"> and &lt;3</span>
    <div class="update">Last update:
<?php
$fp = fopen ("dateupdate.txt", "r");
echo fgets ($fp, 255);
fclose ($fp);
?></div>
<!-- Encore des étoiles. Qu'elles brillent sur vous et que vous brillez avec elles. -->
</footer>
	</div>
</body>
</html>