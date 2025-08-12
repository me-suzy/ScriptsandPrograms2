<?php
/*
BN Soft bMachine 2.7

Written by Kailash Nadh
http://bnsoft.net, support@bnsoft.net

bMachine - http://boastology.com
*/
//####################################################################
//####################################################################
//####################################################################
//################# ADMIN FUNCTIONS !!         #######################

// A variable to protect the include files from being called outside
// the scripts

if($valid_flag != "true") {
echo "ACCESS DENIED!"; exit();
}

// Check/Validate/Format and Save the data
function saveData() {
global $c_dir;

	if($_POST[title] && $_POST[msg]) {

	$date=time(); // We need only the timestamp in seconds. Human readable can be generated from this


		// Check whether HTML or TEXT
		if($_POST[fmt] == "txt") {
		$msg=str_replace("<","&lt;",$_POST[msg]);
		$msg=str_replace(">","&gt;",$_POST[msg]);
		}
		else { $msg=$_POST[msg]; }

//###################################
// FILE UPLOAD!
$userfile_name = $_FILES['file']['name'];
$userfile_tmp = $_FILES['file']['tmp_name'];

$fn2=md5($userfile_name);
$fn2=substr($fn2,5);
$fn2="$fn2-$userfile_name";

	if (trim($userfile_name)) {
	move_uploaded_file($userfile_tmp, "./files/$fn2");
	}
	else { $fn2=""; }
//###################################

// Get data from form, do necessary changes and save it into the db
	if(!$_POST[f_aname]) { $a_name="Anonymous"; } else { $a_name=$_POST[f_aname]; }
	if(!$_POST[f_email]) { $a_email="$admin"; } else { $a_email=$_POST[f_email]; }
	if(!$_POST[f_url]) { $a_site="None"; } else { $a_site=$_POST[f_url]; }

		$smr=str_replace("<","&lt;",$_POST[smr]);
		$smr=str_replace(">","&gt;",$_POST[smr]);

putPost($a_name, $a_email, $a_site, $_POST[title], $date, $fn2, $_POST[fmt], $_POST[keyw], $smr, $msg, "","");

global $send_ping,$s_title,$c_urls;

// Send piing to Weblogs.com if enabled
if($send_ping == "1") { send_ping($c_urls,$s_title); }

header("Location: admin.php?done=true"); exit();
}
	else {
echo <<<EOF
<i>Empty Fields!</i>
EOF;
exit();
	}
}

// The edit page.
// Load the data from the file and put it in the edit form

if($_GET[id] == "edit" && $_GET[f]) {

global $c_dir;

$dat=getSpost($_GET[f],"admin");
global $c_urls;
if(!count($dat)) { header("Location: $c_urls/admin.php?done=true") or scrpt("$c_urls/admin.php?done=true"); exit(); }

	$title=$dat[title];
	$date=$dat[date]; // Convert the timestamp to readable date
	$file=$dat[file];
	$frmt=$dat[format];
	$a_name=$dat[a_name];
	$a_email=$dat[a_email];
	$a_site=$dat[a_url];
	$keyw=$dat[keyws];
	$msg=$dat[data];
	$smr=$dat[summary];

if(!trim($title)) { header("Location: $c_urls/admin.php?done=true") or scrpt("$c_urls/admin.php?done=true"); exit(); }

		$smr=str_replace("&lt;","<",$smr);
		$smr=str_replace("&gt;",">;",$smr);

	// Show the values to be editted in the edit page
	$eda=@fread(fopen("edit.html", "r"), 1000000);
	$eda=str_replace("[TITLE]", stripslashes($title), $eda);
	$eda=str_replace("[MSG]", stripslashes($msg), $eda);
	$eda=str_replace("[FNM]", stripslashes($_GET[f]), $eda);
	$eda=str_replace("[FILE]", stripslashes($file), $eda);
	$eda=str_replace("[NAME]", stripslashes($a_name), $eda);
	$eda=str_replace("[EMAIL]", stripslashes($a_email), $eda);
	$eda=str_replace("[URL]", stripslashes($a_site), $eda);
	$eda=str_replace("[SMR]", stripslashes($smr), $eda);
	$eda=str_replace("[KEYW]", stripslashes($keyw), $eda);
	$eda=str_replace("[DATE]", trim(stripslashes($date)), $eda);

		if($frmt=="txt") { $eda=str_replace("[st]", "selected", $eda); }
		else { $eda=str_replace("[sh]", "selected", $eda); }
	ahdr("","");
	echo $eda; exit();
	aftr();
}


