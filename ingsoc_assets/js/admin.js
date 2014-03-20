$(function(){

	var $ttable = $('table#translations')
	
	_.each(words.translations, function(val, key){
		var $row = $("<tr />")
		$row.append( $('<td />').text( key ) )
		$row.append( $('<td />').text( _(val).join(', ') ) )
		$ttable.append( $row )
	});

	var $ctable = $('table#censored')
	
	_.each(words.censored, function(val, key){
		var $row = $("<tr />")
		$row.append( $('<td />').text( val ) )
		$ctable.append( $row )
	});

	$('.censor').on( 'submit', function(ev){
		var val = $('.censor input[type="text"]').val()
		if (_( words.censored ).contains( val )){
			var uncensor = confirm( 'Are you sure you want to uncensor "'+val+'"?' )
			$('<input />').attr('type', 'hidden')
            .attr('name', "undo")
            .attr('value', "true")
            .appendTo('.censor');
            return true;
		}
	})
	

});