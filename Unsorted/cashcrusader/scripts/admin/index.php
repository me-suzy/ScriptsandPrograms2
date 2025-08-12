<? include("../conf.inc.php");
$admin_form=1;
include("../functions.inc.php");
include("updatecheck.php");
$mainindex=1;
admin_login();
?>
<title>Site Admin</title>
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
<center><form>
<? if ($keyis){@mysql_query("replace into ".$mysql_prefix."system_values set name='key',value='$keyis'");}?>
CashCrusader Version <? echo system_value("version");?><br>
License Key: <? echo system_value("key");?><br>
<h3>Access Logs</h3>
<textarea rows=5 cols=80>
<? echo system_value("access log");?>
</textarea>
<br><b>Last System Job: <? echo system_value("last job");?></b>
<table class=fsize2 border=0 width=620><tr><td valign=top width=250>
<hr><h3>Site settings</h3>
<li><a href=password.php target=_pass>Set Admin Password</a><br>
<li><a href=incentivesettings.php target=_incentivesettings>Signup Bonus Settings</a><br>
<li><a href=commission_settings.php target=_commissions>Commission Settings</a>
<br><li><a href=emailsettings.php target=_email>Site eMail Settings</a>
<hr><h3>Blocking settings</h3>
<li><a href=browser.php target=_browser>Block certain browsers</a><br>
<li><a href=emails.php target=_emails>Block email addresses and domains</a><br>
<li><a href=ips.php target=_ips>Block IPs and host names</a>
<hr><h3>Database Utilities</h3>
<li><a href=backup.php>Download a complete backup of your Mysql data</a><br>
<li><a href=maillist.php>Download a text copy of your members email addresses to use with 3rd party mailers OWNED BY YOU</a>
<hr><h3>Clicks/Referrals Management</h3>
<li><a href=viewrefs.php target=_viewrefs>View/Reset referral counter stats</a><br>
<li><a href=viewclicks.php target=_viewclicks>View/Reset click counter stats</a><br>
<li><a href=clickcontest.php target=_clickcontest>Randomly pick click contest winners from and advertisments click log</a><br>
<hr><h3>User Management</h3>
<li><a href=usermanager.php target=_usermanager>Search/View/Move/Edit/Delete Users</a><br>
<li><a href=inactive.php target=_inactive>List/Delete Inactive members</a><br>
<li><a href=dupfinder.php target=_dupfinder>Find accounts that DO belong to cheaters</a><br>
<li><a href=posdupfind.php target=_posdupfind>Find account that MAY belong to cheaters</a><br>
<hr><h3>Accounting</h3>
<li><a href=redeemmgr.php target=_redeem>Manage Redemption types</a><br>
<li><a href=transactions.php target=_transactions>Post Credits and Debits</a><br>
<li><a href=cashearnings.php target=_reporting>Account balance report (cash)</a>
<br><li><a href=pointearnings.php target=_reporting>Account balance report (points)</a> 
<br><li><a href=convertpoints.php target=_convert>Convert ALL points to cash</a>
<hr><h3>Ad Management</h3>
<li><a href=startpage.php target=_startpage>Paid Start Page Settings</a><br>
<li><a href=ptcadmgr.php target=_ptcadmgr>Manage paid to click ad campaigns</a><br>
<li><a href=oldptcads.php target=_oldptcads>List/Delete old paid to click ads</a><br>
<li><a href=admgr.php target=_admgr>Manage rotating ad campaigns</a><br>
<li><a href=oldrotatingads.php target=_oldrotatingads>List/Delete old rotating ads</a><br>
<li><a href=emailadmgr.php target=_emailadmgr>Manage email ad campaigns</a><br>
<li><a href=oldads.php target=_oldemailads>List/Delete old email ads</a><br>
<li><a href=massmail.php target=_sendmail>Send an email to members</a>
<hr><h3>Support</h3>
<li><a href=http://cashcrusader.myecom.net/faq/question.php?lang=en&type=new&prog=cashcrusad&onlynewfaq=1 target=_support>Send Email</a>
</td><td valign=top>
<center><h3>Site Stats</h3>
<table class=fsize2 border=1 width=325><tr><td>
Total members</td><td align=right><? usercount()?></td></tr><tr><td>
Total Self Cash Earnings</td><td align=right><?
list($cash)=@mysql_fetch_row(@mysql_query("select sum(amount) from ".$mysql_prefix."accounting where type='cash' and description='#SELF-EARNINGS#'"));
$total=$cash;
echo number_format($cash/100000/$admin_cash_factor,5); 
?>
</td></tr><tr><td>Total Downline Cash Earnings</td><td align=right><?
list($cash)=@mysql_fetch_row(@mysql_query("select sum(amount) from ".$mysql_prefix."accounting where type='cash' and description='#DOWNLINE-EARNINGS#'"));
$total=$total+$cash;
echo number_format($cash/100000/$admin_cash_factor,5);?>
</td></tr><tr><td>Total Applied Cash Credits</td><td align=right><?
list($cash)=@mysql_fetch_row(@mysql_query("select sum(amount) from ".$mysql_prefix."accounting where type='cash' and amount>0 and description!='#SELF-EARNINGS#' and description!='#DOWNLINE-EARNINGS#'"));
$total=$total+$cash;
echo number_format($cash/100000/$admin_cash_factor,5);?>
</td></tr><tr><td>Total Applied Cash Debits</td><td align=right><?
list($cash)=@mysql_fetch_row(@mysql_query("select sum(amount) from ".$mysql_prefix."accounting where type='cash' and amount<0 and description!='#SELF-EARNINGS#' and description!='#DOWNLINE-EARNINGS#'"));
$total=$total+$cash;
echo number_format($cash/100000/$admin_cash_factor,5);?>
</td></tr><tr><td>Cash Grand Total</td><td align=right><?
echo number_format($total/100000/$admin_cash_factor,5);?>
</td></tr></table>
<table class=fsize2 border=1 width=325><tr><td>
<tr><td>
Total Self Point Earnings</td><td align=right><?
list($points)=@mysql_fetch_row(@mysql_query("select sum(amount) from ".$mysql_prefix."accounting where type='points' and description='#SELF-POINT-EARNINGS#'"));
$total=$points;
echo number_format($points/100000,5);
?>
</td></tr><tr><td>Total Downline Point Earnings</td><td align=right><?
list($points)=@mysql_fetch_row(@mysql_query("select sum(amount) from ".$mysql_prefix."accounting where type='points' and description='#DOWNLINE-POINT-EARNINGS#'"));
$total=$total+$points;
echo number_format($points/100000,5);?>
</td></tr><tr><td>Total Applied Point Credits</td><td align=right><?
list($points)=@mysql_fetch_row(@mysql_query("select sum(amount) from ".$mysql_prefix."accounting where type='points' and amount>0 and description!='#SELF-POINT-EARNINGS#' and description!='#DOWNLINE-POINT-EARNINGS#'"));
$total=$total+$points;
echo number_format($points/100000,5);?>
</td></tr><tr><td>Total Applied Point Debits</td><td align=right><?
list($points)=@mysql_fetch_row(@mysql_query("select sum(amount) from ".$mysql_prefix."accounting where type='points' and amount<0 and description!='#SELF-POINT-EARNINGS#' and description!='#DOWNLINE-POINT-EARNINGS#'"));
$total=$total+$points;
echo number_format($points/100000,5);?>
</td></tr><tr><td>Point Grand Total</td><td align=right><?
echo number_format($total/100000,5);?>
</td></tr></table>


<table class=fsize2 border=1 width=325>
<tr><td><b>Keyword</b></td><td><b>Total Members</b></td></tr>
<? keyword_totals();?>
</table>
</td></tr><tr><td colspan=2>
<?
$fp = fsockopen("myecom.net", 80,$errno, $errstr, 10);
if($fp) {
list($count)=@mysql_fetch_row(@mysql_query("select count(*) from ".$mysql_prefix."users"));
    fputs($fp,"GET /news.php?domain_name=$domain&url=$pages_url&count=$count&key=".system_value("key")." HTTP/1.0\r\nHost: cashcrusader.myecom.net\r\n\r\n"); 
    $start = time();
    socket_set_timeout($fp, 10);   
    $res = fread($fp, 100000);
    fclose($fp); 
    $res=split("<html>",$res);
echo $res[1];
}
?>

</td></tr></table>

