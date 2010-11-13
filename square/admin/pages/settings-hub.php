<?php $pageid = "settings hub"; include('admin/stubs/header.php'); ?>
	<div class="wrapper">
		<h1 class="page">Configure your blog</h1>
		<p>This is your hub for changing settings on your blog. Here, you can change your theme, configure your settings, setup plugins and more. This page, though, shows you at a glance your blogs statistics and provides some handy hints, as well as providing you with hints as to whether an upgrade is needed.</p>
		<?php $drafts = current_posts_number("drafts"); $postnumber = current_posts_number("posts"); ?>
		<div class="stats drafts"><h2><?php echo $drafts; ?></h2><p><?php if($drafts==1){echo "draft";}else{echo "drafts";} ?></p></div>
		<div class="stats posts"><h2><?php echo $postnumber; ?></h2><p><?php if($postnumber==1){echo "post";}else{echo "posts";} ?></p></div>
		<div class="stats date"><h2><?php echo date("j"); ?></h2><p><?php echo date("M"); ?></p></div>
		<?php echo check_new_version(); ?>
		<p style="clear: both;">&nbsp;</p>
		<h1 class="page">About SquareCMS</h1>
		<p>All the way back in 2009, I decided to make my own PHP/MySQL driven blog engine after a Wordpress upgrade hosed my entire blog and almost two years worth of blog posts. A month or two later the <em>Bespoke Blog Engine</em> was a pretty full features system and people started asking me to release it. I did and B&sup2;e was released to the world. Only months later SquareCMS was born out of it's little brother and quickly became a solid and lightweight option for technically minded bloggers. I hope you enjoy using SquareCMS as much as I enjoyed making it. - T</p>
		<p class="footer">SquareCMS Version <?php echo VERSION; ?></p>
	</div>
<?php include('admin/stubs/footer.php'); ?>