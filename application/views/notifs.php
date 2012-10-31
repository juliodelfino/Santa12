<?php foreach ($notifs as $notif) { ?>
	<div><?php echo NotifType::toHtml($notif) ?></div>
<?php } ?>