<?
$pagetitle = "Code Generator";
include("cn_auth.php");
include("cn_head.php");

if($_POST['op'] == "gen") {
	$tspath = pathinfo($_SERVER['SCRIPT_FILENAME']);
$code = "&lt;?
\$tpath = \"" . $tspath["dirname"] . "/\";
";
if($_POST['cats'] == "sel") { $code .= "\$c = \"$_POST[cat]\";\n"; }
if($_POST['page'] == "headlines.php" && !empty($_POST['lim'])) { $code .= "\$lim = \"$_POST[lim]\";\n"; }
if($_POST['page'] == "headlines.php" && !empty($_POST['page'])) { $code .= "\$page = \"$_POST[page]\";\n"; }
$code .= "include(\$tpath . \"$_POST[page]\");
?&gt;";
	?>
	<form method="post" action="<? print $_SERVER['PHP_SELF']; ?>" name="theform">
	PHP Code:<br>
	<small>Copy and Paste the code below into your own PHP file</small><br>
	<textarea cols="60" rows="6" name="output" class="input"><? print $code; ?></textarea>
	</form>
	<?
}
?>

<p>This page will generate code for you to use on your website to include news on your page.  Simply select the category for the news you want to display, or select all categories.</p>

<form method="post" action="<? print $_SERVER['PHP_SELF']; ?>" name="theform">
<table  width="100%" border="0" cellspacing="1" cellpadding="2" align="center">
<tr><td nowrap>
<b>Generate Code</b><br>
</td><td>
<hr size="2" color="#000000" width="100%">
</td></tr>
<tr><td bgcolor="<? print $MenuBg2; ?>" nowrap>
Category:
</td><td bgcolor="<? print $MenuBg1; ?>">
<input type="radio" name="cats" value="all" id="allcats" CHECKED> <label for="allcats">Display news in ALL categories</label><br>
<input type="radio" name="cats" value="sel" id="selcats" onClick="openBox(1);"> <label for="selcats">Display news for selected category</label><br>
<div id="1" style="display: none">
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<? print cn_catBox("cat"); ?>
</div>
</td></tr>
<tr><td nowrap>
<b>Include Page</b>
</td><td>
<hr size="2" color="#000000" width="100%">
</td></tr>
<tr><td bgcolor="<? print $MenuBg2; ?>" nowrap>
Page:
</td><td bgcolor="<? print $MenuBg1; ?>">
<input type="radio" name="page" value="news.php" id="news" CHECKED> <label for="news">News Page (news.php)</label><br>
<input type="radio" name="page" value="headlines.php" id="head" onClick="openBox(2);"> <label for="head">Headlines (headlines.php)</label><br>
<div id="2" style="display: none">
<table border="0" cellpadding="0" cellspacing="0" summary=""><tr>
<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
<td>URL to your news page:</td>
<td><input type="text" name="pageurl" value="/index.php"></td>
</tr><tr>
<td>&nbsp;</td>
<td># of news items to display:&nbsp;</td>
<td><input type="text" name="lim" value="5" size="3"></td>
</tr>
</table>
</div>
</td></tr>

<tr><td bgcolor="<? print $MenuBg2; ?>">&nbsp;

</td><td bgcolor="<? print $MenuBg1; ?>">
<input type="hidden" name="op" value="gen">
<input type="submit" name="submit" value="Generate Code" class="input">
</td></tr>
</table><br>
</form>

<hr size="1" width="95%">
<span class="subtitle"></span>
<?
include("cn_foot.php");
?>