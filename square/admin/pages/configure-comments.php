<?php

if ($_POST["enable"]) {
	mysql_query('UPDATE '.$settings.' SET `value`="true" WHERE `name`="commenting_enabled"');
	header('Location: ./?cmd=configure-comments');
	exit();
}
if ($_POST["disable"]) {
	mysql_query("UPDATE $settings SET `value` = 'false' WHERE `name` = 'commenting_enabled'");
	header('Location: ./?cmd=configure-comments');
	exit();
}
if ($_GET["commentsystem"] || $_GET["uniquekey"]) {
	$commentingsystem = $_GET["commentsystem"];
	$uniqueid = $_GET["uniquekey"];
	mysql_query("UPDATE $settings SET `value` = '$commentingsystem' WHERE `name` = 'comment_system'");
	mysql_query("UPDATE $settings SET `value` = '$uniqueid' WHERE `name` = 'comment_unique_id'");
	header('Location: ./?cmd=configure-comments');
	exit();
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
	<title><?php echo SITE_NAME; ?> - Dashboard</title>
	<meta name="description" content="Square <?php echo VERSION; ?>" />
	<meta http-equiv="Content-type" content="text/html; charset=UTF-8" />
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.0/jquery.min.js"></script>
	<style type="text/css">
		@import url('admin/admin.css');
		html {margin: 0; padding: 0;}
		.wrapper {width: 350px !important;}
		.box {border-right: 0 !important; height: 375px;}
		.commit {float: none !important; display: block !important; margin: 0 auto;}
	</style>
</head>
<body>
<div class="wrapper">
	<h1>Configure Comments</h1>
	<p>Square does not offer a bespoke commenting system out of the box, instead, it fully supports any custom JS or otherwise commenting system you want to throw at it, you can configure comments manually in the "views/post.php" file or use one of the supported clients and set it up below.</p>
	<form method="post" action="./?cmd=configure-comments">
		<?php
		if(COMMENTING_ENABLED == false) { ?>
		<button name="enable" type="submit" value="enable" class="commit">Enable Comments</button>
		<div class="hints">
			This button enables comments globally and allows you to configure the blog system you are using, note that comments are enabled on a post by post and page by page basis.
		</div>
		<?php } else { ?>
		<button name="disable" type="submit" value="disable">Disable Comments</button>
		<div class="hints">
			Note that you can turn off comments for individual posts rather than disabling them site-wide.
		</div><br />
		<?php } ?>
	</form>
	<?php if(COMMENTING_ENABLED == true) { ?>
	<form method="get" action="./" name="configurecomments">
		<select name="commentsystem">
			<?php if(COMMENT_SYSTEM == "Intense Debate") { ?>
			<option value="Disqus">Disqus</option>
			<option value="Intense Debate" selected="selected">Intense Debate</option>
			<?php } else { ?>
			<option value="Disqus" selected="selected">Disqus</option>
			<option value="Intense Debate">Intense Debate</option>
			<?php } ?>
		</select>
		<input type="text" name="uniquekey"
			<?php if(COMMENT_UNIQUE_ID == '') { ?> class="text_field placeholder_content" value="Unique ID"
			onfocus="
				if (this.className == 'text_field placeholder_content') {
					if (! this.getAttribute('default_value')) {
						this.setAttribute('default_value', this.value);
					}
					this.value = '';
					this.className = 'text_field';
				}
			"
			onblur="
				if (! this.value) {
					this.value = this.getAttribute('default_value');
					this.className = 'text_field placeholder_content';
				}
			" <?php } else { ?> class="text_field" value="<?php echo COMMENT_UNIQUE_ID; ?>"<?php } ?>
		/>
		<input type="hidden" name="cmd" value="configure-comments" />
		<button type="submit" value="configure">Confirm</button>
		<p>The unique ID is provided by the commenting system, for Intense Debate it's your "site acct" key listed under your site keys and for Disqus it's the name of your Disqus domain, e.g. domain.disqus.com would simply be "domain".</p>
	</form>
	<?php } ?>
</div>
</body>
</html>