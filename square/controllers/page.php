<?php
	$num = 6; /* The number of posts to be displayed per page - SHOULD BE MOVED TO SETTINGS */
	if (isset($input[1])) {$page = intval($input[1]);} else { $page = 1;}

	function get_archives($page = 1, $num = 6, $page_name = "Archives") {
		global $posts, $order;
		$start = (($page - 1) * $num); // Default to 0
	
		// Pull the results from post in blog order, limited to 6 (or "n") from the start value
		$DateNow = gmdate("Y-m-d H:i:s");
		$result = return_array("SELECT * FROM $posts WHERE `date-time` <= '$DateNow' AND `status` = 'publish' $order LIMIT $start, $num", false);
		$file = build_page("archive");
		eval("echo '".$file."';");
	}
	
	get_archives($page,6,"Home");
?>