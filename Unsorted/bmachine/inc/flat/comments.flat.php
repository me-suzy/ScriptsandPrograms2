<?php

/*
********************************************

BN Soft bMachine 2.7

Written by Kailash Nadh
http://bnsoft.net, support@bnsoft.net

bMachine - http://boastology.com

********************************************
*/


//######################################

if($valid_flag != "true") {
echo "ACCESS DENIED!"; exit();
}

if(!file_exists("$c_dir/$id")) {
clearstatcache();
header("Location: index.php");
}

// Check, validate, parse and save the data into the comments data file
function saveData() {

if($_POST[file] != md5($_POST[fn])) {
errd("Permission denied!","Posting data from an Un-Authorized page is forbidden!");
}

global $cmt_dir;

	if(!trim($_POST[name])) { errd("Empty fields!","Empty \"name\" field!<br>Please go back and correct!"); }
	if(!trim($_POST[email])) { errd("Empty fields!","Empty \"email\" field!<br>Please go back and correct!"); }
	if(!trim($_POST[comments])) { errd("Empty fields!","Empty \"comments\" field!<br>Please go back and correct!"); }
	if(!strpos(trim($_POST[email]),"@")) { errd("Empty fields!","Invalid email!<br>Please go back and correct!"); }
	if(!strpos(trim($_POST[email]),".")) { errd("Empty fields!","Invalid email!<br>Please go back and correct!"); }

$dat=$_POST[comments];
$dat=str_replace("<","&lt;",$dat);
$dat=str_replace(">","&gt;",$dat);
$date=time();

$out="$_POST[name]||$_POST[email]||$_POST[url]||$date||$dat\n<!-- comment termination //-->\n";
$out=trim($out);

	$a = fopen("$cmt_dir/$_POST[fn]", "a") or errd("Cannot write to \"$cmt_dir\" dir!", "Unable to write to the \"$cmt_dir\" dir!<br>Please check whether the directory exists or its permission is 777");
	$write = fputs($a, "$out\n");
	fclose($a);
}

/////#######################################################

// Get the summary and also the comments of an entry and print them
function getSmry() {
global $c_dir,$cmt_dir,$m_cnv,$c_wrap;
$fp=@fread(fopen("$c_dir/$_GET[id]","r"),100000);

list($title,$date,$file,$frmt, $a_name, $a_email, $a_site, $keyw, $msg, $smr) = explode("||", $fp);
$smr=str_replace("|-","",$smr);
$smr=str_replace("(&#)","|",$smr);

$date=bmcDate($date);

	$smr=str_replace("<","&lt;",$smr); // Clear off the HTML tags
	$smr=str_replace(">","&gt;",$smr);


$smr=wordwrap($smr,$c_wrap,"\n",1);

// parse the smilies, bbcode and autolinks in the summary

include_once "bbcode.php";
$smr=bbcode($smr);
$smr=smilify($smr);

if($m_cnv == "1") {
$smr=cnvAll($smr);
}

echo <<<EOF
<table border="0" cellpadding="0" cellspacing="0" width="100%">
<tr><td width="449" valign="center">
<a href="index.php?id=$in"><span class="title">$title</span></a><span class="date"> [ $date ] 
[ by: <a href="mailto:$a_email">$a_name</a> ]</span>
</td></tr><tr><td width="100%">
<pre><span class="content">$smr
</span></pre></td></tr></table><hr width="50%" align="left" size="1" color="gray">
EOF;

if(!file_exists("$cmt_dir/$id")) { clearstatcache(); return; }

$cmts=@fread(fopen("$cmt_dir/$_GET[id]","r"),100000);
$cmts=trim($cmts);
$cmts=explode("<!-- comment termination //-->",$cmts);
// explode the file into an array using the comment termination string


// Loop through each line in the file and print out the contents
for($n=0;$n<=count($cmts);$n++) {
$dt=$cmts[$n];
if(trim($dt)) {
list($name,$email,$url,$date,$cm)=explode("||",$dt);

// The restricted character | , is being converted back from the safe form
$cm=str_replace("(&#)","|",$cm);

	$cm=str_replace("<","&lt;",$cm); // Clear off the HTML tags
	$cm=str_replace(">","&gt;",$cm);

	$name=str_replace("<","&lt;",$name); // Clear off the HTML tags
	$name=str_replace(">","&gt;",$name);

	$email=str_replace("<","&lt;",$email); // Clear off the HTML tags
	$email=str_replace(">","&gt;",$email);


$date=bmcDate($date); //Date

// Warp long sentences
$cm=wordwrap($cm,$c_wrap,"\n",1);

if($m_cnv == "1") { $cm=cnvall($cm); }

$cm=smilify($cm);

echo <<<EOF
<table border="0" cellpadding="0" cellspacing="0">
<tr>
<td width="532">
<span class="t_small">Posted by </span><a href="mailto:$email"><span class="t_small">$name</span></a>&nbsp;
<span class="t_small">on $date</span>
EOF;

	$url=str_replace("<","&lt;",$url); // Clear off the HTML tags
	$url=str_replace(">","&gt;",$url);

if(trim($url) && $url !="http://") { echo "&nbsp;&nbsp;<a href=\"$url\"><span class=\"t_small\">[www]</span></a>"; }

echo <<<EOF
</td>
</tr>
<tr>
<td width="532">
<pre><span class="t_small">$cm</span></pre>
</td>
</tr>
</table>
<hr width="50%" align="left" size="1" color="gray">
EOF;
}
}
echo <<<EOF
<br><span class="content"><b>Post a comment</b></span>
EOF;
}
/////////////////////////////////

?>