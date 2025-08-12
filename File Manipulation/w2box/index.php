<?
// w2box: web 2.0 File Repository v2.1
// (c) 2005, Clément Beffa
// use it at your own risk


$w2box_name = "your w2box";
$storage_dir = "data"; // storage directory (chmod 777)
$max_filesize = 100 * pow(1024,2); // maximum filesize for this script (x MiB), update post_max_size & upload_max_filesize in php.ini for big size
$allowed_fileext = array("gif","jpg","jpeg","png","pdf","txt","doc","rtf","zip");// allowed extensions


$auth = true; //if true no authentication, everyone allowed to do everything. 

//to login as admin when everything is hidden, click on "Powered" (hidden link) in the footer!!! 
$admin_user = "admin";
$admin_pass = "admin";
$protect_upload = true; //allow only admin to upload
$hide_upload = $protect_upload; //hide upload form if not admin
$protect_delete = true; //allow only admin to delete
$hide_delete = $protect_delete; //hide delete column if not admin

//login
authorize(true); //silent authorize first
if (isset($_GET["admin"])) {
	authorize();
	Header("Location: ".rooturl());
}

//find real max_filesize
$max_filesize = min(return_bytes(ini_get('post_max_size')),return_bytes(ini_get('upload_max_filesize')),$max_filesize);

// deleting
if (isset($_POST["delete"])) {
	if ($protect_delete) authorize();
	deletefile($_POST["delete"]);
}

function deletefile($cell){
	global $storage_dir;
	$cell=strip_tags($cell);

	$file=substr($cell,0,strlen($cell)-1);
	$file = "$storage_dir/".basename($file);

	if (!file_exists(utf8_decode($file)))
	echo "Error: file not found. ($file)";
	else {
		$return = @unlink(utf8_decode($file));
		if ($return) echo "successful"; else echo "Error: can't delete file.";
	}
	exit;
}

//uploading
if (isset($_FILES['file'])) {
	if ($protect_upload) authorize();
	uploadfile($_FILES['file']);
}

function uploadfile($file) {
	global $storage_dir, $max_filesize, $allowed_fileext, $errormsg;

	if ($file['error']!=0) {
		switch ($file['error']) {
			case 1: $errormsg = "The uploaded file exceeds the upload_max_filesize directive in php.ini"; break;
			case 2: $errormsg = "The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form."; break;
			case 3: $errormsg = "The uploaded file was only partially uploaded."; break;
			case 4: $errormsg = "No file was uploaded."; break;
			case 6: $errormsg = "Missing a temporary folder."; break;
		}
		return;
	}

	$filesource=$file['tmp_name'];

	$filename=$file['name'];
	if (isset($_POST['filename']) && $_POST['filename']!="") $filename=$_POST['filename'];
	$filename=str_replace(" ","_",$filename);
	if (!in_array(strtolower(extname($filename)), $allowed_fileext)) $filename .= ".badext";


	$filesize=$file['size'];
	if ($filesize > $max_filesize) {
		$errormsg = "File size is greater than the file size limit (".getfilesize($max_filesize).").";
		return;
	}

	$filedest="$storage_dir/$filename";
	if (file_exists($filedest)) {
		$errormsg = "$filename exists already in the storage directory.";
		return;
	}

	if (!copy($filesource,$filedest)) {
		$errormsg = "Unable to copy the file into the storage directory.";
	}

	if  ($errormsg=="") {
		Header("Location: ".rooturl());
		exit;
	}
}

//downloading
if (isset($_GET['download']))
downloadfile($_GET['download']);

