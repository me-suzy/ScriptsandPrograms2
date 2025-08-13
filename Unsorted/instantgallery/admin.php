<?
require("config.php");
require("admin_functions.php");

if ( auth($HTTP_POST_VARS['username'], $HTTP_POST_VARS['password']) ) {

?>
	<html>
	<head>
		<title>phpInstantGallery Link Generator</title>
	</head>
	
	<!-- frames -->
	<frameset name="linkgen" rows="*,175">
	    <frame name="tree" src="admin_top.php" marginwidth="10" marginheight="10" scrolling="auto" frameborder="0">
	    <frame name="results" src="admin_bottom.php" marginwidth="10" marginheight="10" scrolling="auto" frameborder="0">
	</frameset>
	</html>

<?
} else {
?>

	<html>
	<head>
		<title>phpInstantGallery Admin Login Screen</title>
		<STYLE TYPE="text/css">
			<!--
			.header			{ font-size:16px; font-family:verdana, arial, helvetica; font-weight: bold }
			.error			{ font-size:12px; font-family:verdana, arial, helvetica; font-weight: bold; color: #f00 }
			// end hiding -->
		</STYLE>
	</head>
	<body>
		<span class="header">Admin Login for phpInstant Gallery Link Generator</span>
		
	<p>
<? 
	if ($HTTP_POST_VARS['errormsg']) {
		echo "<span class=\"error\">Wrong login info.  Try again.</span><br>\n";
	}
?>
			
	<form action="<?= $PHP_SELF ?>" method="post">
	<input type="hidden" name="errormsg" value="true">
	<table>
			<tr>
				<td nowrap valign="top" align="right" nowrap>
				<span class="postPageTxt">username:</span></td>
				<td><input type="text" name="username" size="35"></td>
			</tr>
			<tr>
				<td nowrap valign="top" align="right" nowrap>
				<span class="postPageTxt">password:</span></td>
				<td><input type="password" name="password" size="35"></td>
			</tr>
			<tr><td colspan="2" align="right"><input type="submit" value="Log in"><td><tr>
	</table>
	</form>
	</body>
	</html>
<?
}
?>