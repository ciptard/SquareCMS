<?php $pageid = "settings port"; include('admin/stubs/header.php'); ?>
<?php
if ($_GET["method"] == "extoall") {
	$blogPost = array();
	$DateNow = make_date(gmdate("Y-m-d H:i:s"), "Y-m-d H:i:s");
	$query_result = "SELECT * FROM $posts ORDER BY id DESC"; 
	$result = mysql_query($query_result) or die(mysql_error());
	while($row=mysql_fetch_array($result, MYSQL_ASSOC)) 
	{
		$blogPost[]=$row;
	}
	mysql_free_result($result);

	include('php/closedb.php');

	$url = str_replace("feed/", "", URL);

	$content ="<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
	$content.="<rss version=\"2.0\">\n";
	$content.="<channel>\n";
	$content.="	<title>".SITE_NAME." - RSS</title>\n";
	$content.="	<link>".$url."</link>\n";
	$content.="	<description>".TAGLINE."</description>\n";
	foreach ($blogPost as $item) { 
		$content.="<item>\n";
		$content.="	<title>".htmlentities($item['title'])."</title>\n";
		$content.="	<pubDate>".make_date($item['date-time'], 'D, d M Y H:i:s')."</pubDate>\n";
		$content.="	<link>".$url.'articles/'.$item['url']."</link>\n";
		$content.="	<description>".$item['content']."</description>\n";
		$content.="</item>\n";
	}
	$content.="</channel>\n";
	$content.="</rss>\n";

	// send the header here 
	header('Content-type: application/force-download'); 
	$filename = "square-backup-".time()."-".SITE_NAME.".xml";
	header('Content-Disposition: attachment; filename='.$filename); 

	// put the content in the file 
	echo($content);

	// stop processing the page 
	exit;
}

if ($_GET["method"] == "backup") {
	$backup_filename = backup_tables($dbhost,$dbuser,$dbpass,$dbname, array($dbprefix.'posts', $dbprefix.'posts_trash', $dbprefix.'pages', $dbprefix.'settings'));
}

?>
	<div class="wrapper">
		<h1 class="page">Import and Export</h1>
		<p>In this analogy, the Square Port is your gateway to import or export your posts, pages and settings. You can import from any blog which can produce an XML file for a backup/export but has only been tested with Wordpress and Textpattern. "Export to all" produces an XML file with your posts, containing publish dates, tags and content. "Export to Square" outputs a sql file for settings, post and page backup which can be used to restore your blog should it become corrupted.</p>
		<h2 class="page">Export to All</h2>
		<p>Not coming back to SquareCMS? Sorry to see you go, but you can use this option to generate an <acronym title="Extensible Markup Language">XML</acronym> file which can be used to import your blog into a system such as Wordpress or Textpattern.</p>
		<p>Note that tags and comments will be lost.</p>
		<form method="post" action="./?cmd=port&method=extoall">
			<p><button name="submit" type="submit" value="export">Export</button></p>
		</form>
		<h2 class="page">Export to SquareCMS</h2>
		<p>Coming back to Square? Use this option to generate all the sql you need to get you back on your feet when you choose to restore.</p>
		<p>Restoring? Use your MySQL administrator to upload and execute the sql this form gave you on your old setup.</p>
		<?php if(!isset($backup_filename)) { ?>
		<form method="post" action="./?cmd=port&method=backup">
			<p><button name="submit" type="submit" value="backup">Backup</button></p>
		</form>
		<?php } else { ?>
		<p><a href="<?php echo $backup_filename; ?>">Your latest backup</a></p>
		<?php } ?>
		<h2 class="page">Here be the dragons</h2>
		<p class="alert"><strong>BEWARE:</strong> The following feature is extremely experimental. Please do not raise your voice if it does not work.</p>
		<p>Currently in pre-alpha, supports Wordpress only.</p>
		<h2 class="page">Import from Wordpress</h2>
		<?php 
		if (($_GET["method"] == "import") && ($_GET["system"] == "wordpress")) {
			mysql_query("ALTER TABLE $posts AUTO_INCREMENT = 1");
			echo '<h3>Testing Connection Variables</h3>';
			$server = $_POST["server"];
			echo "<p><strong>Server</strong>: ".$server."</p>";
			$user = $_POST["user"];
			$pass = $_POST["pass"];
			$db = $_POST["db"];
			echo "<p><strong>Database</strong>: ".$db."</p>";
			$prefix = $_POST["prefix"];
			if (!empty($_POST["tag"])) {$tag = $_POST["tag"];} else { $tag = "wordpress"; }
			$wplink = mysql_connect($server, $user, $pass,true);
			if (!$wplink) {
				echo '<p><b>Error: </b>MySQL Connection Variables given are incorrect.</p></div>';
				include('stubs/footer.php');
				exit();
			}
			mysql_select_db($db, $wplink) or die("<p>Failure in database connection</p>".mysql_error());
			echo '<p>Connected to Wordpress. Now Importing...</p>';
			$tb_posts = $prefix."posts";
			$tb_terms = $prefix."terms";
			$tb_relationships = $prefix."term_relationships";
			$tb_taxonomy = $prefix."term_taxonomy";
			$user_posts = mysql_query("
			select
				`post_date`,
				`post_content`,
				`post_title`,
				`post_name`,
				`post_status`
			from
				$tb_posts
			where
				`post_type` = 'post'");
			mysql_select_db($dbname,$conn);
			while($post = mysql_fetch_array($user_posts, MYSQL_ASSOC)) {
				if($post["post_status"] <> "trash") {
					$DateTime = $post["post_date"];
					$DateTime = explode(" ", $DateTime);
					$Date = $DateTime[0];
					$Time = $DateTime[1];
					$Content = mysql_real_escape_string($post["post_content"]);
					$Title = mysql_real_escape_string($post["post_title"]);
					$Friendly_Title = friendlyURL(mysql_real_escape_string($post["post_title"]));
					$Status = $post["post_status"];
					if ($Status == "inherit") {
						$Status = "publish";
					}
						$sql = "INSERT INTO $posts SET id=NULL, title='$Title', url='$Friendly_Title', content='$Content', tags='$tag', `date-time`='$Date $Time', status='$Status', blurb=''";
						$post = mysql_query($sql);
					}
				}
				echo '<h3>Blog Successfully Imported</h3>';
				echo '<p>Please check the manage section to ensure all your posts made it.</p>';
			} else {
			?>
		<form action="./?cmd=port&method=import&system=wordpress" method="post" class="settings">
			<label for="server">MySQL Server</label>
			<input type="text" name="server" value="localhost" id="server" />
			<label for="user">MySQL User</label>
			<input type="text" name="user" placeholder="user" id="user" />
			<label for="pass">MySQL Password</label>
			<input type="password" name="pass" placeholder="password" id="pass" />
			<label for="dbname">Database Name</label>
			<input type="text" name="db" id="dbname" value="database" />
			<label for="prefix">Table Prefix</label>
			<input type="text" name="prefix" value="wp_" />
			<label for="tag">Default Tag</label>
			<input type="text" name="tags" id="tag" value="wordpress" />
			<p></p>
			<button name="import" type="submit" value="Submit" class="commit">Next</button>
		</form>
		<p style="clear: both;"><strong>Limitations: </strong>At the minute tags and categories are not carried across, instead, all posts are given the tag you specify above.</p>
			<?php } ?>
		<p class="footer">SquareCMS Version <?php echo VERSION; ?></p>
	</div>
<?php include('admin/stubs/footer.php'); ?>