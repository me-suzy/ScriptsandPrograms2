<?PHP
if ($nl == "" OR $ei == "" OR $mi == ""){
	print "Invalid  -1079.";
	die();
}
require("lang_select.php");
$result5 = mysql_query ("SELECT * FROM Messages
                         WHERE id LIKE '$mi'
						 AND nl LIKE '$nl'
						 limit 1
                       ");
$row5 = mysql_fetch_array($result5);
$result = mysql_query ("SELECT * FROM ListMembers
                         WHERE email LIKE '$ei'
						 AND nl LIKE '$nl'
						 limit 1
                       ");
$row = mysql_fetch_array($result);
		$em = $ei;
		$name = $row["name"];
		$currentnl = $nl;
		
		$subject = ereg_replace ("[\]", "", $row5["subject"]);
			$textmesg    = $row5["textmesg"];
			$htmlmesg    = $row5["htmlmesg"];
			$htmlmesg = str_replace("subscriberemailec", base64_encode($em), $htmlmesg);
			foreach (array('subject', 'textmesg', 'htmlmesg') as $var) {
				$$var = str_replace ("subscribername", $name, $$var);
				$$var = str_replace ("subscriberemail", $em, $$var);
				$$var = ereg_replace ("[\]", "", $$var);
			//}
			}
			$htmlmesg = str_replace("currentnl", $currentnl, $htmlmesg);
			$textmesg = str_replace("currentnl", $currentnl, $textmesg);
			$textmesg = ereg_replace ("\r", "", $textmesg);
			$htmlmesg = str_replace("currentmesg", $mi, $htmlmesg);
			for ($i=10; $i>=1; $i--) {
				$subject  = str_replace("subscriberfield" . $i, ${'field' . $i}, $subject);
				$textmesg = str_replace("subscriberfield" . $i, ${'field' . $i}, $textmesg);
				$htmlmesg = str_replace("subscriberfield" . $i, ${'field' . $i}, $htmlmesg);
			}
			$htmlmesg = stripslashes($htmlmesg);
			$textmesg = stripslashes($textmesg);

?>
<html>
<head>
<title><?PHP print $subject; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=<?PHP print $lang_char; ?>">
</head>
<body>
<table width="750" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td><font size="3"><b><font size="3"><b><font size="2" face="Arial, Helvetica, sans-serif"><b><font size="2" face="Arial, Helvetica, sans-serif"><b><font size="2" face="Arial, Helvetica, sans-serif"><font size="3"><b><font size="3"><b><font size="2" face="Arial, Helvetica, sans-serif"><b><font size="2" face="Arial, Helvetica, sans-serif"><b><font size="2" face="Arial, Helvetica, sans-serif"><font size="4" face="Arial, Helvetica, sans-serif" color="#336699"> 
      </font></font></b></font></b></font></b></font></b></font></font></b></font></b></font></b></font></b></font> 
      <font color="#336699" size="4" face="Arial, Helvetica, sans-serif"><strong><?PHP print $lang_34; ?><br>
      </strong></font><b><font size="2" face="Arial, Helvetica, sans-serif"> </font></b><font size="2" face="Arial, Helvetica, sans-serif"><b><br>
      </b></font> <table width="400" border="0" cellspacing="0" cellpadding="0">
        <tr> 
          <td width="70" height="25"><font size="2" face="Arial, Helvetica, sans-serif"><b><?PHP print $lang_29; ?></b>:</font></td>
          <td height="25"><font size="2" face="Arial, Helvetica, sans-serif"> 
            <?PHP	print $row5["mdate"];	?>
            </font></td>
        </tr>
        <tr> 
          <td width="70" height="25"><font size="2" face="Arial, Helvetica, sans-serif"><b><?PHP print $lang_30; ?></b>:</font></td>
          <td height="25"><font size="2" face="Arial, Helvetica, sans-serif"> 
            <?PHP	print $row5["mtime"];	?>
            </font></td>
        </tr>
        <tr> 
          <td width="70" height="25"><font size="2" face="Arial, Helvetica, sans-serif"><b><?PHP print $lang_35; ?></b>:</font></td>
          <td height="25"><font size="2" face="Arial, Helvetica, sans-serif"> 
            <?PHP	print $row5["mfrom"];	?>
            </font></td>
        </tr>
        <tr> 
          <td width="70" height="25"><strong><font size="2" face="Arial, Helvetica, sans-serif"><br>
            <?PHP print $lang_31; ?>:</font></strong></td>
          <td height="25"><strong><font size="2" face="Arial, Helvetica, sans-serif"> 
            <br>
            <?PHP print stripslashes($subject); ?> </font></strong></td>
        </tr>
      </table>
      <p><br>
        <font size="2" face="Arial, Helvetica, sans-serif"> 
        <?PHP 
		  if ($row["type"] != text){
		?>
        <font size="4"><b><font size="3"><?PHP print $lang_36; ?></font></b></font></font> 
      </p>
      <table width="100%" border="0" cellspacing="0" cellpadding="1" bgcolor="#D5E2F0">
        <tr> 
          <td> <table width="100%" border="0" cellspacing="0" cellpadding="10" bgcolor="#FFFFFF">
              <tr> 
                <td> <p><font size="2" face="Arial, Helvetica, sans-serif"> 
                    <?PHP	
			  		print $htmlmesg;	
			  		?>
                    </font><font size="2" face="Arial, Helvetica, sans-serif"> 
                    </font></p></td>
              </tr>
            </table></td>
        </tr>
      </table>
      <br> <br> <font size="2" face="Arial, Helvetica, sans-serif"> 
      <?PHP } ?>
      <?PHP 
  if ($row["type"] != html){
  ?>
      <font size="4"><b><font size="3"><?PHP print $lang_37; ?></font></b></font></font> 
      <table width="100%" border="0" cellspacing="0" cellpadding="1" bgcolor="#D5E2F0">
        <tr> 
          <td> <table width="100%" border="0" cellspacing="0" cellpadding="10" bgcolor="#FFFFFF">
              <tr> 
                <td> <p><font size="2" face="Arial, Helvetica, sans-serif"> 
                    <?PHP	
					$textmesg = nl2br($textmesg);
					print $textmesg;	
					?>
                    </font><font size="2" face="Arial, Helvetica, sans-serif"> 
                    </font></p></td>
              </tr>
            </table></td>
        </tr>
      </table>
        <?PHP }  ?>
      <p><font size="2" face="Arial, Helvetica, sans-serif"><a href="box.php?nl=<?PHP print $nl; ?>"><?PHP print $lang_38; ?></a></font></p>
      </td>
  </tr>
</table>
</body>
</html>