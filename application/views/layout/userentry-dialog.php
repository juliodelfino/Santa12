
<div style="background-color: #F0F0F0; padding: 5px; margin: 3px; width: 210px; height: 65px; display: inline-block; font-size: 11px; text-align: left">
	<div style="display: inline-block; vertical-align: top; overflow: hidden; max-height: 64px">
		<a class="user" href="<?php echo AppConfig::$url ?>profile?id=<?php echo $userid ?>">
	        <?php echo "<img src='" . AppConfig::$url . $photo_file . "' width='64px' />"; ?>
		</a>
	</div>
	<div style="display: inline-block; vertical-align: top; width: 140px">
		<div>
			<a class="user" href="<?php echo AppConfig::$url ?>profile?id=<?php echo $userid ?>">
				<?php echo $nickname ?>
			</a>
		</div>
	</div>
</div> 

