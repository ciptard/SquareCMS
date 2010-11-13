<?php
if (isset($_GET["page"])) {
	$page = $_GET["page"];
} else {
	$page = 1;
}

if (isset($_GET["num"])) {
	$num = $_GET["num"];
} else {
	$num = 25;
}
$start = (($page - 1) * $num); // Default to 0

// Pull the results from post in blog order, limited to 6 (or "n") from the start value
$result = mysql_query("SELECT * FROM $trash ORDER BY id DESC LIMIT $start, $num");
?>
<?php $pageid = "edit post"; include('admin/stubs/header.php'); ?>
	<div class="wrapper">
		<h1 class="page">Take out the garbage</h1>
		<p>This is the last chance saloon for unwanted posts. Here you can restore or permenantely delete posts currently in the trash.</p>
		<?php if($deleted==true){ ?>
		<div class="alert">
			<p>Posts successfully deleted.</p>
		</div>
		<?php } ?>
		<table id="manage-posts">
			<tr><th class="id">ID</th><th class="date">Date</th><th class="title">Title</th><th class="status">Status</th><th class="action">&#x25CF;</th></tr>
			<?php while($r=mysql_fetch_array($result)) {
			$id = $r['id'];
			?>
			<tr>
				<td class="id"><?php echo $id; ?></td>
				<td class="date"><?php $postdate = explode(" ", $r["date-time"]); echo $postdate[0]; ?></td>
				<td class="title"><a href="./?cmd=edit&id=<?php echo $id; ?>"><?php echo myTruncate($r["title"],30); ?></a></td>
				<td class="status"><?php echo $r["status"]; ?></td>
				<td class="trash-tion"><a href="./?cmd=restore-trash&id=<?php echo $id; ?>" class="restore">Restore</a> | <a href="./?cmd=empty-trash&id=<?php echo $id; ?>" class="delete">Delete</a></td>
			</tr>
			<?php } ?>
			<tr><th class="id">ID</th><th class="date">Date</th><th class="title">Title</th><th class="status">Status</th><th class="action">&#x25CF;</th></tr>
		</table>
		<?php if (($page-1) > 0) { ?>&lt;<a href="./?cmd=manage-posts&page=<?php echo $page-1?>&num=<?php echo $num ?>" title="Previous">Previous</a><?php } ?>
		<?php
		$newstart = ($page * $num); // Default to 0
		if ($result = mysql_query("SELECT * FROM $posts LIMIT $newstart, $num")) {
			if ($row = mysql_fetch_array($result, MYSQL_ASSOC)) { ?>
				<a href="./?cmd=manage-posts&page=<?php echo $page+1?>&num=<?php echo $num ?>" title="Next">Next</a>&gt; <?php
			}
		}
		?>

		<p class="footer">SquareCMS Version <?php echo VERSION; ?></p>
	</div>
<?php include('admin/stubs/footer.php'); ?>
