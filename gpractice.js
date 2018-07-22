(function( $ ) {
	
    $(function() {

        $.ajax({
            url: gpractice_obj.ajaxurl, 
            data: {
                'action': 'gdp3_request_leaderboard', 
                'leaderboard_id' : 'test'
            },
            success:function(result) {
                $(".leaderboard").html(result);
            },
            error: function(errorThrown){
                console.log(errorThrown);
            }
        });  
    });

})( jQuery );
