<?php $pageid = 'settings themes'; include('admin/stubs/header.php'); ?>
<?php
if ($_POST["submit"]) {
	$dir = $_POST["submit"];
	mysql_query("UPDATE $settings SET `value` = '$dir' WHERE `name` = 'theme' ");
	header("Location: ./?cmd=themes");
}
function value_in($element_name, $xml, $content_only = true) {
	if ($xml == false) {
		return false;
	}
	$found = preg_match('#<'.$element_name.'(?:\s+[^>]+)?>(.*?)'.
			'</'.$element_name.'>#s', $xml, $matches);
	if ($found != false) {
		if ($content_only) {
			return $matches[1];  //ignore the enclosing tags
		} else {
			return $matches[0];  //return the full pattern match
		}
	}
	// No match found: return false.
	return false;
}
if ($handle = opendir('themes/')) {
	/* This is the correct way to loop over the directory. */
	while (false !== ($file = readdir($handle))) {
		if ($file != "." && $file != "..") {
			if (is_dir('themes/'.$file)) {
				$themes[] = $file;
			}
		}
	}
	closedir($handle);
}
?>

	<div class="wrapper">
		<h1 class="page">Themes</h1>
		<p>Here you can view all the themes available to your blog, if you want to install a new theme, copy the theme directory to <?php echo URL; ?>themes/ in your FTP client.</p>
		<p>Fancy a change? There's themes available at <a href="http://spoolio.co.cc/cms/square/themes">the Square themes site</a>!</p>
		<p>Here are the themes currently installed on the system.</p>
		<?php
		foreach ($themes as $new_theme) {
			if (file_exists('themes/'.$new_theme.'/theme.xml')) {
				$xml = file_get_contents('themes/'.$new_theme.'/theme.xml');
				if ($theme == value_in('dir', $xml)) { $iscurrent = true;
					echo '<div class="themes current">';
				} else { $iscurrent = false;
					echo '<div class="themes">';
				}
				$name = value_in('name', $xml);
				$desc = value_in('description', $xml);
				$url = value_in('url', $xml);
				$author = value_in('author', $xml);
				$dir = value_in('dir', $xml);
				echo '<a href="'.value_in('screenshot', $xml).'" target="_blank">';
				echo '<img src="'.value_in('screenshot_thumb', $xml).'" width="200px" alt="" class="screenshot" />';
				echo '</a>';
				echo '<div class="right"><p><strong>Theme Name</strong>: '.$name.'</p>';
				echo '<p><strong>Description</strong>: '.$desc.'</p>';
				echo '<p><strong>Author</strong>: <a href="'.$url.'" target="_blank">'.$author.'</a></p>';
				if ($iscurrent == true) {
					echo '<p><button name="current" type="button" value="current" class="submit">Current Theme</button></p>';
				} else {
					echo '<form method="post" action="./?cmd=themes""><p><button name="submit" type="submit" value="'.$dir.'" class="submit">Set as Theme</button></p></form>';
				}
				echo '</div></div>';
			}
		}
		?>

		<p class="footer">SquareCMS Version <?php echo VERSION; ?></p>
	</div>
<?php include('admin/stubs/footer.php'); ?>