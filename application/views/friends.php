<script src="<?php echo AppConfig::$url ?>scripts/friends.js" type="text/javascript"></script>
<script src="<?php echo AppConfig::$url ?>scripts/reminder_email.js" type="text/javascript"></script>

<button id="email-btn" class="button orange medium">Send an Email</button>
<div style="display: inline-block; text-align: right; padding: 0 10px 20px 10px; width: 445px">
	
	<label for="user-search-box">
		<img src="<?php echo AppConfig::$url . 'images/search.png'?>" width="24px" style="vertical-align: top" />
	</label>
	<input type="text" id="user-search-box" style="width: 300px; vertical-align: bottom" />
</div>
<div id="friends-list">
<?php 

    $ci = get_instance();
    foreach ($users as $user) {
        
        echo $ci->load->view('layout/friendentry', $user, true);
    }
?>
</div>

<form id="send-email-dialog" title="Send an Anonymous Email to your Giftee" style="text-align: left" action="javascript:void(0)">
</form>

<input type="hidden" id="last-offset" value="0" />