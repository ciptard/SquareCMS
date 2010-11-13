<?php
$plugin 	= array("name" => "Hello World", "fun_name" => "hello_world", "version" => "1.0", "type" => "sidebar", "author" => "Thomas Chatting", "url" => "http://spoolio.co.cc/", "description" => "Hello World Plugin");

function hello_world() {
	$settings	= array("return" => "<p>Hello World! This is a Square plugin! Check /square/plugins/sqp_example.php to see what's happening behind the scenes.</p>");
	
	return $settings['return'];
}