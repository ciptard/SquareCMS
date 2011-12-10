<?php
	// Admin Functions Include
	
	// User Time Function
	function user_date($date = null){
		if ($date == null){$date = gmdate("Y-m-d H:i", time());}
		$date = explode(' ', $date);
		$dateArray = explode('-', $date[0]);
		$timeArray=explode(':', $date[1]);
		$date = mktime($timeArray[0], $timeArray[1], $timeArray[2], $dateArray[1], $dateArray[2], $dateArray[0]);
	
		global $uct_offset;
		return date("Y-m-d H:i", $date + $uct_offset);
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
	
	function system_date($date){
		global $uct_offset;
		$date = explode(' ', $date);
		$dateArray = explode('-', $date[0]);
		$timeArray=explode(':', $date[1]);
		$date = mktime($timeArray[0], $timeArray[1], $timeArray[2], $dateArray[1], $dateArray[2], $dateArray[0]);
		return date("Y-m-d H:i", $date - $uct_offset);
	}
	
	// Post Management Functions
	function delete_post($id){
		global $posts, $dbsettings, $trash;
		include('admin/includes/opendb.php');
		if(mysql_query('INSERT INTO '.$trash.' SELECT * FROM '.$posts.' WHERE id="'.$id.'"') or die(mysql_error())){
			if(mysql_query('DELETE FROM '.$posts.' WHERE id="'.$id.'"')){
				return true;
				exit();
			}
		}
		return false;
	}
	
	function new_post($title = '', $content, $tags = "untaggable", $date = null, $time = null, $status = "draft", $blurb = '', $comments = "false") {
		if (empty($tags)) {$tags = 'untaggable';}
		if ($date == null) {$date = gmdate("Y-m-d");}
		if ($time == null) {$time = gmdate("H:m:s");}
		if (empty($title)) {$url = uniqid();} else {$url = friendlyURL($title);}
		global $posts, $dbsettings;
		include('admin/includes/opendb.php');
		$date_time = $date . " " . $time;
		if (mysql_query('INSERT INTO '.$posts.' SET `title`="'.$title.'", `url`="'.$url.'", `content`="'.$content.'", `tags`="'.$tags.'", `date-time`="'.$date_time.'", `status`="'.$status.'", `blurb`="'.$blurb.'", `comments`="'.$comments.'"')){
			return true;
		} else {
			return false;
		}
	}
	
	function edit_post($id, $title = '', $content, $tags = 'untaggable', $date = null, $time = null, $status = 'draft', $blurb = '', $comments = 'false') {
		if (empty($tags)) {$tags = 'untaggable';}
		if ($date == null) {$date = gmdate("Y-m-d");}
		if ($time == null) {$time = gmdate("H:m:s");}
		global $posts, $dbsettings;
		include('admin/includes/opendb.php');
		$date_time = $date . " " . $time;
		if (mysql_query('UPDATE '.$posts.' SET `title`="'.$title.'", `content`="'.$content.'", `tags`="'.$tags.'", `date-time`="'.$date_time.'", `status`="'.$status.'", `blurb`="'.$blurb.'", `comments`="'.$comments.'" WHERE `id`="'.$id.'"')){
			return true;
		} else {
			return false;
		}
	}
	
	function cleaner($data) {
		if(is_array($data)) {
			$ret = array();
			foreach($data as $key=>$value) {
				$ret[$key] = cleaner($value);
			}
			return $ret;
		} else {
			if(!is_numeric($data)) {
				if(get_magic_quotes_gpc()) {
					$data = stripslashes($data);
				}
				$data = mysql_real_escape_string($data);
			}
			return $data;
		}
	}
	
	function friendlyURL($string, $num = 1) {
		global $posts;
	
		if (!isset($string)) {$string = genRandomString();}
	
		$url = myTruncate($string, 40);
		$url = preg_replace("`\[.*\]`U","",$url);
		$url = preg_replace('`&(amp;)?#?[a-z0-9]+;`i','-',$url);
		$url = htmlentities($url, ENT_COMPAT, 'utf-8');
		$url = preg_replace( "`&([a-z])(acute|uml|circ|grave|ring|cedil|slash|tilde|caron|lig|quot|rsquo);`i","\\1", $url );
		$url = preg_replace( array("`[^a-z0-9]`i","`[-]+`") , "-", $url);
		$url = strtolower(trim($url, '-'));
	
		if ($num > 1) {$url = $url.'-'.$num;}
	
		if ($result = return_array("SELECT * FROM $posts WHERE `url` = '".$url."'", false)) {
			if ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
				$url = friendlyURL($string, ($num+1));
			}
		}
	
		return $url;
	}
	// End Post Management Functions
	
	// API Functions
	function send_api_response_json($success) {
		global $dbsettings, $posts;
		require('admin/includes/opendb.php');
	
		// Fixes an issue with IE and JSON
		header('Cache-Control: no-cache, must-revalidate');
		header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
	
		header('Content-type: application/json');
		// header('Content-Disposition: attachment; filename="square.json"');
	
		if ($success == true) {$auth = 1;} else {$auth = 0;}
		$json_header = '{';
		$json_auth = '"auth": '.$auth.', "square_version": "'.VERSION.'"';
		// Let's kill it off now if there's no auth
		if ($auth == 0) {$json_footer = '}'; echo $json_header.$json_auth.$json_body.$json_footer; exit();}
	
		if (isset($_GET['num_posts'])) {
			$query = mysql_query("SELECT * FROM $posts");
			while($r=mysql_fetch_array($query))  {
				$num++;
			}
			$json_body.=', "cmd"="num_posts", "num"='.$num;
			$json_footer = '}';
			echo $json_header.$json_auth.$json_body.$json_footer;
			exit();
		}
	
		if (isset($_GET['read'])) {
			if (isset($_POST['limit']) && is_numeric($_POST['limit'])) {$limit = "LIMIT ".$_POST['limit'];}
			if (isset($limit) && isset($_POST['start'])) {$limit = "LIMIT ".$_POST['start'].", ".$_POST['limit'];}
			$query = mysql_query("SELECT * FROM $posts ORDER BY id DESC $limit");
			$json_body.=', "cmd"="read", post:[';
			while($r=mysql_fetch_array($query))  {
				$json_body.= '{"id":'.$r['id'].', "title":"'.escape_json($r['title']).'", "url":"'.$r['url'].'", "comments":"'.$r['comments'].'", "content":"'.escape_json($r['content']).'", "date":"'.$r['date'].'", "status":"'.$r['status'].'", "tags":"'.$r['tags'].'", "blurb":"'.escape_json($r['blurb']).'"}, ';
			}
			$json_body.=']';
			$json_footer = '}';
			echo $json_header.$json_auth.$json_body.$json_footer;
			exit();
		}
	
		if (isset($_GET['new'])) {
			//function new_post($title, $content, $tags, $date, $time, $status = "draft", $blurb, $comments = false) {
			$title = cleaner($_POST["title"]);
			$status = $_POST["status"];
			$content = cleaner($_POST["content"]);
			$tags = cleaner($_POST["tags"]);
			$date = $_POST["date"];
			$time = $_POST["time"];
			if (isset($_POST["blurb"])){
				$blurb = cleaner($_POST["blurb"]);
			} else {
				$blurb = myTruncate(cleaner($_POST["content"]), 150);
			}
			$comments = $_POST["comments"];
	
			if (new_post($title, $content, $tags, $date, $time, $status, $blurb, $comments)){
				include('admin/includes/opendb.php');
				$result = mysql_query("SELECT * FROM $posts ORDER by `id` DESC LIMIT 1");
				$row = mysql_fetch_array($result, MYSQL_ASSOC);
				$json_body.=', "cmd"="new", status:[';
				$json_body.='"stat"="success", "id",'.$row['id'].'';
				$json_body .=']';
				$json_footer = '}';
			} else {
				$json_body.=', "cmd"="new", status:[';
				$json_body.='"stat"="fail"';
				$json_body .=']';
			}
			echo $json_header.$json_auth.$json_body.$json_footer;
			exit();
		}
	
		if(isset($_GET['edit'])) {
			$id = $_POST["id"];
			$title = cleaner($_POST["title"]);
			$status = $_POST["status"];
			$content = cleaner($_POST["content"]);
			$tags = cleaner($_POST["tags"]);
			$date = $_POST["date"];
			$time = $_POST["time"];
			if (isset($_POST["blurb"])){
				$blurb = cleaner($_POST["blurb"]);
			} else {
				$blurb = myTruncate(cleaner($_POST["content"]), 150);
			}
			$comments = $_POST["comments"];
			$comments = $_POST["comments"];
	
			if(edit_post($id,$title,$content,$tags,$date,$time,$status,$blurb,$comments)) {
				$json_body.=', "cmd"="edit", status:[';
				$json_body.='"stat"="success", "id",'.$id.'';
				$json_body .=']';
				$json_footer = '}';
			} else {
				$json_body.=', "cmd"="edit", status:[';
				$json_body.='"stat"="fail"';
				$json_body .=']';
			}
			echo $json_header.$json_auth.$json_body.$json_footer;
			exit();
		}
	
		if(isset($_GET["delete"])) {
			if(delete_post($_POST["id"])) {
				$json_body.=', "cmd"="delete", "id"="'.$_POST["id"].'", status:[';
				$json_body.='"stat"="success"';
				$json_body .=']';
			} else {
				$json_body.=', "cmd"="delete", status:[';
				$json_body.='"stat"="fail"';
				$json_body .=']';
			}
			echo $json_header.$json_auth.$json_body.$json_footer;
			exit();
		}
	
		$json_footer = '}'; echo $json_header.$json_auth.$json_body.$json_footer; exit();
	}
	
	function escape_json($input) {
		$string = str_replace('"', '\"', $input);
		return $string;
	}
	
	function api_authenticated() {
		if (isset($_GET['api'])) {
			$api_key = $_POST['api_key'];
			// The password must already be md5'd before hitting us
			// The input will be md5($username.':'.md5($password));
			$true_key = md5(USERNAME.':'.PASSWORD);
			if ($api_key == $true_key) {
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
	
	// End API Functions
	
	// Backup script
	function backup_tables($host,$user,$pass,$name,$tables = '*') {
		$link = mysql_connect($host,$user,$pass);
		mysql_select_db($name,$link);
	
		//get all of the tables
		if($tables == '*')
		{
			$tables = array();
			$result = mysql_query('SHOW TABLES');
			while($row = mysql_fetch_row($result))
			{
				$tables[] = $row[0];
			}
		}
		else
		{
			$tables = is_array($tables) ? $tables : explode(',',$tables);
		}
	
		//cycle through
		foreach($tables as $table)
		{
			$result = mysql_query('SELECT * FROM '.$table);
			$num_fields = mysql_num_fields($result);
	
			$return.= 'DROP TABLE '.$table.';';
			$row2 = mysql_fetch_row(mysql_query('SHOW CREATE TABLE '.$table));
			$return.= "\n\n".$row2[1].";\n\n";
	
			for ($i = 0; $i < $num_fields; $i++) 
			{
				while($row = mysql_fetch_row($result))
				{
					$return.= 'INSERT INTO '.$table.' VALUES(';
					for($j=0; $j<$num_fields; $j++) 
					{
						$row[$j] = addslashes($row[$j]);
						$row[$j] = ereg_replace("\n","\\n",$row[$j]);
						if (isset($row[$j])) { $return.= "'".$row[$j]."'" ; } else { $return.= '""'; }
						if ($j<($num_fields-1)) { $return.= ','; }
					}
					$return.= ");\n";
				}
			}
			$return.="\n\n\n";
		}
	
		//save file
		$filename = 'db-backup-'.time().'-'.(md5(implode(',',$tables))).'.sql';
		$handle = fopen($filename,'w+');
		fwrite($handle,$return);
		fclose($handle);
	
		return $filename;
	}
	
	// Others
	
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
	
	function current_posts_number($type = "posts") {
		global $posts, $dbsettings;
		include('admin/includes/opendb.php');
		$query = mysql_query("SELECT `status` FROM $posts");
		while($r=mysql_fetch_array($query, MYSQL_ASSOC)){
			if($r["status"] == "publish"){$postnumber++;}
			if($r["status"] == "draft"){$drafts++;}
		}
		if ($type == "drafts") {
			if (!$drafts) { return 0;}
			return $drafts;
		} else {
			if (!$postnumber) { return 0;}
			return $postnumber;
		}
	}
	
	function myTruncate($string, $limit, $break=" ", $pad="...") {
		// return with no change if string is shorter than $limit
		if(strlen($string) <= $limit) return $string;
	
		$string = substr($string, 0, $limit);
		if(false !== ($breakpoint = strrpos($string, $break))) {
		$string = substr($string, 0, $breakpoint);
	}
	
	return $string . $pad;
	}
?>