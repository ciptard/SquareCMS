<?php
	function print_tags($tag = '') {
		global $posts, $order;
		if(empty($tag)) {
			header("Location: " . URL);
		} else {
			$tag = urldecode($tag);
			$result = return_array("SELECT * FROM $posts WHERE status = 'publish' AND tags LIKE '%$tag%' $order", false);
			$numInArray = 0;
			while($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
				$blogPost[]=$row;
				$numInArray ++;
			}
			$page_name = '#'.strtoupper($tag);
			mysql_free_result($result);
			$file = build_page("tags");
			eval("echo '".$file."';");
		}
	}
	
	print_tags($input[1]);
?>