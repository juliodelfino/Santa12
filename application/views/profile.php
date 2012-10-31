<script src="<?php echo AppConfig::$url ?>scripts/santa12feed.js" type="text/javascript"></script>
<script type="text/javascript">
	userId = <?php echo $user->userid ?>;
</script>

<?php
    $primaryPhoto = "<img src='" . AppConfig::$url . $user->photo_file . "' width='120px' />"; 
    if (!$viewMode) {
        $primaryPhoto = '<a href="' . AppConfig::$url . 'profile/photo" title="Change your photo">'
        	. $primaryPhoto . '</a>';
    }
?>
<?php 
    $ci = get_instance(); 
    $me = SessionUtil::getProfile();
?>

<div>
	<div style="display: inline-block; vertical-align: top">
        <?php echo $primaryPhoto; ?>
	</div>
	<div style="display: inline-block; vertical-align: top; width: 300px; padding-left: 10px">
		<div style="font-weight: bold; font-size: 25px; margin-bottom: 10px">
			<?php echo $user->nickname ?>
		</div>
		<?php if (!empty($user->email)) { ?>
    		<div style="font-size: 17px">
    			<?php echo $user->email ?>
    		</div>
    	<?php } ?>
		<div style="font-size: 17px">
			<?php echo $user->phone ?>
		</div>
		<div style="margin-top: 20px">
			<?php if (!$viewMode) { ?>
    		<a href="<?php echo AppConfig::$url?>profile/edit" class="button orange medium">Edit Profile</a>
			<?php } ?>
		</div>
		
	</div>
	
</div> 

<br>

<div style="border-top: dashed 1px #D0D0D0; padding: 10px 0;">
	<?php if (!$viewMode) { ?>
		<a href="<?php echo AppConfig::$url?>wishlist/post" class="button orange medium">Post a Wishlist Item</a>
	<?php } ?>
</div>

<?php
   
/*    if ($userHasStories) { */

    	foreach ($stories as $story) {
    
    		echo $ci->load->view('layout/wishlistentry', $story, true);
    	}
/*    }
    else {
        if ($viewMode) {
           echo $ci->load->view('noposts', array(), true);
        }
    }
*/
?>

