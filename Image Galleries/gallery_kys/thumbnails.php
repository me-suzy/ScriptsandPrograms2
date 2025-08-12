<html>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
<title>Thumbnails</title>
</head>

<body text="#FFFFFF" bgcolor="#000000"><style>
BODY { scrollbar-face-color: "#000000"; scrollbar-arrow-color: "#000000"; scrollbar-track-color: "#000000"; scrollbar-3dlight-color:"#333333"; scrollbar-darkshadow-color: "#333333"; }
</style>
<table border="0" cellpadding="0" cellspacing="2" id="AutoNumber1">
  <tr>
<? for ($i=1; file_exists("thumbnails/image".strval($i).".jpg"); $i++) {
$a=getimagesize("thumbnails/image".strval($i).".jpg");
$i=strval($i);
echo "<td width=\"".strval($a[0])."\"><a href=preview.php?i=$i target=pre><img border=0 src=thumbnails/image$i.jpg></a></td>";
}  ?>
  </tr>
</table>

</body>

</html>
