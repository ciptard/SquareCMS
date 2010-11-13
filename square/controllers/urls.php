<?php
	if (CLEAN_URLS == true) {
		// We know that clean URLs are enabled, so now short posts can be used, as well as clean post links
		function get_unbased_url($postid) { /* Now deprecated, defaults to base 58 */ return URL.'s/'.base58_encode($postid);}
		function get_short_url($postid) {
			return URL.'s/'.base58_encode($postid);
		}
		function get_friendly_url($friendly_title) {
			return URL.'articles/'.$friendly_title.'.html';
		}
		function tags_url($tag) {
			return '<a class="tags" href="'.URL.'tags/'.$tag.'">#'.strtoupper($tag).'</a> ';
		}
		function archives_url($page = 1) {
			return URL.'page/'.$page;
		}
		function about_url() {
			return URL.'p/about';
		}
		function page_url($page) {
			return URL.'p/'.$page;
		}
	} else {
		// We know that there is no .htaccess support or the user has disabled clean URLs... their loss...
		function get_unbased_url($postid) { /* Now deprecated, defaults to base 58 */ return URL.'?/s/'.base58_encode($postid);}
		function get_short_url($postid) {
			return URL.'?/s/'.base58_encode($postid);
		}
		function get_friendly_url($friendly_title) {
			return URL.'?/articles/'.$friendly_title.'.html';
		}
		function tags_url($tag) {
			return '<a class="tags" href="'.URL.'?/tags/'.$tag.'">#'.strtoupper($tag).'</a> ';
		}
		function archives_url($page) {
			return URL.'?/page/'.$page;
		}
		function about_url() {
			return URL.'?/p/about';
		}
		function page_url($page) {
			return URL.'?/p/'.$page;
		}
	}
?>