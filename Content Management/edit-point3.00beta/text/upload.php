<?php
include('config.php');
// name of page for links and title.
if ($su == "on") {
	$page_name = "su";
} else {
	$page_name = "upload";
}
// password protection.
if ($password_protect == "on") {
	// start password protection code:
	session_start();
	// store hash of password.
	$cmp_pass = md5("$upload_password");
	if(!empty($_POST['pass3'])) {
		// store md5'ed password.
		$_SESSION['pass3'] = md5($_POST['pass3']);
	}
	// if they match, it's ok.
	if($_SESSION['pass3']!=$cmp_pass) {
		// otherwise, give login page.
		if ($head == "on") {
			include("header.php");
	}
	echo "$p
	<strong>Enter Password</strong>
	$p2
	<form action=\"upload.php\" method=\"post\">
	$p
	<input type=\"password\" name=\"pass3\">
	<input type=\"submit\" value=\"login\">
	$p2
	</form>";
	if ($head == "on") {
		include("footer.php");
	}
	exit();
	}
} else {
	echo "";
}
// end password protection.

// include header if "on" in config.php.
if ($head == "on") {
	include("header.php");
}

//Mmaximum file size. You may increase or decrease.
$MAX_SIZE = $fileupload_size;

//Allowable file ext. names. you may add more extension names.            
$FILE_EXTS  = array('.jpg','.gif','.png','.doc','.pdf','.zip','.rtf','.pub','.rar','.mp3','.mpg','.tar','.wav','.txt','.mov','.wmv','.avi');  

//Allowable file Mime Types. Add more mime types if you want.
$FILE_MIMES = array('image/jpeg','image/jpg','image/gif','image/png','application/msword','application/pdf','application/zip','application/rtf','application/x-mspublisher','application/x-rar-compressed','application/x-tar','audio/mpeg','audio/wav','text/plain','video/quicktime','video/x-ms-wmv','video/x-msvideo','video/mpeg');

//Allow file delete? no, if only allow upload only.
if ($fileupload_delete == "on") {
	$DELETABLE = true;
} else {
	$DELETABLE == "no";
}                              

/************************************************************
 *     Setup variables
 ************************************************************/
$site_name = $_SERVER['HTTP_HOST'];
$url_dir = "$fileupload_domain/$textdir";
$url_this =  "$fileupload_domain/$textdir/upload.php";
$upload_dir = "../$fileupload_directoryname/";
$upload_url = "$url_dir/$fileupload_directoryname/";
$message ="";

/************************************************************
 *     Create Upload Directory
 ************************************************************/
if (!is_dir("../$fileupload_directoryname")) {
	if (!mkdir($upload_dir))
	die ("upload_files directory doesn't exist and creation failed");
	if (!chmod($upload_dir,0755))
	die ("change permission to 755 failed.");
}

/************************************************************
 *     Process User's Request
 ************************************************************/
if ($_REQUEST[del] && $DELETABLE) {
	$resource = fopen("$datadir/upload_log.txt","a");
	fwrite($resource,date("Ymd h:i:s")." DELETE - $_SERVER[REMOTE_ADDR]"."$_REQUEST[del]\n");
	fclose($resource);
	unlink($_REQUEST['del']); {
	print "<script>window.location.href='$url_this?message=Deleted successfully'</script>";
	}
} else if ($_FILES['userfile']) {
	$resource = fopen("$datadir/upload_log.txt","a");
	fwrite($resource,date("Ymd h:i:s")." UPLOAD - $_SERVER[REMOTE_ADDR] "
	.$_FILES['userfile']['name']." "
	.$_FILES['userfile']['type']."\n");
	fclose($resource);
	$file_type = $_FILES['userfile']['type'];
	$file_name = $_FILES['userfile']['name'];
	$file_ext = strtolower(substr($file_name,strrpos($file_name,".")));

//File Size Check.
if ( $_FILES['userfile']['size'] > $MAX_SIZE) $message = "The file size is too large.";

//File Type/Extension Check.
	//else if (!in_array($file_type, $FILE_MIMES) && !in_array($file_ext, $FILE_EXTS) )
	//$message = "Sorry, $file_name($file_type) is not allowed to be uploaded.";
	//else
	$message = do_upload($upload_dir, $upload_url);
	print "<script>window.location.href='$url_this?message=$message'</script>";
}
else if (!$_FILES['userfile']);
	else 
	$message = "Invalid File Specified.";

/************************************************************
 *     List Files
 ************************************************************/
$handle=opendir($upload_dir);
$filelist = "";
while ($file = readdir($handle)) {
if(!is_dir($file) && !is_link($file)) {
	$filelist .= "<tr><td><a href=\"$upload_dir$file\">$file</a></td>";
	if ($DELETABLE) $filelist .= "<td><a href=\"?del=$upload_dir$file\" title=\"delete\">Delete</a></td>";
	$filelist .= "<td>".date("d M Y - H:i", filemtime($upload_dir.$file))."</td></tr><tr><td colspan=\"3\"> URL: $fileupload_domain/$fileupload_directoryname/$file<hr /></td></tr>";
	
	}
}

function do_upload($upload_dir, $upload_url) {
$temp_name = $_FILES['userfile']['tmp_name'];
$file_name = $_FILES['userfile']['name'];
$file_name = str_replace("\\","",$file_name);
$file_name = str_replace("'","",$file_name);
$file_path = $upload_dir.$file_name;

//File Name Check.
if ( $file_name =="") {
	$message = "Invalid File Name Specified";
	return $message;
	}

$result  =  move_uploaded_file($temp_name, $file_path);
if (!chmod($file_path,0777)) $message = "change permission to 777 failed.";
	else $message = ($result)?"$file_name uploaded successfully." :
	"Somthing is wrong with uploading the file.";
	return $message;
}

echo "
<table class=\"upload\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\"> 
<tr>
<td colspan=\"3\">
$p
$_REQUEST[message]
$p2
<form action=\"upload.php\" id=\"upload\" enctype=\"multipart/form-data\" method=\"post\">
$p
Upload File: <input type=\"file\" id=\"userfile\" name=\"userfile\" />
<input type=\"submit\" name=\"upload\" value=\"Upload\" />
$p2
</form>
</td>
</tr>
<tr>
<td colspan=\"3\">
$p
<b>My Files</b>
$p2
<hr />
</td>
</tr>
$filelist
</table>";

// include footer if "on" in config.php.
if ($head == "on") {
	include("footer.php");
}
?> 