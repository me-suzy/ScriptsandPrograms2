<? include("../conf.inc.php");
include("../functions.inc.php");
admin_login();
$description=addslashes($description);
if ($type=='cash'){
$amount=$amount*100;
}
$amount=$amount*100000;
if ($save==2 and $oldid){
@mysql_query("update ".$mysql_prefix."accounting set username='$formusername',description='$description',type='$type',amount='$amount' where transid='$oldid'");}  
if ($save==1){
$searchphrase='';
$unixtime=time();
$rand=substr(md5($formusername),0,3).rand(0,9);
$pickedtransid="$unixtime$rand";
@mysql_query("insert into ".$mysql_prefix."accounting set transid='$pickedtransid',username='$formusername',unixtime=$unixtime,description='$description',type='$type',amount='$amount'");}
if ($mode=='Delete'){
@mysql_query("delete from ".$mysql_prefix."accounting where transid='$transid'");}
echo "<html><title>Credits/Debits manager</title><script>window.focus();</script>
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
<body><center><h2>Transaction Manager</h2></center><hr>
<font face=arial size=2 class=fsize2>
<a href=http://myecom.net/main/thegetpaidsite/transaction_instructions.htm target=_instructions><font size=4>CLICK HERE FOR INSTRUCTIONS</font></a><br><br>
<form action=transactions.php method=post>Search Transactions Database: (leave blank to list all transactions) <input type=text name=searchphrase><br>List <input type=text name=limit value=100> transactions <input type=hidden name=get value=search><input type=submit value='Search'><br><a href=transactions.php#transform target=_top>Create a new transaction</a></form><br>";
if ($get=='search'){$searchphrase="%".$searchphrase."%";}
echo "<table class=fsize2 border=1><tr><th>Transaction ID</th><th>Username</th><th>Description</th><th>Type</th><th>Amount</th><th>Date Posted</th><td></td></tr>";                                                                               
if ($limit){$slimit="limit $limit";}
if ($formusername and $type){
$usersearch=$formusername;
$transtype=$type;}
if ($searchphrase){
$getads=@mysql_query("select * from ".$mysql_prefix."accounting where (transid like '$searchphrase' or username like '$searchphrase' or description like '$searchphrase' or transid='$pickedtransid') and description!='#SELF-EARNINGS#' and description!='#DOWNLINE-EARNINGS#' and description!='#SELF-POINT-EARNINGS#' and description!='#DOWNLINE-POINT-EARNINGS#' order by time desc $slimit"); 
} else {
$getads=@mysql_query("select * from ".$mysql_prefix."accounting where username='$usersearch' and type='$transtype' and description!='#SELF-EARNINGS#' and description!='#DOWNLINE-EARNINGS#' and description!='#SELF-POINT-EARNINGS#' and description!='#DOWNLINE-POINT-EARNINGS#' order by time desc");}
while($row=@mysql_fetch_array($getads)){
$row[time]=substr($row[time],4,2)."/".substr($row[time],6,2)."/".substr($row[time],0,4)." ".substr($row[time],8,2).":".substr($row[time],10,2);
if ($row[type]=='cash'){
$row[amount]=$row[amount]/100;
}
$row[amount]=$row[amount]/100000;
$user=@mysql_fetch_row(@mysql_query("select username from ".$mysql_prefix."users where username='$row[username]'"));
$usrmsg='';
if (!$user[0]){
$usrmsg="<br><FONT COLOR=RED><b>INVALID USERNAME</b></FONT>";
}
else {
$user=@mysql_fetch_row(@mysql_query("select sum(amount) from ".$mysql_prefix."accounting where username='$row[username]' and type='$row[type]'"));
if ($user[0]<0){
$usrmsg="<br><FONT COLOR=RED><b>Account holds a NEGATIVE balance</b></font>";}}
echo "<form action=transactions.php#transform method=post><input type=hidden name=limit value=$limit><input type=hidden name=searchphrase value='$searchphrase'><input type=hidden name=transid value='$row[transid]'><tr $bgcolor><td>$row[transid]</td><td><a href=viewuser.php?userid=$row[username] target=_viewuser>$row[username]</a>$usrmsg</td><td>$row[description]</td><td>$row[type]</td><td align=right>".number_format($row[amount],5)."</td><td>$row[time]</td><td><input type=submit name=mode value='Delete'><input type=submit name=mode value='Edit'><input type=submit name=mode value='Copy'></td></tr></form>";
if($bgcolor){$bgcolor='';}else{$bgcolor='bgcolor=#DDDDDD';}
}
echo "</table>";
$count=mysql_num_rows($getads);
if ($searchphrase){echo "<b>".$count." ad(s) found</b><br><br>";}
$savemode=1;
$row='';
if ($mode=='Edit' or $mode=='Copy'){
$savemode=2;
if ($mode=='Copy'){
$savemode=1;}
$row=@mysql_fetch_array(@mysql_query("select * from ".$mysql_prefix."accounting where transid='$transid'"));
}
if (!$mode){$mode='Create New';}
if ($row[type]=='cash'){
$row[amount]=$row[amount]/100;
}
$row[amount]=$row[amount]/100000;
?>
<a name="transform"></a><form action="transactions.php" method="POST" name="form">
<input type=hidden name=searchphrase value='<?= $searchphrase;?>'>
<input type=hidden name=usersearch value='<?= $usersearch;?>'>
<input type=hidden name=transtype value='<?= $transtype;?>'>
<input type="hidden" name="save" value="<?=$savemode;?>">
<input type=hidden name=usersearch value='<? echo $usersearch;?>'>
<? if ($savemode==2){?><input type=hidden name=oldid value='<?=$row[transid];?>'><? } ?>
	<table class=fsize2 border=0 width=400><tr><th colspan=2><?= $mode;?> Transaction</th></tr><tr><td>Username:</td><td><input type="text" name="formusername" value="<?=stripslashes($row[username]);?>">
        </td></tr><tr><td>Description:</td><td><input type="text" maxlength=32 name="description" value="<?=stripslashes($row[description]);?>">
        </td></tr><tr><td>Type:</td><td><select name=type><option <? if ($row[type]=='cash'){ echo "selected";}?> value=cash>Cash<option <? if ($row[type]=='points'){ echo "selected";}?> value=points>Points</select>
	</td></tr><tr><td>Amount:</td><td><input type="text" name="amount" value=<?= number_format($row[amount],5,".","");?>>
	</td></tr><tr><td colspan=2><input type="submit" name="add" value="Save Transaction">
</form>
<?echo "</td></tr></table></body></html>";
