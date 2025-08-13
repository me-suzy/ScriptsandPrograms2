<?
require("config.php");
require("admin_functions.php");

$g_link = $HTTP_POST_VARS['glink'];
$templates = $HTTP_POST_VARS['templates'];
$currentdir = dirname($HTTP_SERVER_VARS['PHP_SELF']);

if (!empty($g_link)) {
	$gallery = $currentdir . "/index.php?gallery=" . ereg_replace('^/', '', $g_link);
	$httpstr = "http://" . $HTTP_SERVER_VARS['HTTP_HOST'] . $gallery;
	$headerstr = "Links to Gallery in $docRoot$galleryRoot$g_link";
} else {
	$gallery = "";
	$headerstr = "Select a gallery folder above";
}
?>

<html>
<head>
	<title>Untitled</title>
	<STYLE TYPE="text/css">
		<!--
		A	     		{ color:#2663E2; text-decoration:none }
		A:hover	   		{ color:#2663E2; text-decoration:underline }
		BODY 			{ font-family:arial, helvetica; background-color:#ffffff; font-size:12px }
		.txt 			{ font-size:10px; font-family:verdana, arial, helvetica; }
		.labeltxt 		{ font-size:10px; font-family:verdana, arial, helvetica; font-weight: bold }
		.uri			{ font-size:10px; font-family:verdana, arial, helvetica }
		.head			{ font-size:16px; font-family:arial, helvetica, sans-serif }
		// end hiding -->
	</STYLE>

	<script language="JavaScript">
		<!--
		function postURL(str) {
			document.uri.glink.value = str;
			document.uri.method = "post";
			document.uri.target = "results";
			document.uri.action = "admin_bottom.php";
			document.uri.submit();
		
		//-->
		}
	</script>
</head>

<body topmargin="0" leftmargin="0" rightmargin="0">
<!-- begin stripes -->
<table cellspacing="0" cellpadding="0" border="0" width="100%">
<tr><td valign="top" bgcolor="#000000"><img src="clear.gif" width="1" height="1"></td></tr>
<tr><td valign="top"><img src="clear.gif" width="1" height="2"></td></tr>
<tr><td valign="top" bgcolor="#000000"><img src="clear.gif" width="1" height="1"></td></tr>
</table>
<!-- end stripes -->
<br>

<table cellspacing="0" cellpadding="0" border="0" width="85%">
<tr>
<td align="center"><img src="images/clear.gif" width="10" height="1"></td>
<td width="100%">
	<span class="head"><?= $headerstr ?></span>

	<br><img src="images/empty.gif" width="1" height="15" border="0"><br>

	<table cellspacing="0" cellpadding="0" border="0" width="100%">
	<tr><td><img src="images/empty.gif" width="16" height="1" border="0"></td><td><img src="images/empty.gif" width="20" height="1" border="0"></td><td></td></tr>
	
<?
if (!empty($g_link)) {
?>
	<tr><td></td><td colspan="2" width="100%"><span class="labeltxt">* With default template:</span></td></tr>
	<tr><td></td><td></td><td width="100%"><span class="txt">Root Relative Path:</span>&nbsp;<span class="uri"><?= $gallery?></span></td></tr>
	<tr><td></td><td></td><td width="100%"><span class="txt">URL:</span>&nbsp;<span class="uri"><a href="<?= $httpstr ?>" target="new"><?= $httpstr ?></a></span></td></tr>

<?
	// inefficent, but doesn't matter with this script
	if (count($templates) > 1) {
		for ($i=0; $i<count($templates); $i++) {
	
			if ($templates[$i] != $defaulttemplate) {
				echo "<tr><td><img src=\"empty.gif\" width=\"1\" height=\"15\" border=\"0\"></td></tr>";
				echo "<tr><td></td><td colspan=\"2\" width=\"100%\"><span class=\"labeltxt\">* With \"" . $templates[$i] . "\" template:</span></td></tr>";
				echo "<tr><td></td><td></td><td width=\"100%\"><span class=\"txt\">Root Relative Path:</span>&nbsp;<span class=\"uri\">" . $gallery . "&tmplt=" . $templates[$i] . "</span></td></tr>";
				echo "<tr><td></td><td></td><td width=\"100%\"><span class=\"txt\">URL:</span>&nbsp;<span class=\"uri\"><a href=\"" . $httpstr . "&tmplt=" . $templates[$i] . "\" target=\"new\">" . $httpstr . "&tmplt=" . $templates[$i] . "</span></td></tr>";
			}
		}
	}
}
?>
	</table>
</td>
</tr>
</table>


</body>
</html>