function downloadfile($file){
	global $storage_dir;
	$file = "$storage_dir/".basename($file);
	if (!is_file($file)) { return; }
	header("Content-Type: application/octet-stream");
	header("Content-Size: ".filesize($file));
	header("Content-Disposition: attachment; filename=\"".basename($file)."\"");
	header("Content-Length: ".filesize($file));
	header("Content-transfer-encoding: binary");
	@readfile($file);
	exit(0);
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "DTD/xhtml1-strict.dtd">
<html>
<head>
<meta http-equiv="Content-type" content="text/html; charset=iso-8859-1" />
  <title><? print $w2box_name; ?> | powered by w2box</title>
  <meta name="author" content="cb" />
  <meta name="description" content="w2box, web2.0 File Repository" />
  <meta name="keywords" content="upload, download, box, web2.0, ajax" />
  <meta name="content-language" content="en" />
  <link rel="stylesheet" type="text/css" href="w2box.css" />
  <script type="text/javascript" src="sorttable.js"></script>  
  <script type="text/javascript" src="pt.ajax.js"></script>
  <script type="text/javascript">
  <!--//<![CDATA[

  function deletefile(row) {
  	row.className='delete';
  	new Ajax.Request("index.php", {
  		parameters: '&delete=' + encodeURIComponent(row.cells[0].innerHTML),
  		onComplete: function (req) {
  			if (req.responseText == "successful") {
  				row.parentNode.removeChild(row);
  			} else {
  				alert(req.responseText);
  				row.className='off';
  			}
  		}
  	});
  }

  function renameSync() {
  	var fn = document.getElementById("file").value;
  	if (fn == ""){
  		document.getElementById("filename").value = '';
  	} else {
  		var filename = fn.match(/[\/|\\]([^\\\/]+)$/);
  		if (filename==null) 
  		  filename = fn; //opera...
  		else
  		  filename = filename[1];
  		
  		document.getElementById("filename").value = filename;
  	}

  	filetypeCheck();
  }

  function filetypeCheck() {
  	var allowedtypes = '.<? echo join(".",$allowed_fileext); ?>.';

  	var fn = document.getElementById("filename").value;
  	if (fn == ""){
  		document.getElementById("allowed").className ='';
  		document.getElementById("upload").disabled = true;
  	} else {
  		var ext = fn.split(".");
  		if (ext.length==1)
  		ext = '.noext.';
  		else
  		ext = '.' + ext[ext.length-1].toLowerCase() + '.';

  		if (allowedtypes.indexOf(ext) == -1) {
  			document.getElementById("allowed").className ='red';
  			document.getElementById("upload").disabled = true;
  		} else {
  			document.getElementById("allowed").className ='';
  			document.getElementById("upload").disabled = false;
  		}
  	}

  }
  //]]>-->
</script>
</head>

<body onload="document.getElementById('upload').disabled = true;">
<div id="page">
<div id="header"><a href="." style="height: 100%;display: block;"></a><h1>w2box - File Manager</h1>
</div>

<div id="content">
 	<div id="errormsg">
 	 <p class="red"><? if (isset($errormsg)) {echo $errormsg;} ?></p>
 	</div>
<? if ($demo) { ?>
	<div id="warning">
 	 <p style="background-color:#f84">I'm not responsible of any file here. Download them at your own risk!</p>
 	</div>
<? } ?>
<? if (!$hide_upload || $auth) { ?>
 	<div id="uploadform">
		<form method="post" enctype="multipart/form-data" action="">
		<p><label for="file">file :</label><input type="file" id="file" name="file" size="50" onchange="renameSync();" /><input id="upload" type="submit" value="Upload" class="button" /></p>
		<p><label for="filename">rename to :</label><input type="text" id="filename" name="filename" onkeyup="filetypeCheck();" size="50" /></p>
		<p class="small"><span id="allowed">file types allowed: <? echo join(",",$allowed_fileext); ?></span><br />file size limit: <? echo getfilesize($max_filesize); ?></p>
		</form>
 	</div>
<? } ?>
	<div id="filelisting">
	  <img src="images/arrow-up.gif" alt="" style="display:none;" /><img src="images/arrow-down.gif" alt="" style="display:none;" />
		<? listfiles($storage_dir); ?>
	</div>
</div>
<div id="footer">
  <p><a class="hiddenlink" href="?admin" onmouseover="return true;">Powered</a> by <a href="http://labs.beffa.org/w2box/">w2box</a>, using valid <a href="http://validator.w3.org/check/referer">xhtml</a> &amp; <a href="http://jigsaw.w3.org/css-validator/check/referer">css</a>.</p>
</div>

</div>
</body>
</html>
<?php

function listfiles($dir) {
	global $demo,$hide_delete,$auth;
	if ($demo){
		// demo code -- deleteme file
		$file = "data/deleteme.txt";
		if (!$file_handle = fopen($file,"a")) { echo "Cannot open file"; }
		if (!fwrite($file_handle, "Delete me or I'll become fat!!!\n")) { echo "Cannot write to file"; }
		fclose($file_handle);
	}
?>
<table id="t1" class="sortable">
  <tr>
    <th id="th1" class="lefted">File Name</th>
    <th id="th2">Type</th>
    <th id="th3">Size</th>
<? if (!$hide_delete || $auth) { ?>
    <th id="th4" class="unsortable">Delete</th>
<? } ?>
  </tr>
<?php
if ($handle = opendir($dir)) {
	while (false !== ($file = readdir($handle))) {
		if ($file != "." && $file != ".."  && $file != "index.html") {
			$size=filesize($dir."/".$file);
			$ext=strtolower(extname($file));
			$file_ue=urlencode($file);
			print("<tr class=\"off\" onmouseover=\"if (this.className!='delete') {this.className='on'};\" onmouseout=\"if (this.className!='delete') {this.className='off'};\">");
			print("<td class=\"lefted\"><a href=\"$dir/$file_ue\">$file</a>");
			print(" <a href=\"?download=$file_ue\"><img src=\"images/download_arrow.gif\" alt=\"(download)\" title=\"Download Now!\" /></a></td>");
			print("<td>$ext</td>");
			//print("<td><a href=\"http://filext.com/detaillist.php?extdetail=$ext\">$ext</a></td>");
			print("<td>".getfilesize($size)."</td>");
			if (!$hide_delete || $auth) { 
			  print("<td><a title=\"delete\" onclick=\"deletefile(this.parentNode.parentNode); return false;\" href=\"\"><img src=\"images/delete.gif\" alt=\"delete\" title=\"Delete\" /></a></td>");
			}
			print("</tr>\n");
		}
	}
	closedir($handle);
}
?>
</table>
<?php
}

function authorize($silent=false){
	global $auth,$admin_user,$admin_pass;
	//authentication
	if (!$auth){
		if ((isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW'])) &&
		($_SERVER['PHP_AUTH_USER'] == $admin_user && $_SERVER['PHP_AUTH_PW']==$admin_pass)) {
			$auth = true; // user is authenticated
		} else {
			if (!$silent) {
				header( 'WWW-Authenticate: Basic realm="w2box admin"' );
				header( 'HTTP/1.0 401 Unauthorized' );
				echo 'Your are not allowed to access this function!';
				exit;
			}
		}
	}

}

function extname($file) {
	$file = explode(".",basename($file));
	return $file[count($file)-1];
}

function getfilesize($size) {
	if ($size < 2) return "$size byte";
	$units = array(' bytes', ' KiB', ' MiB', ' GiB', ' TiB');
	for ($i = 0; $size > 1024; $i++) { $size /= 1024; }
	return round($size, 2).$units[$i];
}

function return_bytes($val) {
	$val = trim($val);
	$last = strtolower($val{strlen($val)-1});
	switch($last) {
		// The 'G' modifier is available since PHP 5.1.0
		case 'g':
		$val *= 1024;
		case 'm':
		$val *= 1024;
		case 'k':
		$val *= 1024;
	}
	return $val;
}

function rooturl(){
	$dir = dirname($_SERVER['PHP_SELF']);
	if (strlen($dir) > 1) $dir.="/";
	
	return "http://".$_SERVER['HTTP_HOST'].$dir;
}
?>