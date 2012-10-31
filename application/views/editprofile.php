<script src="<?php echo AppConfig::$url ?>scripts/editprofile.js" type="text/javascript"></script>

<form id="edit-profile-form" method="post" action="javascript:void">

	<div class="label"><span class="required">*</span> Nickname</div>
	<div class="value"><input type="text" class="textbox" id="nickname" name="nickname" value="<?php echo $profile->nickname ?>" maxlength="64" /></div> <br/>
	<div class="label">Mobile Phone (in case your Santa needs to contact you)</div>
	<div class="value"><input type="text" class="textbox" id="phone" name="phone" value="<?php echo $profile->phone ?>" maxlength="15" /></div> <br/>
	<div class="label">Email address of your 'Giftee' (the one who will receive your gift)</div>
	<div class="value"><input type="text" class="textbox email" id="giftee-email" name="giftee_email" value="<?php echo $profile->giftee_email ?>" /></div> <br/>
	<div class="label">Email Notifications </div>
	<div class="value">
		<input type="checkbox" id="giftee-notif" name="giftee_notif" <?php echo $profile->giftee_notif? 'checked="checked"' : '' ?> />
		<label for="giftee-notif">Updates from the Giftee </label> 
	</div>
	<div class="value">
		<input type="checkbox" id="comment-notif" name="comment_notif" <?php echo $profile->comment_notif? 'checked="checked"' : '' ?> />
		<label for="comment-notif">Comments on your Posts </label> 
	</div><br/>

	<input type="submit" id="save-btn" class="button orange" value="Save" onclick="return false;" />
</form>
<br/><br/>