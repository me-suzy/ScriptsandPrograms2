<?PHP 
if ($nl == "" OR $ei == "" OR $eid == ""){
	print "Invalid  -1079.";
	die();
}
require("lang_select.php");
$ei = base64_decode($ei);
$eid = base64_decode($eid);
$result = mysql_query ("SELECT * FROM ListMembers
                         WHERE email LIKE '$ei'
						 AND id LIKE '$eid'
						 limit 1
                       ");
$row = mysql_fetch_array($result);
$num_email = mysql_result($result, 0, 0);
if ($num_email == "0"){
	print "Invalid  -1079.";
	die();
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title><?PHP print $lang_390; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=<?PHP print $lang_char; ?>">
</head>

<body>
<p><font color="#336699" size="4" face="Arial, Helvetica, sans-serif"><strong><?PHP print $lang_390; ?></strong></font></p>
<?PHP if ($val != final){ ?>
<font size="3"><b><font size="3"><b><font size="2" face="Arial, Helvetica, sans-serif"><b><font size="2" face="Arial, Helvetica, sans-serif"><b><font size="2" face="Arial, Helvetica, sans-serif"><font size="4" face="Arial, Helvetica, sans-serif" color="#336699"> 
</font></font></b></font></b></font></b></font></b></font> 
<form name="" method="post" action="p_m.php">
  <font size="2" face="Arial, Helvetica, sans-serif"> </font> 
  <table width="500" border="0" cellspacing="0" cellpadding="8">
    <tr> 
      <td width="120" height="30" bgcolor="#F3F3F3"><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_5; ?></font></td>
      <td height="30" bgcolor="#F3F3F3"> <font size="2" face="Arial, Helvetica, sans-serif"> 
        <?PHP	print $row["email"];	?>
        </font></td>
    </tr>
    <tr> 
      <td width="120" height="30"><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_4; ?></font></td>
      <td height="30"> <font size="2" face="Arial, Helvetica, sans-serif"> 
        <input type="text" name="name" value="<?PHP	print $row["name"];	?>">
        </font></td>
      <?PHP
					  $result213 = mysql_query ("SELECT * FROM Lists
                         WHERE id LIKE '$nl'
						 limit 1
                       ");
$listinfo = mysql_fetch_array($result213);

		$cnumc = 0;
		while($cnumc !=11){
		if ($listinfo["field$cnumc"] != ""){
		?>
    </tr>
    <tr> 
      <td width="120" height="30" bgcolor="#F3F3F3"><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $listinfo["field$cnumc"]; ?></font></td>
      <td height="30" bgcolor="#F3F3F3"> <font size="2" face="Arial, Helvetica, sans-serif"> 
        <input type="text" name="field<?PHP print $cnumc; ?>" value="<?PHP	print $row["field$cnumc"];	?>">
        </font></td>
    </tr>
    <?PHP
		}
		$cnumc = $cnumc + 1;
		}
		?>
  </table>
  <br>
  <table width="500" border="0" cellspacing="0" cellpadding="0">
    <tr> 
      <td><font size="2" face="Arial, Helvetica, sans-serif"> 
        <input type="submit" value="<?PHP print $lang_98; ?>" name="submit">
        <input type="hidden" name="nl" value="<?PHP print $nl; ?>">
        <input type="hidden" name="val" value="final">
        <input name="ei" type="hidden" id="ei" value="<?PHP print base64_encode($ei); ?>">
		<input name="eid" type="hidden" id="eid" value="<?PHP print base64_encode($eid); ?>">
        </font></td>
    </tr>
  </table>
  </form>
<?PHP
}
else {
?>
<font size="2" face="Arial, Helvetica, sans-serif" color="#990000"><?PHP print $lang_160; ?></font> <font size="2" face="Arial, Helvetica, sans-serif" color="#FF0000"> 
<?PHP
mysql_query("UPDATE ListMembers SET name='$name',field1='$field1',field2='$field2',field3='$field3',field4='$field4',field5='$field5',field6='$field6',field7='$field7',field8='$field8',field9='$field9',field10='$field10' WHERE (id='$eid')");
?>
<br>
</font> 
<p> <font size="2" face="Arial, Helvetica, sans-serif" color="#FF0000"> </font> 
  <?PHP } ?>
</body>
</html>