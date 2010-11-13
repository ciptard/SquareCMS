<?php if (!file_exists('config.php')) {echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"><html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en"> <head> <title>Square CMS - Configuration</title> <meta http-equiv="Content-type" content="text/html; charset=UTF-8" /> <style type="text/css"> body { -webkit-text-stroke:1px transparent; width: 100%; margin: 0 auto;}h1 {text-align: center; color: #444;}@media only screen and (max-device-width:480px) {body{-webkit-text-stroke:0 black;}}html {font: 16px/1em Helvetica, Arial, sans-serif; color: #666; text-shadow: #ccc 0px 1px 0px; text-align: center; padding-top: 50px;}.install {background: #ddd; padding: 5px; border: 1px solid #333;}.install:hover {background: #ccc;}a.install {color: #333; text-decoration: none;}img {box-shadow:1px 1px 12px 0 rgba(0,0,0,0.4); -moz-box-shadow:1px 1px 12px 0 rgba(0,0,0,0.4); -webkit-box-shadow:1px 1px 12px 0 rgba(0,0,0,0.4);}</style> </head> <body> <img src="http://spoolio.po.gs/SquareSetup.png" alt="" /> <h1>Unwrapping the <del>Box</del> Square...</h1> <p>Run the <a href="install/">install</a> to begin the installation process.</p> <br /> <a href="install/" title="Begin Install" class="install">Install Square CMS</a> </body> </html>'; exit();}

	ob_start();
	$m_time = explode(" ",microtime());
	$m_time = $m_time[0] + $m_time[1];
	$starttime = $m_time;
	
	require_once('controllers/base.php');
	require_once('config.php');
	require_once('controllers/settings.php');
	require_once('admin/includes/functions.php');
	require_once('admin/includes/opendb.php');
	
	// handle login
	if (!api_authenticated()){
		if (isset($_POST['username'])) {
			if (md5($_POST['username'].md5($_POST['password']).COOKIE_SALT) == COOKIE_VALUE) {
				setcookie(COOKIE_NAME, COOKIE_VALUE, NOW + WEEK, '/', COOKIE_DOMAIN);
				$_COOKIE[COOKIE_NAME] = COOKIE_VALUE;
			} else {
				$fail = true;
				include('admin/pages/login.php');
				exit();
			}
		}
	}
	
	// handle logout
	if (isset($_GET['logout'])) {
		setcookie(COOKIE_NAME, '', NOW - WEEK, '/', COOKIE_DOMAIN);
		unset($_COOKIE[COOKIE_NAME]);
		header('Location:./');
		exit();
	}
	
	// require login
	if (!isset($_COOKIE[COOKIE_NAME]) || $_COOKIE[COOKIE_NAME] != COOKIE_VALUE) {
		include('admin/pages/login.php');
		exit();
	} else {
		setcookie(COOKIE_NAME, COOKIE_VALUE, NOW + WEEK, '/', COOKIE_DOMAIN);
	}
	
	$cmd = $_GET["cmd"];
	
	if($_GET["cmd"]=="manage-posts" || $_POST["cmd"]=="manage-posts") {
		if (!isset($_POST["submit"])) {
			include('admin/pages/manage-posts.php');
			exit();
		}
		if (isset($_POST["submit"])) {
			if(empty($_POST['post'])) {
				include('admin/pages/manage-posts.php');
				exit();
			}
			include('admin/pages/manage-posts-action.php');
			exit();
		}
	}
	
	if($_GET["cmd"]=="edit" || $_POST["cmd"]=="edit-post") {
		if (!isset($_POST["submit"])) {
			$id = $_GET["id"];
			if (!isset($_GET["code"])) { $code = $_GET["code"]; }
			$result = mysql_query("SELECT * FROM $posts WHERE `id`='$id' LIMIT 1");
			$row = mysql_fetch_array($result, MYSQL_ASSOC);
			if (isset($_GET["saved"]))
			{
				$alert = 'Post successfully saved.';
			}
			include('admin/pages/post.e.php');
			exit();
		}
	
		if (isset($_POST["submit"])) {
			$id = $_POST["id"];
			$title = cleaner($_POST["title"]);
			$status = $_POST["status"];
			$content = cleaner($_POST["content"]);
			$tags = cleaner($_POST["tags"]);
			if(!isset($_POST["date"])){$date = gmdate("Y-m-d"); $time = gmdate("H:m:s");}else{
				$datetime = system_date($_POST["date"]);
				$datetime = explode(" ", $datetime);
				$date = $datetime[0];
				$time = $datetime[1];
			}
			$comments = $_POST["comments"];
	
			if(edit_post($id,$title,$content,$tags,$date,$time,$status,'',$comments)) {
				include('admin/includes/opendb.php');
				$result = mysql_query("SELECT * FROM $posts WHERE `id`= '$id' LIMIT 1");
				$row = mysql_fetch_array($result, MYSQL_ASSOC);
	
				$alert = 'Post successfully saved.';
				include('admin/pages/post.e.php');
				exit();
			} else {
				die('There was an error editing the post, check your MySQL connection and settings and try again.');
			}
		}
	}
	
	if($_GET["cmd"]=="apply-draft" || $_POST["cmd"]=="apply-draft") {
		foreach ($_POST['post'] as $post) {
			mysql_query("UPDATE $posts SET status='draft' WHERE id='$post'");
		}
		$updated = true;
		include('admin/pages/manage-posts.php');
		exit();
	}
	
	if($_GET["cmd"]=="apply-publish" || $_POST["cmd"]=="apply-publish") {
		foreach ($_POST['post'] as $post) {
			mysql_query("UPDATE $posts SET status='publish' WHERE id='$post'");
		}
		$updated = true;
		include('admin/pages/manage-posts.php');
		exit();
	}
	
	if($_GET["cmd"]=="apply-delete" || $_POST["cmd"]=="apply-delete") {
		foreach ($_POST['post'] as $post) {
			delete_post($post);
		}
		$deleted = true;
		include('admin/pages/manage-posts.php');
		exit();
	}
	
	if($_GET["cmd"]=="manage-trash" || $_POST["cmd"]=="manage-trash") {
		include('admin/pages/manage-trash.php');
		exit();
	}
	
	if($_GET["cmd"]=="restore-trash" || $_POST["cmd"]=="restore-trash") {
		$id = $_GET["id"];
		mysql_query("INSERT INTO $posts SELECT * FROM $trash WHERE id='$id'");
		mysql_query("DELETE FROM $trash WHERE id='$id'");
		$updated = true;
		include('admin/pages/manage-posts.php');
		exit();
	}
	
	if($_GET["cmd"]=="empty-trash" || $_POST["cmd"]=="empty-trash") {
		$id = $_GET["id"];
		mysql_query("DELETE FROM $trash WHERE id='$id'");
		$deleted = true;
		include('admin/pages/manage-posts.php');
		exit();
	}
	
	if($_GET["cmd"]=="new-page" || $_POST["cmd"]=="new-page") {
		mysql_query("ALTER TABLE '.$pages.' AUTO_INCREMENT = 1");
	
		if (!isset($_POST["submit"])) {
			include('admin/pages/page.php');
			exit();
		}
	
		if (isset($_POST["submit"])) {
			$name = cleaner($_POST["name"]);
			$url = cleaner($_POST["url"]);
			$type = $_POST["type"];
			$content = cleaner($_POST["content"]);
			$status = $_POST["status"];
			$comments = $_POST["comments"];
			mysql_query("INSERT INTO `$pages` SET `name`='$name', `url`='$url', `type`='$type', `content`='$content', `comments`='$comments', `status`='$status'");
			$id = mysql_insert_id();
			$alert = 'Page created';
			include('admin/pages/page.e.php');
			exit();
		}
	}
	
	if($_GET["cmd"]=="edit-page" || $_POST["cmd"]=="edit-page") {
	mysql_query("ALTER TABLE '.$pages.' AUTO_INCREMENT = 1");
	
		if (!isset($_POST["submit"])) {
			include('admin/pages/page.e.php');
			exit();
		} else {
			$id = $_POST["id"];
			$name = cleaner($_POST["name"]);
			$url = cleaner($_POST["url"]);
			$type = $_POST["type"];
			$content = cleaner($_POST["content"]);
			$status = $_POST["status"];
			$comments = $_POST["comments"];
			mysql_query('UPDATE `'.$pages.'` SET `name`="'.$name.'", `url`="'.$url.'", `type`="'.$type.'", `content`="'.$content.'", `comments`="'.$comments.'", `status`="'.$status.'" WHERE `id`='.$id) or die(mysql_error());
			$alert = 'Page successfully saved';
			include('admin/pages/page.e.php');
			exit();
		}
	}
	
	if($_GET["cmd"]=="delete-page") {
		$id = $_GET["id"];
		$sql = "DELETE FROM $pages WHERE id='$id'";
		$result = mysql_query($sql);
		header('Location: ./?cmd=manage-pages');
	}
	
	if($_GET["cmd"]=="manage-pages" || $_POST["cmd"]=="manage-pages") {
		include('admin/pages/manage-pages.php');
		exit();
	}
	
	if($_GET["cmd"]=="config" || $_POST["cmd"]=="config") {
		include('admin/pages/config.php');
		exit();
	}
	
	if($_GET["cmd"]=="themes" || $_POST["cmd"]=="themes") {
		include('admin/pages/themes.php');
		exit();
	}
	
	if($_GET["cmd"]=="settings") {
		include('admin/pages/settings-hub.php');
		exit();
	}
	
	if($_GET["cmd"]=="port" || $_POST["cmd"]=="port") {
		include('admin/pages/port.php');
		exit();
	}
	
	if($_GET["cmd"]=="plugins" || $_POST["cmd"]=="plugins") {
		include('admin/pages/plugins.php');
		exit();
	}
	
	if($_GET["cmd"]=="configure-comments" || $_POST["cmd"] == "configure-comments") {
		include('admin/pages/configure-comments.php');
		exit();
	}
	
	if(!isset($_GET['api'])) {
		if(!isset($cmd) || $_POST["cmd"]=="new-post") {
			if (!isset($_POST["submit"])) {
				include('admin/pages/post.php');
			} else {
				$title = cleaner($_POST["title"]);
				$status = $_POST["status"];
				$content = cleaner($_POST["content"]);
				$tags = cleaner($_POST["tags"]);
				if(!isset($_POST["date-time"])){$date = gmdate("Y-m-d"); $time = gmdate("H:m:s");}else{
					$datetime = system_date($_POST["date"]);
					$datetime = explode(" ", $datetime);
					$date = $datetime[0];
					$time = $datetime[1];
				}
				$comments = $_POST["comments"];
	
				if (new_post($title, $content, $tags, $date, $time, $status, '', $comments)) {
					include('admin/includes/opendb.php');
					$result = mysql_query("SELECT * FROM $posts ORDER by `id` DESC LIMIT 1");
					$row = mysql_fetch_array($result, MYSQL_ASSOC);
					$alert = 'The post was successfully saved.';
					include('admin/pages/post.e.php');
					exit();
				} else {
					die('There was an error creating the new post, check your MySQL connection and settings and try again.');
				}
	
			}
		}
	} else {
		send_api_response_json(api_authenticated());
	}
?>