<?php
$plugin 	= array("name" => "JQuery", "fun_name" => "jquery", "version" => "1.0", "type" => "meta", "author" => "Thomas Chatting", "url" => "http://spoolio.co.cc/", "description" => "Includes the latest version of JQuery into your blog");

function jquery() {
	$settings	= array("version" => "1.4.2");

	return '<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/'.$settings['version'].'/jquery.min.js"></script>';
}