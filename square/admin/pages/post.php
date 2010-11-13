<?php $pageid = "write post"; include('admin/stubs/header.php'); ?>
	<div class="wrapper">
		<h1 class="page">New Post</h1>
		<p>Welcome to Square<strong>CMS</strong>, your PHP/MySQL Content Management System. This is <em>your</em> admin, <strong><?php echo USERNAME; ?></strong>, and you may have noticed if you've been around since the 0.2 days that it has changed. Any comments or feedback? Feel free to hit me up on <a href="http://twitter.com/phenomenontom">Twitter</a>.</p>
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
						<input type="text" name="title" class="title" tabindex=1 />
						<textarea name="content" tabindex=2></textarea>
					</div>
					<div class="right">
						<label>Tags <code>(comma, separated)</code>: <input type="text" name="tags" tabindex=3 /></label>
						<label>Date <code>(YYYY-MM-DD HH:MM)</code>: <input type="text" name="date" tabindex=4 value="<?php echo user_date(); ?>" /></label>
						<label>Comments: <select name="comments" id="status" tabindex=5><option value="true">On</option><option value="false" selected="selected">Off</option></select></label>
						<label>Status: <select name="status" id="status" class="publish" tabindex=6><option value="publish">Publish</option><option value="draft">Draft</option></select></label>
						<button name="submit" type="submit" value="com" class="commit" tabindex=7>Commit</button>
					</div>
				</form>
			</fieldset>
		</div>
		<p class="footer">SquareCMS Version <?php echo VERSION; ?></p>
	</div>
<?php include('admin/stubs/footer.php'); ?>