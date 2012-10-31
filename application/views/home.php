<?php if (empty($noFeed)) { ?>
	<script src="<?php echo AppConfig::$url ?>scripts/santa12feed.js" type="text/javascript"></script>
<?php } ?>
<?php 

    $ci = get_instance();
    foreach ($stories as $story) 
    {
        echo $ci->load->view('layout/wishlistentry', $story, true);
    }
?>