<form method="post" action="" enctype="multipart/form-data">

	<?php if (!empty($errorMessage)) { ?>
		<div class="errorbox"><?php echo $errorMessage ?></div>
		<br/>
	<?php } ?>
	
	<div style="display: inline-block; vertical-align: top">
	    <?php echo "<img src='" . AppConfig::$url . $profile->photo_file . "' width='120px' />"; ?>
	</div>
	<br/>
	<br/>
	
	<div class="label">
		Update your profile picture. Let your Santa know what you look like. :)<br/>
		Only GIF, JPEG or PNG files lesser than 1 MB can be uploaded. 
	</div>
	<div class="value"><input type="file" name="photo" class="textbox" /></div> <br/>

	<input type="submit" id="upload-btn" class="button orange" value="Upload" />
	<input type="hidden" name="action" value="save" />
</form>
<br/><br/>