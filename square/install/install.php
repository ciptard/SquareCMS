<?php
	/*
		The Bespoke Blog Engine - Version 1.0 (SquareCMS)
		Last edited on the 13th November 2010

		------------------------------------------------------------------
		This program is free software. It comes without any warranty, to
		the extent permitted by applicable law. You can redistribute it
		and/or modify it under the terms of the Do What The Fuck You Want
		To Public License, Version 2, as published by Sam Hocevar. See
		http://sam.zoy.org/wtfpl/COPYING for more details.
	*/

	// Small debugging script that posts the page load time - Uncommenting will invalidate the xHTML markup :(
	$start_time = microtime(true); register_shutdown_function('my_shutdown'); function my_shutdown() {global $start_time; echo "<p style='text-align: right; font-style: italic; color: grey; font-size: 12px; clear: both;'>Page loaded in ".(round((microtime(true) - $start_time), 5))." seconds.</p>";}

	function http_get_file($url)    {
		$url_stuff = parse_url($url);
		$port = isset($url_stuff['port']) ? $url_stuff['port']:80;

		$fp = fsockopen($url_stuff['host'], $port);

		$query  = 'GET ' . $url_stuff['path'] . " HTTP/1.0\n";
		$query .= 'Host: ' . $url_stuff['host'];
		$query .= "\n\n";

		fwrite($fp, $query);

		while ($line = fread($fp, 1024)) {
			$buffer .= $line;
		}

		preg_match('/Content-Length: ([0-9]+)/', $buffer, $parts);
		return substr($buffer, - $parts[1]);
	}

	function findColumn($database, $tableName, $columnName){
		$result = mysql_query('select * from '.$tableName.' limit 0,1');
		$columns = mysql_num_fields($result);

		for ($i = 0; $i < $columns; $i++) {
			$field_array[] = mysql_field_name($result, $i);
		}

		if (!in_array($columnName, $field_array)) {
			return false;
		} else {
			return true;
		}
	}

	function generate_config($server, $database, $username, $password, $prefix) {
		return "<?php
	\$dbsettings = array('host' => '".$server."', 'username' => '".$username."', 'password' => '".$password."', 'database' => '".$database."');
	\$dbprefix	= '".$prefix."';
?>";
	}

	function user_date_selector($offset) {
		$user_offset = ($offset * 60 * 60);
		$start = '<option value="' . $offset . '"';
		if ((date("d/m/Y H:i", time() + $user_offset)) == gmdate("d/m/Y H:i")) {
			$selected = ' selected="selected"';
		}
		$output = $start . $selected . '>'.gmdate("d/m/Y H:i", time() + $user_offset).'</option>';
		return $output;
	}

	ob_start(); 							/* Lets us change the headers further down */

	define('SOFT_NAME',	'square'); 		/* Folder name in case I change it later */
	define('HARD_NAME',	'The Bespoke Blog Engine - SquareCMS'); 	/* Application Name */
	define('VERSION', '1.0.0');
	error_reporting(E_ALL^E_NOTICE^E_WARNING);

	if (isset($_GET['step'])) {$step = $_GET['step'];} else {$step = 1;}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
	<meta http-equiv="Content-type" content="text/html; charset=UTF-8" />
	<title><?php echo HARD_NAME; ?> - Installation</title>
	<link rel="stylesheet" href="style.css" type="text/css" />
</head>
<body>
	<div class="wrapper">
		<h1><?php echo HARD_NAME; ?></h1>
