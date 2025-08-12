<center><table width="90%"  border="0" cellspacing="1" cellpadding="0">
  <tr>
    <td class='header2'>Submit Signature</td>
  </tr>
  <tr>
    <td bgcolor="#FFFFFF" class='smalltxt'>
	<?
	include('config.php');
	if ("image/pjpeg" == $_FILES['userfile']['type'] || "image/jpeg" == $_FILES['userfile']['type'] || "image/gif" == $_FILES['userfile']['type'])
	{
	$sql = "SELECT * FROM sotw_week ORDER BY wid DESC LIMIT 1";
	$q = mysql_query($sql);
	while($row = mysql_fetch_array($q)){
	$wid = $row['wid'];
	}
	
	$sigs = $sotwPath.$wid."/";
	$uploadfile = $sigs . $_FILES['userfile']['name'];
	if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) {
	$img = $_FILES['userfile']['name'];
	$name = $_FILES['userfile']['name'];
	$debug = $_FILES;
	
	$sql = "INSERT INTO sotw_submits VALUES ('NULL', '$user', '$name', '$website', '$wid', 'N')";
	mysql_query($sql) or die("Couldnt add sig to DB - ".mysql_error());
	@chmod($sotwPath.$wid."/".$name, 0777);
	
   print "File is valid, and was successfully added to this weeks contest (Week #".$wid.").";
	}else{ echo "File couldnt be uploaded. <b>Debug:</B><br>";
	print_r($_FILES);
	 }
	}
?>
	<form action="" method="post" enctype="multipart/form-data" name="form1">
  <strong>User</strong>: <input type="text" name="user"><br>
  <strong>Sig</strong>:
  <input name="userfile" type="file">
  <br>
  <strong>Website</strong>:
  <input type="text" name="website">
  <br>
  <input type="submit" name="Submit" value="Submit Sig">
    </form></td>
  </tr>
</table>
<? echo $copyright; ?>

