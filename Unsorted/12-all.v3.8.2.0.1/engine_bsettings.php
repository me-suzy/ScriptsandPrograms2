<p><font color="#336699" size="4" face="Arial, Helvetica, sans-serif"><strong><?PHP print $lang_88; ?></strong></font></p>
<p><font color="#336699" size="4" face="Arial, Helvetica, sans-serif"><font size="3"><b><font size="3"><b><font size="2" face="Arial, Helvetica, sans-serif"><b><font size="2" face="Arial, Helvetica, sans-serif"><b><font size="2" face="Arial, Helvetica, sans-serif">
  <?PHP
                  $result = mysql_query ("SELECT * FROM Backend
                         WHERE valid LIKE '1'

                                                 limit 1
                       ");
$row = mysql_fetch_array($result)
?>
  </font></b></font></b></font></b></font></b></font></font>
  <?PHP
if ($action != save){
?>
</p>
<form action="main.php" method="post" name="" id="">
  <p><strong><font size="2" face="Arial, Helvetica, sans-serif"><?PHP print $lang_90; ?>:</font></strong></p>
  <p><img src="media/line_mblue.gif" width="500" height="2"></p>
  <p><font size="2"><font face="Arial, Helvetica, sans-serif"><strong>
    <input name="method" type="radio" value="pop" <?PHP        if($row["btype"] == pop){ print "checked"; } ?>>
    <?PHP print $lang_91; ?></strong><br>
    <font size="1"><?PHP print $lang_92; ?></font></font></font></p>
  <blockquote>
    <table width="385" border="0" cellpadding="1" cellspacing="0" bgcolor="#D5E2F0">
      <tr>
        <td><table width="383" border="0" cellspacing="0" cellpadding="5">
            <tr bgcolor="#FFFFFF">
              <td width="50%"><div align="center"><font size="2" face="Arial, Helvetica, sans-serif">POP
                  E-mail Address</font><br>
                  <font size="2" face="Arial, Helvetica, sans-serif">
                  <input name="pop_em" type="text" id="pop_em" value="<?PHP print $row["pop_em"]; ?>">
                  <br>
                  </font></div></td>
              <td width="50%"><div align="center"><font size="1" face="Arial, Helvetica, sans-serif">Number
                  of times an address may bounce before being removed <br>
                  </font><font size="2" face="Arial, Helvetica, sans-serif">
                  <input name="pop_nu" type="text" id="pop_nu" value="<?PHP
                                  print $row["pop_nu"];
                                  ?>" size="8">
                  </font><font size="1" face="Arial, Helvetica, sans-serif"> </font></div></td>
            </tr>
            <tr bgcolor="#FFFFFF">
              <td width="50%"><div align="center"><font size="2" face="Arial, Helvetica, sans-serif">POP
                  Host<font color="#666666"><br>
                  (IE: pop.domain.com)</font><br>
                  <input name="pop_ho" type="text" id="pop_ho" value="<?PHP print $row["pop_ho"]; ?>">
                  </font></div></td>
              <td width="50%"><div align="center"><font size="2" face="Arial, Helvetica, sans-serif">POP
                  Port<font color="#666666"><br>
                  (Default is 110)</font><br>
                  <input name="pop_po" type="text" id="pop_po" value="<?PHP
                                  $em = $row["pop_po"];
                                  if ($em == ""){
                                  print "110";
                                  }
                                  else {
                                  print $em;
                                  }
                                  ?>" size="8">
                  </font></div></td>
            </tr>
            <tr bgcolor="#FFFFFF">
              <td width="50%"><div align="center"><font size="2" face="Arial, Helvetica, sans-serif">POP
                  Username<br>
                  <input name="pop_us" type="text" id="pop_us" value="<?PHP print $row["pop_us"]; ?>">
                  </font></div></td>
              <td width="50%"><div align="center"><font size="2" face="Arial, Helvetica, sans-serif">POP
                  Password<br>
                  <input name="pop_pa" type="password" id="pop_pa" value="<?PHP print base64_decode($row["pop_pa"]); ?>">
                  </font></div></td>
            </tr>
            <tr bgcolor="#FFFFFF">
              <td colspan="2"><div align="center"><font size="2" face="Arial, Helvetica, sans-serif">Bounce Management
                  Options</font><br>
                  <table width="340" border="0" cellspacing="0" cellpadding="3">
                    <tr valign="top">
                      <td width="20" height="25"><input name="pop_op" type="radio" value="0" <?PHP if ($row["pop_op"] == "0"){ print "checked"; } ?>></td>
                      <td height="25"><font size="2" face="Arial, Helvetica, sans-serif">Process
                        bounced addresses without review.</font></td>
                    </tr>
                    <tr valign="top">
                      <td width="20"><input type="radio" name="pop_op" value="1" <?PHP if ($row["pop_op"] == "1"){ print "checked"; } ?>></td>
                      <td><font size="2" face="Arial, Helvetica, sans-serif">Require
                        review of addresses before processing bounced addresses.<font size="1">
                        (Will display addresses and options)</font></font></td>
                    </tr>
                  </table>

                </div></td>
            </tr>
          </table></td>
      </tr>
    </table>

  </blockquote>
  <p><img src="media/line_mblue.gif" width="500" height="1"></p>
  <p><font size="2"><font size="2"><font face="Arial, Helvetica, sans-serif"><strong>
    <input type="radio" name="method" value="none"  <?PHP        if($row["btype"] == none){ print "checked"; } ?>>
    </strong></font></font><strong><font face="Arial, Helvetica, sans-serif"><?PHP print $lang_95; ?></font></strong><font face="Arial, Helvetica, sans-serif">
    (Default)<br>
    <font size="1"><?PHP print $lang_96; ?></font></font></font></p>
  <p><img src="media/line_mblue.gif" width="500" height="1"></p>
  <p><font size="2" face="Arial, Helvetica, sans-serif">
    <input type="submit" value="<?PHP print $lang_98; ?>" name="submit2">
    <input name="page" type="hidden" id="page" value="engine_bsettings">
    <input name="action" type="hidden" id="action" value="save">
    <input name="nl" type="hidden" id="nl" value="<?PHP print $nl; ?>">
    </font> </p>
</form>
<p><br>
  <img src="media/line_mblue.gif" width="500" height="3"></p>
<p><font size="2" face="Arial, Helvetica, sans-serif"><strong>POP Frequently Asked
  Questions</strong></font></p>
<p><font size="2" face="Arial, Helvetica, sans-serif">Do the bounced e-mails stay
  on my POP server even after I check for Bounced mail via the control panel?</font></p>
<blockquote>
  <p><font size="2" face="Arial, Helvetica, sans-serif">No. If the bounced e-mail
    message is detected, the system will flag that e-mail address and delete that
    message.</font></p>
</blockquote>
<p><font size="2" face="Arial, Helvetica, sans-serif">Does this process affect
  any other mail in my pop account?</font></p>
<blockquote>
  <p align="left"><font size="2" face="Arial, Helvetica, sans-serif">No. Only
    mail that are bounces from your mailing system will be processed / scanned.</font></p>
</blockquote>
<p align="left"><font size="2" face="Arial, Helvetica, sans-serif">What does flagging
  mean or what does it mean when you say the system will flag an e-mail address?</font></p>
<blockquote>
  <p align="left"><font size="2" face="Arial, Helvetica, sans-serif">An e-mail
    address is flagged when it is found to have bounced. Every time that e-mail
    address bounces, the system keeps track of the flags. Upon being flagged 3
    times, the e-mail address is removed from the system. The amount of flags
    before removal may be modified.</font></p>
</blockquote>
<p align="left"><font size="2" face="Arial, Helvetica, sans-serif">It does not
  appear to be working, what should I do?</font></p>
<blockquote>
  <p align="left"><font size="2" face="Arial, Helvetica, sans-serif">Ensure that
    your POP3 information is entered correctly.</font></p>
</blockquote>
<p align="left"><font size="2" face="Arial, Helvetica, sans-serif">If you have
  any further questions, please visit the <a href="http://www./support" target="_blank">support
  center</a>.</font></p>
<p>
  <?PHP
}
else {
?>
  <?PHP
  if ($method == "pop"){
          if($pop_us == "" OR $pop_pa == "" OR $pop_ho == "" OR $pop_po == "" OR $pop_em == "" OR $pop_nu == ""){
                  print "Missing required fields.  Please go back and try again.";
                die();
          }
                $pop_pa=base64_encode($pop_pa);
                mysql_query("UPDATE Backend SET btype='$method',pop_ho='$pop_ho', pop_us='$pop_us', pop_po='$pop_po', pop_em='$pop_em', pop_pa='$pop_pa', pop_op='$pop_op', pop_nu='$pop_nu' WHERE (valid='1')");
  }
  else {
          mysql_query("UPDATE Backend SET btype='$method' WHERE (valid='1')");
  }
  ?>
  <font size="2" face="Arial, Helvetica, sans-serif" color="#990000"><?PHP print $lang_99; ?></font><font size="2" face="Arial, Helvetica, sans-serif" color="#FF0000">
  </font>

  <?PHP

}
?>
</p>