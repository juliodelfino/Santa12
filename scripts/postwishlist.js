var errorDialog = '';

$(function() 
{
	errorDialog = $('<div id="error-dialog" title="Invalid Input">Please enter a valid name for your wishlist item.</div>').dialog({
		autoOpen: false,
		modal: true,
		resizable: false,
		show: 'fade',
		hide: 'fade',
		width: 300,
		height: 130,
		buttons: {			
			'OK' : function() {
			
				errorDialog.dialog('close');
			}
		}
	});
	
	$('#post-btn').click(function ()
	{
		wishData = getWishData();
		if (wishData.item_name.length < 5) {
			errorDialog.dialog('open');
		} else {
			document.postwish_form.submit();
		}
		
		
	/*	$.ajax({
			type: 'POST',
			url: document.URL,
			data: getWishData()
		}).done(function(data){
			
			json = $.parseJSON(data);

			$('<div id="dialog" title="Wishlist Post">' + json.message + '</div>').dialog({
				modal: true,
				show: 'fade',
				hide: 'fade',
				buttons: {
					'OK' : function() {
						window.location = json.redirect_link;
					}
				}
			});
		});
		*/
	});
	
});

function getWishData()
{
	return {
			'item_name' : $.trim($('#item-name').val()),
			'description' : $.trim($('#description').val()),
			'photo' : $('#photo').val(),
			'action' : 'save'
		};
}