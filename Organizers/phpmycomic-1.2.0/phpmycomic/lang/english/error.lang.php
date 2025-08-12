<?php

$tpl->assign("lang_error_errormsg", "Error Messsage");

$lang_error_needlogin = "<b>Failed to Login, You need to <a href=\"login.php\" class=\"defaultlink\">login</a> to request this page</b>";
$lang_error_loginfail = "<b>Incorrect Login, go back and try again!</b><br /><br /><a href=\"login.php\" class=\"defaultlink\">Go Back</a>";
$lang_error_emtyfield = "<b>A field was left empty, please go back and fill in the emty fields</b>";
$lang_error_nomatch = "<b>The Password Fields Did Not Match</b>";
$lang_error_adminaccess = "<b>Only the Admin can access this page, login as Admin and try again!</b>";
$lang_error_fileheight = "<b>File height and/or width are too big!</b>";
$lang_error_filetype = "<b>Incorrect file extension, only .jpg, .jpeg, .png and .gif!</b>";
$lang_error_openfile = "<b>PhpMyComic Setup could not open file!</b>";
$lang_error_filewrite = "<b>PhpMyComic Setup could not write settings to file!</b>";
$lang_error_noadmin = "<b>YOU ARE NOT THE <font class=\"code\">ADMIN</font> OF THIS PROGRAM</b>";
$lang_error_nologin = "<b>YOU ARE NOT LOGGED IN</b>";
$lang_error_nodefault = "<b>This is a default artist option and can not be removed!</b>";
$lang_error_choosetype = "<b>You did not choose an artist type, please go back and choose an artist type!</b>";
$lang_error_noedit = "<b>This is a default artist option and can not be edited!</b>";
$lang_error_exists = "<b>This comic already exists in the database!</b>";
$lang_error_install = "<b>You MUST DELETE the install file / directory to use PhpMyComic!</b><br /><br /><a href=\"index.php\" class=\"defaultlink\">Return to Index</a>";

$lang_error_artistexist = "<b>This <u>artist</u> already exists in the database!</b>";
$lang_error_filename = "<b>The filename already exists!</b>";
$lang_error_typeexist = "<b>The name of this type of artist already exists!</b>";
$lang_error_settype = "<b>You must set a type for this artist!</b>";
$lang_error_artisttype = "<b>The artist name exists of this type of artist!</b>";
$lang_error_imageexist = "<b>The image's name already exists!</b>";
$lang_error_imagetobig = "<b>The image's size is to big!</b>";
$lang_error_userexist = "<b>The username already exists!</b>";

$lang_error_toshort = "<b>The password is to short (Minimum = 5)!</b>";
$lang_error_characters = "<b>Password contains a character besides (a-z,0-9,_ ,-)!</b>";
$lang_error_update = "<b>Please, update your copy of phpmycomic!</b>";
$lang_error_addmulti = "<b>You cannot add more that a 100 issues at a time</b>";

?>