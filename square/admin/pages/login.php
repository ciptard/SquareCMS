<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
	<title>Login to <?php echo SITE_NAME; ?></title>
	<link rel="stylesheet" href="admin/admin.css" type="text/css" media="screen" />
	<style type="text/css" id="test">
		h1.header {text-align: center;}
		.wrapper {width: 300px; margin: auto; text-align: left;}
		label, input {display: block !important;}
		input {width: 295px; font-size: 20px;}
		button {clear: both !important; height: 40px !important; width: 100px !important; float: none !important; display: block; margin: 0 auto;}
	</style>
</head>
<body>
	<h1 class="header"><?php echo SITE_NAME; ?> <span>login</span></h1>
	<div class="wrapper">
		<?php if($fail == true){?>
		<div class="alert">
			Incorrect username/password.
		</div>
		<?php } ?>
		<fieldset>
			<form method="post">
				<label>Username: <input type="text" name="username" /></label>
				<label>Password: <input type="password" name="password" /></label>
				<button name="login" type="submit" value="Login" class="commit">Login</button>
			</form>
		</fieldset>
	</div>
</body>
</html>