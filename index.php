<?php
/* / */
include "config.php";
include "traduction.php";
include "functions.php";
include "timer_top.php";
include "open_conn.php";
include "init.php";
include "params.php";
include "text_nav.php";
?><!doctype html>
<html lang="<?php echo $l ?>">
<head>
<?php
include "title_desc_index.php";
include "header.php";
?>      
<script>
function disp_notice(e) {
	var notice=document.getElementById("notice"+e.id.replace("iconot",""));
	var notice_state=notice.style.display;
	if(notice_state == 'none'){
		notice.style.display="block";
<?php
if ($disp==0)
	echo "		e.setAttribute(\"src\",\"img/arrow_up.png\");";
else
	echo "		e.setAttribute(\"src\",\"img/arrow_up_day.png\");";
?>		
	}
	else{
		notice.style.display="none";
<?php
if ($disp==0)
	echo "		e.setAttribute(\"src\",\"img/arrow_down.png\");";
else
	echo "		e.setAttribute(\"src\",\"img/arrow_down_day.png\");";
?>
	}
};
wait = function() { return false; }
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
			$(this).prop("onclick", null);
			this.href=$(this).attr("data-commons");
			if (!($(this).hasClass("linksolo")))
				$(this).children("img").attr("src",$(this).children("img").attr("data-img"));
		});
	}
	else{
		$(".item").css("width","100%");
		$( ".thumb a" ).each(function( index ) {
			$(this).prop("onclick", null);
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

	var trueValues = [-40000,-30000,-20000,-10000,-8000,-6000,-4000,-3500,-3000,-2500,-2000,-1800,-1600,-1400,-1200,-1000,-800,-700,-600,-500,-400,-300,-200,-100,1,100,200,300,400,500,600,700,800,900,1000,1050,1100,1150,1200,1250,1300,1350,1400,1420,1440,1460,1480,1490,1500,1510,1520,1530,1540,1550,1560,1570,1580,1590,1600,1610,1620,1630,1640,1650,1660,1670,1680,1690,1700,1710,1720,1730,1740,1750,1760,1770,1780,1790,1800,1810,1820,1830,1840,1850,1860,1870,1880,1890,1900,1910,1920,1930,1940,1950,1960,1970,1980,1990,2000,2010,2015];
	var values =[ 0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42,43,44,45,46,47,48,49,50,51,52,53,54,55,56,57,58,59,60,61,62,63,64,65,66,67,68,69,70,71,72,73,74,75,76,77,78,79,80,81,82,83,84,85,86,87,88,89,90,91,92,93,94,95,96,97,98,99,100];
	var slider =$("#slider-range").slider({
		range: true,
        min:0,
        max:100,
<?php
$pos_y1=0;
$pos_y2=100;
if (($y1!=-40000)||($y2!=2015))
	$date_pos=array(0 => -40000,1 => -30000,2 => -20000,3 => -10000,4 => -8000,5 => -6000,6 => -4000,7 => -3500,8 => -3000,9 => -2500,10 => -2000,11 => -1800,12 => -1600,13 => -1400,14 => -1200,15 => -1000,16 => -800,17 => -700,18 => -600,19 => -500,20 => -400,21 => -300,22 => -200,23 => -100,24 => 1,25 => 100,26 => 200,27 => 300,28 => 400,29 => 500,30 => 600,31 => 700,32 => 800,33 => 900,34 => 1000,35 => 1050,36 => 1100,37 => 1150,38 => 1200,39 => 1250,40 => 1300,41 => 1350,42 => 1400,43 => 1420,44 => 1440,45 => 1460,46 => 1480,47 => 1490,48 => 1500,49 => 1510,50 => 1520,51 => 1530,52 => 1540,53 => 1550,54 => 1560,55 => 1570,56 => 1580,57 => 1590,58 => 1600,59 => 1610,60 => 1620,61 => 1630,62 => 1640,63 => 1650,64 => 1660,65 => 1670,66 => 1680,67 => 1690,68 => 1700,69 => 1710,70 => 1720,71 => 1730,72 => 1740,73 => 1750,74 => 1760,75 => 1770,76 => 1780,77 => 1790,78 => 1800,79 => 1810,80 => 1820,81 => 1830,82 => 1840,83 => 1850,84 => 1860,85 => 1870,86 => 1880,87 => 1890,88 => 1900,89 => 1910,90 => 1920,91 => 1930,92 => 1940,93 => 1950,94 => 1960,95 => 1970,96 => 1980,97 => 1990,98 => 2000,99 => 2010,100 => 2015);
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
if ($y2!=2015){
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
$(function(){
	$('#topic_title').autoComplete({
		minChars: 2,
		cache:false,
		source: function(term, suggest){
			 $.ajax({
				url: 'ajax_refresh.php',
				type: 'GET',
				data: {keyword:$('#topic_title').val()},
				success:function(data){
					stip=$('#topic_title').val();
					var suggestions = data.split("|");
					suggest(suggestions);
				},
				error: function(data) {
				  $('#topic_title').removeClass('ui-autocomplete-loading');  
				}
			});
		},
		renderItem: function (item, search){
			var re = new RegExp("(" + search + ")", "gi");
			var n = item.indexOf("¤");
			var data=item.substring(0,n);
			item=item.substring(n+1,item.length);
			return '<div class="autocomplete-suggestion"  data-val="/crotos/?' + data + '" title="'+item.replace( /<.*?>/g, '' )+'">'+ item + '</div>';
		}
	});
	$('#topic_title').keyup(function(e){
		if(e.keyCode == 13)
			if ($(".selected" ).length)
				document.location.href=$(".selected").attr("data-val");
	});
});
</script>
	<style><?php
	if (($l=="ar")||($l=="fa")||($l=="he"))
    	echo "
.yoxview_imgPanel{direction: rtl;}
#yoxview{text-align:right}";
	
    ?>
	</style>
</head>
<?php
	if (($l=="ar")||($l=="fa")||($l=="he"))
		echo "<body id=\"lg_rtl\" >\n";
	else
		echo "<body>\n";
?>
	<div id="global">

<?php
include "access.php";
?>  
<div id="entete">
	<?php 
	include "top.php";
	include "form.php"; ?>    
</div>
<?php
	include "nav_index.php";
	include "content_index.php";
	include "nav_bot_index.php";
	include "timer_bottom.php";
	include "footer.php";
	include "close_conn.php";
?> 

	</div>
 <!-- Encore des étoiles. Qu'elles brillent sur vous et que vous brillez avec elles. -->
</body>
</html>