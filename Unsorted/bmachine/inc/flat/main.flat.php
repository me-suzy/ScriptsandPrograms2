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
//#################### MAIN SCRIPT FUNCTIONS #########################

// Check for unauthrorised script calls
if($valid_flag != "true") {
echo "ACCESS DENIED!"; exit();
}

// Get file list

function dfil () {
global $s_title,$c_dir;
$files = array();
$fn = array();
$directory="$c_dir/";

// Read all the files in the DATA folder
	$handle = opendir("$c_dir") or errd("\"$c_dir\" directory not found!", "\"$c_dir\" directory not found or is not writable!");
	$i=0;

while($filename = readdir($handle)) 
{
	if($filename != "." && $filename != ".." && substr($filename,0,2) != "[]") { 
	$files[$i] = array(filemtime($directory .$filename), $filename); 
	$i++; 
	} 
} 
	closedir($handle);

// Sort the files who are already in the "created date" order
arsort($files); 
reset($files); 

if($fmt != "full") { hdr($s_title,"");} 

// 0 articles!
if(count($files)==0) {
	echo "<center><br><br><font face=verdana size=1>0 articles in database!</font><br><br></center>";
	ftr("","");
	exit();
}

else {
	echo "<span class=\"t_small\">Total articles: ".count($files)."</span>";
	echo "<br><br>";
}

foreach($files as $name ) 
{ 
	array_push($fn, $name[1]);
}

global $p_page;
$t=$p_page; $p=$_GET[p]; $nm=count($fn);

if(!$p || $p<0 || $p>$nm || !is_numeric($p)) { $p=0; }


for($j=$p;$j<=($p+$t)-1;$j++) {
// Pass the current filename to doDat(). doDat() will read the contents of the file
// and will do all the parsing
echo doDat($fn[$j],"");
}


// Create page numbers based on the number of articles
$k=$p+$t;

if($k <= $nm) {
$k2=$k-$t-$t;
if($k2 <= 0) { $k2=0; }
echo <<<eof
<br><a href="index.php?p=$k2"><span class="t_small">
<< Back</span></a>&nbsp;&nbsp;&nbsp;
eof;

echo <<<eof
<a href="index.php?p=$k"><span class="t_small">
Next >></span></a>
eof;
}
else {
$k=$p-$t;
if($k2 > 0) {
echo <<<eof
<a href="index.php?p=$k"><span class="t_small">
<< Back</span></a>
eof;
}
}

}

//#######################################################################

// Print the article summaries or on the full page

