<?php
	/*
		A brand new feature for the engine is basic plugin functionality, so far it only enables additions to the sidebar
		or <head> section of the page, but more will be enabled over time
	*/
	global $sidebar_plugins;
	$sidebar_plugins = array();
	$header_plugins = array();
	
	if (!empty($plugins)) {
		foreach ($plugins as $plugin) {
			require_once($plugins_dir.$plugin);
			if ($plugin["type"] == "sidebar") {
				$sidebar_plugins[] = $plugin["fun_name"];
			}
			if ($plugin["type"] == "meta") {
				$header_plugins[] = $plugin["fun_name"];
			}
		}
	
		$plug_list = "";
		$head_list = "";
	
		foreach ($sidebar_plugins as $sb) {
			$plug_list.= "'<hr />'.".$sb."().";
		}
	
		foreach ($header_plugins as $hp) {
			$head_list.= $hp."().";
		}
	}
?>