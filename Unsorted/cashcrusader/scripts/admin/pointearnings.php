<? include("../conf.inc.php");
include("../functions.inc.php");
admin_login();
if (!isset($to)){$to=1000000;}
if (!isset($from)){$from=-1000000;}
echo "<title>Point Earnings Report</title><script>window.focus()</script><center><h2>Point Earnings Report</h2><hr></center><form method=post>List all members with account balances ranging<br>from: <input type=text name=from value=$from> to: <input type=text name=to value=$to><input type=submit name=report value=Report></form>";
if ($report){
$f=$from*100000;
$t=$to*100000;
@mysql_query("drop table tmppointtbl");
@mysql_query("create table tmppointtbl (username char(64) not null, amount bigint not null, key amount(amount))");
@mysql_query("insert into tmppointtbl (username,amount) select username,sum(amount) from accounting where type='points' group by username");
$report=@mysql_query("select * from tmppointtbl where amount>=$f and amount<=$t order by amount desc");
echo "<table border=1><tr><td><b>Username</b></td><td><b>Amount</b>";
while($row=@mysql_fetch_array($report)){
echo "</td></tr><tr><td><a href=viewuser.php?userid=$row[username] target=_viewuser>$row[username]</a></td><td align=right>".number_format($row[amount]/100000,5);
}
echo "</td></tr></table>";}
@mysql_query("drop table tmppointtbl");
