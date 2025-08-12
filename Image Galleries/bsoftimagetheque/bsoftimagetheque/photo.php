<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 //EN">
<html>
<head>
<title>BSoftImageTheque</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="style.css" rel="stylesheet" type="text/css">
<script type="text/javascript">window.defaultStatus="BSoftImageTheque";</script>
<script language="JavaScript" type="text/javascript" src="fct.js"></script>
</head>
<body class='vignettepage'>
<?
$chemin = "image/".$_GET[p]."/".$_GET[f];
if (file_exists($chemin))
	echo "<img src='",$chemin,"' border='0' alt='Close' title='Close'onclick=\"javascript:self.close();\"/>\n";
else
	echo "Arghhh ??!!";
?>

</body>
</html>