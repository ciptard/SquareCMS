<?php
	function print_search($s) {
		global $dbsettings, $posts, $order;
	
		$search = preg_replace("[^A-Za-z0-9]", " ", $s);
		$search = trim($search);
		$connect = mysql_connect($dbsettings["host"], $dbsettings["username"], $dbsettings["password"]);
		$search = mysql_real_escape_string($search, $connect);
		$result = return_array(sprintf("SELECT * FROM $posts WHERE `status`='publish' AND `title` LIKE '%s' OR `content` LIKE '%s' $order", "%".$search."%", "%".$search."%"), false);
		$numInArray = mysql_num_rows($result);
		while($row=mysql_fetch_array($result, MYSQL_ASSOC)) 
		{
			$blogPost[]=$row;
		}
		
		$page_name = "Search for ".$search;
		$file = build_page("search");
		eval("echo '".$file."';");
	}
	
	print_search($_GET["s"]);
?>