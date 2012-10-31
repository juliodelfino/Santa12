<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title>NSP Secret Santa 2012</title>

<?php include "application/views/layout/metatags.php"?>
<?php include "application/views/layout/imports.php"?>

</head>
<body>
	
	<div id="container">
		<div id="login-body" style="padding-top: 100px">
			<br/>
			<br/>
			<span style="font-size: 50px; font-weight: bold">Secret Santa 2012</span>
			<br/>
			<br/>
			<span style="font-size: 20px;">
				Tell Santa what you want for christmas.
			</span>
			<br/><br/>
			<?php if (!empty($errorMsg)) { ?>
				<div class="errorbox" style="margin: 0 auto"><?php echo $errorMsg ?></div>
			<?php } ?>
			Please use your NSP LDAP account.
			<br/><br/>
				
		   <form method="post" name="loginform" action="<?php echo AppConfig::$url?>login">
				Username:  <input type="text" class="textbox" name="username" value="<?php echo $user->email ?>" maxlength="64"  /><br/><br/>
				Password:  <input type="password" class="textbox" name="password"  maxlength="32" /><br/><br>
				<input type="submit" class="button orange" value="Login" />
           </form>
			
			
		</div>
	</div>
    <p class="footer">
        <a href="">NSP Secret Santa 2012</a> 
        <?php echo AppConfig::$separator ?> This site is best viewed in Firefox or Chrome.
	</p>
    
	<input type="hidden" id="site_url" value="<?php echo AppConfig::$url ?>" />
	
    <script>
		$(function()
		{
			document.loginform.username.focus();
		});
	</script>
</body>
</html>
