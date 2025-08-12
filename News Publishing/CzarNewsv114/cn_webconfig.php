<?
$pagetitle = "CzarNews Configuration";
include("cn_auth.php");
include("cn_head.php");

### If user is not a "Config Admin" or an "Ultimate Admin", do not grant access
if($useri[config] != "on" && $useri[admin] != "on") {
	print E("You are not allowed to edit news configurations");
}

if($_REQUEST['op'] == "edit") {
	if($_POST['go'] == "true") {
		if($demo == "on") { print S("This would normally update and save the changes you have made in the configuration table."); }
		### Save changes into DB
		$sitename = addslashes($_POST['sitename']);
		$img_dir = $_POST['img_dir'];
		if(strrpos($img_dir, "/") != (strlen($img_dir)-1)) { $img_dir .= "/"; }
		$q[update] = mysql_query("UPDATE $t_conf SET sitename='$sitename', siteurl='$_POST[siteurl]', scripturl='$_POST[scripturl]', newslimit='$_POST[newslimit]', timezone='$_POST[timezone]', dateform='$_POST[dateform]', output='$_POST[output]', source='$_POST[source]', author='$_POST[author]', coms_text='$_POST[coms_text]', words='$_POST[words]', comments='$_POST[comments]', search='$_POST[search]', pages='$_POST[pages]', catbox='$_POST[catbox]', images='$_POST[images]', img_thumbw='$_POST[img_thumbw]', img_thumbh='$_POST[img_thumbh]', img_dir='$img_dir', img_maxsize='$_POST[img_maxsize]'", $link) or E("Could not update news configurations:<br>" . mysql_error());
		echo S("News configurations have been updated");
	}
}

// Define a few variables...
$servtime = (date("Z")/3600);
$tpath = $current_root;

// Set default configuration setttings
$defoutput = "<p><strong>{subject}</strong> Posted on {date} by {author}<br />
{news}<br />
{source}</p>";
$setsource = "<small>Source: <a href=\"{surl}\" target=\"_blank\">{sname}</a></small><br />";
$setauthor = "<a href=\"mailto:{aemail}\">{aname}</a>";
$setcoms = "View/Post Comment ({cnum})";

// Check if config settings already exist
$q[chk] = mysql_db_query($dbname,"SELECT COUNT(*) from $t_conf");
$confnum = mysql_result($q[chk],0,"COUNT(*)"); 

if($confnum != "1") {
	$q[del] = mysql_query("DELETE FROM $t_conf", $link) or E("Could not clear configuration settings:<br>" . mysql_error());
	$q[prep] = mysql_query("INSERT INTO $t_conf (sitename, siteurl, scripturl, newslimit, timezone, dateform, output, source, author, coms_text, version, words, comments, search, pages, catbox) VALUES ('', 'http://$_SERVER[HTTP_HOST]/', 'http://$_SERVER[HTTP_HOST]$rtpath', '15', '$servtime', 'M d, Y', '$defoutput', '$setsource', '$setauthor', '$setcoms', '$cnver', 'on', 'on', 'on', 'on', 'on')", $link) or E("Could not prepare configuration settings:<br>" . mysql_error());
}
$q[edit] = mysql_query("SELECT * FROM $t_conf LIMIT 1", $link) or E("Could not retieve configuration settings:<br>" . mysql_error());
$ev = mysql_fetch_array($q[edit], MYSQL_ASSOC);
?>

<form method="post" action="<? print $_SERVER['PHP_SELF']; ?>" name="theform">
<table  width="100%" border="0" cellspacing="1" cellpadding="2" align="center">
<tr><td nowrap>
<b>Site & News Settings</b>
</td><td>
<hr size="2" color="#000000" width="100%">
</td></tr>
<tr><td bgcolor="<? print $MenuBg2; ?>" nowrap>
Website Name:
</td><td bgcolor="<? print $MenuBg1; ?>">
<input type="text" name="sitename" size="25" class="input" value="<?=stripslashes($ev[sitename])?>">
</td></tr>
<tr><td bgcolor="<? print $MenuBg2; ?>" nowrap>
Website URL:
</td><td bgcolor="<? print $MenuBg1; ?>">
<input type="text" name="siteurl" size="45" class="input" value="<?=$ev[siteurl]?>">
</td></tr>
<tr><td bgcolor="<? print $MenuBg2; ?>" nowrap>
HTTP path to CzarNews:
</td><td bgcolor="<? print $MenuBg1; ?>">
<input type="text" name="scripturl" size="45" class="input" value="<?=$ev[scripturl]?>">
</td></tr>
<tr><td bgcolor="<? print $MenuBg2; ?>" nowrap>
News per page:
</td><td bgcolor="<? print $MenuBg1; ?>">
<input type="text" name="newslimit" size="3" class="input" value="<?=$ev[newslimit]?>">
</td></tr>
<tr><td bgcolor="<? print $MenuBg2; ?>" nowrap>
Timezone Offset:
</td><td bgcolor="<? print $MenuBg1; ?>">
<u>Current Server Time:</u><br>
GMT <? print (date("Z")/3600); ?> Hours<br>
<? print date("F j, Y, g:i a"); ?><br><br>
<u>Display News in:</u><br>
	<select name="timezone">
<? foreach($tzones as $t1 => $t2) { ?>
	<option value="<? print $t1; ?>"<?if($t1==$ev[timezone]){print " SELECTED";}?>><? print $t2 ?></option>
<? } ?>
	</select><br>
</td></tr>

