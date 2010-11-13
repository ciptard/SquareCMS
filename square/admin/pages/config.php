<?php $pageid = "settings config"; include('admin/stubs/header.php');

function date_selector($format) {
	global $date_format;
	$start = '<option value="' . $format . '"';
	if ($format == $date_format) {
		$selected = ' selected="selected"';
	}
	$output = $start . $selected . '>'.date("$format").'</option>';
	return $output;
}

function user_date_selector($offset) {
	global $uct_offset;
	if ($offset >= 0) {$plus = "+";}
	$user_offset = ($offset * 60 * 60);
	$start = '<option value="' . $offset . '"';
	if ($uct_offset == $user_offset) {
		$selected = ' selected="selected"';
	}
	$output = $start . $selected . '>'.gmdate("d/m/Y H:i", time() + $user_offset).' ('.$plus.$offset.' hours)</option>';
	return $output;
}

if ($_POST["save_settings"]) {
	$new_settings['site_name'] = mysql_real_escape_string($_POST['site_name']);
	$new_settings['tagline'] = mysql_real_escape_string($_POST['tagline']);
	$new_settings['username'] = mysql_real_escape_string($_POST['new_username']);
	if (!empty($_POST["new_password"])) {
		$new_settings['password'] = md5($_POST["newpassword"]);
	} else {
		$new_settings['password'] = PASSWORD;
	}
	$new_settings['date_format'] = $_POST['date_format'];
	$new_settings['timezone'] = $_POST['timezone'];
	if ($_POST["cleanurls"]){$new_settings['cleanurls'] = 'true';} else {$new_settings['cleanurls'] = 'false';}
	foreach ($new_settings as $key => $value) {
		mysql_query("UPDATE `$settings` SET `value` = '$value' WHERE `name` = '$key'") or die(mysql_error());
	}
	header('Location: ./?cmd=config');
};
?>
	<div class="wrapper">
		<h1 class="page">Change settings</h1>
			<p>This page allows you to tweak and customise the many settings Square CMS has.</p>
			<form method="post" action="index.php?cmd=config" class="settings">
				<label>Blog Title <input type="text" name="site_name" class="settings" value="<?php echo SITE_NAME; ?>" /></label>
				<label>Blog Tagline <input type="text" name="tagline" class="settings" value="<?php echo TAGLINE; ?>" /></label>
				<label>Username <input type="text" name="new_username" class="settings" value="<?php echo USERNAME; ?>" /></label>
				<label>Password <input type="password" name="new_password" class="password settings" />Leave blank to keep current password</label>
				<label>Date Format <select name="date_format"><?php $date_types = array("M j, h:i A", "d.m.y", "j F, h:i A", "D M j, h:i A", "l F j, Y", "M j", "j F y", "d m Y - h:i", "F j, Y", "Y-m-d", "Y-d-m", "d/m/y h:i A", "d/m/y", "Y-m-d H:i");foreach ($date_types as $date_type) {echo date_selector($date_type);}?></select></label>
				<label>Date where you are <select name="timezone"><?php for ($i = -12; $i <= +14; $i++) {echo user_date_selector($i);}?></select></label>
				<label>Check if your server supports .htaccess <input type="checkbox" name="cleanurls" <?php if(CLEAN_URLS == true) { echo 'checked="checked"'; } ?> /></label>
				<p>Click <a href="./?cmd=configure-comments" onclick="return popitup('./?cmd=configure-comments')">here</a> to configure comments.</p>
				<p></p>
				<button type="submit" name="save_settings" value="submit" class="commit">Commit</button>
			</form>

		<p class="footer">SquareCMS Version <?php echo VERSION; ?></p>
	</div>
<?php include('admin/stubs/footer.php'); ?>