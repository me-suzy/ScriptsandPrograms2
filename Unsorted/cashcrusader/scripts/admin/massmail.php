<? include("../conf.inc.php");
include("../functions.inc.php");
admin_login();
$emailsubject=addslashes($emailsubject);
$emailtosend=addslashes($emailtosend);

$emailsubject=$highcheck.$emailsubject;
if ($mode=='Delete'){
@mysql_query("delete from ".$mysql_prefix."interest where username='#MASS-MAIL-ID:$id#'");
@mysql_query("delete from ".$mysql_prefix."mass_mailer where massmailid=$id");
}
if ($mode=='Send'){
@mysql_query("update ".$mysql_prefix."mass_mailer set current=start where massmailid=$id and current=0");
}
	if ($HTTP_POST_VARS["send"] == "mail")
		{
$hicount=0;




if ($keyword){
reset($keyword);
while (list($key, $value) = each($keyword)){
$value=trim($value);
if ($value){
if (substr($value,0,2)!="c:"){
$keywordct++;
$words=$words.$or." keyword='$value'";
$or=" or";}
else {
$value=str_replace("c:","",$value);
$countries=$countries.$cor." country='$value'";
$cor=" or";}
}
}}
if ($words or $countries){
@mysql_query("drop table tmpmailtbl");
@mysql_query("drop table tmpmailcttbl");
@mysql_query("create table tmpmailtbl (username char(64) not null, keyword char(16) not null,key username(username),key keyword(keyword))");
@mysql_query("create table tmpmailcttbl (username char(64) not null, counter int not null,key username(username),key counter(counter))");
if ($words){
@mysql_query("insert into tmpmailtbl (username,keyword) select username,keyword from ".$mysql_prefix."interest where $words");
}
if ($countries){
$keywordct++;
@mysql_query("insert into tmpmailtbl (username,keyword) select username,country from ".$mysql_prefix."users where $countries");
}
@mysql_query("insert into tmpmailcttbl (username,counter) select username,count(*) from tmpmailtbl group by username");
@mysql_query("delete from tmpmailcttbl where counter<$keywordct");
$leftjoinfirst="LEFT JOIN tmpmailcttbl ON ".$mysql_prefix."users.username=tmpmailcttbl.username";
$leftjoinsecond=" and tmpmailcttbl.username IS NOT NULL";
@mysql_query("drop table tmpmailtbl");
list($hicount)=@mysql_fetch_row(@mysql_query("select count(*) from tmpmailcttbl"));
@mysql_query("drop table tmpmailcttbl");
$numtosend=$hicount;
}
@mysql_query("insert into ".$mysql_prefix."mass_mailer set subject='".$emailsubject."',is_html='$is_html',start=$startnum,stop=$numtosend,ad_text='".$emailtosend."'");
if ($keyword){
reset($keyword);
$lastid=mysql_insert_id();
while (list($key, $value) = each($keyword)){
$value=trim($value);
@mysql_query("insert into ".$mysql_prefix."interest set username='#MASS-MAIL-ID:$lastid#',keyword='$value'");
}}
			echo "<b>Emails have been successfully queued for delivery!</b><br>";
			?>
			 <br>

			<?
	}