function doDat($in,$nl="") {
global $clr,$font,$titl_clr, $fmt, $s_title,$c_dir, $c_wrap, $m_cnv;

	if(!$in) { return; }
	// Hidden article check
	if(strpos("--$in","[]")) { errd("Restricted!", "The article which you were trying to access has been<br>frozen by the Administrator."); }

$fp=@fread(@fopen("$c_dir/$in", "r"), 1000000) or chkFrz($in);

// Explode the contents of the file
list($title,$date,$file,$frmt, $a_name, $a_email, $a_site, $keyw, $msg, $smr) = explode("||", $fp);

$date=bmcDate($date);

// Check whether the format is TEXT or HTML
	if($frmt=="txt") {
	$msg=wordwrap($msg, $c_wrap,"\n",1);
	$msg=str_replace("(&#)","|",$msg);
	$smr=str_replace("(&#)","|",$smr);
	$pr="pre";

	$msg=str_replace("<","&lt;",$msg); // Clear off the HTML tags
	$msg=str_replace(">","&gt;",$msg);
	}
	else { $msg=str_replace("(&#)","|",$msg); $smr=str_replace("(&#)","|",$smr); $pr="k"; }

	if(trim($file)) {
	$tp=explode("-",$file);
	$tm=count($tp);
	$tp=$tp[$tm-1];
	$fnm="<a href=\"files/$file\"><img border=\"0\" alt=\"Download Attached File [ $tp ]\" src=\"images/dl.gif\"></a>";
	} else {$fnm=""; }


	if($title && $date) {

// Article FULL view. Print out the whole thing
if($fmt=="full") {
hdr("$s_title > $title", $keyw);

global $m_vote, $m_send;

if($m_vote) {
$rating=showrate($in);
list($rating,$num)=explode("|",$rating);
if(!$rating) { $rating=5; } if(!$num) { $num=1; }
}

if($m_vote) {
echo <<<EOF
<script>
<!--
function rate() {
var ix=frate.selectedIndex;
var vl=frate.options[ix].value;
if(vl != "") {
document.location="index.php?rate="+vl+"&f=$in";
}
}
//-->
</script>
EOF;
}

echo <<<EOF
<!-- [T] $s_title > $title [T] //-->
<!-- startprint -->
<table border="0" cellpadding="5" cellspacing="0" width="100%">
<tr>
<td width="532">
<table border="0" cellpadding="0" cellspacing="0" width="100%">
<tr>
<td width="70%" valign="top">
<a href="index.php?id=$in"><span class="title">$title</span></a>
<span class="aname">/ by </span>
<a href="mailto:$a_email"><span class="aname">$a_name</span></a><br>
<span class="date">Posted on $date</span>&nbsp;&nbsp;
EOF;

if($m_vote) {
echo <<<EOF
<span class="rating">Rating: $rating/5</span>&nbsp;&nbsp;
<span class="rating">Votes: $num</span>&nbsp;&nbsp;
</td>
<td valign="top" align="right">
<span class="rating">Rate:</span>
<select class="trate" name="frate" size="1" class="vote" OnChange="rate();">
<option value=""></option>
<option value="1">1</option>
<option value="2">2</option>
<option value="3">3</option>
<option value="4">4</option>
<option value="5">5</option>
</select>&nbsp;
EOF;
} else { print "</td><td width=\"30%\" valign=\"top\" align=\"right\">"; }

if($m_send) {
echo <<<EOF
&nbsp;<a href="mail.php?id=$in"><img src="images/em.gif" border="0" alt="Mail this article to a friend"></a>
EOF;
}

global $m_cmt;
if($m_cmt) {
echo <<<EOF
&nbsp;<a href="comments.php?id=$in"><img src="images/cmt.gif" alt="View/Add comments" border="0"></a>
EOF;
}

	$msg=stripslashes($msg);

	include_once "bbcode.php";

	$msg=bbcode($msg);
	$msg=smilify($msg);

	if($frmt=="txt") { $msg=cnvAll($msg); }

echo <<<EOF
&nbsp;<a href="index.php?print=y&pg=$in"><img src="images/print.gif" border="0" alt="Printer friendly page"></a>
$fnm
</td>
</tr>
</table>
</td>
</tr>
<tr>
<td width="532" valign="top" height="42">
<br><$pr><span class="content">$msg</span></$pr><br>
</td>
</tr>
</table>
<!-- stopprint -->
EOF;
ftr("","");
exit();
}

// Get the number of comments for an entry
global $m_cmt;
if($m_cmt == "1") { $num=getCmts($in);
$cmn=<<<EOF
&nbsp;<span class="t_small">|</span>&nbsp;<a href="comments.php?id=$in"><span class="aname"><font color="#FF6600">comments ($num)</font></span></a>&nbsp;$fnm
EOF;
}

// Print the summaries of all the articles.
$smr=stripslashes($smr);
$smr=wordwrap($smr,$c_wrap,"\n",1);
$smr=str_replace(" ","&nbsp;",$smr);

	$smr=str_replace("<","&lt;",$smr); // Clear off the HTML tags
	$smr=str_replace(">","&gt;",$smr);

$smr=nl2br($smr);

include_once "bbcode.php";
$smr=bbcode($smr);
$smr=smilify($smr);

if($m_cnv=="1") { $smr=cnvAll($smr); } // Auto convert urls

include "inc/templates/summary.inc.php"; // Load the summary template and display it

return $tb;
}
}

//##############################################################

//##################### RATING MODULE #########
function rateit($rat,$fid) {
global $c_dir;
if(!$rat) { return; }

// check file existence
if(!file_exists("$c_dir/$fid")) {
	clearstatcache();
	errd("Invalid Article ID!", "No article was found with that id!!");
}

// open the vote file.
// get the number of votes for the current id
// add the present vote to it and save

$ct= fopen("vote.txt", "r");
while (!feof($ct)) {
$cn = fgets($ct, 4096);

list($uid,$num,$rt) = explode("|",trim($cn));

if($uid == $fid) {
	$nm2=trim($num)+1;
	$rte=trim($rt)+$rat;
	$cn2="$uid|$nm2|$rte";
}
else {
	$dm.=trim($cn)."\n";
}


}
fclose ($ct);

if(!$cn2) {
	$azz = fopen("vote.txt", "a");
	$write = fputs($azz, "\n$fid|1|$rat");
	fclose($azz);
}

else {
	$azz = fopen("vote.txt", "w+");
	$write = fputs($azz, "$dm$cn2");
	fclose($azz);
}

}


// load the vote file. get the number of votes
// for the current entry and return it

function showrate($inid) {
	$dat=@fread(fopen("vote.txt", "r"), 1000000);
	$dat=explode("\n", $dat);

for($n=0;$n<=count($dat);$n=$n+1) {

	if(trim($dat[$n]) != "") {
	list($uid,$nm,$rt)=explode("|",$dat[$n]);
if(trim($uid) == trim($inid))
{
	$rate=trim($rt)/trim($nm);
	$rate=substr($rate,0,4);
	return("$rate|$nm");
}
	}
}
}

//########## CHECK FOR FROZEN/HIDDEN ARTICLES ##########
function chkFrz($in) {
	global $c_dir;
	if(file_exists("$c_dir/[]$in")) { clearstatcache(); errd("Restricted!", "The article which you were trying to access has been<br>frozen by the Administrator."); }
	else { errd("Cant open file!", "Unable to open file in the data dir!"); }
}

?>