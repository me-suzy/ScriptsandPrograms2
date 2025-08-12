<?
/*
##########################################################
## This script is copyrighted to MyECom Online
## Duplication, selling, or transferring of this script
## is a violation of the copyright and purchase agreement
## unless you have received approval from MyECom Online 
## before doing so.
##
## Alteration of this script in any way voids any
## responsibility MyECom Online has towards the
## functioning of the script.
##########################################################
*/
include("conf.inc.php");
require_once("functions.inc.php");
$unixtime=time();
chdir($pages_dir);
if ($GA){
echo "document.write('";
getad($GA,"js");
echo "');";
exit;
}
if ($BA){
@mysql_query("update ".$mysql_prefix."rotating_ads set clicks=clicks+1 where bannerid='$BA'");
header("Location: $url");
exit;
}
if ($EA and $TI>999 and $AU){
if ($AU==md5($US.$TI.$EA.$mysql_password)){
$emailad=@mysql_fetch_array(@mysql_query("select * from ".$mysql_prefix."email_ads where emailid='$EA' limit 1"));
if (!$emailad[0]){
echo "Invalid paid mail ID";
echo "<br>The URL is valid and working";
logs("click_errors","Invalid paid mail ID",$US);
exit;
}
if (($emailad[clicks]>=$emailad[run_quantity] and $emailad[run_type]=="clicks") or ($mysqldate>=$emailad[run_quantity] and $emailad[run_type]=="date") or $userinfo[id_change_date]>$emailad[creation_date]){
$expireurl=$emailad[site_url];
include($pages_dir."expired_paid_mail.php");
logs("click_errors","Expired paid mail ID",$US);
exit;
} 
if (time()-$emailad[timer]+2<$TI){
echo "Timer was not finished countdown";
logs("click_errors","Timer did not finish countdown",$US);
echo "<br>The URL is valid and working";
exit;
}
//@mysql_query("LOCK TABLES ".$mysql_prefix."paid_clicks WRITE");
$clickcheck=@mysql_fetch_array(@mysql_query("select * from ".$mysql_prefix."paid_clicks_$EA where username='$US'
 limit 1"));
if ($clickcheck[0] or ereg("#".$EA."-paidmail-$US#",$sessionclicks)){
echo "Site already visited";
logs("click_errors","Site already visited",$US);
echo "<br>The URL is valid and working";
exit;
}
$sessionclicks=$sessionclicks."#".$EA."-paidmail-$US#";
session_register("sessionclicks");
if ($emailad[vtype]=='points'){
$P='POINT-';}
@mysql_query("insert into ".$mysql_prefix."paid_clicks_$EA set username='$US',value='$emailad[value]',vtype='$emailad[vtype]',ip_host='$ipaddr'");
$update=@mysql_query("UPDATE ".$mysql_prefix."latest_stats set time=concat('".$mysqldate.",',time),type=concat('paidmail,',type),id=concat('$EA,',id) where username='$US' limit 1");
if (!mysql_affected_rows()){
@mysql_query("insert into ".$mysql_prefix."latest_stats set time='$mysqldate',type='paidmail',id='$EA',username='$US'");}
@mysql_query("update ".$mysql_prefix."email_ads set clicks=clicks+1 where emailid=$EA");
if ($emailad[value]){
$update=@mysql_query("UPDATE ".$mysql_prefix."accounting SET amount=amount+$emailad[value] WHERE type='$emailad[vtype]' and username = '$US' and description='#SELF-".$P."EARNINGS#' limit 1");
if (!mysql_affected_rows()){
$rand=substr(md5($US),0,3).rand(0,9);
$pickedtransid="$unixtime$rand";
$update=@mysql_query("INSERT INTO ".$mysql_prefix."accounting set transid='$pickedtransid',username = '$US',unixtime=0,description='#SELF-".$P."EARNINGS#',amount=$emailad[value],type='$emailad[vtype]'");
}
creditulclicks($US,$emailad[value],$emailad[vtype]);
}
if (!$frame_size){
echo "<html><head>
<SCRIPT language='JavaScript'><!--
message=window.open(\"".$pages_url.$VT."account_credited.php\",\"message\",\"width=$popup_width,height=$popup_height,left=0,top=0,toolbars=0, scrollbars=0, location=0, statusbars=0, menubars=0, resizable=0\");
//-->
message.window.focus();
</SCRIPT></head></html>";
echo "<br>The URL is valid and working";
} else {
include($pages_dir."account_credited.php");}
exit;
}
else { echo "Invalid Auth Code - The URL you provided does not seem to be working";  
$message="Hello. I am the runner.php script for your website. I am reporting the entry of an invalid Auth Code for a paid mail tracking URL. Please verify that the URL is not working (click it) and keep an eye out on this member. They are possably trying to cheat\n\nURL: ".$scripts_url."runner.php?".getenv("QUERY_STRING")."\n\nUsername: $US\nIP/HOST: $ipaddr";
mail(system_value("security_email"),"Automated Security ALERT.",$message);   
exit;
}
}
if ($EA and $VT and $TI and $VA){
if ($VA!="N/A"){
$VA=$VA/100000;}
$thetime=time();
echo "<html><head>
<META HTTP-EQUIV=\"REFRESH\" CONTENT=\"$TI;URL=".$scripts_url."runner.php?EA=$EA&TI=$thetime&US=$US&AU=".md5($US.$thetime.$EA.$mysql_password)."\">";
if (!$frame_size){ 
echo "<SCRIPT language='JavaScript'><!--
message=window.open(\"".$pages_url.$VT."_timer.php?VA=$VA&TI=$TI\",\"message\",\"width=$popup_width,height=$popup_height,left=0,top=0,toolbars=0, scrollbars=0, location=0, statusbars=0, menubars=0, resizable=0\");
//-->
message.window.focus();
</SCRIPT>";}
?>
<script language="JavaScript">
<!--

var sURL = 'http://<? echo $scripts_url."runner.php?EA=$EA&TI=$thetime&US=$US&AU=".md5($US.$thetime.$EA.$mysql_password); ?>';

function doLoad()
{
    // the timeout value should be the same as in the "refresh" meta-tag
    setTimeout( "refresh()", <? echo $TI;?>*1000 );
}
function refresh()
{
    //  This version of the refresh function will cause a new
    //  entry in the visitor's history.  It is provided for
    //  those browsers that only support JavaScript 1.0.
    //
    window.location.href = sURL;
}
//-->
</script>

<script language="JavaScript1.1">
<!--
function refresh()
{
    //  This version does NOT cause an entry in the browser's
    //  page view history.  Most browsers will always retrieve
    //  the document from the web-server whether it is already
    //  in the browsers page-cache or not.
    // 
    window.location.replace( sURL );
}
//-->
</script>
<? if ($frame_size){
include($pages_dir.$VT."_timer.php");}?>
</head></html>
<?
exit;
} 
if ($EA){
$EA=stripslashes($EA);
list($EA)=split("\"",$EA);
list($EA)=split(">",$EA);
$firstpart=substr($EA,0,-4);
$secondpart=substr($EA,-4,4);
$EA=$firstpart;
$emailad=@mysql_fetch_array(@mysql_query("select * from ".$mysql_prefix."email_ads where emailid='$EA' limit 1"));
if (!$emailad[0] or $secondpart!=substr(md5($EA.$mysql_password),0,4)){                      
include($pages_dir."invalid_paid_mail.php");
exit;
}
if ($emailad[login]!='auto'){
if (!ereg("#".$EA."-paidmail#",$loginsessionclicks)){
$loginsessionclicks=$loginsessionclicks."#".$EA."-paidmail#";
session_register("loginsessionclicks");
session_unregister("username");
session_unregister("password");
$loginmode='RELOG';
}}
login();
if (($emailad[clicks]>=$emailad[run_quantity] and $emailad[run_type]=="clicks") or ($mysqldate>=$emailad[run_quantity] and $emailad[run_type]=="date") or $userinfo[id_change_date]>$emailad[creation_date]){
$expireurl=$emailad[site_url];
include($pages_dir."expired_paid_mail.php");    
exit;
} 
$clickcheck=@mysql_fetch_array(@mysql_query("select * from ".$mysql_prefix."paid_clicks_$EA where username='$username' limit 1"));
if ($clickcheck[0] or ereg("#".$EA."-paidmail-$username#",$sessionclicks)){
include($pages_dir."already_credited.php");
exit;
} 
if ($emailad[timer]){
if (!$emailad[value]){
$emailad[value]="N/A";
}
if ($frame_size){
$border=1;
} else {$frame_size=0; $noresize='noresize'; $border=0;}
echo "<frameset framespacing=0 frameborder=$border border=$border rows='$frame_size,1*'> 
  <frame name=Top src=".$scripts_url."runner.php?EA=$EA&VT=$emailad[vtype]&TI=$emailad[timer]&VA=$emailad[value]&US=$username scrolling=no $noresize>
    <frame name=Frame4 src=$emailad[site_url] $noresize style='mso-linked-frame:auto'>
  </frameset>
  <noframes> 
  <body lang=EN-US style='tab-interval:.5in'>
  <div class=Section1> 
    <p class=MsoNormal>This page uses frames, but your browser doesn't support 
      them.</p>
  </div>
  </body>
  </noframes> </frameset>";
exit;
}
$sessionclicks=$sessionclicks."#".$EA."-paidmail-$username#";
session_register("sessionclicks");
@mysql_query("insert into ".$mysql_prefix."paid_clicks_$EA set username='$username',value='$emailad[value]',vtype='$emailad[vtype]',ip_host='$ipaddr'");
if ($emailad[vtype]=='points'){ 
$P='POINT-';} 
@mysql_query("update ".$mysql_prefix."email_ads set clicks=clicks+1 where emailid=$EA");
$update=@mysql_query("UPDATE ".$mysql_prefix."latest_stats set time=concat('".$mysqldate.",',time),type=concat('paidmail,',type),id=concat('$EA,',id) where username='$username' limit 1");
if (!mysql_affected_rows()){
@mysql_query("insert into ".$mysql_prefix."latest_stats set time='$mysqldate',type='paidmail',id='$EA',username='$username'");}

if ($emailad[value]){
$update=@mysql_query("UPDATE ".$mysql_prefix."accounting SET amount=amount+$emailad[value] WHERE type='$emailad[vtype]' and username = '$username' and description='#SELF-".$P."EARNINGS#' limit 1");
if (!mysql_affected_rows()){
$rand=substr(md5($username),0,3).rand(0,9);
$pickedtransid="$unixtime$rand";
$update=@mysql_query("insert INTO ".$mysql_prefix."accounting set transid='$pickedtransid',username = '$username',unixtime=0,description='#SELF-".$P."EARNINGS#',amount=$emailad[value],type='$emailad[vtype]'");
}
creditulclicks($username,$emailad[value],$emailad[vtype]);
}
header("Location: $emailad[site_url]");
exit;}

if ($PA and $TI>999 and $AU){
if ($AU==md5($US.$TI.$PA.$mysql_password)){
$ptcad=@mysql_fetch_array(@mysql_query("select * from ".$mysql_prefix."ptc_ads where ptcid='$PA' limit 1"));
if (!$ptcad[0]){
echo "Invalid PTC ID";
echo "<br>The URL is valid and working";
logs("click_errors","Invalid PTC ID",$US);
exit;
}
if (($ptcad[clicks]>=$ptcad[run_quantity] and $ptcad[run_type]=="clicks") or ($mysqldate>=$ptcad[run_quantity] and $ptcad[run_type]=="date") or ($ptcad[views]>=$ptcad[run_quantity] and $ptcad[run_type]=="views")){
$expireurl=$ptcad[site_url];
include($pages_dir."expired_ptc_ad.php");
logs("click_errors","Expired PTC ID",$US);
exit;
} 
if (time()-$ptcad[timer]+2<$TI){
echo "Timer was not finished countdown";
logs("click_errors","Timer did not finish countdown",$US);
echo "<br>The URL is valid and working";
exit;
}
$clickcheck=@mysql_fetch_array(@mysql_query("select * from ".$mysql_prefix."paid_clicks where id=$PA and username='$US' limit 1"));
if ($clickcheck[0] or ereg("#".$PA."-ptc-$US#",$sessionclicks)){
echo "Site already visited";
logs("click_errors","Site already visited",$US);
echo "<br>The URL is valid and working";
exit;
}
$sessionclicks=$sessionclicks."#".$PA."-ptc-$US#";
session_register("sessionclicks");
if ($ptcad[vtype]=='points'){
$P='POINT-';}
@mysql_query("insert into ".$mysql_prefix."paid_clicks set username='$US',id='$PA',value='$ptcad[value]',vtype='$ptcad[vtype]',ip_host='$ipaddr'");
@mysql_query("update ".$mysql_prefix."ptc_ads set clicks=clicks+1 where ptcid=$PA");
$update=@mysql_query("UPDATE ".$mysql_prefix."latest_stats set time=concat('".$mysqldate.",',time),type=concat('ptc,',type),id=concat('$PA,',id) where username='$US' limit 1");
if (!mysql_affected_rows()){
@mysql_query("insert into ".$mysql_prefix."latest_stats set time='$mysqldate',type='ptc',id='$PA',username='$US'");}

if ($ptcad[value]){
$update=@mysql_query("UPDATE ".$mysql_prefix."accounting SET amount=amount+$ptcad[value] WHERE type='$ptcad[vtype]' and username = '$US' and description='#SELF-".$P."EARNINGS#' limit 1");
if (!mysql_affected_rows()){
$rand=substr(md5($US),0,3).rand(0,9);
$pickedtransid="$unixtime$rand";
$update=@mysql_query("INSERT INTO ".$mysql_prefix."accounting set transid='$pickedtransid',username = '$US',unixtime=0,description='#SELF-".$P."EARNINGS#',amount=$ptcad[value],type='$ptcad[vtype]'");
}
creditulclicks($US,$ptcad[value],$ptcad[vtype]);
}
if (!$frame_size){
echo "<html><head>
<SCRIPT language='JavaScript'><!--
message=window.open(\"".$pages_url.$VT."account_credited.php\",\"message\",\"width=$popup_width,height=$popup_height,left=0,top=0,toolbars=0, scrollbars=0, location=0, statusbars=0, menubars=0, resizable=0\");
//-->
message.window.focus();
</SCRIPT></head></html>";
echo "<br>The URL is valid and working";
} else {
include($pages_dir."account_credited.php");}
exit;
}
else { echo "Invalid Auth Code - The URL you provided does not seem to be working";  
logs("security","Invalid Auth Code for a paid mail tracking URL",$US);
$message="Hello. I am the runner.php script for your website. I am reporting the entry of an invalid Auth Code for a ptc tracking URL. Please verify that the URL is not working (click it) and keep an eye out on this member. They are possably trying to cheat\n\nURL: ".$scripts_url."runner.php?".getenv("QUERY_STRING")."\n\nUsername: $US\nIP/HOST: $ipaddr";
mail(system_value("security_email"),"Automated Security ALERT.",$message);   
exit;
}
}
if ($PA and $VT and $TI and $VA){
if ($VA!="N/A"){
$VA=$VA/100000;}
$thetime=time();
echo "<html><head>
<META HTTP-EQUIV=\"REFRESH\" CONTENT=\"$TI;URL=".$scripts_url."runner.php?PA=$PA&TI=$thetime&US=$US&AU=".md5($US.$thetime.$PA.$mysql_password)."\">";
if (!$frame_size){ 
echo "<SCRIPT language='JavaScript'><!--
message=window.open(\"".$pages_url.$VT."_timer.php?VA=$VA&TI=$TI\",\"message\",\"width=$popup_width,height=$popup_height,left=0,top=0,toolbars=0, scrollbars=0, location=0, statusbars=0, menubars=0, resizable=0\");
//-->
message.window.focus();
</SCRIPT>";}
?>
<script language="JavaScript">
<!--

var sURL = 'http://<? echo $scripts_url."runner.php?PA=$PA&TI=$thetime&US=$US&AU=".md5($US.$thetime.$PA.$mysql_password); ?>';

function doLoad()
{
    // the timeout value should be the same as in the "refresh" meta-tag
    setTimeout( "refresh()", <? echo $TI;?>*1000 );
}
function refresh()
{
    //  This version of the refresh function will cause a new
    //  entry in the visitor's history.  It is provided for
    //  those browsers that only support JavaScript 1.0.
    //
    window.location.href = sURL;
}
//-->
</script>

<script language="JavaScript1.1">
<!--
function refresh()
{
    //  This version does NOT cause an entry in the browser's
    //  page view history.  Most browsers will always retrieve
    //  the document from the web-server whether it is already
    //  in the browsers page-cache or not.
    // 
    window.location.replace( sURL );
}
//-->
</script>
<? if ($frame_size){
include($pages_dir.$VT."_timer.php");}?>
</head></html>
<?
exit;
} 
if ($PA){
$PA=stripslashes($PA);
list($PA)=split("\"",$PA);
list($PA)=split(">",$PA);
$ptcad=@mysql_fetch_array(@mysql_query("select * from ".$mysql_prefix."ptc_ads where ptcid='$PA' limit 1"));
$lastptc=$PA;
session_register("lastptc");
if (!$ptcad[0]){                      
include($pages_dir."invalid_paid_mail.php");
logs("click_errors","Invalid PTC ID",$username);
exit;
}
if (($ptcad[clicks]>=$ptcad[run_quantity] and $ptcad[run_type]=="clicks") or ($mysqldate>=$ptcad[run_quantity] and $ptcad[run_type]=="date") or ($ptcad[views]>=$ptcad[Run_quantity] and $ptcad[run_type]=="views")){
$expireurl=$ptcad[site_url];
include($pages_dir."expired_ptc_ad.php");
logs("click_errors","Expired PTC ID",$username);    
exit;
}
login();
$clickcheck=@mysql_fetch_array(@mysql_query("select * from ".$mysql_prefix."paid_clicks where id=$PA and username='$username' limit 1"));
if ($clickcheck[0] or ereg("#".$PA."-ptc-$username#",$sessionclicks)){
logs("click_errors","Site already visited",$username);
include($pages_dir."already_credited.php");
exit;
}
if ($ptcad[timer] and $ptcad[image_url]){
if (!$ptcad[value]){
$emailad[value]="N/A";
}
if ($frame_size){
$border=1;
} else {$frame_size=0; $noresize='noresize'; $border=0;}
echo "<frameset framespacing=0 frameborder=$border border=$border rows='$frame_size,1*'> 
  <frame name=Top src=".$scripts_url."runner.php?PA=$PA&VT=$ptcad[vtype]&TI=$ptcad[timer]&VA=$ptcad[value]&US=$username scrolling=no $noresize>
    <frame name=Frame4 src=$ptcad[site_url] $noresize style='mso-linked-frame:auto'>
  </frameset>
  <noframes> 
  <body lang=EN-US style='tab-interval:.5in'>
  <div class=Section1> 
    <p class=MsoNormal>This page uses frames, but your browser doesn't support 
      them.</p>
  </div>
  </body>
  </noframes> </frameset>";
exit;
}
$sessionclicks=$sessionclicks."#".$PA."-ptc-$username#";
session_register("sessionclicks");
@mysql_query("insert into ".$mysql_prefix."paid_clicks set username='$username',id='$PA',value='$ptcad[value]',vtype='$ptcad[vtype]',ip_host='$ipaddr'");
if ($ptcad[vtype]=='points'){ 
$P='POINT-';} 
@mysql_query("update ".$mysql_prefix."ptc_ads set clicks=clicks+1 where ptcid=$PA");
$update=@mysql_query("UPDATE ".$mysql_prefix."latest_stats set time=concat('".$mysqldate.",',time),type=concat('ptc,',type),id=concat('$PA,',id) where username='$username' limit 1");
if (!mysql_affected_rows()){
@mysql_query("insert into ".$mysql_prefix."latest_stats set time='$mysqldate',type='paidmail',id='$PA',username='$username'");}
if ($ptcad[value]){
$update=@mysql_query("UPDATE ".$mysql_prefix."accounting SET amount=amount+$ptcad[value] WHERE type='$ptcad[vtype]' and username = '$username' and description='#SELF-".$P."EARNINGS#' limit 1");
if (!mysql_affected_rows()){
$rand=substr(md5($username),0,3).rand(0,9);
$pickedtransid="$unixtime$rand";
$update=@mysql_query("insert INTO ".$mysql_prefix."accounting set transid='$pickedtransid', username = '$username',unixtime=0,description='#SELF-".$P."EARNINGS#',amount=$ptcad[value],type='$ptcad[vtype]'");
if (!mysql_affected_rows()){logs("db_write_errors","Credit did not save for ptc ad",$username);}
}
creditulclicks($username,$ptcad[value],$ptcad[vtype]);
}
if (!$ptcad[image_url]){
echo "<script>
window.focus();
</script>";
include($pages_dir."account_credited.php");}
else { header("Location: $ptcad[site_url]");}
exit;}
if ($SP){
$secondpart=trim(substr($SP,8,strlen($SP)-8));
if (!$startpagehit and $SP==substr(md5($secondpart.$mysql_password),0,8).$secondpart){
$ptcad=@mysql_fetch_array(@mysql_query("select * from ".$mysql_prefix."ptc_ads where description='#PAID-START-PAGE#' limit 1"));
if (!$ptcad[site_url]){
include($pages_dir."invalid_paid_mail.php"); 
logs("click_errors","Start page does not have URL set",$secondpart);
exit;
}
setcookie("startpagehit",$ptcad[site_url],time()+($ptcad[hrlock]*60*60),"/");
$clickcheck=@mysql_fetch_array(@mysql_query("select * from ".$mysql_prefix."paid_clicks where id=$ptcad[ptcid] and username='$secondpart' limit 1"));
if (!$clickcheck[0]){
if ($ptcad[vtype]=='points'){
$P='POINT-';}
@mysql_query("insert into ".$mysql_prefix."paid_clicks set username='$secondpart',id='$ptcad[ptcid]',value='$ptcad[value]',vtype='$ptcad[vtype]',ip_host='$ipaddr'");
if ($ptcad[value]){
$update=@mysql_query("UPDATE ".$mysql_prefix."accounting SET amount=amount+$ptcad[value] WHERE type='$ptcad[vtype]' and username = '$secondpart' and description='#SELF-".$P."EARNINGS#' limit 1");
if (!mysql_affected_rows()){
$rand=substr(md5($secondpart),0,3).rand(0,9);
$pickedtransid="$unixtime$rand";
$update=@mysql_query("INSERT INTO ".$mysql_prefix."accounting set transid='$pickedtransid',username = '$secondpart',unixtime=0,description='#SELF-".$P."EARNINGS#',amount=$ptcad[value],type='$ptcad[vtype]'");
}
creditulclicks($secondpart,$ptcad[value],$ptcad[vtype]);
}
}
}
if (!$ptcad[site_url]){
$ptcad[site_url]=$startpagehit;}
header("Location: $ptcad[site_url]");
exit;
}
if (!$_POST['txn_type'])
{
echo "CashCrusader Version:".system_value("version"); 
if ($unixtomysql){
echo "<br>".date("YmdHis",$unixtomysql);
}
exit;
}
else
{ header("Status: 200 OK");
}
$postvars = array();
$restrict = array('receiver_email','business','item_name','item_name_','item_number','item_number_','quantity','invoice','custom','option_name','option_selection','option_name_','option_selection_','num_cart_items','payment_status','pending_reason','payment_date','settle_amount','settle_currency','exchange_rate','payment_gross','payment_fee','mc_gross','mc_fee','mc_currency','txn_id','tax','txn_type','for_auction','memo','first_name','last_name','address_street','address_city','address_state','address_zip','address_country','address_status','payer_email','payer_id','payer_status','payment_type','notify_version','verify_sign','subscr_date','subscr_effective','period','amount','mc_amount','recurring','reattempt','retry_at','recur_times','username','password','subscr_id');
foreach ($_POST as $ipnkey => $ipnvalue)
{ if (in_array (ereg_replace("[0-9]", '', $ipnkey), $restrict)) {
$GLOBALS[$ipnkey] = $ipnvalue; // Posted variable Localization
$postvars[] = $ipnkey;
}}
$postipn = 'cmd=_notify-validate';
$noteipn = '<b>IPN post variables in order of appearance:</b><br><br>';
for ($x=0; $x < count($postvars); $x++)
{ $y=$x+1;
$postkey = $postvars[$x];
$postval = $$postvars[$x];
$postipn.= "&" . $postkey . "=" . urlencode($postval);
$noteipn.= "<b>#" . $y . "</b> Key: " . $postkey . " <b>=</b> Value: " . $postval . "<br>";
}
$socket = fsockopen ("www.paypal.com", 80, $errno, $errstr, 30);
$header = "POST /cgi-bin/webscr HTTP/1.0\r\n";
$header.= "Host: www.paypal.com\r\n";
$header.= "Content-Type: application/x-www-form-urlencoded\r\n";
$header.= "Content-Length: " . strlen($postipn) . "\r\n\r\n";
if (!$socket && !$error)
{
echo "Problem: Error Number: " . $errno . " Error String: " . $errstr;
exit;
}
else
{
fputs ($socket, $header . $postipn);
while (!feof($socket))
{
$reply = fgets ($socket, 1024);
$reply = trim ($reply);}
$receiver_email = $_POST['receiver_email'];
$business = $_POST['business'];
$item_name = $_POST['item_name'];
$item_number = $_POST['item_number'];
$quantity = $_POST['quantity'];
$invoice = $_POST['invoice'];
$custom = $_POST['custom'];
$option_name1 = $_POST['option_name1'];
$option_selection1 = $_POST['option_selection1'];
$option_name2 = $_POST['option_name2'];
$option_selection2 = $_POST['option_selection2'];
$num_cart_items = $_POST['num_cart_items'];
$payment_status = $_POST['payment_status'];
$pending_reason = $_POST['pending_reason'];
$payment_date = $_POST['payment_date'];
$settle_amount = $_POST['settle_amount'];
$settle_currency = $_POST['settle_currency'];
$exchange_rate = $_POST['exchange_rate'];
$payment_gross = $_POST['payment_gross'];
$payment_fee = $_POST['payment_fee'];
$mc_gross = $_POST['mc_gross'];
$mc_fee = $_POST['mc_fee'];
$mc_currency = $_POST['mc_currency'];
$tax = $_POST['tax'];
$txn_id = $_POST['txn_id'];
$txn_type = $_POST['txn_type'];
$for_auction = $_POST['for_auction'];
$memo = $_POST['memo'];
$first_name = $_POST['first_name'];
$last_name = $_POST['last_name'];
$address_street = $_POST['address_street'];
$address_city = $_POST['address_city'];
$address_state = $_POST['address_state'];
$address_zip = $_POST['address_zip'];
$address_country = $_POST['address_country'];
$address_status = $_POST['address_status'];
$payer_email = $_POST['payer_email'];
$payer_id = $_POST['payer_id'];
$payer_status = $_POST['payer_status'];
$payment_type = $_POST['payment_type'];
$notify_version = $_POST['notify_version'];
$verify_sign = $_POST['verify_sign'];
$subscr_date = $_POST['subscr_date'];
$subscr_effective = $_POST['subscr_effective'];
$period1 = $_POST['period1'];
$period2 = $_POST['period2'];
$period3 = $_POST['period3'];
$amount1 = $_POST['amount1'];
$amount2 = $_POST['amount2'];
$amount3 = $_POST['amount3'];
$mc_amount1 = $_POST['mc_amount1'];
$mc_amount2 = $_POST['mc_amount2'];
$mc_amount3 = $_POST['mc_amount3'];
$recurring = $_POST['recurring'];
$reattempt = $_POST['reattempt'];
$retry_at = $_POST['retry_at'];
$recur_times = $_POST['recur_times'];
$username = $_POST['username'];
$password = $_POST['password'];
$subscr_id = $_POST['subscr_id'];
if (!strcmp ($reply, "VERIFIED"))
{
$rand=substr(md5($custom),0,3).rand(0,9);
$pickedtransid="$unixtime$rand";
$mc_gross=$mc_gross*100000*$admin_cash_factor;
if ($payment_status=="Completed"){
$comm=system_value("sales_comm");
$desc=system_value("sales_desc");
if ($comm){
$postcomm="Y";}
@mysql_query("insert into ".$mysql_prefix."accounting set comm='$postcomm',transid='$pickedtransid',username='$custom',description='PayPal $txn_id',unixtime='0',type='cash',amount='$mc_gross'");
if ($comm){
creditul($custom,$mc_gross,"cash",$comm,$desc);}
}
}
fclose ($socket);
exit;
}


