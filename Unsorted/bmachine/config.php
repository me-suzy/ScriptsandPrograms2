<?php

/*
********************************************

BN Soft bMachine 2.7

Written by Kailash Nadh
http://bnsoft.net, support@bnsoft.net

bMachine - http://boastology.com

********************************************
*/

//######################### EDIT THE FOLLOWING VARIABLES #################

$passw="admin";
	// Admin password

$db="text";
	// What storage method to use?
	// text - use flat files for storage
	// mysql - use MySQL database for storage

$c_name="Your name";
	// Your Full Name

$c_email="you@site.com";
	// Your Email address

$c_url="http://site.com";
	// Your Homepage

$c_urls="http://site.com/bmachine";
	// URL to the directory where the script has been installed
	// NO TRAILING SLASH AT THE END!! "/"

$s_title="My bogs";
	// Your bMachine page title

$s_desc="My blogs. you wont stop reading!";
	// Your bMachine page description [ Used for RSS feeds ]

$s_lang="en";
	// Your bMachine page Language [Used for RSS feeds]
	// eg: en for English, de for Deutch ..

$date_str="M d\\t\h (D) Y";
	// Date formatting string. Dont mess it if you have no idea :)
	// GOTO http://in.php.net/manual/en/function.date.php for detailed info

$send_ping="1";
	// Send pings to Weblogs.com blog list when your blog is updated?
	//  Set 1 to send, 0 to not to send

$p_page=50;
	// Articles to be shown per page

$x_wrap=15;
	// When displaying the titles of the last X posts,
	// if the titles are too long, they might wreck your page :)
	// So enter the number of characters at which the Titles
	// in the "Last x posts" should be truncated.

$c_wrap=85;
	// Number of characters to be wrapped while displaying
	// text posts. 85 is the normal character width for default template.
	// [this only takes place if a line is too long]
	// this is applicable for the summary, full article, comments

$m_cmt="1";
	// Allow readers to Post their comments on an article?
	// Set 1 to allow, 0 to disallow

$mcmt_ses="0";
	// Enable comment per session?
	// [If enabled, a user will be able to post a comment
	// only once per browser session]. Good to prevent flooding
	// Set 1 to allow, 0 to disallow

$m_vote="1";
	// Allow readers to rate an article. Disabling this will stop the
	// ratings of articles from being shown.
	// Set 1 to allow, 0 to disallow
	// FLOOD PREVENTION INCLUDED :)

$m_send="1";
	// Allow users to send the articles to their friends?
	// set 1 to allow, set 0 to disallow

$m_search="1";
	// Allow users to search articles
	// set 1 to allow, set 0 to disallow

$m_log="log.txt";
	// Enter a filename here to log all the mails send out from "Send to a friend"
	// eg: log.txt . If you leave it empty, the script wont log the details.
	// Be sure to create an empty file with the same name, upload it to
	// the same folder as all the scripts and CHMOD to 777

$m_cnv="1";
	// Do you want to automatically convert URLs and EMAILs to CLICKABLE
	// links while posting an article in Plain Text format??
	// UBB code is not necessary for this feature
	// 1 = Yes , 0 = No

$subject="[NAME] has asked you read this article!";
	// Subject of the mail if someone refers an article to his/her friends
	// [NAME] is automatically changed to the sender's name by the script


//######################### Flat files path configuration ###########
// Configure this only if you are using FlatFile storage

$c_dir="./data";
	// Path to the directory where the data files are to be stored.
	// NO TRAILING SLASH AT THE END!!! "/"
	// Change this to something else, for security reasons
	// eg: data-dir-5584
	// If you are changing this, be sure to create a direcotry
	// with that name and CHMOD to 777

$cmt_dir="./comments";
	// Path to the directory where the data files for comments are to be stored.
	// NO TRAILING SLASH AT THE END!!! "/"
	// Change this to something else, for security reasons
	// eg: comments-dir-5584
	// If you are changing this, be sure to create a directory
	// with that name and CHMOD to 777



//######################### MYSQL configuration #####################
// Configure this only if you are using MySQL storage

$my_host="localhost";
	// Your MYSQL server

$my_user="user";
	// Your MySQL username

$my_pass="pass";
	// Your MySQL password

$my_db="mysql";
	// Your MySQL database name

$my_prefix="bmc_";
	// MySQL tables prefix


//######################### DONOT EDIT ANYTHING BELOW! #####################
// DONOT TOUCH!

