<?php
/* / */
$cosmos=1;
$tab_facets=array(31,170,135,136,144,921,180,608,195);
$prop=31;
if (isset($_GET['f']))
	if ($_GET['f']!="31") 
		$prop=intval($_GET['f']);
include "../config.php";
include "../init.php";
include "../traduction.php";
include "../functions.php";
include "../timer_top.php";
include "../open_conn.php";
include "params.php";
include "../text_nav.php";
$txt_res="";
if ($num_rows<2)
	$txt_res.=$num_rows." ".mb_ucfirst(translate($l,"result"));
elseif ($num_rows<=$nb)
	$txt_res.=$num_rows_ec." ".mb_ucfirst(translate($l,"results"));
else {
	if ($rand_sel)
		$txt_res.=$num_rows." ".mb_ucfirst(translate($l,"results"));
	else
		$txt_res.=mb_ucfirst(translate($l,"results"))." ".($deb+1)." - ".($deb+$num_rows_ec)." ".translate($l,"of")." ".$num_rows;
}
?><!doctype html>
<html lang="<?php echo $l ?>">
<head>
<title>Crotos - Cosmos</title>
<?php
include "../header.php";
?>      
<script>
$(function(){
	$('#topic_title').autoComplete({
		minChars: 2,
		cache:false,
		source: function(term, suggest){
			 $.ajax({
				url: '../ajax_refresh.php',
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
</head>
<?php
	if (($l=="ar")||($l=="fa")||($l=="he"))
		echo "<body id=\"lg_rtl\" >\n";
	else
		echo "<body>\n";
?>
	<div id="global">

<?php
include "../access.php";
?>  
<div id="entete">
<form action="<?php echo $script_name; ?>" method="get" id="form"  name="form"  accept-charset="UTF-8">
	<?php 
	include "../top.php";
	include "../form.php"; 
?>
</form>   
</div>
<?php
	include "../nav_index.php"; 
	include "content_cosmos.php";
	include "../nav_bot_index.php";
	include "../timer_bottom.php";
	include "../footer.php";
	include "../close_conn.php";
?> 

	</div>
</body>
</html>