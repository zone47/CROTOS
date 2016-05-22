$(document).ready(function(){

	var jcrop_api = null;

	/* When URL submitted... */
	$('#submit').click(function(){
		// If there was an instance of jcrop, start fresh it
		if (jcrop_api != null){
			jcrop_api.destroy();
		}
		// Remove img styling-- clears out the 'display: none' when first image is loaded.
		// This is also a workaround for a jcrop issue whereby it does not properly handle changing images and box constraints.
		$('#target').removeAttr('style');

		// Clear output box
		$('#output').val('');

		// Get input
		var url = document.getElementById("URL").value;
        url = "http://tools.wmflabs.org/zoomviewer/proxy.php?iiif=" + url + "/full/full/0/default.jpg" ;
		// Display image
		$('#target').attr("src", url);
		// Start jcrop
		$('#target').Jcrop({
			boxHeight:700,
			onSelect: getURL,
			onChange: clearURL,
			allowSelect: true,
			allowMove: true,
			allowResize: true
		}, function(){
			jcrop_api=this;
		});
	});


	function clearURL(){
		$('#output').val("");
		$('#fragment').val("");
		$('#linkfragment').html("");
	}

	/* Get the new URL from JCrop coordinates */
	function getURL(c){
		var oldURL = $('#target').attr('src');
		var split = oldURL.split('/');
		var sc = scaleCoords(c, oldURL); // scaled coordinates
		var tc = translateCoords(sc, oldURL);
        
        /////////////////////////////////////////////////
        /* / */
        /*var coordStr = tc.x.toString() + ","+ tc.y.toString()+","+tc.w.toString()+","+tc.h.toString();
		split[split.length - 4] = coordStr;
		var newURL = split.join("/");
		$('#output').val(newURL);*/
        var widthL  =  $('#target').prop('naturalWidth');
        var heightL =  $('#target').prop('naturalHeight');
        var px=Math.round(tc.x/parseInt(widthL)*1000)/10;
        var py=Math.round(tc.y/parseInt(heightL)*1000)/10;
        var pw=Math.round(tc.w/parseInt(widthL)*1000)/10;
        var ph=Math.round(tc.h/parseInt(heightL)*1000)/10;
        var coordStr = "pct:"+px + ","+ py +","+pw+","+ph;
        $('#output').val(coordStr);
        split[split.length - 4] = coordStr;
		var newURL = split.join("/");
		var txt='<a href="'+newURL+'">link</a>';
		$('#fragment').val(newURL);
		$('#linkfragment').html(txt);
        /* / */
        /////////////////////////////////////////////////
	}

	/* Adjusts for IIIF image scaling (pct:40, for example) */
	function scaleCoords(c, url){
		var x = c.x;
		var y = c.y;
		var w = c.w;
		var h = c.h;
		var split = url.split('/');
		var pctStr = split[split.length-3];
		if (pctStr != "full"){
			var pct = pctStr.split(':')[1];
			var scale = 100/pct;
			x *= scale;
			y *= scale;
			w *= scale;
			h *= scale;
		}
		x = Math.round(x);
		y = Math.round(y);
		w = Math.round(w);
		h = Math.round(h);

		return {'x':x, 'y':y, 'w':w, 'h':h}
	}

	/* Moves box over/down already cropped URLs. Takes scaled coordinates */
	function translateCoords(sc, oldURL){
		var x = parseInt(sc.x);
		var y = parseInt(sc.y);
		var w = parseInt(sc.w);
		var h = parseInt(sc.h);

		var oldCoords = oldURL.split('/')[oldURL.split('/').length - 4]

		if (oldCoords != "full"){
			var split = oldCoords.split(',');
			x += parseInt(split[0]);
			y += parseInt(split[1]);
		}
		return {'x':x, 'y':y, 'w':w, 'h':h}
	}

});
