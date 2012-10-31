
var sendEmailDialog = '';

$(function() 
{
	sendEmailDialog = $('#send-email-dialog').dialog({
		autoOpen: false,
		modal: true,
		draggable: false,
		resizable: false,
		show: 'fade',
		hide: 'fade',
		width: 500,
		height: 400,
		buttons: {
			'Send' : function() {

				if (!$('#send-email-dialog').valid())
				{
					return;
				}
				var email = $('#email').val();
				var message = $.trim($('#message').val());
				if (message.length < 4)
				{
					return;
				}
				sendEmailDialog.dialog('close');
				
				$.ajax({
					type: 'POST',
					url: siteUrl + 'friends/send_mail',
					data: {
						email: email,
						message: message
					}
				}).done(function(data){
					//TODO: do something after sending the mail
				});
			},
			'Cancel' : function() {
			
				sendEmailDialog.dialog('close');
			}
		},
		open: function() {	
			
			$.ajax({
				url: siteUrl + 'friends/reminder_email'
			}).done(function(data){
				sendEmailDialog.html(data);
			});
		}
	});
	$('.ui-dialog').css({position:'fixed'});
	
	$('#email-btn').click(function(){
		sendEmailDialog.dialog('open');
	});
});