// Save the data to the file

if($_POST[act] == "edit") {
	if($_POST[title] && $_POST[msg]) {

	$msg=$_POST[msg];

		if(!$_POST[f_aname]) { $a_name="Anonymous"; } else { $a_name=$_POST[f_aname]; }
		if(!$_POST[f_email]) { $a_email="$admin"; } else { $a_email=$_POST[f_email]; }
		if(!$_POST[f_url]) { $a_site="None"; } else { $a_site=$_POST[f_url]; }

		$smr=str_replace("<","&lt;",$_POST[smr]);
		$smr=str_replace(">","&gt;",$_POST[smr]);

// Update Mysql fields
myUpdate($_POST[fnm], $a_name, $a_email, $a_site, $_POST[title], $date, $_POST[file], $_POST[fmt], $_POST[keyw], $smr, $msg, "","");

header("Location: admin.php?done=true"); exit();
}
else {
echo <<<EOF
<i>Empty Fields!</i>
EOF;
exit();
	}

}

//###############################################################################

// Edit

function editComments($fl) {
global $mnu;
	if(!$fl) { header("Location: admin.php?done=true&r=".rand(999,9999)); exit(); }
ahdr("BoastMachine: Edit comments",$mnu);

// Get all the comments into an array
$ar=getAllCmts($fl);

	if(!count($ar[a_name])) { header("Location: admin.php?done=true&r=".rand(999,9999)); exit(); }

echo <<<EOF
<font face="verdana" size="1">[ To delete a comment, just leave its 'Name' field empty ]</font>
<br><br><br>
EOF;
echo "<form method=\"POST\" action=\"$_SERVER[PHP_SELF]\">\n";

// Loop through the array and print the comments
for($m=0;$m<=count($ar[a_name]);$m++) {

if(trim($ar[a_name][$m])) {

$name=$ar[a_name][$m];
$email=$ar[a_email][$m];
$url=$ar[a_url][$m];
$date=$ar[date][$m];
$cmt=$ar[data][$m];

$n=$m;
// Comments edit form
echo <<<EOF
<table border="0" cellpadding="0" cellspacing="0" width="526">
<tr>
<td width="76">
<span class="t_small">Name</span>
</td>
<td width="450">
<input class="search" value="$name" type="text" name="name$n" size="28">
</td>
</tr>
<tr>
<td width="76">
<span class="t_small">Email</span>
</td>
<td width="450">
<input type="text" value="$email" class="search" name="email$n" size="28">
</td>
</tr>
<tr>
<td width="76">
<span class="t_small">URL</span>
</td>
<td width="450">
<input type="text" value="$url" class="search" name="url$n" value="http://" size="28">
</td>
</tr>
<tr>
<td width="76" valign="top">
<span class="t_small">Comments</span>
</td>
<td width="450">
<textarea name="comments$n" class="search" rows="10" cols="50">$cmt</textarea><br>
</td>
</tr>
</table>
<hr width="100%" color="gray" size="1">
EOF;

}
	}

echo <<<EOF

<input type="hidden" name="date" value="$date">
<input type="hidden" name="act" value="ecmts">
<input type="hidden" name="ext" value="$fl">
<input type="hidden" name="count" value="$m">
<input type="submit" value="    Save Changes    " class="search">
<input type="button" value="    DELETE ALL COMMENTS!    " class="search" onClick="javascript:document.location='admin.php?id=dlcmts&fnm=$fl';">
</form>
EOF;
aftr();
exit();
}


//###############################################################################

