<?PHP
	require("lang_select.php");

	require("engine.inc.php");
?>
<html>
  <head> 
<title><?PHP print $lang_59; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=<?PHP print $lang_char; ?>">
</head> <body bgcolor="#FFFFFF" text="#000000">
<?PHP
if ($funcml == "" OR $funcml == addpre){
?>
<form name="" method="post" action="box.php">
  <table width="550" border="0" cellspacing="0" cellpadding="4" align="center">
    <tr> 
      <td colspan="2"> <font size="3" face="Arial, Helvetica, sans-serif"> <b> 
        <font size="4">
        <?PHP
	  $result21 = mysql_query ("SELECT * FROM Lists
                         WHERE id LIKE '$nl'
						 limit 1
                       ");
$listinfo = mysql_fetch_array($result21);
$liname = $listinfo["name"];
?>
        <?PHP print "$lang_60 "; if ($liname != "") { print "$lang_61 "; print $listinfo["name"]; } ?>
        </font></b></font></td>
    </tr>
    <tr valign="top"> 
      <td colspan="2"><img src="media/box1.gif" width="542" height="1"></td>
    </tr>
    <tr valign="top"> 
      <td><font size="2" face="Arial, Helvetica, sans-serif"> 
        <input type="text" name="email" value="<?PHP print $email; ?>">
        <?PHP print $lang_62; ?></font></td>
      <td> 
        <div align="right"><font size="2" face="Arial, Helvetica, sans-serif">
          <input type="submit" value="<?PHP print $lang_38; ?>" name="submit2">
          </font></div>
      </td>
    </tr>
    <tr> 
      <td colspan="2"><img src="media/box1.gif" width="542" height="1"></td>
    </tr>
    <tr> 
      <td width="60%"><b><font size="2" face="Arial, Helvetica, sans-serif" color="#333333"><?PHP print $lang_63; ?></font></b></td>
      <td width="40%"><b><font size="2" face="Arial, Helvetica, sans-serif" color="#333333"><?PHP if ($mlt != no){ print $lang_64; } ?></font></b></td>
    </tr>
    <tr valign="top"> 
      <td width="60%"> <font size="2" face="Arial, Helvetica, sans-serif"> 
        <input type="text" name="name">
        </font> <font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_4; ?></font><br><br>
        <?PHP
		$cnumc = 0;
		while($cnumc !=11){
		if ($listinfo["field$cnumc"] != ""){
		?>

        <font size="2" face="Arial, Helvetica, sans-serif"> 
        <input type="text" name="field<?PHP print $cnumc; ?>">
        </font> <font size="2" face="Arial, Helvetica, sans-serif"> 
        <?PHP print $listinfo["field$cnumc"]; ?>
        </font><br><br>
        <?PHP
		}
		$cnumc = $cnumc + 1;
		}
		?>
      </td>
      <td width="40%"> 
        <table width="100%" border="0" cellspacing="1" cellpadding="3" align="center">
          <tr bgcolor="#FFFFFF"> 
            <?PHP
			if ($mlt != no){
		$numbox = 1; 
		if (empty($offset)) {
    $offset=0;
}
		$count1 = 0;


		$finder = mysql_query ("SELECT * FROM Lists
		WHERE name != ''
		AND a_priv != '1'
                       	ORDER BY name 
						");

if ($c1 = mysql_num_rows($finder))
{
while($find = mysql_fetch_array($finder)) {
?>
          <tr width="100%" bgcolor="#<?PHP if ($nl == $find["id"]){ print ECECFF; } else { print E9E9E9; } ?>"> 
            <div align="left"><font size="2" face="Arial, Helvetica, sans-serif"> 
              <input type="checkbox" name="nlbox[<?PHP print $numbox; ?>]" value="<?PHP print $find["id"]; ?>" <?PHP if ($nl == $find["id"]){ print checked; } ?>>
              <?PHP print $find["name"]; ?>
              </font></div>
          </tr>
          <?PHP
$numbox = $numbox + 1;

} 
}
else {
?>
          <font size="2" face="Arial, Helvetica, sans-serif"> 
          <?PHP
print "";
?>
          </font> 
          <?PHP
		  }
}
?>
        </table>
      </td>
    </tr>
    <tr valign="top"> 
      <td width="60%">&nbsp;</td>
      <td width="40%">
        <div align="right">
          <p><font size="2" face="Arial, Helvetica, sans-serif"> 
            <input type="submit" value="<?PHP print $lang_38; ?>" name="submit">
            <input type="hidden" name="murl" value="<?PHP print $murl; ?>">
            <input type="hidden" name="funcml" value="add">
            <input type="hidden" name="loc" value="<?PHP print $loc; ?>">
			<?PHP
			if ($mlt == no){
			?>
			            <input type="hidden" name="nlbox[1]" value="<?PHP print $nl; ?>">

			<?PHP
			}
			?>
            </font></p>
          <p><font size="2" face="Arial, Helvetica, sans-serif"><a href="box.php?funcml=unsub1&nl=<?PHP print $nl; ?>&mlt=<?PHP print $mlt; ?>"><?PHP print $lang_65; ?></a></font></p>
        </div>
      </td>
    </tr>
  </table>
  <p>&nbsp;</p>
</form>
<font size="2" face="Arial, Helvetica, sans-serif" color="#FF0000"> 
<?PHP
}
?>
<?PHP
if ($funcml == "unsub1"){


?>
</font>
<form name="" method="post" action="box.php">
  <table width="550" border="0" cellspacing="0" cellpadding="4" align="center">
    <tr> 
      <td colspan="2"> <font size="3" face="Arial, Helvetica, sans-serif"> <b> 
        <font size="4"> 
        <?PHP
	  $result21 = mysql_query ("SELECT * FROM Lists
                         WHERE id LIKE '$nl'
						 limit 1
                       ");
$listinfo = mysql_fetch_array($result21);
?>
        <?PHP print $lang_65; ?> </font></b></font></td>
    </tr>
    <tr> 
      <td colspan="2"><img src="media/box1.gif" width="542" height="1"></td>
    </tr>
    <tr> 
      <td width="60%"><b><font size="2" face="Arial, Helvetica, sans-serif"> 
        <input name="email" type="text" id="email" value="<?PHP print $email; ?>">
        <?PHP print $lang_62; ?></font></b></td>
      <td width="40%"><b><font size="2" face="Arial, Helvetica, sans-serif" color="#333333"> 
        <?PHP if ($mlt != no){ print $lang_66; } ?>
        </font></b></td>
    </tr>
    <tr valign="top"> 
      <td width="60%">&nbsp; </td>
      <td width="40%"> <table width="100%" border="0" cellspacing="1" cellpadding="3" align="center">
          <tr bgcolor="#FFFFFF"> 
            <?PHP
						if ($mlt != no){

		$numbox = 1; 
		if (empty($offset)) {
    $offset=0;
}
		$count1 = 0;


		$finder = mysql_query ("SELECT * FROM Lists
		WHERE name != ''
		AND a_priv != '1'
                       	ORDER BY name 
						");

if ($c1 = mysql_num_rows($finder))
{
while($find = mysql_fetch_array($finder)) {
?>
          <tr width="100%" bgcolor="#<?PHP if ($nl == $find["id"]){ print ECECFF; } else { print E9E9E9; } ?>"> 
            <div align="left"><font size="2" face="Arial, Helvetica, sans-serif"> 
              <input type="checkbox" name="nlbox[<?PHP print $numbox; ?>]" value="<?PHP print $find["id"]; ?>" <?PHP if ($nl == $find["id"]){ print checked; } ?>>
              <?PHP print $find["name"]; ?> </font></div>
          </tr>
          <?PHP
$numbox = $numbox + 1;

} 
}
else {
?>
          <font size="2" face="Arial, Helvetica, sans-serif"> 
          <?PHP
print "";
?>
          </font> 
          <?PHP
		  }
}
?>
        </table></td>
    </tr>
    <tr valign="top"> 
      <td width="60%">&nbsp;</td>
      <td width="40%"> <div align="right">
          <p><font size="2" face="Arial, Helvetica, sans-serif"> 
            <input name="submit" type="submit" id="submit" value="<?PHP print $lang_65; ?>">
            <input type="hidden" name="murl" value="<?PHP print $murl; ?>">
            <input type="hidden" name="funcml" value="unsub2">
            <input type="hidden" name="loc" value="<?PHP print $loc; ?>">
            </font></p>
          <p><font size="2" face="Arial, Helvetica, sans-serif"><a href="box.php?nl=<?PHP print $nl; ?>&mlt=<?PHP print $mlt; ?>"><?PHP print $lang_38; ?></a></font></p>
        </div></td>
    </tr>
  </table>
  <p>&nbsp;</p>
</form>
<font size="2" face="Arial, Helvetica, sans-serif" color="#FF0000"> 
<?PHP
}
?>
</font><font size="2" face="Arial, Helvetica, sans-serif"> </font> 
</body>
</html>
