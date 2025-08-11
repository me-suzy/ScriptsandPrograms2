<?php

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

function setup() {
// config.php is the main configuration file.
include('config.php');
// name of page for links and title.
if ($su == "on") {
	$page_name = "su";
} else {
	$page_name = "setup";
};
// include header if "on" in config.php.
if ($head == "on") {
	include("header.php");
}
echo "<h1>Setup Utility</h1>
$p
This <b>Setup Utility</b> performs two functions.
$p2
$p
<b>1)</b> The <b>Setup Utility</b> will check to see if the \"image\" directory, specified in \"Options\"(config.php), exists.
<br />
- If it does not exist, the <b>Setup Utility</b> will create it for you.
<br />
- If the directory does exist, the utility will automatically set the permissions of the directory and all subdirectories to 755 and all files contained in the directories to 644.
$p2
$p
<b>2)</b> The <b>Setup Utility</b> also automatically sets the permissions for your \"data\" directory to 755.
$p2
<hr />
<h1>WARNING!!!</h1>
$p
The utility will chmod 755 <b>ALL SUBDIRECTORIES</b> and chmod 644 <b>ALL FILES</b> under the image directory!!!
$p2
<form action=\"setup.php\" method=\"post\">
$p
<input type=\"hidden\" name=\"cmd\" value=\"setup2\" />
<input name=\"submit\" type=\"submit\" value=\"Continue\" /> <input type=\"button\" onClick=\"javascript:location='options.php';\" value=\"Cancel\">
$p2
</form>";
}

function setup2() {
// config.php is the main configuration file.
include('config.php');
// name of page for links and title.
if ($su == "on") {
	$page_name = "su";
} else {
	$page_name = "setup";
}
// include header if "on" in config.php.
if ($head == "on") {
	include("header.php");
}

echo "<h1>Setup Utility Results</h1>";

//Change the data directory permissions.
chmod(($_SERVER['DOCUMENT_ROOT'] . "/$textdir/$datadir") , 0755);

if(chmod(($_SERVER['DOCUMENT_ROOT'] . "/$textdir/$datadir") , 0755)) {
	echo "$p<b>Directory</b>: <i>" .($_SERVER['DOCUMENT_ROOT'] . "/$textdir/$datadir") ."</i> permissions set to 755 $p2";
} else {
	echo "$p<b>Failed</b> to set directory permissions on: <i>".($_SERVER['DOCUMENT_ROOT'] . "/$textdir/$datadir")."</i>$p2";
}

// Start image directory manipulation (creation and/or set permissions).
// Image directory path.
$img_path_dir = ($_SERVER['DOCUMENT_ROOT'] . "/$imagedir"); 

// chmod value for files. 
$perms['file'] = 0644; 

// chmod value for directories.
$perms['folder'] = 0755; 


//image directory check/create.
if (is_dir($img_path_dir)) {
	$exist = "true";
} else {
	$exist = "false";
}

// Create image directory if it doesn't exist. 
if ($exist == "false") {
	mkdir ($img_path_dir, 0755);
	echo "<b>$imagedir</b> has been created and the permissions have been set to 755.";

// If image directory exists, chmod it and all files and subdirectories.
} elseif ($exist == "true") {
	chmod("$img_path_dir", 0755);
	chmod_img_dir("$img_path_dir");
}
echo "<form>
$p
<input type=\"button\" onClick=\"javascript:location='admin.php';\" value=\"Continue\">
$p2
</form>";
}
// End image directory manipulation.

// Chmod all files and subdirectories function.
// chmod value for files. 
$perms['file'] = 0644; 
// chmod value for directories.
$perms['folder'] = 0755; 

function chmod_img_dir($dir) { 
// config.php is the main configuration file.
include('config.php');
global $perms;
$dh=@opendir($dir);
	
	if ($dh) {
	while (false!==($file = readdir($dh))) {
	
	if($file!="." && $file!="..") {
	
		$fullpath = $dir .'/'. $file;
		if(!is_dir($fullpath)) {
		
			if(chmod($fullpath, $perms['file'])) {
			echo "$p<b>File</b>: <i>$file</i> permissions set to " .decoct($perms['file']). "$p2";
		} else {
			echo "$p<b>Failed</b> to set file permissions on: <i>$file</i>$p2";
			}
		} else {
			if(chmod($fullpath, $perms['folder'])) { 
                        echo "$p<b>Directory</b>: <i>$fullpath</i> permissions set to " .decoct($perms['folder']). "$p2";
			chmod_img_dir($fullpath);
		} else {
			echo "$p<b>Failed</b> to set directory permissions on: <i>$fullpath</i>$p2";
			}
		}
	}
}
	closedir($dh);
	} 
}

// include footer if "on" in config.php.
if ($head == "on") {
	include("footer.php");
}

switch($_REQUEST['cmd']){ 
	default:
	setup();
	break; 

case "setup2";
	setup2();
	break;
}

?>