// Bakcup all data
function bkp() {
global $c_dir,$c_urls,$cmt_dir;

$dat=getPostList();

if(!count($dat)) {
ahdr("No data available for backup!!","");
echo <<<EOF
<p><font size="2" face="Verdana"><b>No data available for backup!</b></font></p><br><br><br>
<center>
<a href="admin.php?"><font size="2" face="Verdana"><b><< Back</b></font></a></center>
EOF;
aftr();
exit();
}

	// Create a unique backup filename
	$time=date("M_d_y__h-i-a");

	//Create a random string to separate the values in the $arr array
	for($x=0;$x<=4;$x++) {
	$a.=chr(rand(rand(65,75),90));
	}

	$a=md5($a); $a=substr($a,1,3); $a="($a)";

$bp=fopen("backup/$time","w+");
for($n=0;$n<=count($dat[title]);$n++) {

	//Save the data in a particular format to save the data into a backup file
	if($dat[title][$n]) {

$data=$dat[title][$n].$a.$dat[date][$n].$a.$dat[file][$n].$a.$dat[format][$n].$a.$dat[a_name][$n].$a.$dat[a_email][$n].$a.$dat[a_url][$n].$a.$dat[keyws][$n].$a.$dat[data][$n].$a.$dat[summary][$n];

	// Get all the comments into an array
$arr=getAllCmts($dat[id][$n]);

	if(count($arr)) {
		for($m=0;$m<=count($arr[title]);$m++) {
		$cdat=$arr[a_name][$m].$a.$arr[a_email][$m].$a.$arr[a_url][$m].$a.$arr[date][$m].$a.$arr[data][$m]."\n<!-- comment termination //-->\n";
		}
	}

$data=str_replace("|","(&#)",$data);
$data=str_replace("$a","||",$data);
$cdat=str_replace("|","(&#)",$cdat);
$cdat=str_replace("$a","||",$cdat);

// Backup file format
if($dat[ext1][$n] == "hide") { $j="[]"; } else { $j=""; }
$nm=$j.$dat[id][$n];
	$dt="{{{FN}}}$nm{{{FN}}}\n$data\n{{{FN}}}$cdat{{{FN}}}------FN-----\n";
	$w=fputs($bp,$dt);
	}
}

fclose($bp);
ahdr("Data backed up succesfully!","");
echo <<<EOF
<p><font size="2" face="Verdana"><b>$n</b> articles have been backed up successfully!<br><a href="backup/$time">Right 
Click here</a> and select </font><font size="2" face="Verdana" color="red">&quot;Save 
target as&quot;</font><font size="2" face="Verdana"> and save<br>the backup 
file to your local pc.</font></p>
<font size="2" face="Verdana"><b>Filename: $time</b></font><br>
<a href="$c_urls/backup/$time"><font size="2" face="Verdana"><b>$c_urls/$time</b></font></a><br>
<br><br>
<p><font size="2" face="Verdana">You will be able to upload the backup file 
and restore the<br>data later.</font></p><br><br><br><center>
<a href="admin.php"><font size="2" face="Verdana"><b><< Back</b></font></a></center>
EOF;
aftr();
exit();
}

//###############################################################################

// Restore data
function rstr() {
global $c_dir,$cmt_dir;
$userfile_name = $_FILES['file']['name'];
$userfile_tmp = $_FILES['file']['tmp_name'];

// Create temporary file
$fnme=md5($userfile_name);

	if (trim($userfile_name)) {
	move_uploaded_file($userfile_tmp, "./backup/$fnme");
	}
	else { header("Location: admin.php?id=bkprst"); exit(); }

$bdata=@fread(fopen("./backup/$fnme", "r"), 10000000);

	if(!strpos($bdata,"------FN-----")) {
	ahdr("Invalid Backup File!!","");
echo <<<EOF
<p><font size="2" color="red" face="Verdana"><b>
The file which you tried to upload is NOT A Valid BoastMachine backup file!</font><br><br><br><br>
<center>
<a href="admin.php?id=bkprst"><font size="2" face="Verdana"><b><< Back</b></font></a></center>
EOF;
	aftr();
	@unlink("backup/$fnme");
	exit(); 
	}

$bdata=explode("------FN-----",$bdata);

for($n=0;$n<=count($bdata)-1;$n=$n+1) {
list($na,$fname,$data,$cdat)=explode("{{{FN}}}",$bdata[$n]);
$fname=trim($fname);

	if($fname && trim($data) != "") {
	list($title,$date,$file,$format,$auth_name,$auth_email,$auth_url,$keyws,$data,$summary)=explode("||",$data);
	if(strpos($fname,"[]")) { $ext="hide"; } else { $ext=""; }
	$fname=str_replace("[]","",$fname);

	$data=str_replace("(&#)","|",$data);
	$summary=str_replace("(&#)","|",$summary);
	$title=str_replace("(&#)","|",$title);

	// Save the posts
	putPost($auth_name, $auth_email, $auth_url, $title, $date, $file, $format, $keyws, $summary, $data, $ext, "");

	}
}

// Delete the temp backup file
@unlink("backup/$fnme");
$n=$n-1;
ahdr("Data RESTORED succesfully!","");
echo <<<EOF
<p><font size="2" face="Verdana"><b>$n</b> articles have been RESTORED successfully!</font><br><br><br><br>
<center>
<a href="admin.php"><font size="2" face="Verdana"><b><< Back</b></font></a></center>
EOF;
aftr();
exit();
}