<tr><td nowrap>
<b>Output Settings</b><br>
(<a href="news.php" target="_blank">View Current</a>)
</td><td>
<hr size="2" color="#000000" width="100%">
</td></tr>
<tr><td bgcolor="<? print $MenuBg2; ?>" nowrap>
Date Format:
</td><td bgcolor="<? print $MenuBg1; ?>">
<input type="text" name="dateform" size="15" class="input" value="<?=$ev[dateform]?>">
(Use PHP's <a href="http://www.php.net/manual/en/function.date.php" target="_blank">date()</a> format)
</td></tr>
<tr><td bgcolor="<? print $MenuBg2; ?>" nowrap>
Comment Link Text:<br>
{cnum} - # of coms
</td><td bgcolor="<? print $MenuBg1; ?>">
<input type="text" name="coms_text" size="30" class="input" value="<?=$ev[coms_text]?>">
("View/Post Comments" Text)
</td></tr>
<tr><td bgcolor="<? print $MenuBg2; ?>" nowrap>
Source Link:<br>
{sname} {surl}
</td><td bgcolor="<? print $MenuBg1; ?>">
<small>HTML format the source and author links will be made with</small>
<textarea cols="50" rows="2" name="source" class="input"><?=$ev[source]?></textarea>
</td></tr>
<tr><td bgcolor="<? print $MenuBg2; ?>" nowrap>
Author Link:<br>
{aemail} {aname}
</td><td bgcolor="<? print $MenuBg1; ?>">
<textarea cols="50" rows="2" name="author" class="input"><?=$ev[author]?></textarea>
</td></tr>
<tr><td bgcolor="<? print $MenuBg2; ?>">
Output Display:<br><br>
<small><u>Available Tags:</u></small><br>
{news} {subject} {author} {date} {source} {cat} {comments}<br>
</td><td bgcolor="<? print $MenuBg1; ?>">
<small>HTML format that each news item will be generated with</small>
<textarea cols="53" rows="6" name="output" class="input"><?=stripslashes($ev[output])?></textarea>
</td></tr>

<tr><td nowrap>
<b>Image Settings</b>
</td><td>
<hr size="2" color="#000000" width="100%">
</td></tr>
<tr><td bgcolor="<? print $MenuBg2; ?>" nowrap>
Image Admin:
</td><td bgcolor="<? print $MenuBg1; ?>">
<input type="checkbox" name="images" id="images"<? if($ev[images] == "on") { print " CHECKED"; } ?>> <label for="images">Use image admin and allow image uploads</label><br>
<small>(Requires at least GD Library 1.6, preferrably GD 2.0 - GD 2.0+ is bundled with PHP 4.3.0+)</small>
</td></tr>
<tr><td bgcolor="<? print $MenuBg2; ?>" nowrap>
Upload Directory:
</td><td bgcolor="<? print $MenuBg1; ?>">
<input type="text" name="img_dir" size="25" class="input" value="<?=$ev[img_dir]?>">
</td></tr>
<tr><td bgcolor="<? print $MenuBg2; ?>" nowrap>
Max Filesize:
</td><td bgcolor="<? print $MenuBg1; ?>">
<input type="text" name="img_maxsize" size="5" class="input" value="<?=$ev[img_maxsize]?>"> (in bytes)
</td></tr>
<tr><td bgcolor="<? print $MenuBg2; ?>" nowrap>
Thumbnail Width:
</td><td bgcolor="<? print $MenuBg1; ?>">
<input type="text" name="img_thumbw" size="4" class="input" value="<?=$ev[img_thumbw]?>" maxlength="4"> px
</td></tr>
<tr><td bgcolor="<? print $MenuBg2; ?>" nowrap>
Thumbnail Height:
</td><td bgcolor="<? print $MenuBg1; ?>">
<input type="text" name="img_thumbh" size="4" class="input" value="<?=$ev[img_thumbh]?>" maxlength="4"> px
</td></tr>

<tr><td nowrap>
<b>Display Options</b>
</td><td>
<hr size="2" color="#000000" width="100%">
</td></tr>
<tr><td bgcolor="<? print $MenuBg2; ?>" nowrap>
Additional Options:
</td><td bgcolor="<? print $MenuBg1; ?>">
<input type="checkbox" name="words" id="words"<? if($ev[words] == "on") { print " CHECKED"; } ?>> <label for="words">Use keywords/word filter for news/comments</label><br>
<input type="checkbox" name="comments" id="comments"<? if($ev[comments] == "on") { print " CHECKED"; } ?>> <label for="comments">Allow news comments</label><br>
<input type="checkbox" name="search" id="search"<? if($ev[search] == "on") { print " CHECKED"; } ?>> <label for="search">Display search box at bottom of news page</label><br>
<input type="checkbox" name="pages" id="pages"<? if($ev[pages] == "on") { print " CHECKED"; } ?>> <label for="pages">Display page numbers at bottom of news page</label><br>
<input type="checkbox" name="catbox" id="catbox"<? if($ev[catbox] == "on") { print " CHECKED"; } ?>> <label for="catbox">Display category selection box at top of news page</label><br>
</td></tr>
<tr><td bgcolor="<? print $MenuBg2; ?>">&nbsp;

</td><td bgcolor="<? print $MenuBg1; ?>">
<input type="hidden" name="op" value="edit">
<input type="hidden" name="go" value="true">
<input type="submit" name="submit" value="Save Settings" class="input">&nbsp;&nbsp;
<input type="button" name="cancel" value="Cancel" class="input" onClick="javascript:location.href='<?=$_SERVER['PHP_SELF']?>'">
</td></tr>
</table><br>
</form>

<?
include("cn_foot.php");
?>