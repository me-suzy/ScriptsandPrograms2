<p><font color="#336699" size="4" face="Arial, Helvetica, sans-serif"><strong><?PHP print $lang_390; ?></strong></font></p>
<?PHP if ($val != final){ ?>
<font size="3"><b><font size="3"><b><font size="2" face="Arial, Helvetica, sans-serif"><b><font size="2" face="Arial, Helvetica, sans-serif"><b><font size="2" face="Arial, Helvetica, sans-serif"><font size="4" face="Arial, Helvetica, sans-serif" color="#336699"> 
<?PHP
		  $result = mysql_query ("SELECT * FROM ListMembers
                         WHERE id LIKE '$id'
						 
						 limit 1
                       ");
$row = mysql_fetch_array($result)
?>
</font></font></b></font></b></font></b></font></b></font> 
<form name="" method="post" action="main.php">
  <font size="2" face="Arial, Helvetica, sans-serif"> </font> 
  <table width="75%" border="0" cellspacing="0" cellpadding="8">
    <tr> 
      <td width="150" height="30" bgcolor="#F3F3F3"><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_5; ?></font></td>
      <td height="30" bgcolor="#F3F3F3"> <font size="2" face="Arial, Helvetica, sans-serif"> 
        <input type="text" name="email" value="<?PHP	print $row["email"];	?>">
        </font></td>
    </tr>
    <tr> 
      <td width="150" height="30"><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_4; ?></font></td>
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
      <td width="150" height="30" bgcolor="#F3F3F3"><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $listinfo["field$cnumc"]; ?></font></td>
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
  <table width="75%" border="0" cellspacing="0" cellpadding="0">
    <tr> 
      <td><font size="2" face="Arial, Helvetica, sans-serif"> 
        <input type="submit" value="<?PHP print $lang_98; ?>" name="submit">
        <input type="hidden" name="page" value="sub_modify">
        <input type="hidden" name="nl" value="<?PHP print $nl; ?>">
        <input type="hidden" name="val" value="final">
        <input type="hidden" name="id" value="<?PHP print $id; ?>">
        </font></td>
    </tr>
  </table>
  <br>
</form>
<?PHP
}
else {
?>
<font size="2" face="Arial, Helvetica, sans-serif" color="#990000"><?PHP print $lang_174; ?></font> <font size="2" face="Arial, Helvetica, sans-serif" color="#FF0000"> 
<?PHP
mysql_query("UPDATE ListMembers SET email='$email',name='$name',field1='$field1',field2='$field2',field3='$field3',field4='$field4',field5='$field5',field6='$field6',field7='$field7',field8='$field8',field9='$field9',field10='$field10' WHERE (id='$id')");

?>
<br>
</font> 
<p> <font size="2" face="Arial, Helvetica, sans-serif" color="#FF0000"> </font> 
  <?PHP } ?>
