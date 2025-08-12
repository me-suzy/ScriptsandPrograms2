<?php
/*************************
  Copyright (c) 2004-2005 TinyWebGallery
  written by Michael Dempfle

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License as published by
  the Free Software Foundation; either version 2 of the License, or
  (at your option) any later version.
  ********************************************
  TWG version: 1.3c
  $Date: 2005/11/15 09:02 $
**********************************************/
require ( dirname(__FILE__) . "/config.php");
include ("inc/sha256.inc.php");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Password sha1/256 generator for TWG</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" type="text/css" href="style.css" >
</head>

<body>
<table class='twg' summary=""><tr><td>
<h4>Password generator for TinyWebGallery 1.2</h4>
Enter password and press generate:
<form action="password.php" method="post">
<input name="password" type="text" size="30" maxlength="30">
<input name="" type="submit" value="Generate">
</form>
<?php
if (isset( $_POST['password'])) {

	 if (function_exists("sha1") && $use_sha1_for_password) {
      echo "SHA1 hash value for '" . $_POST['password']  . "': '" . sha1($_POST['password']) . "'";
	 } else {
	 if (!function_exists("sha1") && $use_sha1_for_password) {
	 echo "SHA1 does not exist - using interneal SHA256 instead!<br />";
	 }
	    echo "SHA256 hash value '" . $_POST['password']  . "': '" . sha2($_POST['password']) . "'";
	 
	 }
}
?>
<p>Copy the generated value to your config.php -> $privatepasswort or into one of your password files.<br/>
If you want to use more than one password for a gallery plese seperate the password with a ',' like<br/>
388ad1c312a488ee9e12998fe097f2258fa8d5ee,a17fed27eaa842282862ff7c1b9c8395a26ac320</p>
</td></tr></table>
</body>
</html>
