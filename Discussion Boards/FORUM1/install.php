<HTML>
<HEAD>
<TITLE>Forum Installer</TITLE>
</HEAD>
<BODY>
<CENTER>
<FONT FACE="verdana">
<?php
@ $forum2 = $_POST['forum'];
@ $u2 = $_POST['u2'];
@ $pass = $_POST['pass'];
@ $name = $_POST['name'];
if(empty($forum2) || empty($u2) || empty($pass) || empty($name)) {
?>
<STRONG>Forum Installer</STRONG>
<FORM ACTION="install.php" METHOD="post">
Directory to install to: <INPUT TYPE="text" NAME="forum"> (To install into this directory, just put "." into this field)<BR>
Forum name: <INPUT TYPE="text" NAME="name"><BR>
Admin username: <INPUT TYPE="text" NAME="u2"><BR>
Admin password: <INPUT TYPE="password" NAME="pass"><BR>
<INPUT TYPE="submit" VALUE="Install!">
</FORM>
<?php
exit;
}
if(file_exists($forum."/index.php")) {
echo "Please choose a different directory to install into.";
exit;
}
echo "Copying files...<BR>";
echo "This can take a few minutes so please be patient.<BR>";

@ mkdir($forum2);

$f1 = fopen($forum2."/config.php", "wb");
fwrite($f1, '<?php $username = "'.$u2.'"; $password = "'.$pass.'"; ?>');
fclose($f1);

$f12 = fopen($forum2."/title.txt", "wb");
fwrite($f12, $name);
fclose($f12);

$admin = <<<AMFRADMIN
<HTML>
<HEAD>
<TITLE>Control Panel</TITLE>
</HEAD>
<BODY BGCOLOR="<?php include('bg.txt'); ?>" LINK="black" ALINK="black" VLINK="black">
<CENTER>
<H2>Control Panel</H2><BR>
<?php
include('config.php');
@ \$u = \$_POST['u'];
@ \$p = \$_POST['p'];
if(\$u == \$username && \$p == \$password) {
?>
<STRONG>Change Forum Options:</STRONG><BR>
<FORM ACTION="save3.php" METHOD="post">
Forum title: <INPUT TYPE="text" NAME="t" VALUE="<?php include('title.txt'); ?>"><BR>
Forum background color (<A HREF="http://webmonkey.wired.com/webmonkey/reference/color_codes/" TARGET=_blank>Hexidecimal</A>): <INPUT TYPE="text" NAME="color" VALUE="<?php include('bg.txt'); ?>"><BR>
Topics listing/Post display color (<A HREF="http://webmonkey.wired.com/webmonkey/reference/color_codes/" TARGET=_blank>Hexidecimal</A>): <INPUT TYPE="text" NAME="color2" VALUE="<?php include('bg2.txt'); ?>"><BR>
<INPUT TYPE="hidden" NAME="u" VALUE="<?php echo \$username; ?>">
<INPUT TYPE="hidden" NAME="p" VALUE="<?php echo \$password; ?>">
<INPUT TYPE="submit" VALUE="Change Forum Options">
</FORM>
<STRONG>Post Sticky:</STRONG><BR>
How to use BBcode:<BR>
[B] - Bold text [/B] - End of bold text [I] - Italic text [/I] - End of italic text [U] - Underlined text [/U] - End of underlined text<BR>
Links:<BR>
[LINK="URL"]Text to display[/LINK]<BR>
<FORM ACTION="ps.php" METHOD="post">
Your name: <INPUT TYPE="text" NAME="name"><BR>
Title: <INPUT TYPE="text" NAME="title"><BR>
Content: <TEXTAREA COLS="100" ROWS="20" NAME="content"></TEXTAREA><BR>
<INPUT TYPE="hidden" NAME="u" VALUE="<?php echo \$username; ?>">
<INPUT TYPE="hidden" NAME="p" VALUE="<?php echo \$password; ?>">
<INPUT TYPE="submit" VALUE="Post Sticky">
</FORM>
<?php
} else {
?>
<B>Please login:</B><BR>
<FORM ACTION="admin.php" METHOD="post">
Username: <INPUT TYPE="text" NAME="u"><BR>
Password: <INPUT TYPE="password" NAME="p"><BR>
<INPUT TYPE="submit" VALUE="Login">
</FORM>
<?php
}
?>
</CENTER>
</BODY>
</HTML>
AMFRADMIN;

$f2 = fopen($forum2."/admin.php", "wb");
fwrite($f2, $admin);
fclose($f2);

