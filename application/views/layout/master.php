
<?php 
    $myProfile = SessionUtil::getProfile(); 
    $loggedIn = SessionUtil::loggedIn();
    $notif_count = 0;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title><?php echo ($notif_count > 0 ? "($notif_count) " : '') . $title ?></title>

<?php include "application/views/layout/metatags.php"?>
<?php include "application/views/layout/imports.php"?>

</head>
<body>

	<div id="header">
		<div id="header-content">
			<div style="display: inline">
        		<span id="title">NSP Christmas 2013 </span>
    			<?php if ($loggedIn) { ?>
            		<?php echo AppConfig::$separator; ?> 
            		
            		<a id="notif-btn" class="header-item" data-dropdown="#notif-dropdown" title="View notifications">
        				<img src="<?php echo AppConfig::$url ?>images/megaphone.png" style="vertical-align: bottom; z-index: 0" />
        				<span id="notif-count" style="display: <?php echo ($notif_count > 0 ? 'inline' : ' none') ?>">
        				    <?php echo $notif_count ?></span>
            		</a>
			    <?php } ?>
    		</div>
			
    		<?php if ($loggedIn) { ?>
			<div style="float: right; display: inline">
        			    Hello <?php echo $myProfile->nickname ?>! Glitters of Hope!
						<a class="header-item">
							<img id="menu-btn" data-dropdown="#menu-dropdown"
								src="<?php echo AppConfig::$url ?>images/gold-star-small.png" 
								style="vertical-align: bottom; cursor: pointer; width: 24px" />
						</a>			
			</div>
			<?php } ?>
		</div>
	</div>
	<div id="container">
        <?php include "application/views/layout/navigator.php"?>
		<div id="content">            
			<div id="center-content">
    			
                <?php echo $content ?>
    			
			</div>
			
            <div id="notif-dropdown" class="dropdown-menu has-tip has-scroll anchor-right" style="position: fixed; font-size: 11px">
                <ul style="width: 320px; max-height: 500px">
                    <li style="font-weight: bold">Notifications</li>
                    <li class="divider" id="notif-start"></li>
                    <li class="divider"></li>
                    <li style="text-align: center; font-weight: bold">
                    	<div><a href="<?php echo AppConfig::$url ?>notifications">See All</a></div>
                    </li>
                </ul>
            </div>
            
            <div id="menu-dropdown" class="dropdown-menu has-tip anchor-right" style="position: fixed; font-size: 11px">
                <ul style="max-width: 100px; min-width: 100px; width: 100px">
                    <li>
						<a href="<?php echo AppConfig::$url?>logout">Logout</a>
					</li>
                </ul>
            </div>
            
            <?php if ($loggedIn) { include "application/views/layout/adboard.php"; } ?>
			
		</div>
		<?php include "application/views/layout/footer.php"?>
	</div>
	<input type="hidden" id="site_url" value="<?php echo AppConfig::$url ?>" />
	<input type="hidden" id="orig_title" value="<?php echo $title ?>" />
	<input type="hidden" id="snow_time" value="<?php echo AppConfig::isItSnowTime() ? 'true' : 'false' ?>" />

</body>
</html>
