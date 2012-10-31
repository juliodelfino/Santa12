var errorDialog = '';

$(function() 
{
	errorDialog = $('<div id="error-dialog" title="Invalid Input">Oops! Don\'t you have any cool nickname?</div>').dialog({
		autoOpen: false,
		modal: true,
		resizable: false,
		show: 'fade',
		hide: 'fade',
		width: 300,
		height: 130,
		buttons: {			
			'I Have One' : function() {
			
				errorDialog.dialog('close');
			}
		}
	});

	$('#save-btn').click(function ()
	{
		if (!$('#edit-profile-form').valid()) {
			return;
		}
		
		var profile = getProfile();
		if (profile.nickname.length < 3) {
			errorDialog.dialog('open');
			return;
		}
		
		$.ajax({
			type: 'POST',
			url: document.URL,
			data: getProfile()
		}).done(function(data){
			
			json = $.parseJSON(data);

			$('<div id="dialog" title="Edit Profile">' + json.message + '</div>').dialog({
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
	});
});

function getProfile()
{
	return {
			'nickname' : $.trim($('#nickname').val()),
			'phone' : $.trim($('#phone').val()),
			'giftee_email' : $.trim($('#giftee-email').val()),
			'giftee_notif' : $('#giftee-notif').attr('checked') != undefined,
			'comment_notif' : $('#comment-notif').attr('checked') != undefined,
			'action' : 'save'
		};
}