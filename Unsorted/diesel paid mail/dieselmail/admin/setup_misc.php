<?
include ('../includes/global.php');

if($B1 == "Submit Variables" && $submit_admin == "go")
{
        $content = <<< HTM
<?
\$mail_by_advertiser = "$submit_mail_by_advertiser";
\$gold_membership = "$submit_gold_membership";
\$gold_membership_amount = "$submit_gold_membership_amount";
\$currency_symbol = "$submit_currency_symbol";
\$max_days_for_login = "$submit_max_days_for_login";
\$signup_bonus = "$submit_signup_bonus";
\$paid_email_charge = "$submit_paid_email_charge";
\$nonpaid_email_charge = "$submit_nonpaid_email_charge";
\$banner_ads_charge = "$submit_banner_ads_charge";
?>
HTM;

//writing variable to the vars_money.php file
file_writer("$include_path/vars_misc.php",$content);

Print <<< HTML
 <center><font color=green><b>Datas SuccessfullyUpdated</b></font><br><br>
 The page will automatically redirected to Admin page. <br><br>If not redirected within 5 seconds,Click the link.
 <a href="admin.php">Go to Admin Page</a>
<META HTTP-EQUIV=REFRESH CONTENT="2; URL=admin.php"></center>
HTML;
exit;
}
if(!$gold_membership_amount) { $gold_membership_amount=0;}
if(!$currency_symbol) { $currency_symbol="$";}
if(!$max_days_for_login) { $max_days_for_login=0;}
if(!$signup_bonus) { $signup_bonus=0;}
if(!$paid_email_charge) { $paid_email_charge=0;}
if(!$nonpaid_email_charge) { $nonpaid_email_charge=0;}
if(!$banner_ads_charge) { $banner_ads_charge=0;}

print <<<HTML
<title>Variable Setup: Admin/Site Information</title>
<center><u><b><font face="arial" size="3">Variable Setup: Admin/Misc Information</font></b></u></center>
<p>
<form method="POST" action="setup_misc.php" name="frm1">
<input type="hidden" name="submit_admin" value="go">
<center>
HTML;
if($gold_membership == "ON")
{
$gold_membership_option= <<<HTML
<input type="radio" name="submit_gold_membership" value="ON" checked onclick="Click()">ON&nbsp;&nbsp;
<input type="radio" name="submit_gold_membership" value="OFF" onclick="Click()">OFF<br><br>
HTML;
}
else
{
$gold_membership_option=<<<HTML
<input type="radio" name="submit_gold_membership" value="ON" >ON&nbsp;&nbsp;
<input type="radio" name="submit_gold_membership" value="OFF"  checked >OFF<br><br>
HTML;
}

print <<<HTML
<br>
<font face="arial" size="2">
This option is to set the Gold Membership Option ON/OFF for <br>all members of the site.<br>
$gold_membership_option
<br><br>

Enter the membership amount for Gold Membership Registeration: (Use 0.00 format)<br>
<input type="text" name="submit_gold_membership_amount" value="$gold_membership_amount" size="5"><br><br>

Enter the currency symbol you want to use in the site:<br>
<input type="text" name="submit_currency_symbol" value="$currency_symbol" size="10"><br><br>

Enter the maximum days limit where a member account to be freezed when he/she not logged on to the site:<br>
<input type="text" name="submit_max_days_for_login" value="$max_days_for_login" size="10"><br><br>

Enter the amount to be to be credited as Sign Up Bonus to members: (Use 0.00 format)<br>
<input type="text" name="submit_signup_bonus" value="$signup_bonus" size="10"><br><br>
</font>
  <p><input type="submit" value="Submit Variables" name="B1"></p>
  </center>
</form>
<script language="javascript">
function Click()
{

   if(document.frm1.submit_gold_membership.selected="ON")
   {
      alert ("ON");
   }
   elseif(document.frm1.submit_gold_membership.selected)
   {
      alert ("OFF");
   }
}
</script>
<br><br><pre>Click the link. <a href="$admin_url/admin.php">Go to Admin Index Page</a></pre>
HTML;

?>
