
var lastPostId = 0;
var userId = 0;
var reachedPostEnd = false;

$(function() 
{
	$(window).scroll(function(){
		
		if (reachedPostEnd)
			return;
		//for debugging
    //	$('#title').html($(window).scrollTop() + " " + window.innerHeight + " " + $('#center-content').height());
	    if ($(window).scrollTop() >= ($('#center-content').height() - (window.innerHeight + 100)) && lastPostId == 0){

	    	lastPostId = $('.news-entry').last().attr('id');
	    	
	    	$('#center-content').append('<div class="news-entry loader">' + loaderImg + '</div>');
			$.ajax({
				type: 'GET',
				url: siteUrl + 'home/get_feed',
				data: {
					lastpostid: lastPostId,
					userid: userId
				}
			}).done(function(data){
				
				lastPostId = 0;
				reachedPostEnd = (data.length == 0);
				$('.loader').remove();
				if (!reachedPostEnd) {
					$('#center-content').append(data);
				}
				else {
					$('#center-content').append('<div class="end-of-post">No more posts to show.</div>');
				}
				rebindPostingEvents();
			});
	    }
	});
});