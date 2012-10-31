
var siteUrl = '';
var viewUsers = '';
var deletePostDialog = '';
var deleteCommentDialog = '';
var prevUserSearchText = '';
var loaderImg = '';
var updateInterval = 15000;

$(function() 
{
	siteUrl = $('#site_url').val();
	$('body').css('background-image', "url('" + siteUrl + "images/bg/christmas-gifts-013.jpg')");
	loaderImg = '<img src="' + siteUrl + 'images/ajax-loader.gif" style="text-align: center" />';
	
	if ( $.browser.msie && $.browser.version == 7.0 ){
		$('#nav').css('margin-left', '-120px');
	}
	
	rebindPostingEvents();
	
	viewUsers = $('<div id="view-users-dialog" title="People Who Liked" style="text-align: left"></div>').dialog({
		autoOpen: false,
		modal: true,
		draggable: false,
		resizable: false,
		show: 'fade',
		hide: 'fade',
		width: 500,
		height: 500,
		buttons: {
		'OK' : function() {
			
				viewUsers.dialog('close');
			}
		},
		open: function() {
		
			var postId = viewUsers.attr('postid');
			viewUsers.html(loaderImg);
			$.ajax({
				type: 'GET',
				url: siteUrl + 'friends/likers',
				data: {
					postid: postId
				}
			}).done(function(data){
				
				viewUsers.html(data);
			});
		}
	});
	
	deletePostDialog = $('<div id="delete-post-dialog" title="Delete This Post">Are you sure you want to delete this post?</div>').dialog({
		autoOpen: false,
		modal: true,
		resizable: false,
		show: 'fade',
		hide: 'fade',
		width: 300,
		height: 130,
		buttons: {
		'Yes' : function() {
				
				deletePostDialog.dialog('close');
				var postId = deletePostDialog.attr('postid');
				$.ajax({
					type: 'POST',
					url: siteUrl + 'wishlist/delete',
					data: {
						postid: postId
					}
				});
				$('#' + postId).remove();				
			},			
		'No' : function() {
			
				deletePostDialog.dialog('close');
			}
		}
	});
	
	deleteCommentDialog = $('<div id="delete-comment-dialog" title="Delete This Comment">Are you sure you want to delete this comment?</div>').dialog({
		autoOpen: false,
		modal: true,
		resizable: false,
		show: 'fade',
		hide: 'fade',
		width: 300,
		height: 130,
		buttons: {
		'Yes' : function() {
				
				deleteCommentDialog.dialog('close');
				var postId = deleteCommentDialog.attr('postid');
				$.ajax({
					type: 'POST',
					url: siteUrl + 'wishlist/delete_comment',
					data: {
						postid: postId
					}
				});
				$('#' + postId).remove();				
			},			
		'No' : function() {
			
				deleteCommentDialog.dialog('close');
			}
		}
	});

	$('.ui-dialog').css({position:'fixed'});
	
	$('#notif-btn').click(function()
	{
		$('.notif-entry').remove();
		$('#notif-start').after('<span class="notif-entry">' + loaderImg + '</span>');
		$.ajax({
			url: siteUrl + 'notifications/get_latest'
		}).done(function(data){
			$('.notif-entry').remove();
			window.document.title = $('#orig_title').val();
			
			$('#notif-start').after(data);
			$('#notif-count').css('display', 'none');
		});
	});
	
	if ($('#snow_time').val() == 'true') {
//		magic();
	}
	
//	if ($('input[name=username]') == undefined) {
		doPeriodicTask();
//	}
});

var now = '';
function doPeriodicTask() {

	$.ajax({
		url: siteUrl + 'home/updates'
	}).done(function(data){
		
		json = $.parseJSON(data);
		if (json.notifCount > 0) {

			window.document.title =  "(" + json.notifCount + ") " + $('#orig_title').val();
			$('#notif-count').html(json.notifCount);
			$('#notif-count').css('display', 'inline');
		}
	
		updateTimestamps(json.serverTimestamp);
		setTimeout(doPeriodicTask, updateInterval);
	});
	
}

function updateTimestamps(serverTimestamp)
{
	//var now = Math.round(new Date().getTime() / 1000);
	var now = serverTimestamp;
	$('.live-ts').each(function(){ 
		var diff = now - $(this).attr('ts');
		if (diff < 60) {
			$(this).html(diff + (diff == 1 ? " second" : " seconds") + " ago");
		}
		else if (diff < 3600) {
			diff = Math.round(diff/60);
			$(this).html( (diff == 1 ? "a minute" : (diff + " minutes")) + " ago");
		}
		else if (diff < 86400) {
			diff = Math.round(diff/3600);
			$(this).html( (diff == 1 ? "an hour" : (diff + " hours")) + " ago");
		}
	});
}

