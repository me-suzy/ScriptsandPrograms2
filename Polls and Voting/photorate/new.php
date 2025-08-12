<?
####################################
#        PhotoRate v2.0
#      Nuked Web Services
#    http://www.nukedweb.com/
####################################

include "./config.php";
if ($file && $file_name){
	if ($_FILES['file']['size']>$maxsize) $status = "Error: Picture size too large. Max file size is $maxsize bytes.<br>";
	if (($_FILES['file']['type']!="image/gif") && ($_FILES['file']['type']!="image/jpeg") && ($_FILES['file']['type']!="image/jpg") && ($_FILES['file']['type']!="image/pjpeg")) $status .= "Error: Wrong file type. Must be JPG or GIF only.<br>";
	$picext = substr($file_name,-3);
	$picext = strtolower($picext);
	if ((!$status) && ($picext!="gif") && ($picext!="jpg") && ($picext!="peg")) $status .= "Error: Wrong file type. Must be JPG or GIF only.<br>";

	if (!$status){
		$sql = "insert into $table values('', '$email', '$aim', '$icq', '$yahoo', '$homepage', '0', '0', '0', '0', '0', '', now())";
		$result = mysql_query($sql) or die("Failed: $sql - ".mysql_error());
		$sql = "select max(id) from $table";
		$result = mysql_query($sql);
		$resrow = mysql_fetch_row($result);
		$id = $resrow[0];
		$sql = "update $table set picfile = '".$id.".".$picext."' where id='$id'";
		$result = mysql_query($sql);
		@copy($file, "./pics/".$id.".".$picext);
		Header("Location: index.php?id=$id");
		exit;
	}
}
?>
<html>
<head>
<title>Add Photo</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body bgcolor="#FFFFFF" text="#000000">
<form name="form1" method="post" action="new.php" enctype="multipart/form-data">
  <div align="center"><font color="#FF0000"><b><font size="-1" face="Verdana, Arial, Helvetica, sans-serif"><? print $status; ?></font></b></font><br>
  </div>
  <table width="45%" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr> 
      <td width="501"> 
        <div align="right"><font size="-1" face="Verdana, Arial, Helvetica, sans-serif">Email:</font></div>
      </td>
      <td width="501"> <font size="-1" face="Verdana, Arial, Helvetica, sans-serif"> 
        <input type="text" name="email">
        </font></td>
    </tr>
    <tr> 
      <td width="501"> 
        <div align="right"><font size="-1" face="Verdana, Arial, Helvetica, sans-serif">AIM 
          ScreenName:</font></div>
      </td>
      <td width="501"> <font size="-1" face="Verdana, Arial, Helvetica, sans-serif"> 
        <input type="text" name="aim">
        </font></td>
    </tr>
    <tr> 
      <td width="501"> 
        <div align="right"><font size="-1" face="Verdana, Arial, Helvetica, sans-serif">ICQ 
          #:</font></div>
      </td>
      <td width="501"> <font size="-1" face="Verdana, Arial, Helvetica, sans-serif"> 
        <input type="text" name="icq">
        </font></td>
    </tr>
    <tr> 
      <td width="501"> 
        <div align="right"><font size="-1" face="Verdana, Arial, Helvetica, sans-serif">Yahoo 
          ID:</font></div>
      </td>
      <td width="501"> <font size="-1" face="Verdana, Arial, Helvetica, sans-serif"> 
        <input type="text" name="yahoo">
        </font></td>
    </tr>
    <tr> 
      <td width="501"> 
        <div align="right"><font size="-1" face="Verdana, Arial, Helvetica, sans-serif">Homepage 
          URL:</font></div>
      </td>
      <td width="501"> <font size="-1" face="Verdana, Arial, Helvetica, sans-serif"> 
        <input type="text" name="homepage">
        </font></td>
    </tr>
    <tr> 
      <td width="501"><div align="right"><font size="-1" face="Verdana, Arial, Helvetica, sans-serif">Upload 
        Picture:</font></div></td>
      <td width="501"><font size="-1" face="Verdana, Arial, Helvetica, sans-serif">
        <input type="file" name="file">
        </font></td>
    </tr>
    <tr> 
      <td width="501"> 
        <div align="right"></div>
      </td>
      <td width="501"> <font size="-1" face="Verdana, Arial, Helvetica, sans-serif">
        <input type="submit" value="Add Picture">
        </font></td>
    </tr>
  </table>
</form>
</body>
</html>
