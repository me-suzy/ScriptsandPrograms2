<? include("../conf.inc.php");
include("../functions.inc.php");
admin_login();
$description=addslashes($description);
$ad_text=addslashes($ad_text);
$id=addslashes($id);
$value=$value*100000;
if ($vtype=='cash'){
$value=$value*$admin_cash_factor;}
if ($save==2 and $oldid){
@mysql_query("update ".$mysql_prefix."email_ads set id='$id',description='$description',site_url='$site_url',run_quantity='$run_quantity',run_type='$run_type',value='$value',vtype='$vtype',timer='$timer',login='$login',ad_text='$ad_text' where emailid=$oldid");}
if ($save==1){
$searchphrase='';
@mysql_query("insert into ".$mysql_prefix."email_ads set id='$id',description='$description',site_url='$site_url',run_quantity='$run_quantity',run_type='$run_type',value='$value',vtype='$vtype',timer='$timer',login='$login',ad_text='$ad_text',creation_date='$mysqldate'");
$lastid=mysql_insert_id();
@mysql_query("CREATE TABLE paid_clicks_$lastid (
  username char(64) NOT NULL,
  value int not null,
  vtype char(6) not null,
  ip_host char(64) not null,
  time timestamp not null,
  KEY username(username),
  KEY value(value),
  KEY vtype(vtype),
  KEY ip_host(ip_host),
  KEY time(time)
) TYPE=MyISAM");
}
if ($mode=='Delete' and $emailid){
@mysql_query("drop table ".$mysql_prefix."paid_clicks_$emailid");
@mysql_query("delete from ".$mysql_prefix."email_ads where emailid=$emailid");
}
echo "<html>
<title>eMail Ad Manager</title><script>window.focus()</script>
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
<center><h3>eMail Ad Manager</h3></center><hr>
<a href=http://myecom.net/main/thegetpaidsite/emailmgr_instructions.htm target=_instructions><font size=4>CLICK HERE FOR INSTRUCTIONS</font></a><br><br>To place email ads in your email to the members use the following code. REPLACE: PUT_AD_ID#_HERE with the ad ID# of the email ad you wish to send<br>&lt;PAIDMAIL&gt;PUT_AD_ID#_HERE&lt;/PAIDMAIL&gt;<br><form action=emailadmgr.php method=post>Search Rotating Ads Database: (leave blank to list all ads) <input type=text name=searchphrase><input type=hidden name=get value=search><input type=submit value='Search'><br><a href=emailadmgr.php#adform target=_top>Create a new ad campaign</a></form><br>";
if ($get=='search'){$searchphrase="%".$searchphrase."%";}
echo "<table class=fsize2 border=1><tr><th>Ad ID, Client ID, Ad Description, Created, Last Clicked</th><th>Type</th><th>Expire at</th><th>Clicks</th><th>Value</th><th>Value Type</th><th>Timer</th><th>Login</th></tr>";
if (!$searchphrase){$searchphrase='*****************************';}
$getads=@mysql_query("select * from ".$mysql_prefix."email_ads where id like '$searchphrase' or emailid like '$searchphrase' or description like '$searchphrase' or emailid='$lastid' order by id,description");
while($row=@mysql_fetch_array($getads)){
$row[value]=$row[value]/100000;
if ($row[vtype]=='cash'){
$row[value]=$row[value]/$admin_cash_factor;}
$row[time]=substr($row[time],4,2)."/".substr($row[time],6,2)."/".substr($row[time],0,4)." ".substr($row[time],8,2).":".substr($row[time],10,2);
$row[creation_date]=str_replace(":","",$row[creation_date]);
$row[creation_date]=str_replace("-","",$row[creation_date]);
$row[creation_date]=str_replace(" ","",$row[creation_date]);
$row[creation_date]=substr($row[creation_date],4,2)."/".substr($row[creation_date],6,2)."/".substr($row[creation_date],0,4)." ".substr($row[creation_date],8,2).":".substr($row[creation_date],10,2);
echo "<form action=emailadmgr.php#adform method=post><input type=hidden name=searchphrase value='$searchphrase'><input type=hidden name=emailid value='$row[emailid]'><tr $bgcolor><td><table class=fsize2 border=0 width=100%><tr><th align=center>eMail ID:</th><td>$row[emailid]</td></tr><tr><th align=center>Client ID:</th><td>$row[id]</td></tr><tr><th align=center>Description:</th><td>$row[description]</td></tr><tr><th align=center>Created:</th><td>$row[creation_date]</td></tr><tr><th align=center>Last Clicked:</th><td>$row[time]</td></tr><tr><td colspan=2 align=center><input type=submit name=mode value='Delete'><input type=submit name=mode value='Edit'><input type=submit name=mode value='Copy'><table class=fsize2 border=0 width=100% cellpadding=0 cellspacing=0></form><form action=clicklog.php target=_clicklog method=post><tr><td align=center><input type=hidden name=emailid value='$row[emailid]'><input type=submit value='View Click Log/Rollback'></td></tr></form></table></td></tr></table></td><td>$row[run_type]</td><td>$row[run_quantity]</td><td>$row[clicks]</td><td>".number_format($row[value],5)."</td><td>$row[vtype]</td><td>$row[timer]</td><td>$row[login]</td>
</tr><tr $bgcolor><td colspan=10>Test link for ad $row[emailid]: <a href=".$scripts_url."runner.php?EA=$row[emailid]".substr(md5($row[emailid].$mysql_password),0,4)." target=_blank>".$scripts_url."runner.php?EA=$row[emailid]".substr(md5($row[emailid].$mysql_password),0,4)."</a></td></tr>";
if($bgcolor){$bgcolor='';}else{$bgcolor='bgcolor=#DDDDDD';}
}
echo "</table>";
$count=mysql_num_rows($getads);
if ($searchphrase){echo "<b>".$count." ad(s) found</b><br><br>";}
$savemode=1;
$row='';
if (($mode=='Edit' or $mode=='Copy') and $emailid){
$savemode=2;
if ($mode=='Copy'){
$savemode=1;}
$row=@mysql_fetch_array(@mysql_query("select * from ".$mysql_prefix."email_ads where emailid=$emailid"));
}
if (!$mode){$mode='Create New';}
$row[value]=$row[value]/100000;
if ($row[vtype]=='cash'){
$row[value]=$row[value]/$admin_cash_factor;}
?>
<a name="adform"></a><form action="emailadmgr.php" method="POST" name="form">
<input type=hidden name=searchphrase value='<?= $searchphrase;?>'>
<input type="hidden" name="save" value="<?=$savemode;?>">
<? if ($savemode==2){?><input type=hidden name=oldid value='<?=$row[emailid];?>'><? } ?>
	<table class=fsize2 border=0 width=400><tr><th colspan=2><?= $mode;?> Ad</th></tr><tr><td>Client ID:</td><td><input type="text" name="id" value="<?=stripslashes($row[id]);?>">
        </td></tr><tr><td>Ad Description:</td><td><input type="text" name="description" value="<?=stripslashes($row[description]);?>">
        </td></tr><tr><td>Duration Type:</td><td><select name=run_type><option <? if ($row[run_type]=='ongoing'){ echo "selected";}?> value=ongoing>Never Expire<option <? if ($row[run_type]=='date'){ echo "selected";}?> value=date>Expire by certain date<option <? if ($row[run_type]=='clicks'){ echo "selected";}?> value=clicks>Expire after so many clicks</select>
	</td></tr><tr><td>Duration:</td><td><input type="text" name="run_quantity" value=<?= $row[run_quantity];?>>
        </td></tr><tr><td colspan=2>(if using date to expire use the format YYYYMMDDHHMMSS)
	</td></tr><tr><td>Value:</td><td><input type=text name=value value=<?= number_format($row[value],5,".","");?>>
	</td></tr><tr><td>Value Type:</td><td><select name=vtype><option <? if ($row[vtype]=='points'){echo "selected";}?> value=points>Points<option <? if ($row[vtype]=='cash'){echo "selected";}?> value=cash>Cash</select> 
	</td></tr><tr><td>Timer:</td><td><input type=text name=timer value=<?=$row[timer];?>>
	</td></tr><tr><td>Login:</td><td><select name=login><option <?if ($row[login]=='auto'){echo 'selected';};?> value=auto>Auto<option value=manual <?if ($row[login]=='manual'){ echo 'selected';}?>>Manual</select>
	</td></tr><tr><td>Site URL:</td><td><input type=text size=40 name=site_url value="<?=stripslashes($row[site_url]);?>">
	</td></tr><tr><td colspan=2><hr>
	EMAIL AD TEXT
<br><textarea name="ad_text" rows=10 cols=50><?=htmlentities(stripslashes($row[ad_text]));?></textarea><br>
	<input type="submit" name="add" value="Save Ad">
</form><hr>
<? 
if ($mode!='Create New'){
$row[ad_text]=stripslashes($row[ad_text]);
$row[ad_text]=str_replace("\n","<br>",htmlentities($row[ad_text]));
echo "$row[ad_text]<br><br><a href=".$scripts_url."runner.php?EA=$row[emailid]".substr(md5($row[emailid].$mysql_password),0,4)." target=_blank>".$scripts_url."runner.php?EA=$row[emailid]".substr(md5($row[emailid].$mysql_password),0,4)."</a><br><br>&lt;a href=".$scripts_url."runner.php?EA=$row[emailid]".substr(md5($row[emailid].$mysql_password),0,4)."&gt;AOL Users&lt;/a&gt;";
}
echo "</td></tr></table></body></html>";
