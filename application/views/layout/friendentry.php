
<div style="background-color: #F0F0F0; padding: 5px; margin: 3px; width: 280px; display: inline-block; height: 100px">
	<div style="display: inline-block; vertical-align: top; overflow: hidden; max-height: 100px">
		<a class="user" href="<?php echo AppConfig::$url ?>profile?id=<?php echo $userid ?>">
	        <?php echo "<img src='" . AppConfig::$url . $photo_file . "' width='100px' />"; ?>
		</a>
	</div>
	<div style="display: inline-block; vertical-align: top; width: 170px">
		<div style="font-weight: bold">
			<a class="user" href="<?php echo AppConfig::$url ?>profile?id=<?php echo $userid ?>">
				<?php echo $nickname ?>
			</a>
		</div>
		<span style="font-size: 11px"><?php echo $email ?></span>
	</div>
</div> 

