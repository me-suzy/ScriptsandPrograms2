<?php

/*
********************************************

BN Soft bMachine 2.7

Written by Kailash Nadh
http://bnsoft.net, support@bnsoft.net

bMachine - http://boastology.com

********************************************
*/

/* comments.php - Commenting system for BoastMachine */


include_once "config.php";

if($db == "mysql") { include_once "inc/mysql/comments.mysql.php"; } else { include_once "inc/flat/comments.flat.php"; }

if($m_cmt == "0") { header("Location: index.php?cmt=false"); exit(); }

// Simple validation functions

if($_POST[cmt] == "true") {

	if(md5($_POST[fn]) != $_POST[file]) { errd("Error!","Access denied!"); }

// Check whether the user is posting more than 1 comment/session
if($mcmt_ses == "1") {
	if($_COOKIE[bmchine]) {
	errd("Error!","You can only post 1 comment per session!");
	}
}

saveData();

//Set a cookie to temporarily prevent comment flooding
setcookie("bmchine","commented");

header("Location: comments.php?id=$_POST[fn]&done=true"); exit();

}

$id=$_GET[id];
	if(!$id) { header("Location: index.php"); }


/////////////////////////////////

hdr("Post a comment","");
getSmry($_GET[id]); // Get the summary of the posts and also display the comments

cmtBox(); // Print the Comment page's text boxes
ftr("","");

/////////////////////////////////

// Print out the text boxes
function cmtBox() {
$fild_d=md5($_GET[id]);
echo <<<EOF
<form method="POST" action="$_SERVER[PHP_SELF]">
<input type="hidden" name="cmt" value="true">
<input type="hidden" name="fn" value="$_GET[id]">
<input type="hidden" name="file" value="$fild_d">
<table border="0" cellpadding="0" cellspacing="0" width="526">
<tr>
<td width="76">
<span class="t_small">Name</span>
</td>
<td width="450">
<input class="search" type="text" name="name" size="28">
</td>
</tr>
<tr>
<td width="76">
<span class="t_small">Email</span>
</td>
<td width="450">
<input type="text" class="search" name="email" size="28">
</td>
</tr>
<tr>
<td width="76">
<span class="t_small">URL</span>
</td>
<td width="450">
<input type="text" class="search" name="url" value="http://" size="28">
</td>
</tr>
<tr>
<td width="76" valign="top">
<span class="t_small">Comments</span>
</td>
<td width="450">
<textarea name="comments" class="search" rows="10" cols="50"></textarea><br>
<input type="submit" value="Submit" class="search">
</td>
</tr>
</table>
</form>
EOF;
}


?>