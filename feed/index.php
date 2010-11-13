<?php
ob_start();
include('../square/controllers/base.php');
include('../square/controllers/settings.php');
$conn = mysql_connect($dbsettings['host'], $dbsettings['username'], $dbsettings['password']) or die (mysql_error());
mysql_select_db($dbsettings['database']);
include('../square/admin/includes/functions.php');

$blogPost = array();
$DateNow = make_date(gmdate("Y-m-d H:i:s"), "Y-m-d H:i:s");
$updated = "1900-01-01";
$query_result = "SELECT * FROM $posts WHERE `date-time` <= '$DateNow' AND status = 'publish' ORDER BY id DESC LIMIT 15"; 
$result = mysql_query($query_result) or die(mysql_error());
while($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
	$blogPost[]=$row;
	if (strtotime(make_date($row['date-time'], "Y-m-d")) > strtotime($updated)) {
		$updated = make_date($row['date-time'], "Y-m-d");
	}
}
mysql_free_result($result);

$url = str_replace("feed/", "", URL);
?>
<?php if (!isset($_GET["atom"])) { ?>
<?php header("Content-type: text/xml"); ?>
<?php echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>"; ?>
<rss version="2.0">
<channel>
	<title><?php echo SITE_NAME; ?> - RSS</title>
	<link><?php echo $url; ?></link>
	<description><?php echo TAGLINE; ?></description>
	<?php foreach ($blogPost as $item) { ?>
	<item>
		<title><?php echo htmlentities($item['title']) ?></title>
		<pubDate><?php echo make_date($item['date-time'], 'D, d M Y H:i:s'); ?> GMT</pubDate>
		<link><?php echo $url.'articles/'.$item['url'] ?></link>
		<description><![CDATA[ <?php echo $item['content'] ?> ]]></description>
	</item>
	<?php } ?>
</channel>
</rss>
<?php } else { ?>
<?php header("Content-type: text/xml"); ?>
<?php echo "<?xml version=\"1.0\" encoding=\"utf-8\"?>".PHP_EOL; ?>
<feed xmlns='http://www.w3.org/2005/Atom' xmlns:openSearch='http://a9.com/-/spec/opensearchrss/1.0/' xmlns:georss='http://www.georss.org/georss' xmlns:thr='http://purl.org/syndication/thread/1.0'>
	<title><?php echo SITE_NAME; ?> - ATOM</title>
	<link href="<?php echo $url; ?>" />
	<id><?php echo $url; ?></id>
	<updated><?php echo $updated; ?>T00:00:00Z</updated>
	<?php foreach ($blogPost as $item) { ?>
	<entry>
		<title><?php echo htmlentities($item['title']) ?></title>
		<updated><?php echo make_date($item['date-time'], 'Y-m-d'); ?>T00:00:00Z</updated>
		<link href="<?php echo $url.'articles/'.$item['url'] ?>" />
		<id><?php echo $url.'articles/'.$item['url'] ?></id>
		<content type="xhtml"><div xmlns="http://www.w3.org/1999/xhtml"><?php echo $item['content'] ?></div></content>
	</entry>
	<?php } ?>
</feed>
<?php } ?>