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

require  "../config.php";
include "../inc/mysession.inc.php";
include "../inc/filefunctions.inc.php";
include "i_parserequest.inc.php";

/*
// we need the twg_album !!
if (isset($_GET['twg_album'])) {
    $twg_album = $_GET['twg_album'];
} 
*/

$relativepath = "../";
include "../inc/checkprivate.inc.php";

$passwort = false;

if (isset($_GET['twg_passwort'])) {
		$passwort = urlencode($_GET['twg_passwort']);
    if ($encrypt_passwords) {
    	if (function_exists("sha1") && $use_sha1_for_password) {
		      $passwort = sha1($passwort);
			} else {
			    $passwort = sha2($passwort);
	 		}
    }
    if (in_array($passwort,$passwd)) {
        $_SESSION["privategallogin"] =  $passwort;   
    } 
} 

$logout = false;
if (isset($_GET['twg_logout'])) {
    session_unregister("privategallogin");
    $logout = true;
} 

require "../language/language_" . $default_language . ".php";

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
 "http://www.w3.org/TR/html4/loose.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>TinyWebGallery</title>
<meta name="author" content="mid" />
<link rel="stylesheet" type="text/css" href="iframe.css" />
<script type="text/javaScript">reload = false;</script>
<script type="text/javaScript" src="../js/twg_image.js"></script>
</head>
<body>
 <form action="<?php print $twg_root; ?>" target="_parent" method="get">
 <input name="twg_album" type="hidden" value="<?php echo encodespace($twg_album); ?>" />
 <input type="hidden" name="twg_top10" value="search" />
<table summary='' style="width: 100%; height:100%" cellpadding='0' cellspacing='0'><tr><td class="closebutton"> 
<img name="imageField" onClick="closeiframe()" alt='' align="right" src="../buttons/close.gif" width="12" height="12" border="0" />
</td></tr><tr><td> 
 
<?php
echo $lang_search_text;
?>
  <br/><img alt='' src='../buttons/1x1.gif' height='4' /><br/><input name="twg_search_term" size="32"/>
  &nbsp;
  <input type="submit" name="twg_submit" value="<?php echo $lang_search ?>" />
</td></tr>
<tr>
<td class='leftsearchsi'>
<?php echo $lang_search_where; ?>
</td>
</tr>
<tr>
<td>
<center>
<table summary="" width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>   
    <td class='leftsearchbox'><input type="checkbox" <?php if ($preselect_caption_search) { echo " checked "; } ?> name="twg_search_caption" value="1"/>
      </td><td class=leftsearch><?php echo $lang_menu_titel; ?></td>
  </tr>
  <tr> 
    <td class=leftsearchbox><input type="checkbox" <?php if ($preselect_comments_search) { echo " checked "; } ?> name="twg_search_comment" value="1"/>
</td><td class=leftsearch><?php echo $lang_comments; ?></td>
  </tr>
  <tr>
    <td class=leftsearchbox><input type="checkbox" <?php if ($preselect_filenames_search) { echo " checked "; } ?> name="twg_search_filename" value="1"/>
</td><td class=leftsearch><?php echo $lang_fileinfo_name; ?></td>
  </tr>
</table>
</center>
</td>
</tr>
 <tr>
	<td class=leftsearchsi>
	<?php echo $lang_search_max; ?>
	 <select name="twg_search_max">
	 <option value="10">10</option>
	 <option value="20">20</option>
	 <option selected value="50">50</option>
	 <option value="10000"><?php echo $lang_search_all; ?></option>
	 </select>
	</td>
</tr>
</table>
</form>
<?php include "i_bottom.inc.php"; ?>
</body>
</html>