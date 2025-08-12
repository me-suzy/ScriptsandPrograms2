<p><font color="#336699" size="4" face="Arial, Helvetica, sans-serif"><strong><?PHP print $lang_158; ?> </strong></font></p>
<p><font size="2" face="Arial, Helvetica, sans-serif"> 
  <?PHP if ($action == ""){ ?>
  <?PHP print $lang_159; ?> ( , )</font></p>
<form name="form1" method="post" action="main.php">
  <p> 
    <textarea name="bk" cols="50" rows="12"><?PHP 
$result = mysql_query ("SELECT * FROM Lists
						 WHERE id LIKE '$nl'
						 LIMIT 1
");
$row = mysql_fetch_array($result);
print $row["bk"];


  ?></textarea>
  </p>
  <p> 
    <input type="submit" name="Submit" value="<?PHP print $lang_98; ?>">
    <input type="hidden" name="page" value="list_blocked">
    <input type="hidden" name="nl" value="<?PHP print $nl; ?>">
    <input type="hidden" name="action" value="go">
  </p>
</form>
<?PHP
  }
  else{
  ?>
<font size="2" face="Arial, Helvetica, sans-serif" color="#FF0000"> 
<?PHP
mysql_query("UPDATE Lists SET bk='$bk' WHERE (id='$nl')");
?>
</font> <font size="2" face="Arial, Helvetica, sans-serif" color="#FF0000"><?PHP print $lang_160; ?></font> 
<?PHP
  }
  ?>