$valid_flag="true";

$ver="v 2.7";

// Prints the header
function hdr($title,$keys) {
include_once "inc/templates/header.php";
}

// Prints the footer
function ftr($na,$nl) {
include_once "inc/templates/footer.php";
}

// Display error messages
function errd($ttl, $desc) {
hdr($ttl,"error");
echo <<<EOF
<br><br>
<p align="center"><span class="t_err">$desc</span></p><br><br>
<br><br>
EOF;
ftr("","");
exit();
}
//######################## Search Box ###################
function srchFrm($key,$sl=0) {
?>
<form name="search" method="POST" action="search.php">
<table border="0" cellpadding="0" cellspacing="0" width="418" align="center">
<tr>
<td width="65" valign="top">
<span class="t_small">Search in</span>
</td>
<td width="344" valign="top">
<select name="item" size="1" class="search">
<option value="title">Title</option>
<option value="author">Author</option>
<option value="content">Content</option>
</select>
<input type="text" name="key" value="<? echo $key; ?>" class="search"> <input type="submit" value="Search" class="search">
</td>
</tr>
</table></form>
<script>
<!--
document.search.item[<? echo $sl; ?>].selected="true";
//-->
</script>
<?
}

//############ Smilify a given string :) #########
// Go through the smilies array and replace with appropriate <img> tags
function smilify($ind) {

// Open the smilies data file for parsing
$sm=@fread(fopen("smiles.txt", "r"), 100000);
$sm=explode("\n",$sm);

	for($pp=0;$pp<=count($sm);$pp++) {

		if(trim($sm[$pp])) {
		list($sml, $sgn) = explode("||", $sm[$pp]);
		$ind=str_replace(trim($sml), "<img src=smilies/".trim($sgn).">", $ind);
		}
	}

return $ind;
}

//###################### Return variable values for use in template ####
function sout($in) {
global $c_name,$c_email,$c_url,$s_title;
	if($in == "author_name") { return $c_name; }
	if($in == "author_email") { return $c_email; }
	if($in == "site_title") { return $s_title; }
	if($in == "site_url") { return $site_url; }

}

//####################### Auto Convert URLs & Emails ###############
function convertURLS($text) 
{   
	$text = preg_replace( "/(?<!<a href=\")((http|ftp)+(s)?:\/\/[^<>\s]+)/i", "<a href=\"\\0\">\\0</a>", $text );
    return $text; 
} 

function convertMail($text) 
{   $text = eregi_replace("([_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3}))", "<a href='mailto:\\0'>\\0</a>", $text); 
    return $text; 
} 

function cnvAll($text) 
{    
	$text = convertURLS($text); 
    $text = convertMail($text); 
    return $text; 
}

//######################## Weblogs.com Ping ##########################

function send_ping($url,$title) {
if(!trim($title) || !trim($url)) { return 0; }
$data=@fread(fopen("http://newhome.weblogs.com/pingSiteForm?name=$title&url=$url","r"),10);
if($data) { return 1; } else { return 0; }
}

//################### Simple yet powerful search function ############
// Searches the presense of a string, in another string

function srchString($p,$keys) {
$flag="";
for($n=0;$n<=count($keys)-1;$n++) {
	if(trim($keys[$n]) && strlen(trim($keys[$n])) >= 3) {
		if(strpos(strtolower("-- $p"),strtolower($keys[$n]))) {
		$flag="true";
		}
	}

	if($flag == "true") { return "true"; }
}

return 0;
}

// ########### Date conversion from Timestamp [total seconds] to readable one #########

function bmcDate($input="",$formt="") {
global $date_str;

	if(!$input) { $input=time(); }

	if(!$formt) {
	$time_h=date("h");
	$time_m=date("i");
	$time_s=date("s");

	$date_m=date("m");
	$date_d=date("d");
	$date_y=date("y");

	return date($date_str,$input);
	}

}
//#########################################################

// Misc function to do redirection if header("Location: xx") fails.
function scrpt($file) {
echo <<<EOF
<HTML><HEAD><TITLE>&nbsp;</TITLE></HEAD><BODY>
<script>
<!--
document.location="$file";
//-->
</script>
</BODY></HTML>
EOF;
exit();
}
//######### Include necessary config files ###############

if($db == "mysql") { include_once "inc/mysql/mysql.php"; } else { include_once "inc/flat/flat.php"; }

?>