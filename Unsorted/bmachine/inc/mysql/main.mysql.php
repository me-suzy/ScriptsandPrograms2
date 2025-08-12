<?php
/*
BN Soft bMachine 2.7

Written by Kailash Nadh
http://bnsoft.net, support@bnsoft.net

bMachine - http://boastology.com
*/

//##########################################################
//####################################################################
//####################################################################
//####################################################################
//#################### MAIN SCRIPT FUNCTIONS #########################

if($valid_flag != "true") {
echo "ACCESS DENIED!"; exit();
}

function dfil () {
global $s_title,$fmt;

if($fmt != "full") { hdr($s_title,""); } 

$files=getFieldList("hd","id");

// 0 articles!
if(!count($files)) {
	echo "<center><br><br><font face=verdana size=1>0 articles in database!</font><br><br></center>";
	ftr("","");
	exit();
}

else {
	echo "<span class=\"t_small\">Total articles: ".count($files)."</span>";
	echo "<br><br>";
}

global $p_page;
$t=$p_page; $p=$_GET[p]; $nm=count($files);

if(!$p || $p<0 || $p>=$nm || !is_numeric($p)) { $p=0; }

if($t>$nm) { $t=$nm; }


for($b=$p;$b<=($p+$t)-1;$b++) {
	if($files[$b]) { echo doDat($files[$b]); }
}

// Create page numbers based on the number of articles
$k=$p+$t;

$k2=$k-$t-$t;
if($k2 < 0) { $k2=0; }
echo <<<eof
<br><a href="index.php?p=$k2"><span class="t_small">
<< Back</span></a>&nbsp;&nbsp;
eof;

echo <<<eof
<a href="index.php?p=$k"><span class="t_small">
Next >></span></a>
eof;


}

//#######################################################################

function doDat($in) {
global $clr,$font,$titl_clr, $fmt, $s_title,$c_dir,$m_cnv,$c_wrap,$m_vote, $m_send;

	if(!$in) { return; }

	$ar=getSpost($in);
	$title=$ar[title];
	$date=$ar[date];
	$file=$ar[file];
	$frmt=$ar[format];
	$a_name=$ar[a_name];
	$a_email=$ar[a_email];
	$a_site=$ar[a_url];
	$hde=$ar[ext1];
	$keyw=$ar[keyws];
	$msg=$ar[data];
	$smr=$ar[summary];
	$id=$in;

	$date=bmcDate($date); // Date conversion

// Check whether the format is TEXT or HTML
	if($frmt=="txt") {
	$pr="pre";
	$msg=wordwrap($msg,$c_wrap,"\n",1);
	}
	else { $pr="k"; }

	if(trim($file)) {
	$tp=explode("-",$file);
	$tm=count($tp);
	$tp=$tp[$tm-1];
	$fnm="<a href=\"files/$file\"><img align=\"absbottom\" border=\"0\" alt=\"Download Attached File [ $tp ]\" src=\"images/dl.gif\"></a>";
	} else {$fnm=""; }

if($fmt=="full" && !$title) {
hdr("No post was found with that id!","");
echo "<br><br><br><center><span class=\"t_small\"><font color=\"red\">No post was found with that id!</font></span></center>";
ftr("",""); exit();
}

	if($title && $date) {

// Article FULL view. Print out the whole thing
if($fmt=="full") {
hdr("$s_title > $title", $keyw);

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
<a href="index.php?id=$id"><span class="title">$title</span></a>
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
</select>&nbsp;&nbsp;&nbsp;
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

	if($frmt=="txt" && $m_cnv=="1") { $msg=cnvAll($msg); }

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
$smr=wordwrap($smr,$c_wrap,"\n");

$smr=str_replace(" ","&nbsp;",$smr);

// Print the summaries of all the articles.

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
if(!$rat || !$fid) { return; }

$ar=getSpost($fid);

$ct=$ar[ext2];

list($rating,$num)=explode("|",$ct);
$rating=trim($rating);
$num=trim($num);
$num=$num+1;
$rating=$rating+$rat;
$dat="$rating|$num";

	global $my_db,$my_host,$my_user,$my_pass,$my_prefix;
	$link = mysql_connect("$my_host","$my_user","$my_pass") or errd("MySQL Error!","Connection to MySQL server : $my_host failed!");
	mysql_select_db($my_db , $link) or errd("MySQL Error!","Couldn't open database $my_db !");

	$tbl=$my_prefix."posts";

	mysql_query(" UPDATE $tbl SET ext2='$dat' WHERE id='$fid'") or errd("MySQL error!","Cant write to table $tbl !");
	mysql_close($link);
header("Location: index.php?id=$fid&done=true");
exit();
}


function showrate($inid) {
$ar=getSpost($inid);
$ct=$ar[ext2];

	if($ct) {
	list($rate,$num)=explode("|",$ct);
	$rate=$rate/$num;
	$rate=substr($rate,0,4);
	return "$rate|$num";
	}

	else {
	return "5|1";
	}

}

//########## CHECK FOR FROZEN/HIDDEN ARTICLES ##########
function chkFrz($in) {
	global $c_dir;
	if(file_exists("$c_dir/[]$in")) { clearstatcache(); errd("Restricted!", "The article which you were trying to access has been<br>frozen by the Administrator."); }
	else { errd("Cant open file!", "Unable to open file in the data dir!"); }
}
?>