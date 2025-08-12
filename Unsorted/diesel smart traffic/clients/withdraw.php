<?php

include "../tpl/clients_top.ihtml";
include "../conf/sys.conf";
include "../lib/emails.lib";

if ($mode)
{
$db = c();

$r = q("select * from members where id='$auth'");
$sender=f($r);

q("INSERT INTO event (`id`, `sender`, `title`, `contents`, `type`, `user_id`, `credits`, `status`, `rdate`) VALUES ('', '0', 'Withdrawal : $amount credits. ($sender[login]) - $mode', '<b> Name : $sender[fname] $sender[lname] <br> Credits : $amount <br><br>Payment details : </b><br>$details', 'withdraw', '$auth', '-$amount', '1','".strtotime(date("d M Y H:i:s"))."')");

 echo "Request sent . Please keep the credits you want to exchange in storage. The admin will remove your credits that amount after the payment.";
d($db);
} else
{ ?>

<form name="form1" method="post" action="withdraw.php">
  <table width="600" border="0" align="center">
    <tr bgcolor="#F0F0F0"> 
      <td colspan="2"><strong><font color="#333333" size="2" face="Arial, Helvetica, sans-serif">Request 
        payment &gt;&gt;</font></strong></td>
    </tr>
    <tr> 
      <td><font color="#333333" size="2" face="Arial, Helvetica, sans-serif">Amount<br>
        <font size="1">(number of credits to be exchanged in money, minimum 20 000) </font></font></td>
      <td><font color="#333333" size="2" face="Arial, Helvetica, sans-serif"> 
        <input name="amount" type="text" value="1000" size="8">
        credits. </font></td>
    </tr>
    <tr> 
      <td><font color="#333333" size="2" face="Arial, Helvetica, sans-serif">Mode</font></td>
      <td><font color="#333333" size="2" face="Arial, Helvetica, sans-serif"> 
        <select name="mode" id="mode">
          <option value="Paypal" selected>Paypal</option>
          <option value="Check">Check</option>
          <option value="Wiretransfer">Wire Transfer</option>
          <option>E-Gold</option>
        </select>
        </font></td>
    </tr>
    <tr> 
      <td><font color="#333333" size="2" face="Arial, Helvetica, sans-serif">Your 
        details for receiving the payment<br>
        <font size="1">(any details needed to receive this payment like name, 
        account number, email, bank name, swift code ...) </font></font></td>
      <td><font color="#333333" size="2" face="Arial, Helvetica, sans-serif"> 
        <textarea name="details" cols="40" rows="4" id="details">Paypal email  :


</textarea>
        </font></td>
    </tr>
    <tr> 
      <td colspan="2"><div align="center"> 
          <input type="reset" name="Reset" value="Reset">
          <input type="submit" name="Submit2" value="Submit request">
        </div></td>
    </tr>
  </table>
</form>
<?php };

include "../tpl/clients_bottom.ihtml";
?>