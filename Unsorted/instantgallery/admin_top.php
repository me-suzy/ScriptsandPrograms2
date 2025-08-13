<?
require("config.php");
require("admin_functions.php");
$root = $docroot . $galleryroot;
$id = 0;

if (auth()) {
?>

<html>
<head>
	<title>phpInstantGallery Admin Page</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	
	<STYLE TYPE="text/css">
		<!--
		#tree 			{ font-family: Verdana, Geneva, Arial, Helvetica, sans-serif; font-size: 11px }
		#tree img 		{ border: 0px; width: 19px; height: 16px }
		A	     		{ color:#000; text-decoration:none }
		A:hover	   		{ text-decoration:underline }
		BODY 			{ font-family:arial, helvetica; background-color:#ffffff; font-size:12px }
		.header			{ font-size:16px; font-family:verdana, arial, helvetica; font-weight: bold }
		.header A:hover	{ text-decoration:none }
		.txt 			{ font-size:12px; font-family:verdana, arial, helvetica; font-weight: bold }
		.galleryCnt		{ font-family: Verdana, Geneva, Arial, Helvetica, sans-serif; font-size: 11px; color: #777 }
		// end hiding -->
	</STYLE>
	<script type="text/javascript" src="tree.js"></script>
	<script type="text/javascript">
		<!--
		
		var Tree = new Array;
		// nodeId | parentNodeId | nodeName | nodeUrl
		<? getTreeArray(); ?>
		
		
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

<body>
<form name="uri">
<input type="hidden" name="glink">
<? getTemplates(); ?>
</form>

<span class="header"><a href="#" onClick="postURL('');">phpInstantGallery Link Generator</a></span>
<p>

<div id="tree">
<script type="text/javascript">
<!--
	createTree("<?= $HTTP_SERVER_VARS['HTTP_HOST'] . $galleryroot ?>", Tree);
//-->
</script>
</div>

</body>
</html>

<?
} else {
	echo "You are not logged in.  Please click <a href=\"admin.php\" target=\"top\">here</a> to go to the login screen";
}
?>
