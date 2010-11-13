<?php $pageid = "write page"; include('admin/stubs/header.php'); ?>
	<div class="wrapper">
		<h1 class="page">New page</h1>
		<p>Pages are an easy way to share more static content with your users (about me pages, for instance), but can also be used to control functions (like getting a random post) or even creating stubs (like the footer).</p>
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
						<input type="text" name="name" class="title" tabindex=1 />
						<textarea name="content" tabindex=2></textarea>
					</div>
					<div class="right">
						<label>URL <code>(keep it friendly)</code>: <input type="text" name="url" tabindex=3 /></label>
						<label>Page type: <select name="type" id="type"><option value="content" selected="selected">Content</option><option value="function">Function</option><option value="stub">Stub</option></select></label>
						<label>Comments: <select name="comments" id="status" class="comments"><option value="true" selected="selected">On</option><option value="false">Off</option></select></label>
						<label>Status: <select name="status" id="status" class="publish"><option value="draft">Draft</option><option value="publish">Publish</option></select></label>
						<input type="hidden" name="cmd" value="new-page" />
						<button name="submit" type="submit" value="com" class="commit">Commit</button>
					</div>
				</form>
			</fieldset>
		</div>
		<p class="footer">SquareCMS Version <?php echo VERSION; ?></p>
	</div>
<?php include('admin/stubs/footer.php'); ?>