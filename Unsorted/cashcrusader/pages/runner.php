<?
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");    // Date in the past
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
                                                     // always modified
header("Cache-Control: no-store, no-cache, must-revalidate");  // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");                          // HTTP/1.0
include("conf.inc.php");
require_once("functions.inc.php");
if (getenv("HTTP_X_FORWARDED_FOR")){
$ipaddr=getenv("HTTP_X_FORWARDED_FOR")."/".getenv("HTTP_VIA");}
$mysqldate=date("YmdHis",time());
if ($BA){
if ($type=='clicks'){$updatecurrent=",run_current=run_current+1";}
mysql_query("update ".$mysql_prefix."rotating_ads set clicks=clicks+1 $updatecurrent where bannerid='$bannerid'");
header("Location: $url");
exit;
}
if ($EA and $TI>999 and $AU){
if ($AU==md5($US.$TI.$EA)){
$emailad=mysql_fetch_array(mysql_query("select * from ".$mysql_prefix."email_ads where emailid='$EA' limit 1"));
if (!$emailad[0]){
echo "Invalid paid mail ID";
echo "<br>The URL is valid and working";
logs("click_errors","Invalid paid mail ID",$US);
exit;
}
if (($emailad[clicks]>=$emailad[run_quantity] and $emailad[run_type]=="clicks") or ($mysqldate>=$emailad[run_quantity] and $emailad[run_type]=="date")){
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
$clickcheck=mysql_fetch_array(mysql_query("select * from ".$mysql_prefix."paid_clicks where type='paidmail' and id=$EA and username='$US' limit 1"));
if ($clickcheck[0] or ereg("#".$EA."-paidmail#",$sessionclicks)){
echo "Site already visited";
logs("click_errors","Site already visited",$US);
echo "<br>The URL is valid and working";
exit;
}
$sessionclicks=$sessionclicks."#".$EA."-paidmail#";
session_register("sessionclicks");
if ($emailad[run_type]=='clicks'){
$run_current="run_current=run_current+1,";}
mysql_query("update email_ads set $run_current clicks=clicks+1 where emailid=$EA");
mysql_query("insert into ".$mysql_prefix."paid_clicks set username='$US',id='$EA',type='paidmail',value='$emailad[value]',vtype='$emailad[vtype]',ip_host='$ipaddr'");
echo "<html><head>
<SCRIPT language='JavaScript'><!--
message=window.open(\"".$pages_url.$VT."account_credited.php\",\"message\",\"width=$popup_width,height=$popup_height,left=0,top=0,toolbars=0, scrollbars=0, location=0, statusbars=0, menubars=0, resizable=0\");
//-->
message.window.focus();
</SCRIPT></head></html>";
echo "<br>The URL is valid and working";
exit;
}
else { echo "Invalid Auth Code - The URL you provided does not seem to be working";  
logs("security","Invalid Auth Code for a paid mail tracking URL",$US);
$message="Hello. I am the runner.php script for your website. I am reporting the entry of an invalid Auth Code for a paid mail tracking URL. Please verify that the URL is not working (click it) and keep an eye out on this member. They are possably trying to cheat\n\nURL: ".$scripts_url."runner.php?".getenv("QUERY_STRING")."\n\nUsername: $US\nIP/HOST: $ipaddr";
mail($security_address,"Automated Security ALERT.",$message);   
exit;
}
}
if ($EA and $VT and $TI and $VA){
$VA=$VA/100000;
$thetime=time();
echo "<html><head>
<META HTTP-EQUIV=\"REFRESH\" CONTENT=\"$TI;URL=".$scripts_url."runner.php?EA=$EA&TI=$thetime&US=$US&AU=".md5($US.$thetime.$EA)."\"> 
<SCRIPT language='JavaScript'><!--
message=window.open(\"".$pages_url.$VT."_timer.php?VA=$VA&TI=$TI\",\"message\",\"width=$popup_width,height=$popup_height,left=0,top=0,toolbars=0, scrollbars=0, location=0, statusbars=0, menubars=0, resizable=0\");
//-->
message.window.focus();
</SCRIPT>";
?>
<script language="JavaScript">
<!--

var sURL = 'http://<? echo $scripts_url."runner.php?EA=$EA&TI=$thetime&US=$US&AU=".md5($US.$thetime.$EA); ?>';

function doLoad()
{
    // the timeout value should be the same as in the "refresh" meta-tag
    setTimeout( "refresh()", <? echo $TI;?>*1000 );
}
function ontop(){
window.focus();
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
</head></html>
<?
exit;
} 
if ($EA){
$EA=stripslashes($EA);
list($EA)=split("\"",$EA);
list($EA)=split(">",$EA);
$emailad=mysql_fetch_array(mysql_query("select * from ".$mysql_prefix."email_ads where emailid='$EA' limit 1"));
if (!$emailad[0]){                      
include($pages_dir."invalid_paid_mail.php");
logs("click_errors","Invalid paid mail ID",$username);
exit;
}
if (($emailad[clicks]>=$emailad[run_quantity] and $emailad[run_type]=="clicks") or ($mysqldate>=$emailad[run_quantity] and $emailad[run_type]=="date")){
include($pages_dir."expired_paid_mail.php");
logs("click_errors","Expired paid mail ID",$username);    
exit;
}
login();
$clickcheck=mysql_fetch_array(mysql_query("select * from ".$mysql_prefix."paid_clicks where type='paidmail' and id=$EA and username='$username' limit 1"));
if ($clickcheck[0] or ereg("#".$EA."-paidmail#",$sessionclicks)){
logs("click_errors","Site already visited",$username);
include($pages_dir."already_credited.php");
exit;
}
if ($emailad[timer]){
echo "<frameset framespacing=0 frameborder=0 border=0 rows='0,1*'> 
  <frame name=Top src=".$scripts_url."runner.php?EA=$EA&VT=$emailad[vtype]&TI=$emailad[timer]&VA=$emailad[value]&US=$username scrolling=no noresize>
    <frame name=Frame4 src=$emailad[site_url] noresize style='mso-linked-frame:auto'>
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
$sessionclicks=$sessionclicks."#".$EA."-paidmail#";
session_register("sessionclicks");
if ($emailad[run_type]=='clicks'){
$run_current="run_current=run_current+1,";}
mysql_query("update email_ads set $run_current clicks=clicks+1 where emailid=$EA");
mysql_query("insert into ".$mysql_prefix."paid_clicks set username='$username',id='$EA',type='paidmail',value='$emailad[value]',vtype='$emailad[vtype]',ip_host='$ipaddr'"); 
header("Location: $emailad[site_url]");
exit;}

echo "<center><H1>Error! Invalid command systex</h1></center>";