$bg = "lightgrey";

$f2 = fopen($forum2."/bg.txt", "wb");
fwrite($f2, $bg);
fclose($f2);

$bg2 = "orange";

$f3 = fopen($forum2."/bg2.txt", "wb");
fwrite($f3, $bg2);
fclose($f3);

$cat = "<CATEGORIES>";

$f3 = fopen($forum2."/cat.txt", "wb");
fwrite($f3, $cat);
fclose($f3);

$forum = <<<AMFRTHISFORUM
<HTML>
<HEAD>
<TITLE><?php
\$forum = \$_REQUEST['forum'];
echo \$forum;
?></TITLE>
<STYLE>
td{color: black; border: dashed}
</STYLE>
<BASEFONT FACE="verdana">
</HEAD>
<BODY BGCOLOR="<?php include('bg.txt'); ?>" LINK="black" ALINK="black" VLINK="black">
<CENTER><H1><?php include('title.txt'); ?>: <?php echo \$forum; ?></H1><BR>
<TABLE WIDTH="400" BGCOLOR="<?php include('bg2.txt'); ?>">
<?php
\$f2 = \$forum.'.txt';
@ \$ad = fopen(\$f2, 'rb');
@ \$adisplay = fread(\$ad, filesize(\$f2));
@ fclose(\$ad);
echo \$adisplay;
?>
</TABLE>
<BR>
<FORM ACTION="save2.php" METHOD="post">
<B>Reply:</B><BR>
How to use BBcode:<BR>
[B] - Bold text [/B] - End of bold text [I] - Italic text [/I] - End of italic text [U] - Underlined text [/U] - End of underlined text<BR>
Links:<BR>
[LINK="URL"]Text to display[/LINK]<BR>
Name: <INPUT TYPE="text" NAME="name"><BR>
Reply: <TEXTAREA NAME="reply" NAME="reply" COLS="30" ROWS="4"></TEXTAREA><BR>
<INPUT TYPE="hidden" NAME="t" VALUE="<?php echo \$forum; ?>">
<INPUT TYPE="submit" VALUE="Reply">
</FORM>
<A HREF="index.php">Back to forum home</A>
</CENTER>
</BODY>
</HTML>
AMFRTHISFORUM;

$f4 = fopen($forum2."/forum.php", "wb");
fwrite($f4, $forum);
fclose($f4);

$index = <<<AMFRINDEX
<HTML>
<HEAD>
<TITLE><?php include('title.txt'); ?></TITLE>
<STYLE TYPE="text/css">
td{color: black; border: dashed}
</STYLE>
<BASEFONT FACE="verdana">
</HEAD>
<BODY BGCOLOR="<?php include('bg.txt'); ?>" LINK="black" ALINK="black" VLINK="black">
<CENTER>
<H1><?php include('title.txt'); ?></H1><BR>
<A HREF="new.php">New Topic</A> | <A HREF="admin.php">Control Panel</A><BR><BR>
<TABLE WIDTH="400" BGCOLOR="<?php include('bg2.txt'); ?>">
<?php include('sticky.txt'); ?>
</TABLE>
<TABLE WIDTH="400" BGCOLOR="<?php include('bg2.txt'); ?>">
<?php
\$file1 = fopen('last.txt', 'rb');
\$contents1 = fread(\$file1, filesize('last.txt'));
fclose(\$file1);
echo \$contents1;
?>
<TR>
<TD WIDTH="200">
<H3>Topics:</H3>
</TD>
</TR>
<?php
\$file = fopen('cat.txt', 'rb');
\$contents = fread(\$file, filesize('cat.txt'));
fclose(\$file);
echo \$contents;
?>
</TABLE><BR>
<A HREF="new.php">New Topic</A> | <A HREF="admin.php">Control Panel</A>
</CENTER>
</BODY>
</HTML>
AMFRINDEX;

$f5 = fopen($forum2."/index.php", "wb");
fwrite($f5, $index);
fclose($f5);

$last = "<LASTPOST>";

$f6 = fopen($forum2."/last.txt", "wb");
fwrite($f6, $last);
fclose($f6);

