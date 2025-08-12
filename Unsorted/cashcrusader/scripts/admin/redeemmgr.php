<?php
include("../conf.inc.php");
require_once("../functions.inc.php");
admin_login();
if ($show_redeem){
@mysql_query("replace into ".$mysql_prefix."system_values set name='show_redeem',value='$show_redeem'");} 
$description=addslashes($description);
$special=addslashes($special);
$phpcode=addslashes($phpcode);
if ($type=='cash'){
$amount=$amount*100;
}
$amount=$amount*100000;
if ($save==2 and $oldid){
@mysql_query("update ".$mysql_prefix."redemptions set phpcode='$phpcode',special='$special',description='$description',auto='$auto',type='$type',amount='$amount' where id=$oldid");}  
if ($save==1){
$searchphrase='';
@mysql_query("insert into ".$mysql_prefix."redemptions set phpcode='$phpcode',auto='$auto',special='$special',description='$description',type='$type',amount='$amount'");}
if ($mode=='Delete'){
@mysql_query("delete from ".$mysql_prefix."redemptions where id='$id'");}
echo "<html><title>Redemption Manager</title><script>window.focus();</script>
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
<center><h2>Redemption Manager</h2></center><hr>
<a href=http://myecom.net/main/thegetpaidsite/redemptions_instructions.htm target=_instructions><font size=4>CLICK HERE FOR INSTRUCTIONS</font></a><br><br>
To list the redemption types available to a member place the following code in your html<br>
&lt;? redeem_list();?&gt;
<br><center><form method=post>Show redemption selection to ";
if (system_value("show_redeem")=="YES"){
echo "<input type=submit value='all members'><input type=hidden value='NO' name=show_redeem>";}
else { echo "<input type=submit value='qualified members only'><input type=hidden value='YES' name=show_redeem>";}
echo "</center></form>
<form action=redeemmgr.php method=post>Search Redemption Database: (leave blank to list all redemption types) <input type=text name=searchphrase>
<input type=hidden name=get value=search><input type=submit value='Search'><br><a href=redeemmgr.php#transform target=_top>Create a new redemption type</a></form><br>";
if ($get=='search'){$searchphrase="%".$searchphrase."%";}
echo "<table class=fsize2 border=1><tr><th>Redemption ID</th><th>Description</th><th>Type</th><th>Amount</th><th>Auto Deduct</th><td></td></tr>";                                                                               
if (!$searchphrase){$searchphrase='*****************************';}
if ($limit){$limit="limit $limit";}
$getads=@mysql_query("select * from ".$mysql_prefix."redemptions where id like '$searchphrase' or description like '$searchphrase' or id=LAST_INSERT_ID() order by amount"); 
while($row=@mysql_fetch_array($getads)){
if ($row[type]=='cash'){
$row[amount]=$row[amount]/100;
}
$row[amount]=$row[amount]/100000;
echo "<form action=redeemmgr.php#transform method=post><input type=hidden name=searchphrase value='$searchphrase'><input type=hidden name=id value='$row[id]'><tr $bgcolor><td>$row[id]</td><td>$row[description]</td><td>$row[type]</td><td align=right>".number_format($row[amount],5)."</td><td>$row[auto]</td><td><input type=submit name=mode value='Delete'><input type=submit name=mode value='Edit'><input type=submit name=mode value='Copy'></td></tr></form>";
if($bgcolor){$bgcolor='';}else{$bgcolor='bgcolor=#DDDDDD';}
}
echo "</table>";
$count=mysql_num_rows($getads);
if ($searchphrase){echo "<b>".$count." redemption(s) found</b><br><br>";}
$savemode=1;
$row='';
if ($mode=='Edit' or $mode=='Copy'){
$savemode=2;
if ($mode=='Copy'){
$savemode=1;}
$row=@mysql_fetch_array(@mysql_query("select * from ".$mysql_prefix."redemptions where id='$id'"));
}
if (!$mode){$mode='Create New';}
if ($row[type]=='cash'){
$row[amount]=$row[amount]/100;
}
$row[amount]=$row[amount]/100000;
?>
<a name="transform"></a><form action="redeemmgr.php" method="POST" name="form">
<input type=hidden name=searchphrase value='<?= $searchphrase;?>'>
<input type="hidden" name="save" value="<?=$savemode;?>">
<? if ($savemode==2){?><input type=hidden name=oldid value='<?=$row[id];?>'><? } ?>
	<table class=fsize2 border=0 width=400><tr><th colspan=2><?= $mode;?> Redemption Type:</th></tr>
        <tr><td>Description:</td><td><input type="text" name="description" value="<?=stripslashes($row[description]);?>">
        </td></tr><tr><td>Type:</td><td><select name=type><option <? if ($row[type]=='cash'){ echo "selected";}?> value=cash>Cash<option <? if ($row[type]=='points'){ echo "selected";}?> value=points>Points</select>
	</td></tr><tr><td>Amount:</td><td><input type="text" name="amount" value=<?= number_format($row[amount],5,".","");?>>
</td></tr><tr><td>Automaticly deduct amount from users account when they redeem:</td><td><select name=auto><option <? if ($row[auto]=='no'){ echo "selected";}?> value=no>No<option <? if ($row[auto]=='yes'){ echo "selected";}?> value=yes>Yes</select>
        </td></tr><tr><td colspan=2>Special HTML: If you would like to add special HTML code right before the submit button like a textbox where they can put in their ad, you can use this example:<br>
<br><i>&lt;textarea name=userform[ad_info] rows=10 cols=30&gt;Type your ad here&lt;/textarea&gt;&lt;br&gt;</i><br>
<textarea name="special" rows=20 cols=50><?=htmlentities(stripslashes($row[special]));?></textarea>
<br><br>PHP Code: Do not use this unless you know PHP. You can can enter here any PHP scripting you would like to take place when someone selects this redemption. If you use the option to auto deduct, the script you enter here will run before the transaction is entered into the accounting table.    
<br>
<textarea name="phpcode" rows=20 cols=50><?=htmlentities(stripslashes($row[phpcode]));?></textarea>
</td></tr><tr><td colspan=2><input type="submit" name="add" value="Save Redemption">
</form>
<?echo "</td></tr></table></body></html>";
