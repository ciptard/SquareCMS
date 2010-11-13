<?php
	/*
		This controller has all the settings which used to be contained in config.php.
		The reasion for this is that it makes the install easier to use for future versions, as it generates a config file
		for the user.
	*/
	
	// LEAVE AS IS
	$posts 		= $dbprefix.'posts';
	$trash 		= $dbprefix.'posts_trash';
	$pages 		= $dbprefix.'pages';
	$settings 	= $dbprefix.'settings';
	$comments 	= $dbprefix.'comments';

	$order = 'ORDER BY `date-time` DESC, ID DESC';

	$result = return_array("SELECT * FROM $settings", false);
	while($row = mysql_fetch_array($result)){
		$setting[$row['name']] = $row['value'];
	}

	/*
	This is the PHP format of the date, you can find a date format which suits
		you in the settings page, or by putting it in manually here using this page
		http://php.net/date
	*/
	$date_format 	= $setting['date_format'];
	$timezone	 	= $setting['timezone'];		// The UCT Offset
	$uct_offset		= (($timezone) * 60 * 60);


	// COMMENTING
	if ($setting['commenting_enabled'] == "true"){define('COMMENTING_ENABLED', true);}else{define('COMMENTING_ENABLED', false);}
	if (COMMENTING_ENABLED == true) {
		define('COMMENT_SYSTEM', $setting['comment_system']); // Disqus or Intense Debate
		define('COMMENT_UNIQUE_ID', $setting['comment_unique_id']);
	}

	// THEME SETTINGS
	$theme 				= $setting['theme'];
	define('THEME_DIR',	URL.SOFT_NAME.'/themes/'.$theme.'/');
	define('LOCAL_THEME_DIR',	SOFT_NAME.'/themes/'.$theme.'/');

	// PLUGIN SETTINGS
	$plugins_enabled 	= true;
	$plugins_dir		= "square/plugins/";

	// LOGIN
	define('USERNAME',	$setting['username']);
	define('PASSWORD',	$setting['password']);

	// YOUR SITES NAME
	define('SITE_NAME',	$setting['site_name']);
	define('TAGLINE',	$setting['tagline']);

	// FINE AS IS (UNLESS YOU KNOW OTHERWISE)
	if($setting['cleanurls'] == "true") {define('CLEAN_URLS', true);}else{define('CLEAN_URLS', false);}
	define('COOKIE_SALT',	'BB3154R4TH3RG00DBL0G3NG1N3');
	
	// NOMOMOM - COOKIES
	define('COOKIE_NAME', 	'square_auth');
	define('COOKIE_VALUE', 	md5(USERNAME.PASSWORD.COOKIE_SALT));
	define('COOKIE_DOMAIN',	'.'.DOMAIN);
	define('NOW',			time());
	define('WEEK',			(24 * 60 * 60) * 7 );
?>