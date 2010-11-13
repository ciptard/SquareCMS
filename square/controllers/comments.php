<?php
if (COMMENTING_ENABLED == true) {
	if (COMMENT_SYSTEM == 'Intense Debate') {
		echo '<script>var idcomments_acct = \''.COMMENT_UNIQUE_ID.'\'; var idcomments_post_id; var idcomments_post_url;</script><span id="IDCommentsPostTitle" style="display:none"></span><script type=\'text/javascript\' src=\'http://www.intensedebate.com/js/genericCommentWrapperV2.js\'></script>';
	}
	if (COMMENT_SYSTEM == 'Disqus') {
		echo '<div id="disqus_thread"></div><script type="text/javascript">(function() {var dsq = document.createElement(\'script\'); dsq.type = \'text/javascript\'; dsq.async = true;dsq.src = \'http://'.COMMENT_UNIQUE_ID.'.disqus.com/embed.js\';(document.getElementsByTagName(\'head\')[0] || document.getElementsByTagName(\'body\')[0]).appendChild(dsq);})();</script><noscript>Please enable JavaScript to view the <a href="http://disqus.com/?ref_noscript='.COMMENT_UNIQUE_ID.'">comments powered by Disqus.</a></noscript><a href="http://disqus.com" class="dsq-brlink">blog comments powered by <span class="logo-disqus">Disqus</span></a>';
		echo '<script type="text/javascript">//<![CDATA[(function() {var links = document.getElementsByTagName(\'a\');var query = \'?\';for(var i = 0; i < links.length; i++) {if(links[i].href.indexOf(\'#disqus_thread\') >= 0) {query += \'url\' + i + \'=\' + encodeURIComponent(links[i].href) + \'&\';}}document.write(\'<script charset="utf-8" type="text/javascript" src="http://disqus.com/forums/'.COMMENT_UNIQUE_ID.'/get_num_replies.js\' + query + \'"></\' + \'script>\');})();//]]></script>';
	}
}
?>