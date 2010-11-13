<?php
	function print_article($id) {
		global $posts;
	
		$result = return_array("SELECT * FROM $posts WHERE `id`='$id' LIMIT 1", false);
		$item = mysql_fetch_array($result, MYSQL_ASSOC);
		$id = $item['id'];
	
		if (empty($item)) {
			header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found"); // Return a 404
			header("Content-Type: text/plain");
			print("404 Not Found\n");
			exit();
		}
	
		if ($item['status'] == 'draft') {
			/* Here we can check for the admin cookie if the post is a draft
				if it's not set, we'll redirect to the login screen */
			if (!isset($_COOKIE[COOKIE_NAME]) || $_COOKIE[COOKIE_NAME] != COOKIE_VALUE) {
				header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found"); // Return a 404
				header("Content-Type: text/plain");
				print("404 Not Found\n");
				exit();
			}
		}
	
		$splitTags = explode(", ", $item['tags']);
	
		$dateArray=explode('-',$item['date']);
		foreach ($splitTags as $tag){
		$tags = $tags . ' ' . tags_url($tag);
		}
	
		$commenting = $item['comments'];
	
		$page_name = $item['title'];
		$file = build_page("post");
		eval("echo '".$file."';");
	}

	if ($input[0] == "s") {
		if ($input[1]) {
			$wanted = base58_decode($input[1]);
				if ($result = return_array("SELECT * FROM $posts WHERE id=$wanted LIMIT 1", false))
				{
					if ($row = mysql_fetch_array($result, MYSQL_ASSOC))
					{
						header("Location: ".get_friendly_url($row['url']));
						exit();
					}
				}
		}
		header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found"); // Return a 404
		header("Content-Type: text/plain");
		print("404 Not Found\n");
		exit();
	}
	
	if (($input[0] == "articles") or (isset($friendly_title))) {
		if (!isset($friendly_title)) {$friendly_title = $input[1];}
		if(empty($friendly_title)){ 			// If the user did not input a title
			header('Status: 404 Not Found'); 	// Return a 404
			header('Content-Type: text/plain');
			print("not found\n");
			exit();
		}
	
		$query = return_array("SELECT * FROM $posts WHERE `url` = '".$friendly_title."' LIMIT 1", false);
		$post = mysql_fetch_array($query, MYSQL_ASSOC);
	
		$id = $post['id']; // Return the ID for post.php to parse and use
		print_article($id);
		exit();
	}
?>