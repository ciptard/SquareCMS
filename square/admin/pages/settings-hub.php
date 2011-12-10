<?php $pageid = "settings hub"; include('admin/stubs/header.php'); ?>
	<div class="wrapper">
		<h1 class="page">Choose an option above to configure your blog</h1>
		<h2>Your blog stats:</h2>
		<?php $drafts = current_posts_number("drafts"); $postnumber = current_posts_number("posts"); ?>
		<div class="stats drafts"><h2><?php echo $drafts; ?></h2><p><?php if($drafts==1){echo "draft";}else{echo "drafts";} ?></p></div>
		<div class="stats posts"><h2><?php echo $postnumber; ?></h2><p><?php if($postnumber==1){echo "post";}else{echo "posts";} ?></p></div>
		<div class="stats date"><h2><?php echo date("j"); ?></h2><p><?php echo date("M"); ?></p></div>
		<div class="stats square"><h2 class="square" title="Your current version is <?php echo VERSION; ?>">&nbsp;</h2><p><a href="http://github.com/tomchatting/SquareCMS">Github</a></p></div>
		<p style="clear: both;">&nbsp;</p>
		<h1 class="page">About SquareCMS</h1>
		<p>All the way back in 2009, I decided to make my own PHP/MySQL driven blog engine after a Wordpress upgrade hosed my entire blog and almost two years worth of blog posts. A month or two later the <em>Bespoke Blog Engine</em> was a pretty full features system and people started asking me to release it. I did and B&sup2;e was released to the world. Only months later SquareCMS was born out of it's little brother and quickly became a solid and lightweight option for technically minded bloggers. I hope you enjoy using SquareCMS as much as I enjoyed making it. - T</p>
		<p class="footer">SquareCMS Version <?php echo VERSION; ?></p>
	</div>
<?php include('admin/stubs/footer.php'); ?>