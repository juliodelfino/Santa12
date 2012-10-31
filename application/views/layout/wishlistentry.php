<?php 
    $ci = get_instance(); 
    $me = SessionUtil::getProfile();
    $commentCount = count($comments);
?>

<div class="news-entry" id="<?php echo $postid ?>">

	<div style="display: inline-block; vertical-align: top; overflow: hidden; max-height: 64px">
		<a class="user" href="<?php echo AppConfig::$url . 'profile?id=' . $userid ?>">
	   		<?php echo "<img src='" . AppConfig::$url . $user_photo . "' width='64px' />"; ?>
		</a>
	</div>
	<div style="display: inline-block; vertical-align: top; width: 500px">
		<div style="font-weight: bold">
			<a class="user" href="<?php echo AppConfig::$url . 'profile?id=' . $userid ?>">
				<?php echo $nickname ?>
			</a>
			<?php if (!$viewMode) { ?>
				<a class="button orange small delete-post" style="float: right">X</a>
				<a href="<?php echo AppConfig::$url . 'wishlist/edit?id=' . $postid ?>" class="button orange small" style="float: right">Edit</a>
			<?php } ?>
		</div>
		<div>
    		<span style="font-weight: bold; font-size: 14px"><?php echo $title ?></span><br/>
		</div>
		<div>
			<?php if (!empty($photo_file)) { ?>
				<div style="text-align: center; border: solid 1px #EEE; margin: 10px 0; padding: 5px">
		       		<img src="<?php echo AppConfig::$url . $photo_file ?>" style="max-width: 480px; max-height: 400px" /><br/>
		        </div>
    		<?php }?>
			<?php if (!empty($description)) {
			        echo PostUtil::tryHideLongText($description) . '<br>';
			}?>
		</div>
		
		<div class="news-input"><a href="javascript:void(0)" class="like"><?php echo $iLike? 'Unlike' : 'Like' ?></a> 
			<?php echo AppConfig::$separator ?>  <a href="javascript:void(0)">Comment</a>
			<?php echo AppConfig::$separator ?> <?php echo PostUtil::dbTimeToHtml($date_posted) ?>
		</div>
		
		<div class="comment-group">
			<div class="comment-entry like-text" style="display: <?php echo !empty($htmlLike) ? 'block' : 'none' ?>">
				<span style="padding-top: 2px">
					<img src="<?php echo AppConfig::$url ?>images/rock_hand.png" width="13px" />
				</span>
				<span class="like-users"><?php echo $htmlLike ?></span>
			</div>

			<?php if ($commentCount > 4) { ?>
			<div class="comment-entry view-comments-link">
				<a href="javascript:void(0)">View all <?php echo $commentCount ?> comments</a>
			</div>
			<?php } ?>
    		
    		<?php foreach ($comments as $comment) { 
    		
       			echo $ci->load->view('layout/commententry', $comment, true);
    	    } ?>
    		
			<div class="comment-input">
				<div style="display: inline-block; vertical-align: top; overflow: hidden; max-height: 32px;">
            	    <?php echo "<img src='" . AppConfig::$url . $me->photo_file . "' width='32px' />"; ?>
            	</div>
				<div style="display: inline-block; vertical-align: top; width: 400px">
					<textarea class="commentbox" postid="<?php echo $postid ?>" rows="1"></textarea>
            	</div>
			</div>
		</div>
	</div>
	
</div> 