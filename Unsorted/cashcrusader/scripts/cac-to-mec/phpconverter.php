<?
include("../conf.inc.php");
include("../functions.inc.php");
$member_debits=mysql_query("select * from member_debit");
while($row=mysql_fetch_array($member_debits)){
$count++;
echo "debit: $count $row[mem_id]<br>";
mysql_query("insert into accounting set transid='$count',username='$row[mem_id]',description='$row[r_debit]',amount=0-($row[debits]/100*$admin_cash_factor*100000),unixtime='$count',type='cash'");
}
$member_credits=mysql_query("select * from member_credit");
while($row=mysql_fetch_array($member_credits)){
$count++;
echo "credit: $count $row[mem_id]<br>";
mysql_query("insert into accounting set transid='$count',username='$row[mem_id]',description='$row[r_credit]',amount=$row[credits]/100*$admin_cash_factor*100000,unixtime='$count',type='cash'");
}
$member_details=mysql_query("select * from member_details");
while($row=mysql_fetch_array($member_details)){
$count++;
mysql_query("insert into users set username='$row[mem_id]',email='$row[email_id]',first_name='$row[f_name]',last_name='$row[l_name]',address='$row[address]',city='$row[city]',state='$row[state]',zipcode='$row[zip]',country='$row[country]',password='$row[password]',signup_date='$row[joined_date]',signup_ip_host='$row[$ipadds]'");
mysql_query("insert into click_counter set username='$row[mem_id]',time='".time()."'");
}
$member_referrals=mysql_query("select * from member_referrals");
while($row=mysql_fetch_array($member_referrals)){
mysql_query("update users set upline='$row[parent_id]',referrer='$row[parent_id]' where username='$row[mem_id]'");
}
$member_earnings=mysql_query("select * from member_earnings");
mysql_query("update users set upline='',referrer='' where username='1'");
while($row=mysql_fetch_array($member_earnings)){
echo "$row[mem_id]<br>";
$count++;
$value=$row[pd_clickthro]*100000;
mysql_query("insert into accounting set transid='$count',username='$row[mem_id]',unixtime=0,description='#SELF-EARNINGS#',type='cash',amount='$value'");
$value=$row[credits]*10000000;
$count++;
mysql_query("insert into accounting set transid='$count',username='$row[mem_id]',unixtime=0,description='Sign-up Bonus',type='cash',amount='$value'");
$count++;
$value=$row[referral_bonus]*100000;
mysql_query("insert into accounting set transid='$count',username='$row[mem_id]',unixtime=0,description='Referral Bonus',type='cash',amount='$value'");
}
$users=mysql_query("select username from users");
while($row=mysql_fetch_row($users)){
$upline=$row[0];
mysql_query("delete from ".$mysql_prefix."levels where username='$username'");
for ($idx=1;$idx<=count($levels);$idx++){
list($upline)=mysql_fetch_row(mysql_query("select upline from ".$mysql_prefix."users where username='$upline' limit 1"));
if (!$upline){
break;  
}
$tier=$idx-1;
echo "$row[0] - $upline $tier";
mysql_query("insert into ".$mysql_prefix."levels set upline='$upline',username='$row[0]',level=$tier");
}
list($earn)=mysql_fetch_row(mysql_query("select amount from accounting where username='$row[0]' and description='#SELF-EARNINGS#'"));
creditul($row[0],$earn,"cash");
}   

