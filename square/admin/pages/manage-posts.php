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
$result = mysql_query("SELECT * FROM $posts ORDER BY id DESC LIMIT $start, $num");
?>
<?php $pageid = "edit post"; include('admin/stubs/header.php'); ?>
	<div class="wrapper">
		<h1 class="page">Manage your posts</h1>
		<p>Below is a rundown or your latest posts. Here, you can edit a post, change a post or a group of posts publish status' and move posts to the trash.</p>
		<?php if($deleted==true){ ?>
		<div class="alert">
			<p>Posts successfully deleted.</p>
		</div>
		<?php } ?>
		<?php if($updated==true){ ?>
		<div class="alert">
			<p>Posts updated successfully.</p>
		</div>
		<?php } ?>
		<p>Here's a list of all the posts currently in the database, you can view <a href="./?cmd=manage-posts&num=25">25</a>, <a href="./?cmd=manage-posts&num=50">50</a> or <a href="./?cmd=manage-posts&num=100">100</a> of them at a time.</p>
		<?php
		$query = mysql_query("SELECT * FROM $trash");
		$num_rows = mysql_num_rows($query);
		if ($num_rows <> 0) {
			if ($num_rows == 1){$noun = "post";}else{$noun="posts";}
		?>
		<div class="alert">You have <?php echo $num_rows.' '.$noun ?> in your <a href="./?cmd=manage-trash">trash</a>.</div>
		<?php
		}
		?>
		<form method="post" action="./?cmd=manage-posts">
			<table id="manage-posts">
				<tr><th class="id">ID</th><th class="date">Date</th><th class="title">Title</th><th class="status">Status</th><th class="action">&#x25CF;</th></tr>
				<?php while($r=mysql_fetch_array($result)) {
				$id = $r['id'];
				?>
				<tr>
					<td class="id"><?php echo $id; ?></td>
					<td class="date"><?php $postdate = explode(" ", $r["date-time"]); echo $postdate[0]; ?></td>
					<td class="title"><a href="./?cmd=edit&id=<?php echo $id; ?>"><?php echo myTruncate($r["title"],110); if ($r['title'] == '') { echo "Untitled Post"; } ?></a></td>
					<td class="status"><?php echo $r["status"]; ?></td>
					<td class="action"><input type="checkbox" name="post[]" value="<?php echo $id; ?>" /></td>
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
			<p>With selected:&nbsp;
			<select name="action">
				<option value="delete">Delete</option>
				<option value="draft">Set to Draft</option>
				<option value="publish">Set to Publish</option>
			</select>
			<input type="hidden" name="manageposts" value="manageposts" />
			<button name="submit" type="submit" value="action">Apply</button></p>
		</form>

		<p class="footer">SquareCMS Version <?php echo VERSION; ?></p>
	</div>
<?php include('admin/stubs/footer.php'); ?>
