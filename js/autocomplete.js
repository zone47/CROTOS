$( "#topic_title" ).autocomplete({
      source: function( request, response ) {
        $.ajax({
			url: 'ajax_refresh.php',
			type: 'POST',
			data: {keyword:$('#topic_title').val()},
			success:function(data){
				$('#topic_title').removeClass('ui-autocomplete-loading');
				$('#suggest_list_id').show();
				$('#suggest_list_id').html(data);
			}
		});
		/*$.ajax({
            dataType: "json",
            type : 'Post',
            url: 'yourURL',
            success: function(data) {
              $('input.suggest-user').removeClass('ui-autocomplete-loading');  // hide loading image

            response( $.map( data, function(item) {
                // your operation on data
            }));
          },
          error: function(data) {
              $('input.suggest-user').removeClass('ui-autocomplete-loading');  
          }
        });*/
      },
      minLength: 2/*,
      open: function() {

      },
      close: function() {

      },
      focus:function(event,ui) {

      },
      select: function( event, ui ) {

      }*/
    });