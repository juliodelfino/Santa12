
var lockFetching = false;
var userId = 0;
var reachedPostEnd = false;

$(function() 
{
	$('#user-search-box').keyup(function()
	{
		var searchText = $(this).val();
		searchText = jQuery.trim(searchText);
		if (searchText.length < 3 && searchText.length > 0)
			return;
		if (searchText == prevUserSearchText)
			return;
		prevUserSearchText = searchText;

		$('#friends-list').html('<div class="loader">' + loaderImg + '</div>');
		lockFetching = searchText.length > 0;
		$.ajax({
			url: siteUrl + 'friends/search',
			data: {
				text: searchText
			}
		}).done(function(data){

			if (data.length > 0)
				$('#friends-list').html(data);
			else 
				$('#friends-list').html('No wishers found matching the keywords "' 
						+ searchText + '".');
		}).always(function() {
			
			$('.loader').hide();
		});
	});
	
	
	$(window).scroll(function(){
		
		if (reachedPostEnd)
			return;
		//for debugging
   // 	$('#title').html($(window).scrollTop() + " " + window.innerHeight + " " + $('#center-content').height());
	    if ($(window).scrollTop() >= ($('#center-content').height() - (window.innerHeight + 100)) && !lockFetching){

	    	newOffset = 20 + parseInt($('#last-offset').val());
	    	lockFetching = true;
	    	$('#friends-list').append('<div class="loader" style="display: block">' + loaderImg + '</div>');
			$.ajax({
				type: 'GET',
				url: siteUrl + 'friends/get',
				data: {
					offset: newOffset
				}
			}).done(function(data){
				
				reachedPostEnd = (data.length == 0);
				$('.loader').remove();
				if (!reachedPostEnd) {
					$('#friends-list').append(data);
					$('#last-offset').val(newOffset);
				}
				else {
					$('#friends-list').append('<div class="end-of-post">No more wishers to show.</div>');
				}
				lockFetching = false;
			});
	    }
	});
	
});