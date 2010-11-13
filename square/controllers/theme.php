<?php
	/*
		If we were to run a process monitor on a system pushing out a page, I think it would be likely that this file would have the 
		most time spent on it.
		As part of making themes as easy as possible to make with HTML and CSS, I took the decision to not use PHP calls in the theme files,
		this means that I can later change functions or calls without breaking old theme files, plus it only adds a fraction of a second to
		the load time
	*/
	
	function parse_page($file_contents) {
		$DateNow = date("Y-m-d");
	
		$file = parse_theme_template($file_contents);
		
		$find = array(
			"<square:return_posts>".value_in('square:return_posts', $file)."</square:return_posts>",
			'<square:foreach_post>',
			'</square:foreach_post>',
			'<square:if_zero_results>',
			'</square:if_zero_results>',
			'<square:foreach_result>',
			'</square:foreach_result>'
		);
		$replace = array(
			"'; \$result = return_array(\"SELECT * FROM \$posts WHERE `date` <= '$DateNow' AND `status` = 'publish' \$order LIMIT ".value_in('square:return_posts', $file)."\", false); echo'",
			"'; while(\$item = mysql_fetch_array(\$result, MYSQL_ASSOC)) { echo '",
			"'; } echo '",
			"'; if (\$numInArray == 0) { echo '",
			"'; } echo '",
			"'; if (\$numInArray != 0) { foreach (\$blogPost as \$item) { echo '",
			"'; }  } echo '"
		);
		$file = preg_replace("/<square:get_post>([0-9]+)<\/square:get_post>/", "'; \$result = return_array(\"SELECT * FROM \$posts WHERE `date` <= '$DateNow' AND `status` = 'publish' \$order LIMIT $1,1 \", false); echo'", $file);
		$file = str_replace($find, $replace, $file);
	
		if ($_COOKIE[COOKIE_NAME] == COOKIE_VALUE) {$file = str_replace("<square:article_edit_link />", "<a href=\"".URL.SOFT_NAME."/?cmd=edit&id='.\$item['id'].'\">Edit</a>", $file);} else {$file = str_replace("<square:article_edit_link />", "", $file);}

		$file = replace_code_page($file);

		return $file;
	}
	
	function parse_theme_template($file) {
		global $plug_list, $head_list;

		$find = array(
			'url',
			'theme_dir',
			'site_name',
			'import_theme_css',
			'site_tagline',
			'page_name',
			'site_meta',
			'user_name',
			'blog_nav',
			'about_url',
			'archives_url',
			'footer',
			'version',
			'site_footer'
		);

		$i = 0;
		foreach ($find as $found) {
			$find[$i] = '<square:'.$found.' />';
			$i++;
		}

		$replace = array(
			"'.URL.'",
			"'.THEME_DIR.'",
			"'.SITE_NAME.'",
			"<link rel=\"stylesheet\" href=\"".URL."square/themes/'.\$theme.'/'.'>style.css\" type=\"text/css\" />",
			"'.TAGLINE.'",
			"'.\$page_name.'",
			"<link rel=\"alternate\" type=\"application/rss+xml\" title=\"".SITE_NAME."\" href=\"".URL."feed/\" /><link rel=\"alternate\" type=\"application/atom+xml\" title=\"".SITE_NAME."\" href=\"".URL."feed/?atom\" />'.".$head_list."'",
			"'.ucfirst(USERNAME).'",
			"'.blog_nav().'",
			"'.about_url().'",
			"'.archives_url(1).'",
			"'.user_footer().".$plug_list."'",
			"'.VERSION.'",
			'<a href="http://spoolio.co.cc/p/square/">:-)</a> '.HARD_NAME.' '.VERSION
		);

		return str_replace($find, $replace, $file);
	}
	
	function replace_code_page($file) {
		$find = array(
			'article_title',
			'article_tags',
			'article_date',
			'article_date_short',
			'article_wordcount',
			'article_permalink',
			'article_content',
			'article_blurb',
			'article_pagination',
			'article_next',
			'article_prev',
			'article_comments',
			'article_share',
			'num_results',
			'tag_search',
			'search_term',
			'page_title',
			'page_content',
			'prev_page',
			'next_page'
		);

		$i = 0;
		foreach ($find as $found) {
			$find[$i] = '<square:'.$found.' />';
			$i++;
		}

		$replace = array(
			"'.\$item['title'].'",
			"'; \$splitTags = explode(\", \", \$item['tags']); foreach (\$splitTags as \$tag){ echo tags_url(\$tag); } echo '",
			"'.make_date(\$item['date-time'],\$date_format).'",
			"'.make_date(\$item['date-time'],\"d/m/Y\").'",
			"'.wordCount(\$item['content']).'",
			"'.get_short_url(\$item['id']).'",
			"'.\$item['content'].'",
			"'.\$item['blurb'].'",
			"'; \$id = \$item['id']; echo '",
			"'; if (\$result = return_array(\"SELECT `title`, `url` FROM \$posts WHERE `id` > '\".\$id.\"' AND `status`='publish' ORDER BY `id` ASC LIMIT 1\", false)) {if (\$item = mysql_fetch_array(\$result, MYSQL_ASSOC)) {if(\$item['id'] <> \$id) {if (empty(\$item['title'])) {\$item['title'] = 'Untitled Post';} echo '<a class=\"left\" href=\"'.get_friendly_url(\$item['url']).'\" title=\"'.htmlspecialchars(\$item['title']).'\">&lt; '.myTruncate(\$item['title'],40).'</a>';}} else {echo '<a class=\"left\" href=\"#\">This is the most recent post</a>';}} echo '",
			"'; if (\$result = return_array(\"SELECT `title`, `url` FROM \$posts WHERE `id` < '\".\$id.\"' AND `status`='publish' ORDER BY `id` DESC LIMIT 1\", false)) {if (\$item = mysql_fetch_array(\$result, MYSQL_ASSOC)) {if(\$item['id'] <> \$id) {if (empty(\$item['title'])) {\$item['title'] = 'Untitled Post';} echo '<a class=\"right\" href=\"'.get_friendly_url(\$item['url']).'\" title=\"'.htmlspecialchars(\$item['title']).'\">'.myTruncate(\$item['title'],40).' &gt;</a>';}}} echo '",
			"'; if (\$commenting == true) {include(SOFT_NAME.'/controllers/comments.php');} echo '",
			"<hr /><div id=\"share\"><p>'.wordCount(\$item['content']).' hand-crafted words went into this article, why not share them with a friend?</p><a href=\"http://twitter.com/share\" class=\"twitter-share-button\" data-count=\"horizontal\">Tweet</a><script type=\"text/javascript\" src=\"http://platform.twitter.com/widgets.js\"></script><div class=\"prevnext\">';\$id = \$item['id'];if (\$result = return_array(\"SELECT `title`, `url` FROM \$posts WHERE `id` < '\".\$id.\"' AND `status`='publish' ORDER BY `id` DESC LIMIT 1\", false)) {if (\$item = mysql_fetch_array(\$result, MYSQL_ASSOC)) {if(\$item['id'] <> \$id) {if (empty(\$item['title'])) {\$item['title'] = 'Untitled Post';} echo '<p class=\"lastPost\"><a href=\"'.get_friendly_url(\$item['url']).'\" title=\"'.htmlspecialchars(\$item['title']).'\">'.myTruncate(\$item['title'],40).'</a>&gt;</p>';}}}if (\$result = return_array(\"SELECT `title`, `url` FROM \$posts WHERE `id` > '\".\$id.\"' AND `status`='publish' ORDER BY `id` ASC LIMIT 1\", false)) {if (\$item = mysql_fetch_array(\$result, MYSQL_ASSOC)) {if(\$item['id'] <> \$id) {if (empty(\$item['title'])) {\$item['title'] = 'Untitled Post';}echo '<p class=\"nextPost\">&lt;<a href=\"'.get_friendly_url(\$item['url']).'\" title=\"'.htmlspecialchars(\$item['title']).'\">'.myTruncate(\$item['title'],40).'</a></p>';}}}echo '</div></div><hr />",
			"'.\$numInArray.'",
			"#'.strtoupper(\$tag).'",
			"'.\$search.'",
			"'.\$row['name'].'",
			"'.\$row['content'].'",
			"'; \$start = (\$page * \$num); if ((\$page-1) > 0) { echo '<a href=\"'.archives_url(\$page-1).'\" title=\"Newer\">Newer</a>'; } echo '",
			"'; \$start = (\$page * \$num); if (\$result = return_array(\"SELECT * FROM \$posts WHERE status = 'publish' \$order LIMIT \$start, \$num\", false)) {if (\$row = mysql_fetch_array(\$result, MYSQL_ASSOC)) { echo '<a href=\"'.archives_url(\$page+1).'\" title=\"Older\">Older</a>'; }} echo '"
		);
	
		return str_replace($find, $replace, $file);
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
	
	function user_footer() {
		global $pages;
		if ($query = return_array("SELECT * FROM $pages WHERE `url`='footer' and `type`='stub' LIMIT 1", false)) {
			if ($result = mysql_fetch_array($query, MYSQL_ASSOC)) {
				return $result['content'];
			}
		}
	}
	
	function blog_nav() {
		global $pages;
		$nav = 	'<ul class="nav">';
		$nav = $nav.PHP_EOL.'<li title="Home"><a href="'.URL.'">Home</a></li>';
		$nav = $nav.PHP_EOL.'<li title="Archives"><a href="'.archives_url(1).'">Archives</a></li>';
		if ($_COOKIE[COOKIE_NAME] == COOKIE_VALUE) {
			$nav = $nav.PHP_EOL.'<li title="Admin"><a href="'.URL.SOFT_NAME.'">Admin</a></li>';
		}
		$query = return_array("SELECT * FROM $pages WHERE `type` <> 'stub' ORDER BY `name` ASC", false);
		while($row = mysql_fetch_array($query, MYSQL_ASSOC)) {
			$nav = $nav.PHP_EOL.'<li title="'.$row['name'].'"><a href="'.page_url($row['url']).'">'.$row['name'].'</a></li>';
		}
		$nav = $nav.PHP_EOL.'</ul>';
		return $nav;
	}
	
	// truncate.php
	function myTruncate($string, $limit, $break=" ", $pad="...") {
		// return with no change if string is shorter than $limit
		if(strlen($string) <= $limit) return $string;
	
		$string = substr($string, 0, $limit);
		if(false !== ($breakpoint = strrpos($string, $break))) {
		$string = substr($string, 0, $breakpoint);
	}
	
	return $string . $pad;
	}
	
	// restoreTags
	function restoreTags($html) {
		#put all opened tags into an array
		preg_match_all ( "#<([a-z]+)( .*)?(?!/)>#iU", $html, $result );
		$openedtags = $result[1];
		$openedtags = array_diff($openedtags, array("img", "hr", "br"));
		$openedtags = array_values($openedtags);
	
		#put all closed tags into an array
		preg_match_all ( "#</([a-z]+)>#iU", $html, $result );
		$closedtags = $result[1];
		$len_opened = count ( $openedtags );
		# all tags are closed
		if( count ( $closedtags ) == $len_opened ) {
			return $html;
		}
		$openedtags = array_reverse ( $openedtags );
		# close tags
		for( $i = 0; $i < $len_opened; $i++ ) {
			if (!in_array($openedtags[$i], $closedtags)) {
				$html .= "</" . $openedtags[$i] . ">";
			} else {
				unset ( $closedtags[array_search ( $openedtags[$i], $closedtags)] );
			}
		}
		return $html;
	}
	function wordCount($html) {
		$wc = strip_tags($html);
		$pattern = "#[^(\w|\d|\'|\"|\.|\!|\?|;|,|\\|\/|\-|:|\&|@)]+#";
		$wc = trim(preg_replace($pattern, " ", $wc));
		$wc = trim(preg_replace("#\s*[(\'|\"|\.|\!|\?|;|,|\\|\/|\-|:|\&|@)]\s*#", " ", $wc));
		$wc = preg_replace("/\s\s+/", " ", $wc);
		$wc = explode(" ", $wc);
		$wc = array_filter($wc);
		return count($wc);
	}
	
	function make_date($date,$format) {
		global $date_format;
		global $uct_offset;
		if (!isset($format)) { $format = $date_format; }
		$date = explode(' ', $date);
		$dateArray = explode('-', $date[0]);
		$timeArray=explode(':', $date[1]);
		$date = mktime($timeArray[0], $timeArray[1], $timeArray[2], $dateArray[1], $dateArray[2], $dateArray[0]);
		return date($format, $date + $uct_offset);
	}
	
	function parse_tag($tag) {
		$tag = str_replace("<square:","",$tag);
		$tag = str_replace(" />","",$tag);
	}
?>