<?php $pageid = "settings plugins"; include('admin/stubs/header.php'); ?>
<?php
if ($_GET["enable"]){
	$new_name = substr($_GET["enable"], 2);
	rename('../'.$plugins_dir.$_GET["enable"], '../'.$plugins_dir.$new_name);
}
if ($_GET["disable"]){
	rename('../'.$plugins_dir.$_GET["disable"], '../'.$plugins_dir.'d_'.$_GET['disable']);
}
?>
	<div class="wrapper">
		<h1 class="page">Plugins</h1>
		<p>Plugins add additional functionality to your blog. So far they can only add items to your sidebar/footer but in the future will be able to:</p>
		<ul>
			<li>Add tags to your tag dictionary for use in themes</li>
			<li>Add Javascript files to your site for syntax highlighting etc</li>
			<li>Add post-article sections to your site such as different commenting systems or download counters</li>
		</ul>
		<?php
		$square_plugins = array();
		$disabled_plugins = array();
		if ($plugins_enabled) {
			for($dir = @opendir('../'.$plugins_dir); $dir && $f = readdir($dir);) { // load plugins
				if(preg_match('/^sqp_(.+)\.php$/', $f) > 0) {
					$square_plugins[] = $f;
				}
			}
			for($dir = @opendir('../'.$plugins_dir); $dir && $f = readdir($dir);) { // load plugins
				if(preg_match('/^d_sqp_(.+)\.php$/', $f) > 0) {
					$disabled_plugins[] = $f;
				}
			}
		} else {
			echo "<p>Plugins do not appear to be enabled, please check your settings.</p>";
			include('admin/stubs/footer.php');
			exit();
		}
		if (!empty($square_plugins)) {
		?>
		<h3>Enabled</h3>
		<table id="manage-posts">
			<tr><th width="200">Name</th><th>Author</th><th>Ver</th><th>Type</th><th class="action">&#x25CF;</th></tr>
			<?php foreach ($square_plugins as $file) {
				require_once('../'.$plugins_dir.$file);
			?>
			<tr>
				<td title="<?php echo $plugin["description"]; ?>"><?php echo $plugin["name"]; ?></td>
				<td><a href="<?php echo $plugin["url"]; ?>"><?php echo $plugin["author"]; ?></a></td>
				<td><?php echo $plugin["version"]; ?></td>
				<td><?php echo $plugin["type"]; ?></td>
				<td><a href="./?cmd=plugins&disable=<?php echo $file; ?>">Disable</a></td>
			</tr>
			<?php } ?>
			<tr><th>Name</th><th>Author</th><th>Ver</th><th>Type</th><th class="action">&#x25CF;</th></tr>
		</table>
		<?php }
		if (!empty($disabled_plugins)) { ?>
		<h3>Disabled</h3>
		<table id="manage-posts">
			<tr><th width="200">Name</th><th>Author</th><th>Ver</th><th>Type</th><th class="action">&#x25CF;</th></tr>
			<?php foreach ($disabled_plugins as $file) {
				require_once('../'.$plugins_dir.$file);
			?>
			<tr>
				<td title="<?php echo $plugin["description"]; ?>"><?php echo $plugin["name"]; ?></td>
				<td><a href="<?php echo $plugin["url"]; ?>"><?php echo $plugin["author"]; ?></a></td>
				<td><?php echo $plugin["version"]; ?></td>
				<td><?php echo $plugin["type"]; ?></td>
				<td><a href="./?cmd=plugins&enable=<?php echo $file; ?>">Enable</a></td>
			</tr>
			<?php } ?>
			<tr><th>Name</th><th>Author</th><th>Ver</th><th>Type</th><th class="action">&#x25CF;</th></tr>
		</table>
		<?php } ?>

		<p class="footer">SquareCMS Version <?php echo VERSION; ?></p>
	</div>
<?php include('admin/stubs/footer.php'); ?>