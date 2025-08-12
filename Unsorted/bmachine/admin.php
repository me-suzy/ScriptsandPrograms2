<?php

/*
###########################################

BN Soft bMachine 2.7

Written by Kailash Nadh
http://bnsoft.net, support@bnsoft.net

bMachine - http://boastology.com

###########################################
*/


//################ ADMIN SCRIPT ################

include_once "config.php";

if($db == "mysql") { include_once "inc/mysql/admin.mysql.php"; } else { include_once "inc/flat/admin.flat.php"; }

// Check for password
if($_POST[pass] && $_POST[pass]==$passw) {
	setcookie("bn_nws","bn_nws");
	header("Location: admin.php"); exit();
}

if(!$_COOKIE["bn_nws"]) {
ahdr("Admin Login");
?>
<table border="0" align="center">
<tr><td>
<form method="POST" action="admin.php">
<p><font size="2" face="Verdana">Please enter the Admin password</font><br>
<input type="password" name="pass" size="31" style="font-family:Verdana;">
<input type="submit" value="Login" style="font-family:Verdana;"></p>
</form>
</td>
</tr>
</table>
<?
aftr();
exit;
}
//#############################################

//LogOut
if($_GET[id]=="logout") { setcookie("bn_nws",""); header("Location: admin.php?done=true"); exit(); }

//Load the Menu links
$mnu=mnu();

// Show comments editing page
if($_GET[id]=="comments") { editComments($_GET[fnm]); exit(); }

// Delete all comments for a post [show warning]
if($_GET[id] == "dlcmts") { shDlCmts(); }

if($_GET[id] == "del") { delPost(); }

// Delete all comments for a post
if($_GET[id] == "cdely") { delCmts($_GET[f]); header("Location: admin.php?done=true"); }

// Backup/Restore page
if($_GET[id]=="bkprst") { shBkp(); exit(); }

// Open the post.html template and print it out
	if($_GET[id] == "new") {
	$psdat=@fread(fopen("post.html", "r"), 100000);
	$psdat=str_replace("[NAME]", $c_name, $psdat);
	$psdat=str_replace("[EMAIL]", $c_email, $psdat);
	$psdat=str_replace("[URL]", $c_url, $psdat);
	ahdr("Post a new Blog/Article");
	echo $psdat;
	aftr();
	exit();
	}

// Save the comments
if($_POST[act] == "ecmts") {
saveCmts();
}

	if($_POST[bkp]=="backup") {
	bkp(); exit();
	}

	if($_POST[bkp]=="restore") {
	rstr(); exit();
	}


if($_POST[act] == "post") {
saveData();
}

if($_GET[id]=="log") {
$log=@fread(fopen("$m_log", "r"), 1000000);
echo <<<EOF
<HTML><HEAD><TITLE>Logs</TITLE></HEAD><BODY>
<pre>
$log
</pre>
</BODY></HTML>
EOF;
exit();
}

// Clear Log file
if($_GET[id]=="dlog") {
$a = fopen("$m_log", "w+") or errd("Cannot write to the LOG file!", "Unable to write to the log.txt file!<br>Please check whether the file exists and its permission is 777");
$write = fputs($a, " ");
fclose($a);
header("Location: admin.php?bn=eCt4sdd7fftpf");
exit();
}

// Hide/Unhide Articles
	if(strtolower($_GET[id]) == "hide") {
	postStatus("hide");
	}

	if(strtolower($_GET[id]) == "unhide") {
	postStatus("show");
	}

// Delete all comments for a specific post
function shDlCmts() {
ahdr("Delete all comments for this post?");
echo <<<EOF
<br><br><br>
<p align="center"><font size="2" face="Verdana" color="red"><b>Are you sure 
want to delete all the comments on this post<br></b></font>
<a href="admin.php?id=cdely&f=$_GET[fnm]"><img src="images/yes.gif" border="0" alt="Yes"></a>
&nbsp;&nbsp;&nbsp;&nbsp;<a href="admin.php"><img src="images/no.gif" border="0" alt="No"></a></p>
EOF;
aftr();
exit();
}

// Deleting a POST
if($_GET[id] == "fdel") {
ahdr("Delete post?");
echo <<<EOF
<p><font size="2" face="Verdana"><b>&nbsp;</b></font></p>
<p align="center"><font size="2" face="Verdana" color="red"><b>Are you sure 
want to delete this post &amp; the attached file?<br></b></font>
<a href="admin.php?id=del&f=$_GET[f]"><img src="images/yes.gif" border="0" alt="Yes"></a>
&nbsp;&nbsp;&nbsp;&nbsp;<a href="admin.php"><img src="images/no.gif" border="0" alt="No"></a></p>
EOF;
aftr();
exit();
}

