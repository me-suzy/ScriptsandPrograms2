<?php

// config.php is the main configuration file.
include('config.php');
// name of page for links and title.
$page_name = "options";

// password protection.
if ($password_protect == "on") {
	// start password protection code:
	session_start();
	// store hash of password.
	$cmp_pass = md5("$admin_password");
	if(!empty($_POST['pass1'])) {
		// store md5'ed password.
		$_SESSION['pass1'] = md5($_POST['pass1']);
	}
	// if they match, it's ok.
	if($_SESSION['pass1']!=$cmp_pass) {
		// otherwise, give login page.
		if ($head == "on") {
			include("header.php");
	}
	echo "$p
	<strong>Enter Password</strong>
	$p2
	<form action=\"options.php\" method=\"post\">
	$p
	<input type=\"password\" name=\"pass1\">
	<input type=\"submit\" value=\"login\">
	$p2
	</form>";
	if ($head == "on") {
		include("footer.php");
	}
	exit();
	}
}
// end password protection.

// web-based editor for config.php.
function options (){
// config.php is the main configuration file.
include('config.php');
// name of page for links and title.
$page_name = "options";
// include header if "on" in config.php.
if ($head == "on") {
	include("header.php");
}

if ($adminlink == "on") {
	$adminlink2 = "<select name=\"opt_adminlink\">
<option value=\"on\" selected=\"selected\">On</option>
<option value=\"off\">Off</option>
</select>";
} elseif ($adminlink == "off") {
	$adminlink2 = "<select name=\"opt_adminlink\">
<option value=\"on\">On</option>
<option value=\"off\" selected=\"selected\">Off</option>
</select>";
}

if ($multi == "on") {
	$multi2 = "<select name=\"opt_multi\">
<option value=\"on\" selected=\"selected\">On</option>
<option value=\"off\">Off</option>
</select>";
} elseif ($multi == "off") {
	$multi2 = "<select name=\"opt_multi\">
<option value=\"on\">On</option>
<option value=\"off\" selected=\"selected\">Off</option>
</select>";
}

if ($su == "on") {
	$su2 = "<select name=\"opt_su\">
<option value=\"on\" selected=\"selected\">On</option>
<option value=\"off\">Off</option>
</select>";
} elseif ($su == "off") {
	$su2 = "<select name=\"opt_su\">
<option value=\"on\">On</option>
<option value=\"off\" selected=\"selected\">Off</option>
</select>";
}

if ($password_protect == "on") {
	$password_protect2 = "<select name=\"opt_password_protect\">
<option value=\"on\" selected=\"selected\">On</option>
<option value=\"off\">Off</option>
</select>";
} elseif ($password_protect == "off") {
	$password_protect2 = "<select name=\"opt_password_protect\">
<option value=\"on\">On</option>
<option value=\"off\" selected=\"selected\">Off</option>
</select>";
}

if ($fileupload == "on") {
	$fileupload2 = "<select name=\"opt_fileupload\">
<option value=\"on\" selected=\"selected\">On</option>
<option value=\"off\">Off</option>
</select>";
} elseif ($fileupload == "off") {
	$fileupload2 = "<select name=\"opt_fileupload\">
<option value=\"on\">On</option>
<option value=\"off\" selected=\"selected\">Off</option>
</select>";
}

if ($fileupload_domain == "on") {
	$fileupload_domain2 = "<select name=\"opt_fileupload_domain\">
<option value=\"on\" selected=\"selected\">On</option>
<option value=\"off\">Off</option>
</select>";
} elseif ($fileupload_domain == "off") {
	$fileupload_domain2 = "<select name=\"opt_fileupload_domain\">
<option value=\"on\">On</option>
<option value=\"off\" selected=\"selected\">Off</option>
</select>";
}

if ($fileupload_size== "on") {
	$fileupload_size2 = "<select name=\"opt_fileupload_size\">
<option value=\"on\" selected=\"selected\">On</option>
<option value=\"off\">Off</option>
</select>";
} elseif ($fileupload_size == "off") {
	$fileupload_size2 = "<select name=\"opt_fileupload_size\">
<option value=\"on\">On</option>
<option value=\"off\" selected=\"selected\">Off</option>
</select>";
}

if ($fileupload_directoryname== "on") {
	$fileupload_directoryname2 = "<select name=\"opt_fileupload_directoryname\">
<option value=\"on\" selected=\"selected\">On</option>
<option value=\"off\">Off</option>
</select>";
} elseif ($fileupload_directoryname == "off") {
	$fileupload_directoryname2 = "<select name=\"opt_fileupload_directoryname\">
<option value=\"on\">On</option>
<option value=\"off\" selected=\"selected\">Off</option>
</select>";
}

if ($fileupload_ext == "on") {
	$fileupload_ext2 = "<select name=\"opt_fileupload_ext\">
<option value=\"on\" selected=\"selected\">On</option>
<option value=\"off\">Off</option>
</select>";
} elseif ($fileupload_ext == "off") {
	$fileupload_ext2 = "<select name=\"opt_fileupload_ext\">
<option value=\"on\">On</option>
<option value=\"off\" selected=\"selected\">Off</option>
</select>";
}

if ($fileupload_delete== "on") {
	$fileupload_delete2 = "<select name=\"opt_fileupload_delete\">
<option value=\"on\" selected=\"selected\">On</option>
<option value=\"off\">Off</option>
</select>";
} elseif ($fileupload_delete == "off") {
	$fileupload_delete2 = "<select name=\"opt_fileupload_delete\">
<option value=\"on\">On</option>
<option value=\"off\" selected=\"selected\">Off</option>
</select>";
}

if ($head == "on") {
	$head2 = "<select name=\"opt_head\">
<option value=\"on\" selected=\"selected\">On</option>
<option value=\"off\">Off</option>
</select>";
} elseif ($head == "off") {
	$head2 = "<select name=\"opt_head\">
<option value=\"on\">On</option>
<option value=\"off\" selected=\"selected\">Off</option>
</select>";
}

if ($setup == "on") {
	$setup2 = "<select name=\"opt_setup\">
<option value=\"on\" selected=\"selected\">On</option>
<option value=\"off\">Off</option>
</select>";
} elseif ($setup == "off") {
	$setup2 = "<select name=\"opt_setup\">
<option value=\"on\">On</option>
<option value=\"off\" selected=\"selected\">Off</option>
</select>";
}

echo "<form action=\"options.php\" method=\"post\">
$p
<input type=\"hidden\" name=\"opt_head\" value=\"$head\" />
<input type=\"hidden\" name=\"opt_textdir\" value=\"$textdir\" />
<input type=\"hidden\" name=\"opt_pagepath\" value=\"$pagepath\" />
Please feel free to customize your <b>Edit-Point</b> installation below.
$p2
$p
No changes need to be made, unless you want to add options to the TinyMCE WYSIWYG editor or if you changed the default directory structure.
$p2
$p
You can revisit this page at any time to make changes and your settings will be remembered (except for your \"ignore\" settings - see below).
$p2
$p
<input type=\"hidden\" name=\"cmd\" value=\"options2\" />
Site name and page title.
<br />
<input type=\"text\" name=\"opt_page_title\" value=\"$page_title\" />
$p2
$p
Pre-populated text in \"Choose a name for the new Edit-Point.\"
<br />
<input type=\"text\" name=\"opt_samplename\" value=\"$samplename\" />
$p2
$p
Pre-populated text in textarea
<br />
<input type=\"text\" name=\"opt_sampletext\" value=\"$sampletext\" />
$p2
$p
Files and directories to ignore. Limit of 10 entries.
<br />
Values must be enclosed in quotations(<b>&quot;</b>) and seperated by commas(<b>&#44;</b>).
<br />
For example: <b>\".\",\"..\",\".htaccess\",\"text\"</b>
<br />
NOTE: This webbased config won't remember your \"ignore\" settings if you revisit this page.
<br />
<input type=\"text\" name=\"opt_ignore\" value=\"\" />
$p2
$p
Data directory 
<br />
<input type=\"text\" name=\"opt_datadir\" value=\"$datadir\" />
$p2
$p
The redirect speed after editing a point (index.php). 1000 = 1 second
<br />
<input type=\"text\" name=\"opt_edit_redirect\" value=\"$edit_redirect\" />
$p2
$p
The redirect speed after creating a point (admin.php). 1000 = 1 second
<br />
<input type=\"text\" name=\"opt_admin_redirect\" value=\"$admin_redirect\" />
$p2
$p
The width of the textarea when editing points (rows).
<br />
<input type=\"text\" name=\"opt_edit_width\" value=\"$edit_width\" />
$p2
$p
The height of the textarea when editing points (columns).
<br />
<input type=\"text\" name=\"opt_edit_height\" value=\"$edit_height\" />
$p2
$p
Html start tag
<br />
<input type=\"text\" name=\"opt_p\" value=\"$p\" />
$p2
$p
Html end tag
<br />
<input type=\"text\" name=\"opt_p2\" value=\"$p2\" />
$p2
$p
Add Edit-Points to Administration(admin page).
<br />
$adminlink2
$p2
$p
Option to add one Edit-Point to multiple places on a website.
<br />
$multi2
$p2
$p
Option to add links to all script pages on all pages.
<br />
$su2
$p2
<hr />
<h1>Password Protection</h1>
$p
Option to use the built-in password protection.
<br />
<b>NOT RECOMMENDED!!!</b>
<br /><br />
I <b>STRONGLY</b> recommended using <b>.htaccess</b> instead of the built-in password protection unless it is not possible or you are having problems with the \"EMOTIONS\" plugin.
<br />
$password_protect2
$p2
$p
The administrator password for <b>Administration</b>(admin.php) and <b>Options </b>(options.php)
<br />
<input type=\"text\" name=\"opt_admin_password\" value=\"$admin_password\" />
$p2
$p
The user password for the <b>Editor</b>(index.php).
<br />
<input type=\"text\" name=\"opt_user_password\" value=\"$user_password\" />
$p2
$p
The user password for <b>File Upload</b>(upload.php).
<br />
<input type=\"text\" name=\"opt_upload_password\" value=\"$upload_password\" />
$p2
<hr />
<h1>File Upload</h1>
$p
Whether or not the \"File upload\" option is available on the \"Editor Page\" (modified version of \"Simple Upload\" from <a href=\"http://tech.citypost.ca/\" onclick=\"javascript:window.open(this.href, &#39;citypost&#39;, &#39;scrollbars=1, location=1, status=1, width=600, height=400, left=175, top=100&#39;); return false;\" title=\"CityPost\">http://tech.citypost.ca/</a>). If you would like to restrict uploads to certain file types, please see the \"File Upload Settings\" under \"Setup Advanced\" in the \"README\".
<br />
$fileupload2
$p2
$p
Your domain name. It only needs to be changed if you chose \"on\" for \"File Upload\". No end slash \"/\".
<br />
<input type=\"text\" name=\"opt_fileupload_domain\" value=\"$fileupload_domain\" />
$p2
$p
The maximum file size allowed for uploading. The default equals \"2mb\". NOTE: Your server limits the size of uploads via php so you will have varying results. View your \"php info\" and look for \"upload_max_filesize\" to see your limit.
<br />
<input type=\"text\" name=\"opt_fileupload_size\" value=\"$fileupload_size\" />
$p2
$p
The name of the directory that the files are uploaded to. This directory will be automatically created one directory above the \"text\" directory. For instance, your Edit-Point installation is: http://YOURDOMAIN.com/text/ and the file upload directory (files) will be: http://YOURDOMAIN.com/files/
<br />
<input type=\"text\" name=\"opt_fileupload_directoryname\" value=\"$fileupload_directoryname\" />
$p2
$p
Whether or not to allow files to be deleted.
<br />
$fileupload_delete2
$p2
<hr />
<h1><a href=\"http://tinymce.moxiecode.com/\" onclick=\"javascript:window.open(this.href, &#39;TinyMCE&#39;, &#39;scrollbars=1, location=1, status=1, width=600, height=400, left=175, top=100&#39;); return false;\" title=\"TinyMCE\">TinyMCE</a> WYSIWYG Editor Settings</h1>
$p
<b><a href=\"http://www.j-cons.com/news/index.php?id=0\">iManager</a> plugin</b>
<br />
Developed, copyrighted (c)2005 by <a href=\"net4visions.com\">net4visions.com</a>. License: LGPL.
$p2
$p
Your \"image directory name\" from your domain name. No end slash \"/\".
<br />
<input type=\"text\" name=\"opt_imagedir\" value=\"$imagedir\" />
<br />
You must manually chmod your image directory 755 or use the <b>Setup Utility</b> to automatically chmod your \"data directory\" and chmod and/or create your \"image directory\".
$p2
<h1>Setup Utility</h1>
$p
Option to use the <b>Setup Utility</b> to chmod your \"data\" and image directories. If \"on\" is selected, you will be directed to the <b>Setup Utility</b> when you click \"Edit\".
<br />
$setup2
$p2
<hr />
$p
<input name=\"submit\" type=\"submit\" value=\"Edit\" /> : Edit your configuration
$p2
</form>";

// include footer if "on" in config.php.
if ($head == "on") {
	include("footer.php");
}
}

// write changes to config.php.
function options2($opt_page_title, $opt_samplename, $opt_sampletext, $opt_ignore, $opt_textdir, $opt_datadir, $opt_pagepath, $opt_edit_redirect, $opt_admin_redirect, $opt_edit_width, $opt_edit_height, $opt_p, $opt_p2, $opt_adminlink, $opt_multi, $opt_su, $opt_password_protect, $opt_admin_password, $opt_user_password, $opt_upload_password, $opt_fileupload, $opt_fileupload_domain, $opt_fileupload_size, $opt_fileupload_directoryname, $opt_fileupload_delete, $opt_head, $opt_imagedir, $opt_setup){

$page_name = "opt_redirect";

// include header if "on" in config.php.
if ($opt_head == "on") {
	include("header.php");
}

// "nasty" workarounds for html output.
$page_title = '$page_title';
$samplename = '$samplename';
$sampletext = '$sampletext';
$ignore = '$ignore';
$textdir = '$textdir';
$datadir = '$datadir';
$pagepath = '$pagepath';
$edit_redirect = '$edit_redirect';
$admin_redirect = '$admin_redirect';
$edit_width = '$edit_width';
$edit_height = '$edit_height';
$p = '$p';
$p2 = '$p2';
$adminlink = '$adminlink';
$multi = '$multi';
$su = '$su';
$password_protect = '$password_protect';
$admin_password = '$admin_password';
$user_password = '$user_password';
$upload_password = '$upload_password';
$fileupload = '$fileupload';
$fileupload_domain = '$fileupload_domain';
$fileupload_size = '$fileupload_size';
$fileupload_directoryname = '$fileupload_directoryname';
$fileupload_delete = '$fileupload_delete';
$head = '$head';
$imagedir = '$imagedir';
$setup = '$setup';
$array = 'array';
$pare1 = '(';
$pare2 = ')';

// html created for config.php editing.
$edit_config = "<?php

// site name and page title.
$page_title = \"$opt_page_title\";

//Pre-populated text in \"Choose a name for the new Edit-Point\"
$samplename = \"$opt_samplename\";

// Pre-populated text in texarea.
$sampletext = \"$opt_sampletext\";

// Directories or files to ignore in drop-down list of files to choose from. Limit 10.  See README if editing by hand.
$ignore = $array$pare1$opt_ignore$pare2;

// data directory name (where the .txt files, created by the script, are stored).
$datadir = \"$opt_datadir\";

// path from script directory to webpage directory.
$pagepath = \"$opt_pagepath\";

// redirect speed after editing a point (index.php). 1000 = 1 second
$edit_redirect = \"$opt_edit_redirect\";

// redirect speed after creating a point (admin.php)., 1000 = 1 second
$admin_redirect = \"$opt_admin_redirect\";

// Textarea width (rows).
$edit_width = \"$opt_edit_width\";

// Textarea height (columns)
$edit_height = \"$opt_edit_height\";

// html start tag
$p = \"$opt_p\";

// html end tag
$p2 = \"$opt_p2\";

// add Edit-Point links to admin page. on or off.
$adminlink = \"$opt_adminlink\";

// option to add one Edit-Point to multiple places. on or off.
$multi = \"$opt_multi\";

// option to add links to all script pages on all pages.
$su = \"$opt_su\";

// PASSWORD PROTECTION SETTINGS

// whether or not to use the built-in password protection. NOT RECOMMENDED!!! Use .htaccess instead.
$password_protect = \"$opt_password_protect\";
// admin password for admin.php and options.php
$admin_password = \"$opt_admin_password\";
// user password for index.php
$user_password = \"$opt_user_password\";
// upload password for upload.php
$upload_password = \"$opt_upload_password\";

// FILE UPLOAD
// Option to use basic file upload/delete. If used a log file (upload_log.txt) will be created in the \"data\" directory.

// whether or not to use the fileupload is available on the \"Editor\" page. on or off.
$fileupload = \"$opt_fileupload\";
// domain name for fileupload. No end slash \"/\".
$fileupload_domain = \"$opt_fileupload_domain\";
// maximun file size. The default is 2MB. NOTE: Your server limits the size of uploads via php so you will have varying results. View your \"php info\" and look for \"upload_max_filesize\" to see your limit. (1000000 = 1MB)
$fileupload_size = \"$opt_fileupload_size\";
// name of the directory that files are added to. This will be created automatically one directory above the \"text\" directory. For instance, your Edit-Point installation is: http://YOURDOMAIN.com/text/ and the file upload directory (files) will be: http://YOURDOMAIN.com/files/
$fileupload_directoryname = \"$opt_fileupload_directoryname\";
// whether or not to allow files to be deleted.
$fileupload_delete = \"$opt_fileupload_delete\";

// TinyMCE WYSIWYG EDITOR SETTINGS.

$imagedir = \"$opt_imagedir\"; // image directory from domain name. This setting will allow all subdirectories to be indexed as well. Use an end slash \"/\".

// Setup Utility to automatically chmod the \"data\" directory and either create the image directory or set the correct permissions of the existing image directory. The script will chmod the directory and all subdirectories 755 and chmod all files 644.
$setup = \"$opt_setup\";

 // WARNING!!! Do not edit anything below this line unless you manually edit \"/text/jscripts/tiny_mce/plugins/imanager/config/config.inc.php\" line 27 so that \"text\" equals your changed script directory name.
 
//---------------------------------------------------------------//

 // whether or not to use the header/footer. yes or no. NOTE: \"yes\" is required for the WYSIWYG option.
$head = \"$opt_head\";

// script directory
$textdir = \"$opt_textdir\";

// path from script directory to webpage directory.
$pagepath = \"$opt_pagepath\";

?>";

$edit_config = stripslashes($edit_config);

$openlink = fopen('config.php', 'w');
fwrite($openlink, $edit_config);
fclose($openlink);

// redirect to admin page Setup Utility.

if ($opt_setup == "on") {
echo "<script type=\"text/javascript\">
<!--
var URL   = \"setup.php\"
var speed = $opt_admin_redirect
function reload() {
location = URL
}
setTimeout(\"reload()\", speed);
//-->
</script>";

echo "<p>
Your configuration was <b><i>succesfully</i></b> saved.
</p>
<p>
Automatically redirecting to the <a href=\"setup.php\">Setup Utility</a>
</p>";


} elseif ($opt_setup == "off") {
echo "<script type=\"text/javascript\">
<!--
var URL   = \"admin.php\"
var speed = $opt_admin_redirect
function reload() {
location = URL
}
setTimeout(\"reload()\", speed);
//-->
</script>";

echo "<p>
Your configuration was <b><i>succesfully</i></b> saved.
</p>
<p>
Automatically redirecting to the <a href=\"admin.php\">Admin-Page</a>
</p>";
}

// include footer if "on" in config.php.
if ($opt_head == "on") {
	include("footer.php");
}
}

switch($_REQUEST['cmd']){ 
	default:
	options();
	break; 

case "options2";
	options2($_POST['opt_page_title'], $_POST['opt_samplename'], $_POST['opt_sampletext'], $_POST['opt_ignore'], $_POST['opt_textdir'], $_POST['opt_datadir'], $_POST['opt_pagepath'], $_POST['opt_edit_redirect'], $_POST['opt_admin_redirect'], $_POST['opt_edit_width'], $_POST['opt_edit_height'], $_POST['opt_p'], $_POST['opt_p2'], $_POST['opt_adminlink'], $_POST['opt_multi'], $_POST['opt_su'], $_POST['opt_password_protect'], $_POST['opt_admin_password'], $_POST['opt_user_password'], $_POST['opt_upload_password'], $_POST['opt_fileupload'], $_POST['opt_fileupload_domain'], $_POST['opt_fileupload_size'], $_POST['opt_fileupload_directoryname'], $_POST['opt_fileupload_delete'], $_POST['opt_head'], $_POST['opt_imagedir'], $_POST['opt_setup']);
	break; 
}

?>