/**
 * Created by liz on 3/28/16.
 */
$(document).ready(function(){

	$('#lg').change(function() {
		$('#lgform').submit();
	});
	
    /* Trigger URL submit with ENTER */
    $("#URL").keyup(function(event){
        if(event.keyCode == 13){
            $("#submit").click();
        }
    });


    /** CLIPBOARD **/

    var clipboard = new Clipboard('.btn');

    clipboard.on('success', function(e) {
        console.log('Success');
        $('#copy-tip-text').text('Copied!');
        $('#copy-tip-text').fadeIn(300).delay(1000).fadeOut(300);
    });

    clipboard.on('error', function(e) {
        $('#copy-tip-text').text('Ctrl+C to copy');
        $('#copy-tip-text').fadeIn(300).delay(1000).fadeOut(300);
    });
});