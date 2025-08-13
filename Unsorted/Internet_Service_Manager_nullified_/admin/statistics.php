<?
//////////////////////////////////////////////////////////////
// Program Name         : Internet Service Manager             
// Program Version      : 1.0                                  
// Program Author       : InternetServiceManager.com 
// Supplied by          : Zeedy2k                              
// Nullified by         : CyKuH [WTN]                          
//////////////////////////////////////////////////////////////
$secion="management";
include "../conf.php";
include "auth.php";

include "header.php";


echo '<font face="'.$admin_font.'" size="2">';

echo '<P><B>Projects..</B><BR>';
echo 'Total: '.mysql_num_rows(mysql_query("SELECT * FROM projects"));
echo '<BR>Active: '.mysql_num_rows(mysql_query("SELECT * FROM projects WHERE status='0'"));

echo '<P><B>Billing..</B><BR>';
$res=mysql_query("SELECT * FROM invoices WHERE paid='0'");
while($r=mysql_fetch_array($res)){
   $ipay=mysql_query("SELECT * FROM money_received WHERE invoice='".$r[id]."'");
                                              $tpaid=0;
											  while($i=mysql_fetch_array($ipay)){
                                                  $tpaid=$tpaid+$i[amount];
                                              }
											  $outstanding=$outstanding+$r[amount]-$tpaid;
}
echo 'Total Outstanding: '.$payment_unit.round($outstanding,2);
echo '<BR>Invoices Not Settled: '.mysql_num_rows($res);
$res=mysql_query("SELECT * FROM clients WHERE account_balance>0");
while($r=mysql_fetch_array($res)){
$accb=$accb+$r[account_balance];
}
echo '<BR>Accumulated Account Balances: '.$payment_unit.round($accb,2);
$res=mysql_query("SELECT * FROM money_received");
while($r=mysql_fetch_array($res)){
$trec=$trec+$r[amount];
}
echo '<BR>Total Received: '.$payment_unit.round($trec,2);
$res=mysql_query("SELECT * FROM money_received WHERE date>'".(time()-2592000)."'");
while($r=mysql_fetch_array($res)){
$trec30=$trec30+$r[amount];
}
echo '<BR>Total Received <font size="1">[Last 30 Days]</font>: '.$payment_unit.round($trec,2);

$res=mysql_query("SELECT * FROM money_received WHERE date>'".(time()-31536000)."'");
while($r=mysql_fetch_array($res)){
$trec30=$trec30+$r[amount];
}
echo '<BR>Total Received <font size="1">[Last 365 Days]</font>: '.$payment_unit.round($trec,2);

echo '<P><B>Support..</B><BR>';
echo 'Total Tickets Unhandled: '.mysql_num_rows(mysql_query("SELECT * FROM support_tickets WHERE completed='0'"));
echo '<BR>Total Tickets: '.mysql_num_rows(mysql_query("SELECT * FROM support_tickets"));
$res=mysql_query("SELECT * FROM support_tickets WHERE completed='1'");
while($r=mysql_fetch_array($res)){
	if($r[reply_time]){
	$thistime=($r[reply_time]-$r[date])/60;
	$totaltimes=$totaltimes+$thistime;
	$totalticks++;
	}
}
echo '<BR>Average Response Time: '.@round($totaltimes/$totalticks,0).' minutes';
echo '<BR>Total Tickets <font size="1">[Last 30 Days]</font>: '.mysql_num_rows(mysql_query("SELECT * FROM support_tickets WHERE date>'".(time()-2592000)."'"));
$res=mysql_query("SELECT * FROM support_tickets WHERE completed='1' && date>'".(time()-2592000)."'");
$totaltimes=0; $totalticks=0;
while($r=mysql_fetch_array($res)){
	if($r[reply_time]){
	$thistime=($r[reply_time]-$r[date])/60;
	$totaltimes=$totaltimes+$thistime;
	$totalticks++;
	}
}
echo '<BR>Average Response Time <font size="1">[Last 30 Days]</font>: '.@round($totaltimes/$totalticks,0).' minutes';

echo '<P><B>Admins..</B><BR>';
$res=mysql_query("SELECT * FROM admins");
while($r=mysql_fetch_array($res)){
echo '<P><I><B>:::: '.$r[firstname].' '.$r[lastname].'('.$r[email].') ::::</B></I><BR>';
echo 'Support Tickets: '.mysql_num_rows(mysql_query("SELECT * FROM support_tickets WHERE reply_admin='".$r[id]."'"));
echo '<BR>Emails Received: '.mysql_num_rows(mysql_query("SELECT * FROM emails WHERE type='0' && to_id='".$r[id]."'"));
echo '<BR>Emails Sent: '.mysql_num_rows(mysql_query("SELECT * FROM emails WHERE type='1' && from_email LIKE '".$r[email]."'"));
echo '<BR>Client Chats: '.mysql_num_rows(mysql_query("SELECT * FROM chats WHERE admin_id='".$r[id]."'"));
echo '<BR><I>::::::: LAST 30 DAYS</I><BR>';
echo 'Support Tickets: '.mysql_num_rows(mysql_query("SELECT * FROM support_tickets WHERE reply_admin='".$r[id]."' && reply_time>'".(time()-2592000)."'"));
echo '<BR>Emails Received: '.mysql_num_rows(mysql_query("SELECT * FROM emails WHERE type='0' && to_id='".$r[id]."' && date>'".(time()-2592000)."'"));
echo '<BR>Emails Sent: '.mysql_num_rows(mysql_query("SELECT * FROM emails WHERE type='1' && from_email LIKE '".$r[email]."' && date>'".(time()-2592000)."'"));
echo '<BR>Client Chats: '.mysql_num_rows(mysql_query("SELECT * FROM chats WHERE admin_id='".$r[id]."' && lastdate>'".(time()-2592000)."'"));

}


include "footer.php";
?>
