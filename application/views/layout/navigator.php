<?php if (empty($selectedNav)) {

    $selectedNav = '';
}?>

<ul id="nav">
	
	<li class="nav-button <?php echo ($selectedNav == 'home'? 'nav-button-selected' : '') ?>">
		<a href="<?php echo AppConfig::$url?>home">
    		<span>
    		    <?php echo "<img src='" . AppConfig::$url . "images/magic_wand.png' />"; ?>
    		</span>
    		<span>Wish Feed</span>
		</a>
	</li>
	<li class="nav-button <?php echo ($selectedNav == 'profile'? 'nav-button-selected' : '') ?>">
		<a href="<?php echo AppConfig::$url?>profile">
    	    <span>
    	        <?php echo "<img src='" . AppConfig::$url . "images/account_and_control.png' />"; ?>
    		</span>
    		<span>Profile</span>
		</a>
	</li>
	<li class="nav-button <?php echo ($selectedNav == 'friends'? 'nav-button-selected' : '') ?>">
		<a href="<?php echo AppConfig::$url?>friends">
    		<span>
    	        <?php echo "<img src='" . AppConfig::$url . "images/happy-sad.png' />"; ?>
    		</span>
    		<span>Wishers</span>
		</a>
	</li>
	<li class="nav-button <?php echo ($selectedNav == 'friends'? 'nav-button-selected' : '') ?>">
		<a href="<?php echo AppConfig::$url?>friends">
    		<span>
    	        <?php echo "<img src='" . AppConfig::$url . "images/crumpled-badge.png' />"; ?>
    		</span>
    		<span>Groups</span>
		</a>
	</li>
</ul>