$curdate=date("Y-m-d",time());
list($numtosend)=@mysql_fetch_array(@mysql_query("select count(*) from ".$mysql_prefix."users where vacation<'$curdate'"));
$getmessages=@mysql_query("select * from ".$mysql_prefix."mass_mailer order by time desc");
echo "<html><title>Send Mail Manager</title><script>window.focus();</script>
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
<body bgcolor=ffffff><font face=arial size=2 class=fsize2><center><h2>Send Mail Manager</h2><hr></center>
";
echo "Current messages in queue:<br>";
echo "<table border=1 class=fsize2><tr><th>Subject</th><th>First</th><th>Last</th><th>Current</th><th>Date</th><td></td></tr>";
while ($messages=@mysql_fetch_array($getmessages)){
if ($messages[current]>$messages[stop]){$messages[current]="Send Complete";}
if (!$messages[current]) {$messages[current]="<input type=submit name=mode value='Send'>";}
echo "<form action=massmail.php method=post><input type=hidden name=start value=$info[1]><input type=hidden name=id value=$messages[massmailid]><tr $bgcolor><td>".htmlentities(stripslashes($messages[subject]))."</td><td>$messages[start]</td><td>$messages[stop]</td><td>$messages[current]</td><td>".mytimeread($messages[time])."</td><td><input type=submit name=mode value='Delete'><input type=submit name=mode value='Copy'></td></tr></form>";
if($bgcolor){$bgcolor='';}else{$bgcolor='bgcolor=#DDDDDD';}
}
echo "</table>";
if ($mode=='Copy'){
$message=@mysql_fetch_array(@mysql_query("select * from ".$mysql_prefix."mass_mailer where massmailid=$id"));
$emailsubject=$message[subject];
$startnum=$message[start];
$numtosend=$message[stop];
$emailtosend=$message[ad_text];
$username="#MASS-MAIL-ID:$id#";
$is_html=$message[is_html];
}
	if (!$startnum){$startnum=1;}
	echo"Please enter your <b>ADVERTISEMENT EMAIL</b> and how many users you want to send it to.<br><br>If you would like to place the members first name,last name,user name or password in the email place <b>&lt;OWED&gt;</b>, <b>&lt;CASH_BALANCE&gt;</b>, <b>&lt;POINT_BALANCE&gt;</b>, <b>&lt;FIRSTNAME&gt;</b>, <b> &lt;LASTNAME&gt;</b>, or <b>&lt;USERNAME&gt;</b> where you would like that information to be in the email message or subject.<br><br>To place your paid email ads into the mail simple type <b>&lt;PAIDMAIL&gt;AD_ID#&lt;/PAIDMAIL&gt;</b> replace AD_ID# with the id of the email advertisment";?>
 </b>
	<form action="massmail.php" method="POST" name="form">
<input type="hidden" name="send" value="mail">
	Send this email to members #<input type=text name=startnum value=<?= $startnum;?>> through #<input type="text" name="numtosend" value="<?=$numtosend?>"><br>
Send as <select name=is_html><option value='N' <? if ($is_html!='Y'){ echo "selected";}?>>plain text<option value='Y' <? if ($is_html=='Y'){ echo "selected";}?>>HTML</select> email.<br>
<? if (substr($emailsubject,0,2)=="! "){
$emailsubject=substr($emailsubject,2,strlen($emailsubject)-2);
$highcheck="checked";}?>
	<br>Subject of Email: <input type="text" name="emailsubject" value="<?=htmlentities(stripslashes($emailsubject));?>"> High Priority <input type=checkbox <? echo $highcheck;?> name=highcheck value="! "><br>
	<br><br>
	Email: <br><textarea name="emailtosend" rows=20 cols=50><?=htmlentities(stripslashes($emailtosend));?></textarea><br>
Targeting: (Do not select any categories or countries if you want to send to everyone)
<table border=0 class=fsize2><tr><Td valign=top>
<? $getkeys=@mysql_query("select keyword from ".$mysql_prefix."interest
 where keyword not like 'c:%' group by keyword");
$idx=0;
while($row=@mysql_fetch_row($getkeys)){
$line++;
echo "<input type=checkbox name=keyword[$idx] value=\"$row[0]\" ";
$username="#MASS-MAIL-ID:$id#";
interests(strtolower($row[0]),"checked");
echo ">$row[0]<br>";$idx++;
if ($line>25){ echo "</td><td valign=top>"; $line=0;}
}

?>
</td><td valign=top>
<? $getkeys=@mysql_query("select country from ".$mysql_prefix."users
group by country");
$idx=0;
$line=0;
while($row=@mysql_fetch_row($getkeys)){
echo "<input type=checkbox name=keyword[$idx] value=\"c:$row[0]\" ";
$username="#MASS-MAIL-ID:$id#";
interests(strtolower("c:".$row[0]),"checked");
echo ">$row[0]<br>";$idx++; $line++;
if ($line>25){ echo "</td><td valign=top>"; $line=0;}
}?>
</td></tr></table>
</td></tr>
	<input type="submit" name="add" value="Save Email">
	</form>
