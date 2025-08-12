<p><font size="4" face="Arial, Helvetica, sans-serif"><strong>Account Details</strong></font><font color="#336699" size="4" face="Arial, Helvetica, sans-serif"><strong> 
  <? if ($val != final){ ?>
  <font size="3"><b><font size="3"><b><font size="2" face="Arial, Helvetica, sans-serif"><b><font size="2" face="Arial, Helvetica, sans-serif"><b><font size="2" face="Arial, Helvetica, sans-serif"><font size="4" face="Arial, Helvetica, sans-serif" color="#336699"> 
  <?php
//////////////////////////////////////////////////////////////////////////////                      
//                                                                          //
//  Program Name         : Calander Now Pro                                 //
//  Program version      : 2.0                                              //
//  Program Author       : Jason VandeBoom                                  //
//  Supplied by          : drew010                                          //
//  Nullified by         : CyKuH [WTN]                                      //
//  Distribution         : via WebForum, ForumRU and associated file dumps  //
//                                                                          //
//////////////////////////////////////////////////////////////////////////////                      
		  $result = mysql_query ("SELECT * FROM cnpAdmin
                         WHERE user LIKE '$usernow'
						 
						 limit 1
                       ");
$row = mysql_fetch_array($result)
?>
  </font></font></b></font></b></font></b></font></b></font></strong></font></p>
<form name="form1" method="post" action="">
  <table width="450" border="0" cellspacing="0" cellpadding="5">
    <tr> 
      <td width="100" bgcolor="#F3F3F3"><div align="left"><font size="2" face="Arial, Helvetica, sans-serif">Username</font></div></td>
      <td width="350" bgcolor="#F3F3F3"><font size="2" face="Arial, Helvetica, sans-serif"> 
        <?php	print $row["user"];	?></font></td>
    </tr>
    <tr> 
      <td width="100"><div align="left"><font size="2" face="Arial, Helvetica, sans-serif">Password</font></div></td>
      <td width="350"><font size="2" face="Arial, Helvetica, sans-serif"> 
        <input name="pass" type="password" id="pass" value="<?php $passnow=base64_decode ($row["pass"]); print $passnow; ?>">
        </font></td>
    </tr>
    <tr> 
      <td width="100" bgcolor="#F3F3F3"><div align="left"><font size="2" face="Arial, Helvetica, sans-serif">Name</font></div></td>
      <td width="350" bgcolor="#F3F3F3"><font size="2" face="Arial, Helvetica, sans-serif"> 
        <input name="name" type="text" id="name" value="<?php	print $row["name"];	?>">
        </font></td>
    </tr>
    <tr> 
      <td width="100"><div align="left"><font size="2" face="Arial, Helvetica, sans-serif">E-mail</font></div></td>
      <td width="350"><font size="2" face="Arial, Helvetica, sans-serif"> 
        <input name="email" type="text" id="email" value="<?php	print $row["email"];	?>">
        </font></td>
    </tr>
    <tr> 
      <td width="100"><font size="2" face="Arial, Helvetica, sans-serif">&nbsp;</font></td>
      <td width="350"><p><font size="2"><font face="Arial, Helvetica, sans-serif"> 
          <br>
          <input type="submit" name="Submit" value="Update">
          <input name="val" type="hidden" id="val" value="final">
          </font><font size="2"><font face="Arial, Helvetica, sans-serif"> </font><font size="2"><font size="2"><font face="Arial, Helvetica, sans-serif">
          <input name="nl" type="hidden" id="nl" value="<? print $nl; ?>">
          </font></font></font></font><font face="Arial, Helvetica, sans-serif"> 
          </font></font></p></td>
    </tr>
  </table>
  <br>
  <br>
  <table width="450" border="0" cellpadding="1" cellspacing="0" bgcolor="#333333">
    <tr> 
      <td><div align="center"><font color="#FFFFFF" size="2" face="Arial, Helvetica, sans-serif"></font></div>
        <table width="100%" border="0" cellpadding="4" cellspacing="0" bgcolor="#F3F3F3">
          <tr> 
            <td><div align="center"> 
                <p><font size="2" face="Arial, Helvetica, sans-serif"><strong><font color="#990000">NOTE</font><br>
                  </strong></font><font size="2" face="Arial, Helvetica, sans-serif">If 
                  you change the username and/or password,<br>
                  the software will request for you to re-login to<br>
                  your mailing list software.</font></p>
                </div>
              </td>
          </tr>
        </table></td>
    </tr>
  </table>
  <p>&nbsp;</p>
</form>
<?
}
else {
?>
<p><font size="2" face="Arial, Helvetica, sans-serif" color="#990000">Your account 
  settings have been updated.</font> <font size="2" face="Arial, Helvetica, sans-serif" color="#FF0000"> 
  <?php
$pass=base64_encode($pass);
mysql_query("UPDATE cnpAdmin SET pass='$pass',name='$name',email='$email' WHERE (user='$usernow')");
?>
  <br>
  </font> </p>
<p> <font size="2" face="Arial, Helvetica, sans-serif" color="#FF0000"> </font> 
  <? } ?>
