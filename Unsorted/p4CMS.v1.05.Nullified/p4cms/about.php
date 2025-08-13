<?
 include("include/include.inc.php");

 if (!isset($HTTP_SESSION_VARS[u_loggedin]) or !($HTTP_SESSION_VARS[u_loggedin]=='yes')) {
  SessionError();
  exit;
 }
?>
<html>
<head>
 <title>Logout...</title>
 <? StyleSheet(); ?>
</head>
<body bgcolor="" background="/p4cms/gfx/main/bgbody.gif">

<?
 MsgBox('
<table border="0">
	<tr>
		<td ><font face="Arial" size="2">Program Name</font></td>
		<td ><font face="Arial" size="2">: p4CMS - Content Managment System</font></td>
	</tr>
	<tr>
		<td ><font face="Arial" size="2">Release Version</font></td>
		<td ><font face="Arial" size="2">: '.$version.'</font></td>
	</tr>
	<tr>
		<td ><font face="Arial" size="2">Program Author</font></td>
		<td ><font face="Arial" size="2">: Copyright (c)2002-2004 dream4</font></td>
	</tr>
	<tr>
		<td ><font face="Arial" size="2">Home Page</font></td>
		<td ><font face="Arial" size="2">: hxxp://www.dream4.de/</font></td>
	</tr>
	<tr>
		<td ><font face="Arial" size="2">Retail Price</font></td>
		<td ><font face="Arial" size="2">: $389.00 Euro</font></td>
	</tr>
	<tr>
		<td ><font face="Arial" size="2">Supplied by</font></td>
		<td ><font face="Arial" size="2">: Matumba</font></td>
	</tr>
	<tr>
		<td ><font face="Arial" size="2">Nullified by</font></td>
		<td ><font face="Arial" size="2">: WTN Team</font></td>
	</tr>
	<tr>
		<td ><font face="Arial" size="2">Distribution</font></td>
		<td ><font face="Arial" size="2">: via WebForum, ForumRU and associated file dumps
		</font> </td>
	</tr>
</table>
<br>
');
?>
</body>
</html>