<?php 
    $me = SessionUtil::getUser(); 
?>

<div class="comment-entry <?php echo ($hidden ? 'hidden-entry' : '') ?>" id="<?php echo $postid ?>" style="display: <?php echo ($hidden ? 'none' : 'inline-block') ?>">
	<div style="display: inline-block; vertical-align: top; padding-top: 5px; overflow: hidden; max-height: 32px">
		<a class="user" href="<?php echo AppConfig::$url . 'profile?id=' . $userid ?>">
	        <?php echo "<img src='" . AppConfig::$url . $photo_file . "' width='32px' />"; ?>
	    </a>
	</div>
	<div style="display: inline-block; vertical-align: top; width: 400px">
		<a class="user" href="<?php echo AppConfig::$url . 'profile?id=' . $userid ?>">
			<?php echo $nickname ?>
		</a>
		<?php if ($userid == $me->userid) { ?>
			<a href="javascript:void(0)" class="delete-comment" title="Delete this comment"
				style="float: right; height: 100%; font-size: 10px; padding-left: 20px">x</a>
		<?php } ?>
	    <?php echo PostUtil::tryHideLongText($text) ?> 
		<div>
			<?php echo PostUtil::dbTimeToHtml($date_modified);	?>
			<?php echo AppConfig::$separator ?> <a href="javascript:void(0)" class="like-comment"><?php echo $iLike? 'Unlike' : 'Like' ?></a> 
			<?php 
			    echo '<a href="javascript:void(0)" class="like-cm-view" style="display: ' . ($likecount > 0 ? 'inline' : 'none') . '">' 
				    . AppConfig::$separator . ' <img src="' . AppConfig::$url . 'images/rock_hand.png" width="13px" /> ' 
			        . '<span class="like-cnt">' . $likecount . '</span></a>';
			?>
		</div>
	</div>
</div>