$new = <<<AMFRNEW
<HTML>
<HEAD>
<TITLE>New Topic</TITLE>
<STYLE TYPE="text/css">
td{color: black; border: dashed}
</STYLE>
<BASEFONT FACE="verdana">
</HEAD>
<BODY BGCOLOR="<?php include('bg.txt'); ?>" LINK="black" ALINK="black" VLINK="black">
<CENTER>
<H2>New Topic:</H2><BR>
How to use BBcode:<BR>
[B] - Bold text [/B] - End of bold text [I] - Italic text [/I] - End of italic text [U] - Underlined text [/U] - End of underlined text<BR>
Links:<BR>
[LINK="URL"]Text to display[/LINK]<BR>
<FORM ACTION="save.php">
Your Name: <INPUT TYPE="text" NAME="n"><BR>
Title: <INPUT TYPE="text" NAME="t"><BR>
Content: <TEXTAREA NAME="c" COLS="100" ROWS="20"></TEXTAREA><BR>
<INPUT TYPE="submit" VALUE="Create New Topic"> <INPUT TYPE="button" VALUE="Cancel" ONCLICK="window.location='index.php'">
</FORM>
</CENTER>
</BODY>
</HTML>
AMFRNEW;


$f7 = fopen($forum2."/new.php", "wb");
fwrite($f7, $new);
fclose($f7);

$save = <<<AMFRSAVE
<?php
\$name = stripslashes(htmlspecialchars(\$_REQUEST['n']));
\$title = stripslashes(htmlspecialchars(\$_REQUEST['t']));
\$contents = stripslashes(nl2br(htmlspecialchars(\$_REQUEST['c'])));
if(@ \$file = fopen(\$title.'.txt', 'xb')) {
echo "<META HTTP-EQUIV=\"refresh\" CONTENT=\"0;url=forum.php?forum=".\$title."\">";
}
else
{
?>
<SCRIPT LANGUAGE="javascript">
alert('Please choose a different title, there is another topic with that title.')
history.go(-1)
</SCRIPT>
<?php
exit;
}
\$contents = str_replace('[B]', "<B>", \$contents);
\$contents = str_replace('[/B]', "</B>", \$contents);
\$contents = str_replace('[I]', "<I>", \$contents);
\$contents = str_replace('[/I]', "</I>", \$contents);
\$contents = str_replace('[U]', "<U>", \$contents);
\$contents = str_replace('[/U]', "</U>", \$contents);
\$contents = str_replace('[LINK=&quot;', '<A HREF="', \$contents);
\$contents = str_replace('&quot;]', '">', \$contents);
\$contents = str_replace('[/LINK]', "</A>", \$contents);
fwrite(\$file, "<TR><TD><B>".\$title."</B><BR>Posted by: ".\$name."</TD></TR><TR><TD>".\$contents."</TD></TR>\n");
fclose(\$file);
\$f2 = fopen('cat.txt', 'rb');
@ \$c2 = fread(\$f2, filesize('cat.txt'));
fclose(\$f2);
\$f3 = fopen('cat.txt', 'wb');
fwrite(\$f3, "<TR><TD><B><A HREF='forum.php?forum=".urlencode(\$title)."'>".\$title."</A></B><BR>Posted by: ".\$name."</TD></TR>".\$c2);
fclose(\$f3);
\$file2 = fopen('last.txt', 'wb');
fwrite(\$file2, "<TR><TD><B>Last post by ".\$name." in \"<A HREF='forum.php?forum=".\$title."'>".\$title."</A>\"</B></TD></TR>\n");
fclose(\$file2);
?>
AMFRSAVE;

$f8 = fopen($forum2."/save.php", "wb");
fwrite($f8, $save);
fclose($f8);

$save2  = <<<AMFRSAVE2
<?php
\$name = stripslashes(htmlspecialchars(\$_REQUEST['name']));
\$title = stripslashes(htmlspecialchars(\$_REQUEST['t']));
\$contents = stripslashes(nl2br(htmlspecialchars(\$_REQUEST['reply'])));
\$contents = str_replace('[B]', "<B>", \$contents);
\$contents = str_replace('[/B]', "</B>", \$contents);
\$contents = str_replace('[I]', "<I>", \$contents);
\$contents = str_replace('[/I]', "</I>", \$contents);
\$contents = str_replace('[U]', "<U>", \$contents);
\$contents = str_replace('[/U]', "</U>", \$contents);
\$contents = str_replace('[LINK=&quot;', '<A HREF="', \$contents);
\$contents = str_replace('&quot;]', '">', \$contents);
\$contents = str_replace('[/LINK]', "</A>", \$contents);
\$file = fopen(\$title.'.txt', 'ab');
fwrite(\$file, "<TR><TD>Posted by: ".\$name."</TD></TR><TR><TD>".\$contents."</TD></TR>\n");
fclose(\$file);
\$file2 = fopen('last.txt', 'wb');
fwrite(\$file2, "<TR><TD><B>Last post by ".\$name." in \"<A HREF='forum.php?forum=".\$title."'>".\$title."</A>\"</B></TD></TR>\n");
fclose(\$file2);
?>
<META HTTP-EQUIV="refresh" CONTENT="0;url=forum.php?forum=<?php echo \$title; ?>">
AMFRSAVE2;

