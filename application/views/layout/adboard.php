
			<div id="adboard-group">
				<div class="ad-header" style="font-weight: bold; background-color: #FFF3BD; padding: 3px">
					Hear ye, Hear ye!
				</div>
				
				<?php foreach(AdsUtil::getRandom() as $ad) { ?>
    				<div class="adboard">
					<?php
						if (empty($ad->link)){
							echo '<span class="ad-title">' . $ad->title . '</span>';
						}
						else {
							echo '<a class="ad-title" href="' . $ad->link . '" target="_blank">' . $ad->title . '</a>';
						}
					?>
        				<br/>
    					<?php echo $ad->content ?>
    				</div>
				<?php } ?>
				
			</div>