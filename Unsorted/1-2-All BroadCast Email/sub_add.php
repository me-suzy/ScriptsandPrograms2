<p><font color="#336699" size="4" face="Arial, Helvetica, sans-serif"><strong><?PHP print $lang_359; ?> 
  <?PHP if ($val != final){ ?>
  <font size="3"><b><font size="3"><b><font size="2" face="Arial, Helvetica, sans-serif"><b><font size="2" face="Arial, Helvetica, sans-serif"><b><font size="2" face="Arial, Helvetica, sans-serif"><font size="4" face="Arial, Helvetica, sans-serif" color="#336699"> 
  </font></font></b></font></b></font></b></font></b></font></strong></font></p>
<form name="form1" method="post" action="main.php">
  <table width="450" border="0" cellspacing="0" cellpadding="5">
    <tr> 
      <td width="100" bgcolor="#F3F3F3"><div align="left"><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_5; ?></font></div></td>
      <td width="350" bgcolor="#F3F3F3"><font size="2" face="Arial, Helvetica, sans-serif"> 
        <input name="email" type="text" id="email">
        </font></td>
    </tr>
    <tr> 
      <td width="100"><div align="left"><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_4; ?></font></div></td>
      <td width="350"><font size="2" face="Arial, Helvetica, sans-serif"> 
        <input name="name" type="text" id="name">
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
        <input type="text" name="field<?PHP print $cnumc; ?>">
        </font></td>
    </tr>
    <?PHP
		}
		$cnumc = $cnumc + 1;
		}
		?>

	
    <tr> 
      <td width="100"><font size="2" face="Arial, Helvetica, sans-serif">&nbsp;</font></td>
      <td width="350"><p><font size="2"><font face="Arial, Helvetica, sans-serif"> 
          <br>
          <input type="submit" name="Submit" value="<?PHP print $lang_21; ?>">
          <input name="val" type="hidden" id="val" value="final">
          </font><font size="2"><font face="Arial, Helvetica, sans-serif"> </font><font size="2"><font size="2"><font face="Arial, Helvetica, sans-serif"> 
          <input name="nl" type="hidden" id="nl" value="<?PHP print $nl; ?>">
          </font><font size="2"><font size="2"><font size="2"><font size="2"><font face="Arial, Helvetica, sans-serif">
          <input name="page" type="hidden" id="page" value="sub_add">
          </font></font></font></font></font><font face="Arial, Helvetica, sans-serif"> 
          </font></font></font></font><font face="Arial, Helvetica, sans-serif"> 
          </font></font></p></td>
    </tr>
  </table>
  </form>
<?PHP
}
else {
?>
<p><font size="2" face="Arial, Helvetica, sans-serif" color="#FF0000">
  <?PHP
function plugin($email)
{
		$pat1 = "@";	
		$emailarr = split ($pat1,$email);		
		$email1 = $emailarr[0];
		$email2 = $emailarr[1];			
		$email = trim($email);
		$elen = strlen($email);
		$dotpresent = 0;
		for ($i=2;$i<=$elen;$i++)
		{
			$j = substr($email,0,$i);
			$jlen = strlen($j);			
			$lastj = substr($j,$jlen-1,$jlen);			
			$asci = ord($lastj);				
			if ($asci==46)
			{				
				$dotpresent = 1;
			}						
		}	
		$spaceexist = 0;
		for ($k=0;$k<$elen;$k++)
		{
			$myword = substr($email,$k,1);			
			if (ord($myword)==32)
			{
				$spaceexist = 1;
			}
			
		}				
		if ($email2)
		{
			$atpresent = 1;
		}		
		if ($atpresent=='1' AND $dotpresent=='1' AND $spaceexist=='0')
		{
			$validmail = 1;
		}
		else
		{
			$validmail = 0;
		}		
		return ($validmail);		
}		
		$validmail = plugin($email);
		if ($validmail == 1){
		$findcount = mysql_query ("SELECT * FROM ListMembers
                         WHERE email LIKE '$email'
						 AND active LIKE '0'
						 AND nl LIKE '$nl'
                       ");
$countdata = mysql_num_rows($findcount);	
if ($countdata != 0)
{
print "$lang_39 [ $email ] $lang_40";
die();
}
				$check = mysql_query ("SELECT * FROM Lists
                         WHERE id LIKE '$nl'
						 limit 1
                       ");
				$chk = mysql_fetch_array($check);
				if ($chk["a_mx"] != ""){
				$findcount = mysql_query ("SELECT COUNT(nl) FROM ListMembers
                         WHERE nl = '$nl'
						 AND email != ''
                       ");
				$num_email = mysql_result($findcount, 0, 0); 
				if ($num_email >= $chk["a_mx"]){
						$box_status = "1";
						print $lang_469;
						die();
						
				}
				}



$findcount2 = mysql_query ("SELECT * FROM Lists
						 WHERE id LIKE '$nl'
						 AND bk LIKE '%$email%'
                       ");

$countdata2 = mysql_num_rows($findcount2);	
if ($countdata2 != "0"){
print "<br>$lang_43";
die();
}
$today = date("Ymd");
$today_time = date("H:i:s");
mysql_query ("INSERT INTO ListMembers (email, name, nl, sdate, stime, sip, comp, field1, field2, field3, field4, field5, field6, field7, field8, field9, field10) VALUES ('$email' ,'$name' ,'$nl' ,'$today' ,'$today_time' ,'$REMOTE_ADDR' ,'$HTTP_USER_AGENT' ,'$field1' ,'$field2' ,'$field3' ,'$field4' ,'$field5' ,'$field6' ,'$field7' ,'$field8' ,'$field9' ,'$field10')");  
print "$lang_39  $email  $lang_13";
}	
else {
print "$lang_360";
}
?>
  <br>
  </font> </p>
<p> <font size="2" face="Arial, Helvetica, sans-serif" color="#FF0000"> </font> 
  <?PHP } ?>