$f9 = fopen($forum2."/save2.php", "wb");
fwrite($f9, $save2);
fclose($f9);

$save3 = <<<AMFRSAVE3
<?php
include('config.php');
if(\$_POST['u'] != \$username || \$_POST['p'] != \$password) {
exit;
}
\$title = stripslashes(\$_POST['t']);
\$c1 = stripslashes(\$_POST['color']);
\$c2 = stripslashes(\$_POST['color2']);
\$file = fopen('title.txt', 'wb');
fwrite(\$file, \$title);
fclose(\$file);
\$file2 = fopen('bg.txt', 'wb');
fwrite(\$file2, \$c1);
fclose(\$file2);
\$file3 = fopen('bg2.txt', 'wb');
fwrite(\$file3, \$c2);
fclose(\$file3);
?>
<HTML>
<HEAD>
<META HTTP-EQUIV="refresh" CONTENT="0;url=index.php">
</HEAD>
</HTML>
AMFRSAVE3;

$f10 = fopen($forum2."/save3.php", "wb");
fwrite($f10, $save3);
fclose($f10);

$psticky = <<<AMFRPS
<?php
include('config.php');
if(\$_POST['u'] != \$username || \$_POST['p'] != \$password) {
exit;
}
\$name = stripslashes(htmlspecialchars(\$_REQUEST['name']));
\$title = stripslashes(htmlspecialchars(\$_REQUEST['title']));
\$contents = stripslashes(nl2br(htmlspecialchars(\$_REQUEST['content'])));
if(@ \$file = fopen(\$title.'.txt', 'xb')) {
echo "<META HTTP-EQUIV=\"refresh\" CONTENT=\"0;url=forum.php?forum=".\$title."\">";
}
else
{
?>
<SCRIPT LANGUAGE="javascript">
alert('Please choose a different title, there is another topic with that title.')
history.go(-1)
</SCRIPT>
<?php
exit;
}
\$contents = str_replace('[B]', "<B>", \$contents);
\$contents = str_replace('[/B]', "</B>", \$contents);
\$contents = str_replace('[I]', "<I>", \$contents);
\$contents = str_replace('[/I]', "</I>", \$contents);
\$contents = str_replace('[U]', "<U>", \$contents);
\$contents = str_replace('[/U]', "</U>", \$contents);
\$contents = str_replace('[LINK=&quot;', '<A HREF="', \$contents);
\$contents = str_replace('&quot;]', '">', \$contents);
\$contents = str_replace('[/LINK]', "</A>", \$contents);
fwrite(\$file, "<TR><TD><B>".\$title."</B><BR>Posted by: ".\$name."</TD></TR><TR><TD>".\$contents."</TD></TR>\n");
fclose(\$file);
\$f2 = fopen('sticky.txt', 'rb');
@ \$c2 = fread(\$f2, filesize('sticky.txt'));
fclose(\$f2);
\$f3 = fopen('sticky.txt', 'wb');
fwrite(\$f3, "<TR><TD><B>STICKY: <A HREF='forum.php?forum=".urlencode(\$title)."'>".\$title."</A></B><BR>Posted by: ".\$name."</TD></TR>".\$c2);
fclose(\$f3);
\$file2 = fopen('last.txt', 'wb');
fwrite(\$file2, "<TR><TD><B>Last post by ".\$name." in \"<A HREF='forum.php?forum=".\$title."'>".\$title."</A>\"</B></TD></TR>\n");
fclose(\$file2);
?>
AMFRPS;

$f11 = fopen($forum2."/ps.php", "wb");
fwrite($f11, $psticky);
fclose($f11);

$sticky = "<STICKIES>";

$f14 = fopen($forum2."/sticky.txt", "wb");
fwrite($f14, $sticky);
fclose($f14);

echo "Done.<BR>";

?>
<A HREF="<?php echo $forum2; ?>/index.php">Click here to access your forum!</A><BR>
Please delete install.php from this directory.
</BODY>
</HTML>