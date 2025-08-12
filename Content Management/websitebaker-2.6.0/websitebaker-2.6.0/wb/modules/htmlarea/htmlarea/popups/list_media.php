<?php

// $Id: list_media.php 10 2005-09-04 08:59:31Z ryan $

/*

 Website Baker Project <http://www.websitebaker.org/>
 Copyright (C) 2004-2005, Ryan Djurovich

 Website Baker is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 Website Baker is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with Website Baker; if not, write to the Free Software
 Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

*/

// Include the config file
require('../../../../config.php');

// Create new admin object
require(WB_PATH.'/framework/class.admin.php');
$admin = new admin('Pages', 'pages_modify', false);

// Include the WB functions file
require_once(WB_PATH.'/framework/functions.php');

// Get popup type
$popup = $admin->get_get('popup');
if($popup == 'image') {
	$popup = 'insert_image';
} elseif($popup != 'link') {
	$popup = 'link';
}

// Get the directory to browse
$directory = $admin->get_get('folder');
if($directory == '') {
	$directory = '/media';
}
// If the directory contains ../ then set it to /media
if(strstr($directory, '../')) {
	$directory = '/media';
}

// Insert files into the file list
$file_list = array();
foreach(file_list(WB_PATH.$directory, array('index.php')) AS $name) {
	$filename = str_replace(WB_PATH.$directory.'/', '', $name);
	$file_list[] = array('name' => basename($name), 'url' => WB_URL.$directory.'/'.$filename);
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<title>Browse Media</title>
<style type="text/css">
body,td,th {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 12px;
	color: #000000;
	padding: 10px;
}
body {
	background-color: #FFFFFF;
	margin: 0px;
}
a:link, a:visited, a:active {
	color: #0000FF;
	text-decoration: none;
}
a:hover {
	text-decoration: underline;
	color: #0000FF;
}
ul, li {
	margin: 0;
	padding: 0;
	display: block;
	list-style-type: none;
}
li {
	padding: 5px 0px 5px 0px;
}
</style>
</head>
<body>
<?php

// If list is an empty array, then say that no files are in the current dir
if($file_list == array()) {
	echo 'The selected folder is empty';
} else {
	echo '<ul>';
	foreach($file_list AS $file) {
		?>
			<li><a href="#" onclick="javascript: window.parent.document.<?php echo $popup; ?>.url.value = '<?php echo $file['url']; ?>';"><?php echo $file['name']; ?></a></li>
		<?php
	}
	echo '</ul>';
}

?>
</body>
</html>