//#####################################################################

function doDat($in,$title,$date,$file,$ext1) {
	if(!trim($title) || !$in) { return; }

		if(!$file) { $filn="None"; } else {
		$tp=explode("-",$file);
		$tm=count($tp);
		$tp=$tp[$tm-1];
		$filn="<a href=\"files/$file\"><font color=\"#FF9800\">$tp</font></a>";
		}

	$num=getCmts($in);

	if(trim($ext1) == "hide")
	{ $clr="red"; $clr2="#CCCCCC"; $btg="b"; $str="[HIDDEN]"; $hd="Unhide"; } 	else { $str=""; $btg="na"; $clr="blue"; $hd="Hide"; }

$date=bmcDate($date);

$tb=<<<EOF
<li><p><a href="index.php?id=$in" target="_blank"><font color="$clr2"><$btg>$title $str</$btg></a></b></font><br>$date - Attachment : $filn  - <a href="admin.php?id=comments&fnm=$in"><u>Comments [$num]</u></a><br>
<a href="admin.php?id=fdel&f=$in"><img alt="Delete this post" src="images/admin_del.gif" border="0"></a>
<a href="admin.php?id=edit&f=$in"><img alt="Edit this post" src="images/admin_edit.gif" border="0"></a>
<a href="admin.php?id=$hd&f=$in"><img alt="Hide/Unhide this post" src="images/admin_hs.gif" border="0"></a>
</p></li>
EOF;
return $tb;

}

//###################################################################

function saveCmts() {
global $cmt_dir;

$n=$_POST[count];

$arr=array();

	//Create a random string to separate the values in the $arr array
	for($x=0;$x<=4;$x++) {
	$a.=chr(rand(rand(65,75),90));
	}

	$a=md5($a); $a=substr($a,1,3); $a="[$a]";

for($m=0;$m<=$n;$m++) {

	if(trim($_POST["name$m"]) && trim($_POST["comments$m"])) {
	$dat=str_replace("<","&lt;",$_POST["comments$m"]);
	$dat=str_replace(">","&gt;",$dat);
	$dat=trim($dat);

	array_push($arr,$_POST["name$m"].$a.$_POST["email$m"].$a.$_POST["url$m"].$a.$_POST["date$m"].$a.$dat);
	}

}

myCmtUpdate($_POST[ext],$arr,$a);
header("Location: admin.php?done=true");
}

//##################################################################

function postStatus($sts) {
if(!$_GET[f]) { return; }

if($sts == "hide") {
myUpdate($_GET[f], "", "", "", "", "", "", "", "", "", "", "hide", "");
header("Location: admin.php?done=true"); exit();
}

if($sts == "show") {
myUpdate($_GET[f], "", "", "", "", "", "", "", "", "", "", "show", "");
header("Location: admin.php?done=true"); exit();
}

}

//#############################################################

function delAll() {

delId("","all");

/////////////////////////////////////
	$handle = opendir("./files");
	while($filename = readdir($handle))
	{
	if( $filename != "." && $filename != ".." )
	@unlink("./files/$filename");
	}
	closedir( $handle );
/////////////////////////////////////

	header("Location: admin.php?done=true"); exit();
}

//####################################################################

function delPost() {
global $c_dir,$cmt_dir;

	if(!$_GET[f]) { return; }

$ar=getSpost($_GET[f],"admin");
delId($_GET[f],"");

	if(trim($ar[file])) {
	@unlink("files/$file");
	}

header("Location: admin.php?done=true"); exit();
}

//####################################################################

function getFileDat() {
$files = getPostList();

for($n=0;$n<=count($files[title]);$n++) {

$id=$files[id][$n];
$title=$files[title][$n];
$date=$files[date][$n];
$file=$files[file][$n];
$ext1=$files[ext1][$n];

if($id) { echo doDat($id, $title, $date, $file, $ext1); $pr="done"; }
}

if(!$pr) { echo "<font face=verdana color=red size=2><b>0 articles were found in the database!</b></font>"; }
}

?>