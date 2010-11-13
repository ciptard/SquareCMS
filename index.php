<?php
	/*
		The Bespoke Blog Engine - Version 1.0 (Square)
		Last edited on the 13th November 2010

		------------------------------------------------------------------
		This program is free software. It comes without any warranty, to
		the extent permitted by applicable law. You can redistribute it
		and/or modify it under the terms of the Do What The Fuck You Want
		To Public License, Version 2, as published by Sam Hocevar. See
		http://sam.zoy.org/wtfpl/COPYING for more details.
	*/

	// Small debugging script that posts the page load time - Uncommenting will invalidate the xHTML markup :(
	$start_time = microtime(true); register_shutdown_function('my_shutdown'); function my_shutdown() {global $start_time; echo "<p style='text-align: right; font-style: italic; color: grey; font-size: 12px; clear: both;'>Page loaded in ".(round((microtime(true) - $start_time), 5))." seconds.</p>";}

	ob_start(); 							/* Lets us change the headers further down */

	define('SOFT_NAME',	'square'); 		/* Folder name in case I change it later */
	define('HARD_NAME',	'The Bespoke Blog Engine - SquareCMS'); 	/* Application Name */

	/* Install check */ if (!file_exists(SOFT_NAME.'/config.php')) {echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"><html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en"> <head> <title>Square CMS - Configuration</title> <meta http-equiv="Content-type" content="text/html; charset=UTF-8" /> <style type="text/css"> body { -webkit-text-stroke:1px transparent; width: 100%; margin: 0 auto;}h1 {text-align: center; color: #444;}@media only screen and (max-device-width:480px) {body{-webkit-text-stroke:0 black;}}html {font: 16px/1em Helvetica, Arial, sans-serif; color: #666; text-shadow: #ccc 0px 1px 0px; text-align: center; padding-top: 50px;}.install {background: #ddd; padding: 5px; border: 1px solid #333;}.install:hover {background: #ccc;}a.install {color: #333; text-decoration: none;}img {box-shadow:1px 1px 12px 0 rgba(0,0,0,0.4); -moz-box-shadow:1px 1px 12px 0 rgba(0,0,0,0.4); -webkit-box-shadow:1px 1px 12px 0 rgba(0,0,0,0.4);}</style> </head> <body> <img src="http://spoolio.po.gs/SquareSetup.png" alt="" /> <h1>Unwrapping the <del>Box</del> Square...</h1> <p>Run the <a href="square/install/">install</a> to begin the installation process.</p> <br /> <a href="'.SOFT_NAME.'/install/" title="Begin Install" class="install">Install Square CMS</a> </body> </html>'; exit();}

	/* Initialise the base functions, settings and finally the actual blog */
	require(SOFT_NAME.'/controllers/base.php');
	require(SOFT_NAME.'/config.php');
	require(SOFT_NAME.'/controllers/settings.php');
	require(SOFT_NAME.'/controllers/urls.php');
	require(SOFT_NAME.'/'.SOFT_NAME.'.php');
?>