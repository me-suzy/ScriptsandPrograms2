<? include("../conf.inc.php");
include("../functions.inc.php");
admin_login();
if (!isset($to)){$to=1000000/$admin_cash_factor;}
if (!isset($from)){$from=-1000000/$admin_cash_factor;}
echo "<title>Cash Earnings Report</title><script>window.focus()</script>
<STYLE TYPE=\"text/css\">
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
<center><h2>Cash Earnings Report</h2><hr></center><form method=post>List all members with account balances ranging<br>from: <input type=text name=from value=$from> to: <input type=text name=to value=$to><input type=submit name=report value=Report></form>";
if ($report){
$f=$from*100000*$admin_cash_factor;
$t=$to*100000*$admin_cash_factor;
@mysql_query("drop table tmpcashtbl");
@mysql_query("create table tmpcashtbl (username char(64) not null, amount bigint not null, key amount(amount))");
@mysql_query("insert into tmpcashtbl (username,amount) select username,sum(amount) from ".$mysql_prefix."accounting where type='cash' group by username");
$report=@mysql_query("select tmpcashtbl.amount,".$mysql_prefix."users.username,pay_type,pay_account from tmpcashtbl,".$mysql_prefix."users where tmpcashtbl.username=".$mysql_prefix."users.username and amount>=$f and amount<=$t order by amount desc");
echo "<br><table class=fsize2 border=1><tr><td><b>Username</b></td><td><b>Amount</b></td><td><b>Pay Type</b></td><td><b>Pay Account</b>";
while($row=@mysql_fetch_array($report)){
echo "</td></tr><tr $bgcolor><td><a href=viewuser.php?userid=$row[username] target=_viewuser>$row[username]</a></td><td align=right>".number_format($row[amount]/100000/$admin_cash_factor,5)."</td><td>";
$paylinkamount=number_format($row[amount]/100000/$admin_cash_factor-system_value("processfee"),2);
$returnurl=$scripts_url."admin/transactions.php".urlencode("?save=1&formusername=$row[username]&description=".system_value("paydesc")."&type=cash&amount=-".number_format($row[amount]/100000/$admin_cash_factor,2));
if (strtolower($row[pay_type])=='paypal'){
echo "<a href=https://www.paypal.com/xclick/business=$row[pay_account]&item_name=".urlencode(system_value("paydesc"))."&amount=$paylinkamount&return=$returnurl target=_paypal>PayPal</a>";}
elseif (strtolower($row[pay_type])=='egold' or strtolower($row[pay_type])=='e-gold'){
echo "<a href=https://www.e-gold.com/sci_asp/payments.asp?PAYEE_NAME=$row[username]&PAYMENT_URL=$returnurl&NOPAYMENT_URL=$scripts_url/admin/cashearnings.php&BAGGAGE_FIELDS=DESCRIPTION&DESCRIPTION=".urlencode(system_value("paydesc"))."&SUGGESTED_MEMO=".urlencode(system_value("paydesc"))."&PAYEE_ACCOUNT=$row[pay_account]&PAYMENT_AMOUNT=$paylinkamount&PAYMENT_UNITS=1&PAYMENT_METAL_ID=0 target=_egold>e-Gold</a>";}
else { echo $row[pay_type];}
echo "</td><td>$row[pay_account]";
if($bgcolor){$bgcolor='';}else{$bgcolor='bgcolor=#DDDDDD';}
}
echo "</td></tr></table>";}
@mysql_query("drop table tmpcashtbl");