<?php
	if ($step == 1) {
		$heading .= 'Step One: Server compatibility';
		$heading .= '<h2>Prologue</h2><p>Welcome to the last official version of SquareCMS to be published by <a href="http://twitter.com/phenomenontom">me</a>.</p><p>This has been a rollercoaster filled with challenges and issues, both on and off the keyboard, but I have overcome (the majority of) them and met some amazing people along the way.</p><p>After months and months of not being able to get the time nor energy to work on this, I\'ve finally wrapped my baby up, called it version 1 and let it out for adoption.</p><p>Thank you. :)</p>';
		$results[] = array(
			'PHP is enabled on your server.',
			1
		);
		if (!defined('PHP_VERSION_ID')) {
			$version = explode('.', PHP_VERSION);

			define('PHP_VERSION_ID', ($version[0] * 10000 + $version[1] * 100 + $version[2]));
		}
		if (PHP_VERSION_ID > 40400) {
			$results[] = array(
				'Your version of PHP is fully supported.'
				, 1
			);
		} else {
			$results[] = array(
				'Your version of PHP may not work as expected, please consider an upgrade.'
				, 2
			);
		}
		if ($str = http_get_file('http://spoolio.po.gs/current.txt')){
			$onversion = explode('.',$str);
			$offversion = explode('.',$square_version);
			if (intval($onversion[1].$onversion[2]) >= intval($offversion[1].$offversion[2])){
				$results[] = array(
					'This is the most recent version of the software',
					1
				);
			} else {
				$results[] = array(
					'This is not the latest version of the software, please download the most current version at '.URL,
					2
				);
			}
		} else {
			$results[] = array(
				'Could not determine if this is the latest version of the software',
				2
			);
		}
		$footer = '<p>So far, so good, let\'s <a href="./?step=2">continue on.</a></p>';
	}

	if ($step == 2) {
		$heading = 'Step Two: Database connections';
		if (!isset($_POST['mysql'])){
			$form = <<<HTML
<p>The software requires a valid MySQL connection in order to store and retrieve settings, posts, pages and comments (amongst other things).</p>
<p>Please provide your MySQL server (<em>usually <code>localhost</code></em>), the credentials and a database as well as a <strong>prefix</strong>, for clean installations you can leave the given as it is, if you are upgrading, make sure to use the prefix of your current installation or a clean installation will be performed (this is usually <code>square_</code>).</p>
<form method="post" action="./?step=2" name="dbsettings" class="server">
	<fieldset>
		<label>Server:<input type="text" name="db[server]" value="localhost" /></label>
		<label>Username:<input type="text" name="db[username]" /></label>
		<label>Password:<input type="password" name="db[password]" /></label>
		<label>Database:<input type="text" name="db[database]" /></label>
		<label>Prefix:<input type="text" name="db[prefix]" value="square_" /></label>
		<button name="mysql" type="submit">Proceed</button>
	</fieldset>
</form>
HTML;
		} else {
			$db = $_POST['db'];
			if (!mysql_pconnect($db['server'],$db['username'],$db['password'])) {
				$flag = true;
				$results[] = array(
					'Could not connect to your MySQL server, error: '.mysql_error(),
					0
				);
			} else {
				$results[] = array(
					'Successfully connected to your MySQL server',
					1
				);
				if (!mysql_select_db($db['database'])) {
					$flag = true;
					$results[] = array(
						'Could not connect to your selected database',
						0
					);
				} else {
					$results[] = array(
						'Successfully connected to your selected database',
						1
					);

					$config = generate_config($db['server'], $db['database'], $db['username'], $db['password'], $db['prefix']);
					$file = "../config.temp.php";
					$handle = fopen($file, 'a');
					fwrite($handle, $config);
					fclose($handle);
					rename("../config.temp.php", "../config.php");

					$posts = $db['prefix'].'posts';
					if (mysql_query("SELECT * FROM `$posts` LIMIT 0,1")) {
						$results[] = array(
							'Detected your current installation',
							1
						);
						$tag = 'upgrade';
					} else {
						$results[] = array(
							'Did not detect any installation present, proceeding to clean install',
							1
						);
						$tag = 'clean';
					}
					$footer = '<p>Proceed to <a href="./?step=3&tag='.$tag.'">step three</a> to continue (Type: '.$tag.')</p>';
				}
			}
		}
	}

	if ($step == 3) {
		$tag = $_GET['tag'];
		if (!file_exists('../config.php')) {
			$flag = true;
			$results[] = array(
				'Creation of your configuration file failed, please follow the steps below to correct this before continuing.',
				0
			);
			$form = '<p>If you know your configuration details from the previous page to be correct, edit config.example in the application folder to match your variables, rename it <code>config.php</code> and refresh this page.</p>';
		} else {
			$results[] = array(
				'Creation of your configuration file was successful.',
				1
			);
			if ($tag == 'clean') {
				$results[] = array(
					'Clean installation initialised.',
					1
				);
				require_once('../config.php');
				$queries = array(
					array('pages', 'CREATE TABLE `'.$dbprefix.'pages` (`id` int(11) not null auto_increment,`name` varchar(250),`url` varchar(50),`comments` varchar(5) default \'false\',`status` varchar(7) default \'draft\',`content` text,`type` varchar(8),PRIMARY KEY (`id`)) ENGINE=MyISAM DEFAULT CHARSET=latin1;'),
					array('posts', 'CREATE TABLE `'.$dbprefix.'posts` (`id` int(11) not null auto_increment,`title` varchar(250) not null,`url` varchar(50) not null,`comments` varchar(5) default \'false\',`content` text not null,`date-time` datetime not null,`status` varchar(7) default \'draft\',`tags` tinytext,`blurb` varchar(250),PRIMARY KEY (`id`)) ENGINE=MyISAM DEFAULT CHARSET=latin1'),
					array('trash', 'CREATE TABLE `'.$dbprefix.'posts_trash` (`id` int(11) not null,`title` varchar(250) not null,`url` varchar(50) not null,`comments` varchar(5) default \'false\',`content` text not null,`date-time` datetime not null,`status` varchar(7) default \'draft\',`tags` tinytext,`blurb` varchar(250),PRIMARY KEY (`id`)) ENGINE=MyISAM DEFAULT CHARSET=latin1;'),
					array('settings', 'CREATE TABLE `'.$dbprefix.'settings` (`name` varchar(255) not null,`value` varchar(255) not null,PRIMARY KEY (`name`)) ENGINE=MyISAM DEFAULT CHARSET=latin1;')
				);
				mysql_pconnect($dbsettings['host'],$dbsettings['username'],$dbsettings['password']);
				mysql_select_db($dbsettings['database']);
				foreach ($queries as $query) {
					if (!mysql_query($query[1])) {
						$flag = true;
						$results[] = array(
							'Failed to create '.$query[0].' table. Error: '.mysql_error(),
							0
						);
					} else {
						$results[] = array(
							'Successfully created '.$query[0].' table.',
							1
						);
					}
				}
				if (!$flag) {
					$hello_world = "INSERT INTO `".$dbprefix."posts` (`id`, `title`, `url`, `comments`, `content`, `date-time`, `status`, `tags`, `blurb`) VALUES ('1', 'Hello World!', 'hello-world', 'true', '<p>Welcome to your new blog! Feel free to edit this post to make it more of a welcome to your users than to you, then you can add a theme, edit the pages, change the structure of the site; edit away to your hearts content!</p>\r\n<p>Stay up to date with updates at <a href=\"http://spoolio.co.cc/p/square\">http://spoolio.co.cc/</a>. Have fun!</p>', '".gmdate('Y-m-d')." 00:00:00', 'publish', 'hello world, first, quickie', '');";
					if (!mysql_query($hello_world)) {
						$flag = true;
						$results[] = array(
							'Hello World creation failed. Error:'.mysql_error(),
							0
						);
					} else {
						$results[] = array(
							'Successfully created Hello World post.',
							1
						);
						$pages_content = "INSERT INTO `".$dbprefix."pages` (`id`, `name`, `url`, `comments`, `status`, `content`, `type`) VALUES ('1', 'About Me', 'about', 'false', 'publish', '<p>Feugiat tation euismod nostrud, luptatum dolore delenit amet odio accumsan veniam vero accumsan exerci nisl nulla delenit laoreet duis aliquip nulla feugait velit dolore crisare eros vel qui?</p>\r\n<h2>Where I can be found:</h2>\r\n<p>I dive quietly and bring him back to prevent more conflict. And it was real. And, when Leo saw a monster or alien being in the freezer, which came out that the machine opens a door into an otherwise closed emotional world and allows her, almost teaches her, to feel empathy for others.</p>\r\n<h2>A Note to the Admin</h2>\r\n<p>This gibberish was created at <a href=\"http://loremipscream.com/\">Lorem Ipscream</a>, you should probably make it your own gobbledigook.</p>', 'content'),('2', 'Footer', 'footer', 'false', 'publish', '<p>Here you could add some exits for people, a blogroll or something similar for example.</p>\r\n<p>Like what you read? Follow me on Twitter or browse through the archives.</p>\r\n<p><a href=\"http://www.youtube.com/watch?v=E6m44rPoXng\">Did you know that a hippo\'s sweat is pink?</a></p>', 'stub');";
						if (!mysql_query($pages_content)) {
							$flag = true;
							$results[] = array(
								'Pages creation failed. Error:'.mysql_error(),
								0
							);
						} else {
							$results[] = array(
								'Successfully created pages.',
								1
							);
							$footer = '<p>OK Sparky, we\'ve done the ground work, now it\'s time to <a href="./?step=4&tag=clean">configurate</a>!</p>';
						}
					}
				}
			}
			if ($tag == 'upgrade') {
				$results[] = array(
					'Upgrade initialised.',
					1
				);
				require_once('../config.php');
				mysql_pconnect($dbsettings['host'],$dbsettings['username'],$dbsettings['password']);
				mysql_select_db($dbsettings['database']);
				$posts = $dbprefix.'posts';
				$trash = $dbprefix.'posts_trash';
				$pages = $dbprefix.'pages';

					if (!findColumn($dbname,$posts,"date-time")) {
						// Need to create date-time
						mysql_query("ALTER TABLE $posts ADD `date-time` datetime AFTER content;");
						$result = mysql_query("SELECT * FROM $posts");
						while($item = mysql_fetch_array($result, MYSQL_ASSOC)) {
							$datetime = $item['date']." ".$item['time'];
							$id = $item['id'];
							include('../php/opendb.php');
							mysql_query('UPDATE '.$posts.' SET `date-time`="'.$datetime.'" WHERE `id`="'.$id.'"');
							$updatedCount++;
						}
						mysql_query("ALTER TABLE $posts DROP `date`");
						mysql_query("ALTER TABLE $posts DROP `time`");
						$results[] = array(
							'Updated '.$updatedCount.' posts to the new date and time format.',
							1
						);
					}

					if (!mysql_query("SELECT * FROM `$trash` LIMIT 0,1")){
						if (mysql_query("CREATE TABLE `".$trash."` (`id` int(11) not null,`title` varchar(250) not null,`url` varchar(50) not null,`comments` varchar(5) default 'false',`content` text not null,`date-time` datetime not null,`status` varchar(7) default 'draft',`tags` tinytext,`blurb` varchar(250),PRIMARY KEY (`id`)) ENGINE=MyISAM DEFAULT CHARSET=latin1;")) {
							$results[] = array(
								'Trash created.',
								1
							);
						} else {
							$results[] = array(
								'Trash creation failed.',
								0
							);
						}
					}

					if (!$row = mysql_fetch_array(mysql_query("SELECT * FROM `".$pages."` WHERE `url`='footer' AND `type`='stub'"), MYSQL_ASSOC)) {
						if (mysql_query("INSERT INTO `".$pages."` (`id`, `name`, `url`, `comments`, `status`, `content`, `type`) VALUES (NULL, 'Footer', 'footer', 'false', 'publish', '<p>Here you could add some exits for people, a blogroll or something similar for example.</p>\r\n<p>Like what you read? Follow me on Twitter or browse through the archives.</p>\r\n<p><a href=\"http://www.youtube.com/watch?v=E6m44rPoXng\">Did you know that a hippo\'s sweat is pink?</a></p>', 'stub')")) {
							$results[] = array(
								'Stubs created.',
								1
							);
						} else {
							$results[] = array(
								'Stubs creation failed.',
								0
							);
						}
					}

					$footer = '<p>OK Sparky, we\'ve done the ground work, now it\'s time to <a href="./?step=4&tag=upgrade">configurate</a>!</p>';
			}
		}
	}

	if ($step == 4) {
		require_once('../config.php');
		mysql_pconnect($dbsettings['host'],$dbsettings['username'],$dbsettings['password']);
		mysql_select_db($dbsettings['database']);
		$tag = $_GET['tag'];
		$heading = 'Step Four: Configuration';
		$settings = $_POST['st'];
		if (!isset($_POST['st'])) {
			if ($issetflag == true) {
				$form .= '<p class="fail">All fields are required. Please try again.</p>';
			}
			if ($tag == 'clean'){
				$form .= <<<HTML
	<p>This is the last information gathering form before you can call your blog your own.</p>
	<p>All of these settings can be changed at any time from your admin, so don't feel too pressured into making any decisions :-)</p>
	<p>Please fill in <strong>all</strong> fields or your blog may not function correctly.</p>
HTML;
				$form .= <<<HTML
<form method="post" action="./?step=4&tag=clean" name="settings" class="server">
	<fieldset>
		<label>Username:<input type="text" name="st[username]" /></label>
		<label>Password:<input type="password" name="st[password]" /></label>
		<label>Full Name:<input type="text" name="st[full_name]" /></label>
		<label>Site name:<input type="text" name="st[site_name]" value="My Awesome Blog!" /></label>
		<label>Tagline:<input type="text" name="st[tagline]" value="A modest blog, in the top 1 of all blogs, ever." /></label>
HTML;
		$form.='<label>Date:<select id="timezone" name="st[timezone]">'; for ($i = -12; $i <= +14; $i++) {$form.=user_date_selector($i);} $form.= '</select></label>';
		$form.= <<<HTML
		<label>Clean URLs<input type="checkbox" name="st[clean_urls]" id="htaccess" checked="checked" /></label>
		<button name="submit" type="submit" value="Submit">Submit</button>
	</fieldset>
</form>
HTML;
			}
			if ($tag == 'upgrade'){
				$settings = $dbprefix.'settings';
				// We'll show the user their current configuration and just give them the option to continue for now
				if (!findColumn($dbsettings['database'],$settings,"name")) {
					// We have an old school version!
					$query = mysql_query("SELECT * FROM $settings WHERE id = '1'");
					$setting = mysql_fetch_array($query, MYSQL_ASSOC);
					$form .= '<p>We\'ll make a copy of your settings table which will double as a backup, this will be found at "'.$settings.'_old"</p>';

					if (mysql_query("RENAME TABLE `".$settings."` TO `".$settings."_old`")) {
						$results[] = array(
							'Settings backed-up.',
							1
						);
					} else {
						$results[] = array(
							'Settings copy failed.',
							0
						);
					}

					if ($result = mysql_query("SELECT * FROM `".$settings."_old` WHERE `id`=1")){
						while($row = mysql_fetch_array($result)){
							$setting[$row['name']] = $row['value'];
						}
						$results[] = array(
							'Settings retrieved.',
							1
						);
						if (mysql_query('CREATE TABLE `'.$settings.'` (`name` varchar(255) not null,`value` varchar(255) not null,PRIMARY KEY (`name`)) ENGINE=MyISAM DEFAULT CHARSET=latin1;')) {
							if(mysql_query("INSERT INTO `".$settings."` (`name`, `value`) VALUES ('username', '".$setting['username']."'),('password', '".$setting['password']."'),('site_name', '".$setting['site_name']."'),('tagline', '".$setting['tagline']."'),('date_format', '".$setting['date_format']."'),('timezone', '".$setting['timezone']."'),('cleanurls', '".$setting['cleanurls']."'),('theme', '".$setting['theme']."'),('commenting_enabled', '".$setting['commenting_enabled']."'),('comment_system', '".$setting['comment_system']."'),('comment_unique_id', '".$setting['comment_unique_id']."')")) {
								$results[] = array(
									'Settings moved to new table.',
									1
								);
							} else {
								$results[] = array(
									'Settings move failed.',
									0
								);
							}
						} else {
							$results[] = array(
							'Settings table recreation failed.',
							0
						);
						}
					} else {
						$results[] = array(
							'Settings retrieval failed.',
							0
						);
					}

				} else {
					// Post 0.4 Square - add any new fields in future versions here
					$footer = '<p>And that\'s it! You can now visit your <a href="../../">Upgraded Blog!</a></p>';
				}
				$form.= <<<HTML

HTML;
			}
		} else {
			if ($tag == 'clean') {
				$settings = $_POST['st'];
				if (isset($settings['clean_urls'])) {$clean = true;} else {$clean = false;}
				$timezone = $settings['timezone'];
				$tagline = mysql_real_escape_string($settings['tagline']);
				$site_name = mysql_real_escape_string($settings['site_name']);
				$password = md5($settings['password']);
				$username = mysql_real_escape_string($settings['username']);

				$make_settings = "INSERT INTO `".$dbprefix."settings` (`name`, `value`) VALUES ('username', '$username'),('password', '$password'),('site_name', '$site_name'),('tagline', '$tagline'),('date_format', 'F j, Y'),('timezone', '$timezone'),('cleanurls', '$clean'),('theme', 'default'),('commenting_enabled', 'false'),('comment_system', ''),('comment_unique_id', '');";

				if (!mysql_query($make_settings)) {
					$flag = true;
					$results[] = array(
						'Settings creation failed. Error:'.mysql_error(),
						0
					);
				} else {
					$results[] = array(
						'Settings creation succeeded',
						1
					);
					$footer = '<p>And that\'s it! You can now visit your <a href="../../">New Blog!</a></p>';
				}
			}
		}
	}

	$html = '<h2>'.$heading.'</h2>';
	if (!empty($results)) {
		$html .= '<ul class="test">';
		foreach ($results as $r => $result) {
			switch ($result[1]) {
				case 0:
					$html.= '<li class="fail">';
					break;
				case 1:
					$html.= '<li class="pass">';
					break;
				case 2:
					$html.= '<li class="warn">';
					break;
			}
			$html.= $result[0].'</li>';
		}
		$html .= '</ul>';
	}
	$footer .= '<p class="footer">'.HARD_NAME.' '.VERSION.' Installation</p>';
	if ($flag == true) {
		$footer = '<p>There was an issue with installation, please review the results above and try again.</p>';
	}
	echo $html.$form.$footer;
?>
	</div>
</body>
</html>