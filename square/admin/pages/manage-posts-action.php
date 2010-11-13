<?php $pageid = 'edit'; include('admin/stubs/header.php'); ?>
<?php if($_POST["manageposts"]){ ?>
<div class="wrapper">
	<form method="post" action="./?cmd=apply-<?php echo $_POST["action"]; ?>" class="confirm">
	<h1>Post Action</h1>
	<p>You are about to 
	<?php
		if ($_POST["action"] == "delete") {
			echo "delete";
		}
		if ($_POST["action"] == "draft") {
			echo "set as 'drafted'";
		}
		if ($_POST["action"] == "publish") {
			echo "set as 'published'";
		}
	?>
	 the following posts:</p>
	<ul>
	<?php
	$post=$_POST['post'];
	foreach ($post as $postid) {
		echo '<li>'.$postid.': ';
		$query = mysql_query("SELECT `title` from $posts where id = $postid");
		$result = mysql_fetch_array($query, MYSQL_ASSOC);
		echo $result['title'];
		if ($result['title'] == '') { echo "Untitled Post"; }
		 ?>
		<input type="hidden" name="post[]" value="<?php echo $postid; ?>" /></li>
	<?php } ?>
	</ul>
	<p>Are you sure you wish to continue?</p>
	<a href="./?cmd=manage-posts">Cancel</a><button name="apply" type="submit" value="submit">Confirm</button>
	</form>
	<p class="footer">SquareCMS Version <?php echo VERSION; ?></p>
</div>
<?php } ?>