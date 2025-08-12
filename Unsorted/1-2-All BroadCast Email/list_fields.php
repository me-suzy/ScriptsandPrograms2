<p><font color="#336699" size="4" face="Arial, Helvetica, sans-serif"><strong><?PHP print $lang_171; ?> </strong></font><font size="3"><b><font size="3"><b><font size="2" face="Arial, Helvetica, sans-serif"><b><font size="2" face="Arial, Helvetica, sans-serif"><b><font size="2" face="Arial, Helvetica, sans-serif"><font size="4" face="Arial, Helvetica, sans-serif" color="#336699">
  <?PHP
		  $result = mysql_query ("SELECT * FROM Lists
                         WHERE id LIKE '$nl'
						 
						 limit 1
                       ");
$row = mysql_fetch_array($result)
?>
  </font></font></b></font></b></font></b></font></b></font></p>
<?PHP
if ($action != save){
?>
<p><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_172; ?><br>
  <i><?PHP print $lang_173; ?></i></font></p>
<form name="adminForm" method="post" action="main.php">
  <table width="100%" border="0" cellspacing="0" cellpadding="8">
    <tr> 
      <td width="150" height="30" bgcolor="#F3F3F3"><font size="2" face="Arial, Helvetica, sans-serif"><b><?PHP print $lang_105; ?> 1 </b></font></td>
      <td height="30" bgcolor="#F3F3F3"> <font size="2" face="Arial, Helvetica, sans-serif"> 
        <input type="text" name="field1" value="<?PHP	print $row["field1"];	?>">
        </font></td>
    </tr>
    <tr> 
      <td width="150" height="30"><font size="2" face="Arial, Helvetica, sans-serif"><b><?PHP print $lang_105; ?> 2</b></font></td>
      <td height="30"> <font size="2" face="Arial, Helvetica, sans-serif"> 
        <input type="text" name="field2" value="<?PHP	print $row["field2"];	?>">
        </font></td>
    </tr>
    <tr> 
      <td width="150" height="30" bgcolor="#F3F3F3"><font size="2" face="Arial, Helvetica, sans-serif"><b><?PHP print $lang_105; ?> 3</b></font></td>
      <td height="30" bgcolor="#F3F3F3"><font size="2" face="Arial, Helvetica, sans-serif"> 
        <input type="text" name="field3" value="<?PHP	print $row["field3"];	?>">
        </font></td>
    </tr>
    <tr> 
      <td width="150" height="30"><font size="2" face="Arial, Helvetica, sans-serif"><b><?PHP print $lang_105; ?> 4</b></font></td>
      <td height="30"><font size="2" face="Arial, Helvetica, sans-serif"> 
        <input type="text" name="field4" value="<?PHP	print $row["field4"];	?>">
        </font></td>
    </tr>
    <tr bgcolor="#F3F3F3"> 
      <td width="150" height="30"><font size="2" face="Arial, Helvetica, sans-serif"><b><?PHP print $lang_105; ?> 5</b></font></td>
      <td height="30"><font size="2" face="Arial, Helvetica, sans-serif"> 
        <input type="text" name="field5" value="<?PHP	print $row["field5"];	?>">
        </font></td>
    </tr>
    <tr> 
      <td width="150" height="30"><font size="2" face="Arial, Helvetica, sans-serif"><b><?PHP print $lang_105; ?> 6</b></font></td>
      <td height="30"><font size="2" face="Arial, Helvetica, sans-serif"> 
        <input type="text" name="field6" value="<?PHP	print $row["field6"];	?>">
        </font></td>
    </tr>
    <tr bgcolor="#F3F3F3"> 
      <td width="150" height="30"><font size="2" face="Arial, Helvetica, sans-serif"><b><?PHP print $lang_105; ?> 7</b></font></td>
      <td height="30"><font size="2" face="Arial, Helvetica, sans-serif"> 
        <input type="text" name="field7" value="<?PHP	print $row["field7"];	?>">
        </font></td>
    </tr>
    <tr> 
      <td width="150" height="30"><font size="2" face="Arial, Helvetica, sans-serif"><b><?PHP print $lang_105; ?> 8</b></font></td>
      <td height="30"><font size="2" face="Arial, Helvetica, sans-serif"> 
        <input type="text" name="field8" value="<?PHP	print $row["field8"];	?>">
        </font></td>
    </tr>
    <tr bgcolor="#F3F3F3"> 
      <td width="150" height="30"><font size="2" face="Arial, Helvetica, sans-serif"><b><?PHP print $lang_105; ?> 9</b></font></td>
      <td height="30"><font size="2" face="Arial, Helvetica, sans-serif"> 
        <input type="text" name="field9" value="<?PHP	print $row["field9"];	?>">
        </font></td>
    </tr>
    <tr> 
      <td width="150" height="30"><font size="2" face="Arial, Helvetica, sans-serif"><b><?PHP print $lang_105; ?> 10</b></font></td>
      <td height="30"><font size="2" face="Arial, Helvetica, sans-serif"> 
        <input type="text" name="field10" value="<?PHP	print $row["field10"];	?>">
        </font></td>
    </tr>
  </table>
  <br>
  <table width="75%" border="0" cellspacing="0" cellpadding="0">
    <tr> 
      <td><font size="2" face="Arial, Helvetica, sans-serif"> 
        <input type="submit" value="<?PHP print $lang_98; ?>" name="submit">
        <input name="page" type="hidden" id="page" value="list_fields">
        <input name="action" type="hidden" id="action" value="save">
        <input name="nl" type="hidden" id="nl" value="<?PHP print $nl; ?>">
        </font></td>
    </tr>
  </table>
</form>
<?PHP
}
else {
?>
<font size="2" face="Arial, Helvetica, sans-serif" color="#990000"><?PHP print $lang_174; ?></font> <font size="2" face="Arial, Helvetica, sans-serif" color="#FF0000"> 
<?PHP
mysql_query("UPDATE Lists SET field1='$field1',field2='$field2',field3='$field3',field4='$field4',field5='$field5',field6='$field6',field7='$field7',field8='$field8',field9='$field9',field10='$field10' WHERE (id='$nl')");
?>
</font> 
<?PHP
}
?>
