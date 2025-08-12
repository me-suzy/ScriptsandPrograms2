<? include("../conf.inc.php");
include("../functions.inc.php");
admin_login();
echo "<title>Select contest winners</title><script>window.focus()</script><center>
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
<h3>Select contest winners</h3><hr></center><br>
<form>Select <input type=text name=draw size=3 maxlength=3 value=5> contest winners who have clicked on the ad ID of <input type=text size=5 name=id> from the <select name=type><option value=paidmail>eMail Ad<option value=ptc>PTC Ad</select> database. <input type=submit value=\"Select winners\"></form> 
";
if ($type=='ptc'){
$id = " where id='$id' ";} else
{$id="_$id";}
if ($draw and $id and $type){
$report=@mysql_query("select * from ".$mysql_prefix."paid_clicks$id order by rand() limit $draw");
echo "<table class=fsize2 border=1><tr><td><b>Username</b></td><td><b>Amount</b></td><td><b>Type</b></td><td><b>Date</b></td><td><b>IP/Host</b>";
while($row=@mysql_fetch_array($report)){
$cashfactor=1;
if ($row[vtype]='cash'){
$cashfactor=$admin_cash_factor;}
$row[time]=substr($row[time],4,2)."/".substr($row[time],6,2)."/".substr($row[time],0,4)." ".substr($row[time],8,2).":".substr($row[time],10,2);
echo "</td></tr><tr $bgcolor><td><a href=viewuser.php?userid=$row[username] target=_viewuser>$row[username]</a></td><td align=right>".number_format($row[value]/100000/$cashfactor,5)."</td><td>$row[vtype]</td><td>$row[time]</td><td>$row[ip_host]";
if($bgcolor){$bgcolor='';}else{$bgcolor='bgcolor=#DDDDDD';}
}
echo "</td></tr></table>";}
