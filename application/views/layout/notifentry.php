
<?php if ($postid > 0) { ?>
    <a href="<?php echo AppConfig::$url . 'wishlist?id=' . $postid ?>"
    	class="notif-entry2 <?php echo $isread == false? 'notif-entry-unread' : '' ?>">
<?php } else { ?>
<span class="notif-entry2 <?php echo $isread == false? 'notif-entry-unread' : '' ?>">
<?php } ?>
	<div style="display: inline-block; vertical-align: top; overflow: hidden; max-height: 24px">
	        <?php echo "<img src='" . AppConfig::$url . $photo_file . "' width='24px' />"; ?>
	</div>
	<div style="display: inline-block; vertical-align: top; width: 550px">

    	<?php echo $notifText ?>
    	<div style="color: orange; font-size: 10px; display: inline-block"><?php echo $timestamp ?></div>
    </div>
<?php if ($postid == 0) { ?>
</span>
<?php } else { ?>
</a>
<?php } ?>