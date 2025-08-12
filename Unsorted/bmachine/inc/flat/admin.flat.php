<?php
/*
********************************************

BN Soft bMachine 2.7

Written by Kailash Nadh
http://bnsoft.net, support@bnsoft.net

bMachine - http://boastology.com

********************************************
*/

//####################################################################
//################# ADMIN FUNCTIONS !!         #######################

if($valid_flag != "true") {
echo "ACCESS DENIED!"; exit();
}

function saveData() {
global $c_dir;
	// Replace the | character!
	// The whole data file depends upon the || character
	// Donot allow it in the message

	// But when an article is viewed, replace (&#), with |
	// so that the user sees it normal

	$msg=str_replace("|","(&#)",$_POST[msg]);
	$smr=str_replace("|","(&#)",$_POST[smr]);
	if($_POST[title] && $_POST[msg]) {

	$date=time(); // We need only the timestamp in seconds. Human readable can be generated from this

	// Replace the | character!
	// The whole data file depends upon the || character
	// Donot allow it in the message

	// But when an article is viewed, replace (&#), with |
	// so that the user sees it normal

	$msg=str_replace("|","(&#)",$_POST[msg]);
	$smr=str_replace("|","(&#)",$_POST[smr]);


// Check whether HTML or TEXT
		if($_POST[fmt] == "txt") {
		$msg=str_replace("<","&lt;",$_POST[msg]);
		$msg=str_replace(">","&gt;",$_POST[msg]);
		}
		else { $msg=$_POST[msg]; }

$uid=uniqid(md5(rand(1,9999)));
$uid=md5($uid);
$cnt=rand(5,10);
$uid=substr($uid,strlen($uid)-$cnt,strlen($uid));
$fn=$uid;

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

// Get data from form, format it, save it to an encoded file
	if(!$_POST[f_aname]) { $a_name="Anonymous"; } else { $a_name=$_POST[f_aname]; }
	if(!$_POST[f_email]) { $a_email="$admin"; } else { $a_email=$_POST[f_email]; }
	if(!$_POST[f_url]) { $a_site="None"; } else { $a_site=$_POST[f_url]; }

$tmp="$_POST[title]:$fn2:$_POST[fmt]:$a_name:$a_email:$a_site:$_POST[keyw]";

	if(strpos($tmp,"|")) {
	errd("Restricted character!", "You are not allowed to use the character | in any of the fields except Content and Summary!");
	}

$data=<<<EOF
$_POST[title]||$date||$fn2||$_POST[fmt]||$a_name||$a_email||$a_site||$_POST[keyw]||$msg||$smr
EOF;
global $c_dir;
$a = fopen("$c_dir/$fn", "w+") or errd("Cannot write to \"$c_dir\" dir!", "Unable to write to the \"$c_dir\" dir!<br>Please check whether the directory exists or its permission is 777");
$write = fputs($a, "$data");
fclose($a);

global $send_ping,$s_title,$c_urls;
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
	if(file_exists("$c_dir/$_GET[f]")) {
	clearstatcache();
	$fp=@fread(@fopen("$c_dir/$_GET[f]", "r"), 1000000) or errd("Cant Read data!", "The data file with id \"$_GET[f]\" was not found!");
	list($title,$date,$file,$frmt, $a_name, $a_email, $a_site, $keyw, $msg, $smr) = explode("||", $fp);
	$msg=str_replace("(&#)","|",$msg);
	$smr=str_replace("(&#)","|",$smr);
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
	$eda=str_replace("[DATE]", $date, $eda);

		if($frmt=="txt") { $eda=str_replace("[st]", "selected", $eda); }
		else { $eda=str_replace("[sh]", "selected", $eda); }

	ahdr("Edit Post: $title");
	echo $eda;
	aftr();
	exit();
	}
	header("Location: admin.php?done=true"); exit();
}


// Save the data to the file

if($_POST[act] == "edit") {
	if($_POST[title] && $_POST[msg]) {

	$msg=str_replace("|","(&#)",$_POST[msg]);
	$smr=str_replace("|","(&#)",$_POST[smr]);

	$msg=$_POST[msg];

		if(!$_POST[f_aname]) { $a_name="Anonymous"; } else { $a_name=$_POST[f_aname]; }
		if(!$_POST[f_email]) { $a_email="$admin"; } else { $a_email=$_POST[f_email]; }
		if(!$_POST[f_url]) { $a_site="None"; } else { $a_site=$_POST[f_url]; }

	$smr=$_POST[smr];

	$tmp="$_POST[title]:$fn2:$_POST[fmt]:$a_name:$a_email:$a_site:$_POST[keyw]";

		if(strpos($tmp,"|")) {
		errd("Restricted character!", "You are not allowed to use the character | in any of the fields except Content and Summary!");
		}


$data=<<<EOF
$_POST[title]||$_POST[date]||$_POST[file]||$_POST[fmt]||$a_name||$a_email||$a_site||$_POST[keyw]||$msg||$smr
EOF;
global $c_dir;
$a = fopen("$c_dir/$_POST[fnm]", "w+") or errd("Cannot write to \"$c_dir\" dir!", "Unable to write to the \"$c_dir\" dir!<br>Please check whether the directory exists and its permission is 777");
$write = fputs($a, "$data");
fclose($a);
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
global $cmt_dir,$mnu;
	if(!file_exists("$cmt_dir/$fl")) { clearstatcache(); header("Location: admin.php?done=true"); exit(); }
ahdr("BoastMachine: Edit comments",$mnu);

$data=@fread(fopen("$cmt_dir/$fl","r"),1000000);
$data=trim($data);
$data=explode("<!-- comment termination //-->",$data);
echo <<<EOF
<font face="verdana" size="1">[ Do delete a comment, just leave its 'Name' field empty ]</font>
<br><br><br>
EOF;
echo "<form method=\"POST\" action=\"$_SERVER[PHP_SELF]\">\n";

for($n=0;$n<=count($data)-1;$n++) {
	if(trim($data[$n])) {
	list($name,$email,$url,$date,$cmt)=explode("||",$data[$n]);
	$cmt=str_replace("(&#)","|",$cmt);

echo <<<EOF
<table border="0" cellpadding="0" cellspacing="0" width="526">
<tr>
<td width="76">
<input type="hidden" name="date$n" value="$date">
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

<input type="hidden" name="act" value="ecmts">
<input type="hidden" name="fl" value="$fl">
<input type="hidden" name="count" value="$n">
<input type="submit" value="    Save Changes    " class="search">
<input type="button" value="    DELETE ALL COMMENTS!    " class="search" onClick="javascript:document.location='admin.php?id=dlcmts&fnm=$fl';">
</form>
EOF;
aftr();
exit();
}


//###############################################################################

// Create Backup files

function bkp() {
global $c_dir,$c_urls,$cmt_dir;
$directory="$c_dir/";

$handle = opendir("$c_dir") or errd("Cannot find the data dir!", "Cant open the \"$c_dir\" dir!"); 
$i=0;

while($filename = readdir($handle)) 
{
	if($filename != "." && $filename != ".." && $filename != "") { 
	$files[$i] = array(filemtime($directory .$filename), $filename); 
	$i++; 
	} 
} 

if(!count($files)) {
ahdr("No data available for backup!!","");
echo <<<EOF
<p><font size="2" face="Verdana"><b>No data available for backup!</b></font></p><br><br><br>
<center>
<a href="admin.php?"><font size="2" face="Verdana"><b><< Back</b></font></a></center>
EOF;
aftr();
exit();
}

arsort($files); 
reset($files); 
$fn=array();

	foreach($files as $name ) 
	{ 
	array_push($fn, $name[1]);
	}
	$time=date("M_d_y__h-i-a");

$bp=fopen("backup/$time","w+");

for($n=0;$n<=count($fn)-1;$n=$n+1) {
$data=@fread(fopen("$c_dir/$fn[$n]", "r"),1000000);
$cdat=@fread(@fopen("$cmt_dir/$fn[$n]", "r"),1000000);

	//Save the data in a particular format to save the data into a backup file
	if($fn[$n]) {
	$dt="{{{FN}}}$fn[$n]{{{FN}}}\n$data\n{{{FN}}}$cdat{{{FN}}}------FN-----\n";
	$w=fwrite($bp,$dt);
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

// Restore data from backup files

function rstr() {
global $c_dir,$cmt_dir;
$userfile_name = $_FILES['file']['name'];
$userfile_tmp = $_FILES['file']['tmp_name'];

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
list($na,$fname,$data,$cmtdata)=explode("{{{FN}}}",$bdata[$n]);
$fname=trim($fname);

	if($fname && trim($data) != "") {

	$tp=fopen("$c_dir/$fname","w+");
	$k=fputs($tp,trim($data));
	fclose($tp);

		if(strlen(trim($cmtdata)) > 5) {
		$tp=fopen("$cmt_dir/$fname","w+");
		$k=fputs($tp,$cmtdata);
		fclose($tp);
		}
	}
}

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

// Display the articles, neatly formatted

function doDat($in) {
	if(!$in) { return; }
global $c_dir;
	if(substr($in,0,2) == "[]") { $hd="Unhide"; $btg="b"; $str="[HIDDEN]"; $clr="red"; $clr2="#CCCCCC"; } else { $hd="Hide"; $clr="blue"; $btg="na"; $str=""; }

	$fpr=@fopen("$c_dir/$in", "r"); $fp=fgets($fpr); fclose($fpr) or  errd("Cannot read Data!", "Unable to read the file \"$in\" from the data dir!");

	list($title,$date,$file, $nn) = explode("||", $fp);

	$date=bmcDate($date); // Date conversion

		if($title && $date) {
		if(!$file) { $filn="None"; } else {
		$tp=explode("-",$file);
		$tm=count($tp);
		$tp=$tp[$tm-1];
		$filn="<a href=\"files/$file\"><font color=\"#FF9800\">$tp</font></a>";
		}

	$num=getCmts(str_replace("[]","",$in));

$tb=<<<EOF
<li><p><a href="index.php?id=$in" target="_blank"><font color="$clr2"><$btg>$title $str</$btg></a></b></font><br>$date - Attachment : $filn  - <a href="admin.php?id=comments&fnm=$in"><u>Comments [$num]</u></a><br>
<a href="admin.php?id=fdel&f=$in"><img alt="Delete this post" src="images/admin_del.gif" border="0"></a>
<a href="admin.php?id=edit&f=$in"><img alt="Edit this post" src="images/admin_edit.gif" border="0"></a>
<a href="admin.php?id=$hd&f=$in"><img alt="Hide/Unhide this post" src="images/admin_hs.gif" border="0"></a>
</p></li>
EOF;
return $tb;
	}
}

//###################################################################

// Save the editted comments
function saveCmts() {
global $cmt_dir;
$a = fopen("$cmt_dir/$_POST[fl]", "w+") or errd("Cannot write to \"$c_dir\" dir!", "Unable to write to the \"$c_dir\" dir!<br>Please check whether the directory exists or its permission is 777");
for($m=0;$m<=$_POST[count];$m++) {

	if(trim($_POST["name$m"]) && trim($_POST["comments$m"])) {
	$dat=str_replace("<","&lt;",$_POST["comments$m"]);
	$dat=str_replace(">","&gt;",$dat);
	$dat=str_replace("|","(&#)",$dat);
	$dat=trim($dat);

	$write = fputs($a, $_POST["name$m"]."||".$_POST["email$m"]."||".$_POST["url$m"]."||".$_POST["date$m"]."||".$dat."\n<!-- comment termination //-->\n");
	}

}
	fclose($a);
header("Location: admin.php?done=true"); exit();
}

//##################################################################

// Hide/Unhide articles
function postStatus($st) {
global $c_dir;
if($st == "hide") {
	if(!file_exists("$c_dir/$_GET[f]")) { clearstatcache(); errd("Can't find the file!", "Unable to open the file with the id \"$_GET[f]\""); }
rename("$c_dir/$_GET[f]","$c_dir/[]$_GET[f]");
header("Location: admin.php?bn=CrtEx00f".rand()); exit();
}

if($st == "show") {
	if(!file_exists("$c_dir/$_GET[f]")) { clearstatcache(); errd("Can't find the file!", "Unable to open the file with the id \"$_GET[f]\""); }
$tmp_fn=str_replace("[]","",$_GET[f]);
rename("$c_dir/$_GET[f]","$c_dir/$tmp_fn");
header("Location: admin.php?bn=CrtEx00f".rand()); exit();
}

}

?>