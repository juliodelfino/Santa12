
<?php if ($postid > 0) { ?>
    <a href="<?php echo AppConfig::$url . 'wishlist?id=' . $postid ?>"
    	class="notif-entry <?php echo $isread == false? 'notif-entry-unread' : '' ?>">
<?php } else { ?>
	<span class="notif-entry <?php echo $isread == false? 'notif-entry-unread' : '' ?>">
<?php } ?>
	<div style="display: inline-block; vertical-align: top; overflow: hidden; max-height: 48px">
	        <?php echo "<img src='" . AppConfig::$url . $photo_file . "' width='48px' />"; ?>
	</div>
	<div style="display: inline-block; vertical-align: top; width: 230px">

    	<?php echo $notifText ?><br/>
    	<span style="color: orange"><?php echo $timestamp ?></span>
    </div>
<?php if ($postid == 0) { ?>
</span>
<?php } else { ?>
</a>
<?php } ?>