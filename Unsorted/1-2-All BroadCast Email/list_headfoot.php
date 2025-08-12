<p><font color="#336699" size="4" face="Arial, Helvetica, sans-serif"><strong><?PHP print $lang_179; ?></strong></font></p>
<p><font size="2" face="Arial, Helvetica, sans-serif">
  <?PHP
if ($action == add){
$content = addslashes($content);
$name = addslashes($name);
mysql_query ("INSERT INTO Templates (nl, name, content, type) VALUES ('$nl' ,'$name' ,'$content' ,'$type')");  
print "$lang_180 $type, $name, $lang_181<br>
<br>
";
}

if ($action == edit2){
$content = addslashes($content);
$name = addslashes($name);
mysql_query("UPDATE Templates SET name='$name',type='$type',content='$content' WHERE (id='$id')");
print "$lang_180 $type, $name, $lang_182<br>
<br>
";
}

if ($action == del){
mysql_query ("DELETE FROM Templates
                                WHERE id = '$id'
								LIMIT 1
								");
print "$lang_183 <br>
<br>
";
}
if ($action == edit){
?>
  </font></p>
<form name="edit" method="post" action="main.php">
  <font size="2" face="Arial, Helvetica, sans-serif"><b><?PHP print $lang_184; ?></b></font> 
  <?PHP
  $result = mysql_query ("SELECT * FROM Templates
						WHERE id LIKE '$id'
						LIMIT 1
						");
$row = mysql_fetch_array($result);
?>
  <br>
  <br>
  <table width="100%" border="0" cellspacing="0" cellpadding="8">
    <tr> 
      <td width="150" height="30" bgcolor="#F3F3F3"><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_4; ?><br>
        <font size="1"><?PHP print $lang_185; ?></font></font></td>
      <td height="30" bgcolor="#F3F3F3"> <font size="2" face="Arial, Helvetica, sans-serif"> 
        <input type="text" name="name" size="35" value="<?PHP 
		print stripslashes($row["name"]);
		?>">
        </font></td>
    </tr>
    <tr> 
      <td width="150" height="30"><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_186; ?></font></td>
      <td height="30"> <font size="2" face="Arial, Helvetica, sans-serif"> 
        <input type="radio" name="type" value="header" <?PHP if ($row["type"] == header){ print "checked";} ?>>
        <?PHP print $lang_187; ?><br>
        <input type="radio" name="type" value="footer" <?PHP if ($row["type"] == footer){ print "checked";} ?>>
        <?PHP print $lang_188; ?></font></td>
    </tr>
    <tr> 
      <td height="30" colspan="2" bgcolor="#F3F3F3"><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_189; ?><br>
        <font size="2" face="Arial, Helvetica, sans-serif"><font size="1"><?PHP print $lang_190; ?></font></font></font><br> <textarea name="content" cols="49" rows="8"><?PHP 		print stripslashes($row["content"]);
 ?>
</textarea> 
      </td>
    </tr>
  </table>
  <br>
  <table width="75%" border="0" cellspacing="0" cellpadding="0">
    <tr> 
      <td><font size="2" face="Arial, Helvetica, sans-serif"> 
        <input type="submit" value="<?PHP print $lang_98; ?>" >
        <input type="hidden" name="page" value="list_headfoot">
        <input type="hidden" name="nl" value="<?PHP print $nl; ?>">
        <input type="hidden" name="action" value="edit2">
        <input type="hidden" name="id" value="<?PHP print $id; ?>">
        </font></td>
    </tr>
  </table>
  <br>
</form>
<p><font size="2" face="Arial, Helvetica, sans-serif"> 
  <?PHP
}
if ($action != edit){
print $lang_191;
?>
  </font></p>
<table width="100%" border="0" cellspacing="0" cellpadding="1">
  <tr> 
    <td bgcolor="#FFFFFF" width="58"> <div align="center"><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_192; ?></font></div></td>
    <td bgcolor="#FFFFFF" width="58"> <div align="center"><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_193; ?></font></div></td>
    <td bgcolor="#D5E2F0"> <div align="center"><b><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_4; ?></font></b></div></td>
  </tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="1">
  <tr> 
    <td bgcolor="#D5E2F0"> <table width="100%" border="0" cellspacing="0" cellpadding="4" bordercolor="#FFFFFF" align="center">
        <?PHP 
$result = mysql_query ("SELECT * FROM Templates
						WHERE nl LIKE '$nl'
						AND type != 'html'
						AND type != 'text'
                       	ORDER BY name
						");
if ($c1 = mysql_num_rows($result)) {

while($row = mysql_fetch_array($result)) {
?>
        <tr bgcolor="#FFFFFF"> 
          <td width="50" bordercolor="#CCCCCC" bgcolor="#FFFFFF"> <div align="center"><font size="1" face="Arial, Helvetica, sans-serif"><a href="main.php?page=list_headfoot&nl=<?PHP print $nl; ?>&action=edit&id=<?PHP print $row["id"]; ?>"><img src="media/edit.gif" width="11" height="7" border="0"></a> 
              </font></div></td>
          <td bordercolor="#CCCCCC" width="50"> <div align="center"><font size="1" face="Arial, Helvetica, sans-serif"><a href="main.php?page=list_headfoot&nl=<?PHP print $nl; ?>&action=del&id=<?PHP print $row["id"]; ?>"><img src="media/del.gif" width="11" height="7" border="0"></a> 
              </font></div></td>
          <td bordercolor="#CCCCCC"><font size="2" face="Arial, Helvetica, sans-serif"> 
            ( 
            <?PHP 
			
			print $row["type"]; 
			?>
            ) 
            <?PHP 
					print stripslashes($row["name"]);
			?>
            </font></td>
        </tr>
        <?PHP
}

} else {print "$lang_32.
          ";} ?>
      </table></td>
  </tr>
</table>
<br>
<hr width="100%" size="1" noshade>
<form name="" method="post" action="main.php">
  <font size="2" face="Arial, Helvetica, sans-serif"><b><?PHP print $lang_194; ?></b></font><br>
  <br>
  <table width="100%" border="0" cellspacing="0" cellpadding="8">
    <tr bgcolor="#F3F3F3"> 
      <td width="150" height="30"><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_4; ?><br>
        <font size="1"><?PHP print $lang_185; ?></font></font></td>
      <td height="30"> <font size="2" face="Arial, Helvetica, sans-serif"> 
        <input name="name" type="text" id="name" size="35">
        </font></td>
    </tr>
    <tr> 
      <td width="150" height="30"><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_186; ?></font></td>
      <td height="30"> <font size="2" face="Arial, Helvetica, sans-serif"> 
        <input type="radio" name="type" value="header" checked>
        <?PHP print $lang_187; ?><br>
        <input type="radio" name="type" value="footer">
        <?PHP print $lang_188; ?></font></td>
    </tr>
    <tr> 
      <td height="30" colspan="2" bgcolor="#F3F3F3"><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_189; ?><br>
        <font size="1"><?PHP print $lang_190; ?></font></font><br> 
        <textarea name="content" cols="49" rows="8"></textarea> </td>
    </tr>
  </table>
  <br>
  <table width="75%" border="0" cellspacing="0" cellpadding="0">
    <tr> 
      <td><font size="2" face="Arial, Helvetica, sans-serif"> 
        <input type="submit" value="<?PHP print $lang_21; ?>" name="submit">
        <input type="hidden" name="page" value="list_headfoot">
        <input type="hidden" name="nl" value="<?PHP print $nl; ?>">
        <input type="hidden" name="action" value="add">
        </font></td>
    </tr>
  </table>
  <br>
</form>
<?PHP 
}
?>
