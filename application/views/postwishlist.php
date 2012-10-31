<script src="<?php echo AppConfig::$url ?>scripts/postwishlist.js" type="text/javascript"></script>

<form method="post" name="postwish_form" action="<?php echo AppConfig::$url . 'wishlist/' . ($editMode ? 'edit' : 'post') ?>" enctype="multipart/form-data">
	<div class="label"><span class="required">*</span> What would you like to wish for?</div>
	<div class="value"><input type="text" id="item-name" name="item_name"
		class="textbox" maxlength="256" style="width: 90%" value="<?php echo $story->title ?>" /></div> <br/>
	<div class="label">Describe your wishlist item.</div>
	<div class="value"><textarea class="textbox" id="description" name="description" style="width: 90%" rows="10" ><?php echo $story->description ?></textarea></div> <br/>
	<div class="label">
		Upload <?php echo ($editMode ? 'or update' : '') ?> a picture of your wishlist item.<br/>
	</div>
	<div class="value"><input type="file" name="photo" class="textbox" size='70' /></div> <br/>
	
	<?php
		if (!empty($story->photo_file))
		{
			echo '<img src="' . AppConfig::$url . $story->photo_file . '" style="max-width: 550px" />';
		}
	?>
	<br/> <br/>
	<input type="submit" id="post-btn" class="button orange" onclick="return false;"
		value="<?php echo ($editMode ? 'Save Changes' : 'Post') ?>" />
	<input type="hidden" name="action" value="save" />
	<input type="hidden" name="postid" value="<?php echo $story->postid ?>" />
</form>
<br/><br/>