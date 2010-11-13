<?php $pageid = "edit page"; include('admin/stubs/header.php'); if(!isset($id)){$id = intval($_GET["id"]);} ?>
<?php $result = mysql_query("SELECT * FROM $pages WHERE `id`=$id LIMIT 1"); $row = mysql_fetch_array($result, MYSQL_ASSOC); ?>
	<div class="wrapper">
		<h1 class="page">Edit page</h1>
		<div class="page">
			<div class="left">
				<strong>xHTML Guide</strong>
				<p>The code you input on this page can be plain text but can be formatted using xHTML, in the future Markdown will be (re)enabled but for now please stick to <em>valid</em> xHTML.</p>
				<p><code>&lt;h1&gt;Header Level 1&lt;/h1&gt;</code></p>
				<p><code>&lt;strong&gt;<strong>Emphasis</strong>&lt;/strong&gt;</code></p>
				<p><code>A &lt;a href="#"&gt;url&lt;/a&gt;</code></p>
				<p><code>&lt;img src="/img/img.png" alt="An Image" /&gt;</code></p>
			</div>
			<fieldset>
				<form action="./" method="post" class="full-post">
					<div class="center">
						<input type="text" name="name" class="title" tabindex=1 value="<?php echo htmlspecialchars($row['name']) ?>" />
						<textarea name="content" tabindex=2><?php echo htmlspecialchars($row['content']) ?></textarea>
					</div>
					<div class="right">
						<input type=hidden name="id" value="<?php echo $row['id'] ?>" />
						<label>URL <code>(keep it friendly)</code>: <input type="text" name="url" tabindex=3 value="<?php echo htmlspecialchars($row['url']) ?>" /></label>
						<label>Page type: <select name="type" id="type"><option value="content" <?php if($row['type'] == "content"){?>selected="selected"<?php } ?>>Content</option><option value="function" <?php if($row['type'] == "function"){?>selected="selected"<?php } ?>>Function</option><option value="stub" <?php if($row['type'] == "stub"){?>selected="selected"<?php } ?>>Stub</option></select></label>
						<label>Comments: <?php if ($row['comments'] == 'true') {echo '<select name="comments" id="status" class="comments"><option value="true" selected="selected">On</option><option value="false">Off</option></select>';} else {echo '<select name="comments" id="status" class="comments"><option value="true">On</option><option value="false" selected="selected">Off</option></select>'; } ?></label>
						<label>Status: <?php if ($row['status'] == 'draft') {echo '<select name="status" id="status" class="publish"><option value="draft">Draft</option><option value="publish">Publish</option></select>';} else {echo '<select name="status" id="status" class="publish"><option value="publish">Publish</option><option value="draft">Draft</option></select>';} ?></label>
						<input type="hidden" name="cmd" value="edit-page" />
						<button name="submit" type="submit" value="com" class="commit">Commit</button>
					</div>
				</form>
			</fieldset>
		</div>
		<p class="footer">SquareCMS Version <?php echo VERSION; ?></p>
	</div>
<?php include('admin/stubs/footer.php'); ?>