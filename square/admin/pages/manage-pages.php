<?php $pageid = "edit page"; include('admin/stubs/header.php'); $result = mysql_query("SELECT * FROM $pages ORDER BY id DESC"); ?>
	<div class="wrapper">
		<h1 class="page">Manage your pages</h1>
		<p>Pages can be exceptionally useful, they can contain content (be it dynamic or static) or be functions, for example a "random post" function is included in Square, just visit <a href="../p/random" target="_blank">../p/random</a>.</p>
		<table id="manage-posts">
			<tr><th class="id">ID</th><th class="type">Type</th><th class="title">Name</th><th class="status">Status</th><th class="action">&#x25CF;</th></tr>
			<?php while($r=mysql_fetch_array($result)) {
			$id = $r['id'];
			?>
			<tr>
				<td class="id"><?php echo $id; ?></td>
				<td class="type"><?php echo $r["type"]; ?></td>
				<td class="title"><a href="./?cmd=edit-page&id=<?php echo $id; ?>"><?php echo $r["name"]; ?></a></td>
				<td class="status"><?php echo $r["status"]; ?></td>
				<td class="action"><a href="./?cmd=delete-page&id=<?php echo $id; ?>" class="delete">Del</a></td>
			</tr>
			<?php } ?>
			<tr><th class="id">ID</th><th class="type">Type</th><th class="title">Name</th><th class="status">Status</th><th class="action">&#x25CF;</th></tr>
		</table>
		<p class="footer">SquareCMS Version <?php echo VERSION; ?></p>
	</div>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
<script type="text/javascript">
	//<![CDATA[
	$('.delete').click(function() {
		var answer = confirm("Really delete this item? (This cannot be undone!)")
		if (answer){
			return true;
		}else{
			return false;
		};
	});
	//]]>
</script>
<?php include('admin/stubs/footer.php'); ?>