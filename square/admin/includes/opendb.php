<?php
	/*
		In 0.4 I changed the way in which the front end connects to MySQL, so for compatibility on the
		back-end this file is now an absolute necessity
	*/
	$conn = mysql_connect($dbsettings['host'], $dbsettings['username'], $dbsettings['password']) or die (mysql_error());
	mysql_select_db($dbsettings['database']);
?>