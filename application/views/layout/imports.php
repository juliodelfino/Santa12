
<?php $siteUrl = AppConfig::$url; ?>

<link rel="icon" href="<?php echo $siteUrl ?>images/favicon.ico" type="image/x-icon">

<link rel="stylesheet" type="text/css" media="all" 
	href="<?php echo $siteUrl ?>scripts/third_party/jquery-ui-1.8.11.custom.css" />
<link rel="stylesheet" type="text/css"
	href="<?php echo $siteUrl ?>scripts/third_party/jquery.dropdown.css" />
<link rel="stylesheet" type="text/css" media="all" 
	href="<?php echo $siteUrl ?>scripts/style.css" />

<script src="<?php echo $siteUrl ?>scripts/third_party/jquery.min.js" 
	type="text/javascript"></script>
<script src="<?php echo $siteUrl ?>scripts/third_party/slides.min.jquery.js" 
	type="text/javascript"></script>
<script src="<?php echo $siteUrl ?>scripts/third_party/jquery-ui-1.8.11.custom.min.js" 
	type="text/javascript"></script>
<script src="<?php echo $siteUrl ?>scripts/third_party/jquery.validate.js" 
	type="text/javascript"></script>
<script src="<?php echo $siteUrl ?>scripts/third_party/jquery.form.js" 
	type="text/javascript"></script>
<script src="<?php echo $siteUrl ?>scripts/third_party/jquery.dropdown.js" 
	type="text/javascript"></script>
<script src="<?php echo $siteUrl ?>scripts/third_party/fullbg.js" 
	type="text/javascript"></script>
<?php if (AppConfig::isItSnowTime()) { ?>
    <script src="<?php echo $siteUrl ?>scripts/third_party/snowstorm-min.js" 
    	type="text/javascript"></script>
<?php } ?>
<script src="<?php echo $siteUrl ?>scripts/third_party/js_magic.js" 
	type="text/javascript"></script>
<script src="<?php echo $siteUrl ?>scripts/santa12.js" 
	type="text/javascript"></script>