function rebindPostingEvents()
{
	$('.like').unbind('click');
	$('.like').click(function ()
	{
		var postId = $(this).parents('.news-entry').attr('id');
		
		$.ajax({
			type: 'POST',
			url: siteUrl + 'wishlist/like',
			data: {
				postid: postId
			}
		}).done(function(data){
			
			json = $.parseJSON(data);
			$('#' + postId + ' .like-users').html(json.htmlLike);
			$('#' + postId + ' .like-text').css('display', json.htmlLike == '' ? 'none' : 'block');
			$('#' + postId + ' .news-input .like').html(json.iLike ? 'Unlike' : 'Like');
		});
	});

	$('.view-comments-link').unbind('click');
	$('.view-comments-link').click(function()
	{			
		var postId = $(this).parents('.news-entry').attr('id');
		$('#' + postId + ' .hidden-entry').css('display', 'inline-block');		
		$(this).remove();
	});

	$('.show-hidden-text').unbind('click');
	$('.show-hidden-text').click(function()
	{			
		var link = $(this);
		var prevSpan = link.prev();
		var dot3 = prevSpan.prev();
		var mainText = dot3.prev();
		mainText.append(prevSpan.html());
		dot3.remove();
		prevSpan.remove();
		link.remove();
	});
	
	$('.delete-post').click(function()
	{			
		var postId = $(this).parents('.news-entry').attr('id');
		deletePostDialog.attr('postid', postId);
		deletePostDialog.dialog('open');
	});

	rebindCommentEvents();
	rebindCommentBox();
}

function rebindCommentEvents() 
{	
	$('.like-comment').unbind('click');
	$('.like-comment').click(function ()
	{
		var postId = $(this).parents('.comment-entry').attr('id');
		
		$.ajax({
			type: 'POST',
			url: siteUrl + 'wishlist/like_comment',
			data: {
				postid: postId
			}
		}).done(function(data){
			
			json = $.parseJSON(data);
			$('#' + postId + ' .like-cnt').html(json.likeCount);
			$('#' + postId + ' .like-cm-view').css('display', json.likeCount == '0' ? 'none' : 'inline');
			$('#' + postId + ' .like-comment').html(json.iLike ? 'Unlike' : 'Like');
		});
	});
	
	$('.like-view').unbind('click');
	$('.like-view').click(function ()
	{
		var postId = $(this).parents('.news-entry').attr('id');
		viewUsers.attr('postid', postId);
		viewUsers.dialog('open');

	});
	
	$('.like-cm-view').unbind('click');
	$('.like-cm-view').click(function ()
	{
		var postId = $(this).parents('.comment-entry').attr('id');
		viewUsers.attr('postid', postId);
		viewUsers.dialog('open');
	});

	$('.delete-comment').unbind('click');	
	$('.delete-comment').click(function()
	{			
		var postId = $(this).parents('.comment-entry').attr('id');
		deleteCommentDialog.attr('postid', postId);
		deleteCommentDialog.dialog('open');
	});
}

function rebindCommentBox() {
	
	$('.commentbox:not(.keyupbound)').addClass('keyupbound').keyup(function(e)
	{
		$(this).css('height', '1px');
		$(this).css('height', (12+this.scrollHeight)+'px');
	});
	
	$('.commentbox:not(.keypressbound)').addClass('keypressbound').keypress(function(e)
	{
		var cbox = this;
		var jcbox = $(this);				
		var postId = jcbox.parents('.news-entry').attr('id');
		
		if (e.keyCode == 13) {
			
			e.preventDefault();
			var commentText = jQuery.trim(jcbox.val());
			if (commentText == '')
				return;
			
			jcbox.attr('disabled', 'disabled');
			var lastCommentId = jcbox.parents('.news-entry').find('.comment-entry').last().attr('id');
			if (lastCommentId == '') 
			{ lastCommentId = 0; }
					
			$.ajax({
				type: 'POST',
				url: siteUrl + 'wishlist/comment',
				data: {
					postid: postId,
					text: commentText,
					prevpostid: lastCommentId
				}
			}).done(function(data){

				json = $.parseJSON(data);
				jcbox.parents('.comment-input').before(json.appendHtml);	
			    jcbox.val('');
			    jcbox.removeAttr('disabled');
			    rebindCommentEvents();
			    updateTimestamps(json.serverTimestamp);
			});
		}
	});
}
