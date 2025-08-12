<? include("../conf.inc.php");
include("../functions.inc.php");
admin_login();
if ($sysval){
while (list($key, $value) = each($sysval)){
if ($key=='accounting_db' or $key=='accounting_tbl'){
if (@mysql_num_rows(@mysql_query("describe ".$sysval[accounting_db].".".$sysval[accounting_tbl]))>0 and ereg("accounting",$sysval[accounting_tbl])){
@mysql_query("replace into ".$mysql_prefix."system_values set name='$key',value='".trim($value)."'");
}} else {
@mysql_query("replace into ".$mysql_prefix."system_values set name='$key',value='".trim($value)."'");
}}}?>
<html><title>Commission Settings</title><script>window.focus();</script>
<STYLE TYPE="text/css">
  <!--
    A {text-decoration:none;}
    A:hover {text-decoration:underline;}
    .fsize1 {font-family: Arial, Helvetica, sans-serif; font-size: 11px;}
    .fsize2 {font-family: Arial, Helvetica, sans-serif; font-size: 13px;}
    .fsize3 {font-family: Arial, Helvetica, sans-serif; font-size: 14px;}
    .fsizebig {font-family: Arial, Helvetica, sans-serif; font-size: 18px;}
 -->
  </STYLE>
<body bgcolor=ffffff><font face=arial size=2 class=fsize2>
<center><h2>Commission Settings</h2>
<hr>
<br><br><form method=post><table class=fsize2 border=1>
<tr><td>
Set payment processing fee and payment description for Mass Pay and Quicklinks<br><br>
<table border=0 class=fsize2><tr><td>Processing Fee: </td><td><input type=text name=sysval[processfee] value="<? echo system_value("processfee");?>"></td></tr>
<tr><Td>Description: </td><td><input type=text name=sysval[paydesc] value="<? echo system_value("paydesc");?>"></td></tr></table></td></tr>
<tr><td>
You can have each CashCrusader site post commissions to its own accounting database, or have it post to one central CashCrusader database. This is handy if you have many CashCrusader sites for different services and want to post the commissions to one central CashCrusader site. <br>
<br>Do not change the "Database Name" or "Accounting Table Name" settings unless you know what you are doing<br><br>
<table border=0 class=fsize2><tr><td>Database Name:</td><td> <input type=text size=30 name=sysval[accounting_db] value="<? echo system_value("accounting_db");?>"></td></tr><tr><td>Accounting Table Name: </td><td><input type=text size=30 name=sysval[accounting_tbl] value="<? echo system_value("accounting_tbl");?>"></td></tr></table></td></tr><tr><td>
You can have both points and cash referral bonuses active at the same time.  Place 0 in the value to disable referral bonuses<table class=fsize2 border=0><tr><td>Cash Referral Bonus</td><td><input type=text name=sysval[cashreferbonus]  value=<? print system_value("cashreferbonus");?>></td></tr><tr><td>
Points Referral Bonus </td><td><input type=text name=sysval[pointreferbonus] value=<? print system_value("pointreferbonus");?>></td></tr></table></td></tr><tr><td>
Set commission rates for sales transactions posted manualy or using IPN<br>
Example: if your site pays 15% for direct referral sales, 10% for second level referral sales and 5% for third level referral sales you would enter: <b>15,10,5</b><br><br><table class=fsize2 border=0><tr><td>Sales Commissions:</td><td> <input type=text size=30 name=sysval[sales_comm] value="<? print system_value("sales_comm");?>"></td></tr><tr><td>Description:</td><td> <input type=text size=30 name=sysval[sales_desc] value="<? print system_value("sales_desc");?>"></td></tr></table>
</td></tr><tr><td>
Set the percentage amount you would like to credit uplines when their downline clicks on an ad<br>Example: if your site pays 15% for direct referral clicks, 10% for second level referral clicks and 5% for third level referral clicks you would enter: <b>15,10,5</b><br><br>
<table class=fsize2 border=0><tr><td>Cash clicks:</td><td> <input type=text size=30 name=sysval[cashclicks] value="<? print system_value("cashclicks");?>"></td></tr>
<tr><td>Point clicks:</td><td><input type=text size=30 name=sysval[pointclicks] value="<? print system_value("pointclicks");?>"></td></tr></table></td></tr>
<tr><td>When crediting uplines do not credit accounts that have not clicked on an advertiser in <input type=text name=sysval[nocreditdays] value=<? print system_value("nocreditdays");?>> days <br>(to always credit regardless of click status put 0)</td></tr><tr><td>Only credit upline if they are <input type=text name=sysval[nocreditclicks] value=<? print system_value("nocreditclicks");?>>% as active as the downline member that clicked. <br>(In order to make money from their downline they have to be active clickers. If you put in 100% then they will have had to click on as many ads as the downline member to get credit from them. If you set it to 50% then they will have had to click on at least half as many ads as the downline member to get credit from them . 0% means they will get credit from their downline's clicks regardless of how many ads they have clicked on directly</td></tr></table> 
<input type=submit value='Save Changes'></form>

