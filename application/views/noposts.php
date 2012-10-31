<p style="font-size: 17px">
	Hello <?php echo SessionUtil::getProfile()->nickname ?>! Must be your first time here. <br/>
	How about posting a wishlist item first? :)
</p>

<a href="<?php echo AppConfig::$url?>wishlist/post" class="button orange medium">Post a Wishlist Item</a>