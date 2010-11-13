<?php
	$input = get_url_input();

	require(SOFT_NAME.'/controllers/theme.php');

	// Initialise the plugins!
	if ($plugins_enabled) {
		global $plugins;
		$plugins = array();
		for($dir = @opendir($plugins_dir); $dir && $f = readdir($dir);) { // load plugins
			if(preg_match('/^sqp_(.+)\.php$/', $f) > 0) {
				$plugins[] = $f;
			}
		}
	}
	require(SOFT_NAME.'/controllers/plugins.php');

	if ($input[0] == "") {$input[0] = 'page'; $input[1] = 1;}

	if ($input[0] == "s" || $input[0] == "articles") {require(SOFT_NAME.'/controllers/post.php'); exit();}

	if ($_GET["cmd"] == "search") {require(SOFT_NAME.'/controllers/search.php'); exit();}

	if (file_exists(SOFT_NAME.'/controllers/'.$input[0].'.php')) {
		require(SOFT_NAME.'/controllers/'.$input[0].'.php');
	} else {
		return404();
	}
?>