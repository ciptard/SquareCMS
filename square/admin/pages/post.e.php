<?php $pageid = "edit post"; include('admin/stubs/header.php'); ?>
	<div class="wrapper">
		<h1 class="page">Edit Post</h1>
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
						<input type="text" name="title" class="title" tabindex=1 value="<?php echo htmlspecialchars($row['title']) ?>" />
						<textarea name="content" tabindex=2><?php echo htmlspecialchars($row['content']) ?></textarea>
					</div>
					<div class="right">
						<input type=hidden name="id" value="<?php echo $row['id'] ?>" />
						<label>Tags <code>(comma, separated)</code>: <input type="text" name="tags" tabindex=3 value="<?php echo htmlspecialchars($row['tags']) ?>" /></label>
						<label>Date <code>(YYYY-MM-DD HH:MM)</code>: <input type="text" name="date" tabindex=4 value="<?php echo user_date($row['date-time']); ?>" /></label>
						<label>Comments: <?php if ($row['comments'] == 'true') {echo '<select name="comments" id="status" class="comments" tabindex=5><option value="true" selected="selected">On</option><option value="false">Off</option></select>';} else {echo '<select name="comments" id="status" class="comments" tabindex=5><option value="true">On</option><option value="false" selected="selected">Off</option></select>'; } ?></label>
						<label>Status: <?php if ($row['status'] == 'draft') {echo '<select name="status" id="status" class="publish" tabindex=6><option value="draft">Draft</option><option value="publish">Publish</option></select>';} else {echo '<select name="status" id="status" class="publish" tabindex=6><option value="publish">Publish</option><option value="draft">Draft</option></select>';} ?></label>
						<input type="hidden" name="cmd" value="edit-post" />
						<button name="submit" type="submit" value="com" class="commit" tabindex=7>Commit</button>
					</div>
				</form>
			</fieldset>
		</div>
		<p class="footer">SquareCMS Version <?php echo VERSION; ?></p>
	</div>
<?php include('admin/stubs/footer.php'); ?>