// Clear all the data files!
if($_GET[id] == "fdelall") {
ahdr("Delete all posts?");
echo <<<EOF
<p><font size="2" face="Verdana"><b>&nbsp;</b></font></p>
<p align="center"><font size="2" face="Verdana" color="red"><b>WARNING! : Are you sure 
want to delete all posts &amp; all attached files?<br></b></font>
<a href="admin.php?id=delall"><img src="images/yes.gif" border="0" alt="Yes"></a>
&nbsp;&nbsp;&nbsp;&nbsp;
<a href="admin.php"><img src="images/no.gif" border="0" alt="No"></a></p>
EOF;
aftr();
exit();
}

// Delete all the data files/comment files and attached files

if($_GET[id] == "delall") {
delAll();
}

//#############################################
global $mnu;
ahdr("BN Soft BoastMachine Admin area", $mnu);

$mt = explode(' ', microtime()); 
$script_start_time = $mt[0] + $mt[1];

global $db,$ver;
?>
<p><font size="2" face="Verdana"><b>Welcome to your Admin area <?php echo $c_name; ?>!</b></font><br>
<span class="t_small">bMachine <?php echo $ver; ?> running in "<?php echo $db; ?>" mode</span>
</p>
</p><table cellpadding="0" cellspacing="0" border="0">
<tr><td><ul>
<?
//#############################################

// List the articles
getFileDat();

//#############################################

?>
</ul></td></tr></table><br><br><center><font size="1" face="Verdana">
<?
$mt = explode(' ', microtime()); 
$script_end_time = $mt[0] + $mt[1]; 

echo "Done in ".round($script_end_time - $script_start_time, 5)." seconds";

aftr();

//################### BACKUP/RESTORE functions #########################
function shBkp() {
ahdr("Backup/Restore data");
?>
<p><font size="2" face="Verdana"><b>Backup/Restore articles</b></font></p>
<p><font size="2" face="Verdana" color="red">Note: Even though articles can 
be backedup, the files attached<br>with them cant be. Please backup the attached 
files manually</font></p>
<form name="backupp" method="POST" action="admin.php">
<input type="hidden" name="bkp" value="backup">
<p><font size="2" face="Verdana" color="red"><input type="submit" value="Backup all the articles" style="font-family:Verdana;"></font></p>
</form>
<form name="restore" method="POST" action="admin.php" ENCTYPE="multipart/form-data">
<input type="hidden" name="bkp" value="restore">
<font size="2" face="Verdana">Upload a backup file and restore data</font><br>
<font size="2" face="Verdana" color="red">If files with same id exist, they will be overwritten!!</font>
<input type="file" name="file" maxlength="60" size="46" style="font-family:Verdana;"> 
<font size="2" face="Verdana" color="red"><input type="submit" value="Upload and Restore" style="font-family:Verdana;"></font></form>
<br><center>
<input type="button" value="<<CANCEL" onClick="javascript:document.location='admin.php';">
</center>
<?
aftr();
}

//###################################################

// Special functions for Admin header/Footer
function ahdr($ttl,$mnu="") {
$title=$ttl;
include_once "inc/templates/admin.header.php";
}

// Prints the footer, i.e, the part below [HERE]
function aftr() {
include_once "inc/templates/admin.footer.php";
}

function mnu() {
$mnu=<<<EOF
<img width="4" height="4"src="images/box1.gif" aligh="top">&nbsp;<a href="admin.php?id=new">New Post</a><br>
<img width="4" height="4"src="images/box2.gif" aligh="top">&nbsp;<a href="admin.php?id=fdelall">Delete All!!</a><br>

EOF;

global $m_log;
if($m_log) {
$mnu.=<<<EOF
<img width="4" height="4"src="images/box3.gif" aligh="top">&nbsp;<a href="admin.php?id=dlog">Clear Logs</a><br>
<img width="4" height="4"src="images/box4.gif" aligh="top">&nbsp;<a href="admin.php?id=log">View Logs</a><br>
EOF;

}

$mnu.=<<<EOF

<img width="4" height="4"src="images/box5.gif" aligh="top">&nbsp;<a href="admin.php?id=bkprst">Backup/Restore</a>
EOF;
return $mnu;
}
//##########################################
?>