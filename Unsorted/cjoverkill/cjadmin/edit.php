<?php

/******************************************************
 * CjOverkill version 2.0.1
 * © Kaloyan Olegov Georgiev
 * http://www.icefire.org/
 * spam@icefire.org
 * 
 * Please read the lisence before you start editing this script.
 * 
********************************************************/

include ("../cj-conf.inc.php");
include ("../cj-functions.inc.php");   
cjoverkill_connect();
 
include ("security.inc.php");

if ((!isset($_GET["id"]) || $_GET["id"]=="") && (!isset($_POST["trade_id"]) || $_POST["trade_id"]=="")) {
    print_error("Please select a trade first");
}

$tid=$_GET["id"];

if ($_POST["updatetrade"]!="" && $_POST["trade_id"]!="" && isset($_POST["trade_id"])) {
    $tmp_id=$_POST["trade_id"];
    $tmpurl=$_POST["url"];
    $tmp=parse_url($tmpurl);
    $domain=eregi_replace("www\.","",$tmp["host"]);
    $sql=@mysql_query("SELECT domain AS domain_orig FROM cjoverkill_trades WHERE trade_id='$tmp_id'") OR 
      print_error(mysql_error());
    $tmp_sql=@mysql_fetch_array($sql);
    extract($tmp_sql);
    if ($domain!=$domain_orig){
	$tms="This URL is not a part of the trade domain!";
	print_error($tms);
    }
    else {
	$url=$_POST["url"];
	$site_name=$_POST["site_name"];
	$site_desc=$_POST["site_desc"];
	$email=$_POST["email"];
	$icq=$_POST["icq"];
	$return=$_POST["return"];
	$max_ret=$_POST["max_ret"];
	$max_p=$_POST["max_p"];
	$min_p=$_POST["min_p"];
	$max_px=$_POST["max_px"];
	$max_clicks=$_POST["max_clicks"];
	$max_ip=$_POST["max_ip"];
	$boost=$_POST["boost"];
	$overkill=$_POST["overkill"];
	$status=$_POST["status"];
	$passwd=$_POST["passwd"];
	$f0=$_POST["f0"];
	$f1=$_POST["f1"];
	$f2=$_POST["f2"];
	$f3=$_POST["f3"];
	$f4=$_POST["f4"];
	$f5=$_POST["f5"];
	$f6=$_POST["f6"];
	$f7=$_POST["f7"];
	$f8=$_POST["f8"];
	$f9=$_POST["f9"];
	$f10=$_POST["f10"];
	$f11=$_POST["f11"];
	$f12=$_POST["f12"];
	$f13=$_POST["f13"];
	$f14=$_POST["f14"];
	$f15=$_POST["f15"];
	$f16=$_POST["f16"];
	$f17=$_POST["f17"];
	$f18=$_POST["f18"];
	$f19=$_POST["f19"];
	$f20=$_POST["f20"];
	$f21=$_POST["f21"];
	$f22=$_POST["f22"];
	$f23=$_POST["f23"];
	@mysql_query("UPDATE cjoverkill_trades SET url='$url', site_name='$site_name', site_desc='$site_desc', email='$email',
		       icq='$icq', return='$return', max_p='$max_p', min_p='$min_p', boost='$boost', overkill='$overkill',
		       status='$status', passwd='$passwd', max_px='$max_px', max_clicks='$max_clicks', max_ip='$max_ip',
		       max_ret='$max_ret'
		       WHERE trade_id='$tmp_id'") OR
	  print_error(mysql_error());
	@mysql_query("UPDATE cjoverkill_forces SET f0='$f0', f1='$f1', f2='$f2', f3='$f3', f4='$f4', f5='$f5', f6='$f6',
		       f7='$f7', f8='$f8', f9='$f9', f10='$f10', f11='$f11', f12='$f12', f13='$f13', f14='$f14', f15='$f15',
		       f16='$f16', f17='$f17', f18='$f18', f19='$f19', f20='$f20', f21='$f21', f22='$f22', f23='$f23'
		       WHERE trade_id='$tmp_id'") OR
	  print_error(mysql_error());
	$tms="$domain updated";
    }
}

if (!isset($tid) || $tid==""){
    $tid=$tmp_id;
}

$sql=@mysql_query("SELECT * FROM cjoverkill_trades,cjoverkill_forces 
		    WHERE cjoverkill_trades.trade_id='$tid' AND 
		    cjoverkill_trades.trade_id=cjoverkill_forces.trade_id") OR
  print_error(mysql_error());
$tmp=@mysql_fetch_array($sql);
extract($tmp);
if ($uniq_tot!=0){
    $prod_tot="".round(($clicks_tot/$uniq_tot)*100, 1)."%";
}
else {
    $prod_tot="--";
}
if ($out_tot!=0){
    $vale_tot="".round(($clicks_tot/$out_tot)*100,0)."%";
}
else{
    $vale_tot="--";
}
      
if (!isset($tms)) { 
    $tms = "Edit $domain"; 
}
cjoverkill_disconnect();

echo ("<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\">\n
	<html>\n
	<head>\n
	<title>$tms</title>\n
	<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\">\n
	<link href=\"../cj-style.css\" rel=\"stylesheet\" type=\"text/css\">\n
	<script language=\"javascript\">\n
	function allhours() {\n
	      this.form1.f0.value=this.form1.all.value;\n
	    this.form1.f1.value=this.form1.all.value;\n
	    this.form1.f2.value=this.form1.all.value;\n
	    this.form1.f3.value=this.form1.all.value;\n
	    this.form1.f4.value=this.form1.all.value;\n
	    this.form1.f5.value=this.form1.all.value;\n
	    this.form1.f6.value=this.form1.all.value;\n
	    this.form1.f7.value=this.form1.all.value;\n
	    this.form1.f8.value=this.form1.all.value;\n
	    this.form1.f9.value=this.form1.all.value;\n
	    this.form1.f10.value=this.form1.all.value;\n
	    this.form1.f11.value=this.form1.all.value;\n
	    this.form1.f12.value=this.form1.all.value;\n
	    this.form1.f13.value=this.form1.all.value;\n
	    this.form1.f14.value=this.form1.all.value;\n
	    this.form1.f15.value=this.form1.all.value;\n
	    this.form1.f16.value=this.form1.all.value;\n
	    this.form1.f17.value=this.form1.all.value;\n
	    this.form1.f18.value=this.form1.all.value;\n
	    this.form1.f19.value=this.form1.all.value;\n
	    this.form1.f20.value=this.form1.all.value;\n
	    this.form1.f21.value=this.form1.all.value;\n
	    this.form1.f22.value=this.form1.all.value;\n
	    this.form1.f23.value=this.form1.all.value;\n
	    }\n
	</script>\n
	</head>\n
	<body bgcolor=\"#FFFFFF\" text=\"#000000\" link=\"#000000\" vlink=\"#000000\" alink=\"#000000\">\n
	<form action=\"edit.php\" method=\"POST\" name=\"form1\">\n
	<input type=\"hidden\" name=\"trade_id\" value=\"$trade_id\">\n
	<div align=\"center\"><b><font size=\"4\">$tms</font></b><br>\n
	<br>\n
	</div>\n
	<table width=\"600\" border=\"1\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\" bordercolor=\"#000000\">\n
	<tr>\n
	<td><table width=\"100%\" border=\"1\" cellpadding=\"2\" cellspacing=\"0\" class=\"normalrow\">\n
	<tr class=\"toprows\">\n
	<td colspan=\"4\">Webmaster &amp; Trade Info</td>\n
	</tr>\n
	<tr align=\"left\">\n
	<td width=\"25%\">Site Name:</td>\n
	<td width=\"25%\"><input name=\"site_name\" type=\"text\" id=\"site_name\" size=\"20\" maxlength=\"250\" value=\"$site_name\"></td>\n
	<td width=\"25%\">Webmaster Email:</td>\n
	<td width=\"25%\"><input name=\"email\" type=\"text\" id=\"email\" size=\"20\" maxlength=\"250\" value=\"$email\"></td>\n
	</tr>\n
	<tr align=\"left\">\n
	<td>URL:</td>\n
	<td><input name=\"url\" type=\"text\" id=\"url\" size=\"20\" maxlength=\"250\" value=\"$url\"></td>\n
	<td>Description:</td>\n
	<td><input name=\"site_desc\" type=\"text\" id=\"site_desc\" size=\"20\" maxlength=\"250\" value=\"$site_desc\"></td>\n
	</tr>\n
	<tr align=\"left\">\n
	<td>ICQ:</td>\n
	<td><input name=\"icq\" type=\"text\" id=\"icq\" size=\"20\" maxlength=\"50\" value=\"$icq\"></td>\n
	<td>Password:</td>\n
	<td><input name=\"passwd\" type=\"text\" id=\"passwd\" size=\"20\" maxlength=\"250\" value=\"$passwd\"></td>\n
	</tr>\n
	<tr align=\"left\">\n
	<td>Total Raws:</td>\n
	<td>$raw_tot</td>\n
	<td>Total Uniques:</td>\n
	<td>$uniq_tot</td>\n
	</tr>\n
	<tr align=\"left\">\n
	<td>Total Outs:</td>\n
	<td>$out_tot</td>\n
	<td>Total Clicks:</td>\n
	<td>$clicks_tot</td>\n
	</tr>\n
	<tr align=\"left\">\n
	<td>Total Prod:</td>\n
	<td>$prod_tot</td>\n
	<td>Total Q:</td>\n
	<td>$vale_tot</td>\n
	</tr>\n
	
	<tr class=\"toprows\">\n
	<td colspan=\"4\">Trade Settings</td>\n
	</tr>\n
	<tr align=\"left\">\n
	<td>Trade Ratio:</td>\n
	<td><input name=\"return\" type=\"text\" id=\"return\" size=\"20\" maxlength=\"10\" value=\"$return\"></td>\n
	<td>Status:</td>\n
	<td><select name=\"status\">\n
	<option value=\"1\" ");
if ($status==1) { 
    echo ("selected"); 
}
echo (">Enabled</option>\n
	<option value=\"0\" ");
if ($status==0) { 
    echo ("selected"); 
}
echo (">Disabled</option>\n
	<option value=\"2\" ");
if ($status==2) { 
    echo ("selected"); 
}
echo (">Auto Disabled</option>\n
	<option value=\"3\" ");
if ($status==3) {
        echo ("selected");
}
echo (">INMUTABLE</option>\n
	</td>\n
	</tr>\n
	<tr align=\"left\">\n
	<td>Minimum Prod:</td>\n
	<td><input name=\"min_p\" type=\"text\" id=\"min_p\" size=\"20\" maxlength=\"10\" value=\"$min_p\"></td>\n
	<td>Boost Mode:</td>\n
	<td><select name=\"boost\">\n
	<option value=\"1\" ");
if ($boost==1) { 
    echo ("selected"); 
}
echo (">Yes</option>\n
	<option value=\"0\" ");
if ($boost==0) {
        echo ("selected");
}
echo (">NO</option>\n
	</td>\n
	</tr>\n
	<tr align=\"left\">\n
	<td>Maximum Prod:</td>\n
	<td><input name=\"max_p\" type=\"text\" id=\"max_p\" size=\"20\" maxlength=\"10\" value=\"$max_p\"></td>\n
	<td>Overkill Mode:</td>\n
	<td><select name=\"overkill\">\n
	<option value=\"1\" ");
if ($overkill==1) { 
    echo ("selected"); 
}
echo (">Yes</option>\n
	<option value=\"0\" ");
if ($overkill==0) {
        echo ("selected");
}
echo (">NO</option>\n
	</td>\n
	</tr>\n
	<tr align=\"left\">\n
	<td>Maximum Proxy:</td>\n
	<td><input name=\"max_px\" type=\"text\" id=\"max_px\" size=\"20\" maxlength=\"10\" value=\"$max_px\">%</td>\n
	<td>Maximum Clicks per user:</td>\n
	<td><input name=\"max_clicks\" type=\"text\" id=\"max_clicks\" size=\"20\" maxlength=\"10\" value=\"$max_clicks\"></td>\n
	</tr>
	<tr align=\"left\">
	<td>Maximum Return:</td>
	<td><input name=\"max_ret\" type=\"text\" id=\"max_ret\" size=\"20\" maxlength=\"10\" value=\"$max_ret\">%</td>\n
	<td>Maximum Repetitive IPs:</td>
	<td><input name=\"max_ip\" type=\"text\" id=\"max_ip\" size=\"20\" maxlength=\"10\" value=\"$max_ip\"></td>
	</tr>
	<tr class=\"toprows\">\n
	<td colspan=\"4\">Traffic Force:</td>\n
	</tr>\n
	<tr class=\"normalrow\">\n
	<td align=\"left\">Hour 00-01: <input type=\"text\" size=\"5\" name=\"f0\" value=\"$f0\"></td>\n
	<td align=\"left\">Hour 01-02: <input type=\"text\" size=\"5\" name=\"f1\" value=\"$f1\"></td>\n
	<td align=\"left\">Hour 02-03: <input type=\"text\" size=\"5\" name=\"f2\" value=\"$f2\"></td>\n
	<td align=\"left\">Hour 03-04: <input type=\"text\" size=\"5\" name=\"f3\" value=\"$f3\"></td>\n
	</tr>\n
	</tr>\n
	<tr class=\"normalrow\">\n
	<td align=\"left\">Hour 04-05: <input type=\"text\" size=\"5\" name=\"f4\" value=\"$f4\"></td>\n
	<td align=\"left\">Hour 05-06: <input type=\"text\" size=\"5\" name=\"f5\" value=\"$f5\"></td>\n
	<td align=\"left\">Hour 06-07: <input type=\"text\" size=\"5\" name=\"f6\" value=\"$f6\"></td>\n
	<td align=\"left\">Hour 07-08: <input type=\"text\" size=\"5\" name=\"f7\" value=\"$f7\"></td>\n
	</tr>\n
	</tr>\n
	<tr class=\"normalrow\">\n
	<td align=\"left\">Hour 08-09: <input type=\"text\" size=\"5\" name=\"f8\" value=\"$f8\"></td>\n
	<td align=\"left\">Hour 09-10: <input type=\"text\" size=\"5\" name=\"f9\" value=\"$f9\"></td>\n
	<td align=\"left\">Hour 10-11: <input type=\"text\" size=\"5\" name=\"f10\" value=\"$f10\"></td>\n
	<td align=\"left\">Hour 11-12: <input type=\"text\" size=\"5\" name=\"f11\" value=\"$f11\"></td>\n
	</tr>\n
	</tr>\n
	<tr class=\"normalrow\">\n
	<td align=\"left\">Hour 12-13: <input type=\"text\" size=\"5\" name=\"f12\" value=\"$f12\"></td>\n
	<td align=\"left\">Hour 13-14: <input type=\"text\" size=\"5\" name=\"f13\" value=\"$f13\"></td>\n
	<td align=\"left\">Hour 14-15: <input type=\"text\" size=\"5\" name=\"f14\" value=\"$f14\"></td>\n
	<td align=\"left\">Hour 15-16: <input type=\"text\" size=\"5\" name=\"f15\" value=\"$f15\"></td>\n
	</tr>\n
	</tr>\n
	<tr class=\"normalrow\">\n
	<td align=\"left\">Hour 16-17: <input type=\"text\" size=\"5\" name=\"f16\" value=\"$f16\"></td>\n
	<td align=\"left\">Hour 17-18: <input type=\"text\" size=\"5\" name=\"f17\" value=\"$f17\"></td>\n
	<td align=\"left\">Hour 18-19: <input type=\"text\" size=\"5\" name=\"f18\" value=\"$f18\"></td>\n
	<td align=\"left\">Hour 19-20: <input type=\"text\" size=\"5\" name=\"f19\" value=\"$f19\"></td>\n
	</tr>\n
	</tr>\n
	<tr class=\"normalrow\">\n
	<td align=\"left\">Hour 20-21: <input type=\"text\" size=\"5\" name=\"f20\" value=\"$f20\"></td>\n
	<td align=\"left\">Hour 21-22: <input type=\"text\" size=\"5\" name=\"f21\" value=\"$f21\"></td>\n
	<td align=\"left\">Hour 22-23: <input type=\"text\" size=\"5\" name=\"f22\" value=\"$f22\"></td>\n
	<td align=\"left\">Hour 23-00: <input type=\"text\" size=\"5\" name=\"f23\" value=\"$f23\"></td>\n
	</tr>\n
	<tr class=\"normalrow\">\n
	<td colspan=\"4\" align=\"left\">Set All Hours To:\n
	<input type=\"text\" size=\"5\" name=\"all\"> \n
	<input name=\"button1\" type=\"button\" class=\"buttons\" onclick=\"allhours()\" value=\"Set\">\n
	</td>\n
	</tr>\n
	</table></td>\n
	</tr>\n
	</table>\n
	<div align=\"center\"><br>\n
	<input name=\"updatetrade\" type=\"submit\" class=\"buttons\" id=\"updatetrade\" value=\"Update Trade\">\n
	<br><br><br><font size=\"2\"><a href=\"javascript:window.close()\">Close Window</a></font>\n
	</div>\n
	</form>\n
	</body>\n
	</html>\n
	");


?>
