<?php $pageidarr = explode(' ', $pageid); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
	<title>Square Admin</title>
	<meta http-equiv="Content-type" content="text/html; charset=UTF-8" />
	<link rel="stylesheet" href="admin/admin.css" type="text/css" media="screen" />
	
	<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0;">
	<script type="application/x-javascript">
		addEventListener('load', function() { setTimeout(hideAddressBar, 0); }, false);
		function hideAddressBar() { window.scrollTo(0, 1); }
	</script>
	<style type="text/css" media="screen">
		ul.navigation li.<?php echo $pageidarr[0]; ?>		{background: #E5841B;}
		ul.sub_navigation li.<?php echo $pageidarr[1]; ?>		{background: #D07D12;}
	</style>
</head>
<body id="<?php echo $pageid; ?>">
	<h1 class="header"><?php echo SITE_NAME; ?> <span>Admin</span></h1>
	<ul class="meta">
		<li>Welcome, <strong><?php echo USERNAME; ?></strong> | </li>
		<li>Preview your <a href="../">site</a></li>
		<li><a href="./?logout">Logout</a></li>
	</ul>
	<ul class="navigation">
		<li class="write"><a href="./">Write</a></li>
		<li class="edit"><a href="./?cmd=manage-posts">Edit</a></li>
		<li class="settings"><a href="./?cmd=settings">Settings</a></li>
	</ul>
	<?php if ($pageidarr[0] == 'write') { ?>
	<ul class="sub_navigation">
		<li class="post"><a href="./">Post</a></li>
		<li class="page"><a href="./?cmd=new-page">Page</a></li>
	</ul>
	<?php } ?>
	<?php if ($pageidarr[0] == 'edit') { ?>
	<ul class="sub_navigation">
		<li class="post"><a href="./?cmd=manage-posts">Posts</a></li>
		<li class="page"><a href="./?cmd=manage-pages">Pages</a></li>
	</ul>
	<?php } ?>
	<?php if ($pageidarr[0] == 'settings') { ?>
	<ul class="sub_navigation">
		<li class="hub"><a href="./?cmd=settings">Hub</a></li>
		<li class="config"><a href="./?cmd=config">Config</a></li>
		<li class="port"><a href="./?cmd=port">Import/Export</a></li>
		<li class="plugins"><a href="./?cmd=plugins">Plugins</a></li>
		<li class="themes"><a href="./?cmd=themes">Themes</a></li>
	</ul>
	<?php } ?>
	<?php if (isset($alert)) { ?>
	<div class="alert">
		<?php echo $alert; ?>
	</div>
	<?php } ?>