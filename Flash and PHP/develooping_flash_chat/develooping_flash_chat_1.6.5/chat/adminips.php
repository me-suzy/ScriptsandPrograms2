<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="no-cache">
<META HTTP-EQUIV="Pragma" CONTENT="no-cache">
<meta http-equiv="expires" content="Wed, 26 Feb 1995 08:21:57 GMT">
	<title>Develooping Chat Admin</title>
	<style type="text/css">
body {
background-color: #EEEEEE;
font-family: Verdana, Geneva, Arial, Helvetica, sans-serif;  
font-size : 10px;  
}
a:link{ color :#990000;text-decoration: none;}
a:active{ color :#FF9933;text-decoration: none;}
a:visited {  color :#CC6666;text-decoration: none;}
a:hover { text-decoration: underline; 
color : #990000;
}
input, select, textarea{
border : 1px solid #999999;
background-color : #DDDDDD;
color : #666666;
font-size : 10px;
font-family : Verdana, Geneva, Arial, Helvetica, sans-serif;
border-width: 1px 0px 0px 1px;
text-indent : 2px;
}
input.but{
border : 1px solid #AAAAAA;
background-color : #CCCCCC;
color : #666666;
font-size : 10px;
font-family : Verdana, Geneva, Arial, Helvetica, sans-serif;
border-width: 2px 3px 3px 2px;
}
</style>
</head>
<body>
<?php 
/*	ip management for develooping flash chat            */
/*	version 1.6.5 Created by Juan Carlos Pos	            */
/*	juancarlos@develooping.com	                        */
?>


<?php 
require ('required/config.php');
$banned_file = "required/banned_ip.txt";

if (($name==$admin_name) and ($password==$admin_password)){


$lines = file($banned_file);
$a = count($lines);

if ($a==0){
echo "<table width=\"400\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">";
echo "<tr><td>";
echo "<center>";
echo "<font face='Verdana, Arial, Helvetica, sans-serif' size='1' color='#000000'>";
echo htmlentities($no_ips);
echo "</font>";
echo "</center>";
echo "</td></tr></table>";
}
else{
$presence=0;
for($i = $a; $i >= 0 ;$i--){
$each_ip = strval($lines[$i]);//each ip in the file
$each_ip = str_replace ("\n","", $each_ip);
$each_ip = trim ($each_ip);
if ($each_ip!=""){
$presence=1;
echo "<table width=\"400\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">";
echo "<tr>";
echo "<td width=\"100\">";
echo "<font face='Verdana, Arial, Helvetica, sans-serif' size='1' color='#000000'>";
echo $each_ip."&nbsp;";
echo "</font></td><td width=\"100\">";
echo "<font face='Verdana, Arial, Helvetica, sans-serif' size='1' color='#000000'>";
echo "&nbsp;";
echo "</font></td><td width=\"200\">";
echo "<form name=\"$each_user\" method=\"post\" action=\"pardon_ip.php\">";
echo "<font face='Verdana, Arial, Helvetica, sans-serif' size='1' color='#000000'>";
echo "<input type=\"hidden\" name=\"name\" value=\"$name\">";
echo "<input type=\"hidden\" name=\"password\" value=\"$password\">";
echo "<input type=\"hidden\" name=\"ip\" value=\"$each_ip\">";
echo "<input type=\"submit\" name=\"Submit\" value=\"".htmlentities($text_for_pardon_button)."\" class=\"but\" onmouseover=\"style.backgroundColor='#DDDDDD'; style.color='#CC0000';\" onmouseout=\"style.backgroundColor='#CCCCCC'; style.color='#666666'; width:75\">";
echo "</font></form></td></tr></table><hr>"."\n";
}
 }
 if ($presence==0){
echo "<table width=\"400\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">";
echo "<tr><td>";
echo "<center>";
echo "<font face='Verdana, Arial, Helvetica, sans-serif' size='1' color='#000000'>";
echo htmlentities($no_ips);
echo "</font>";
echo "</center>";
echo "</td></tr></table>";
}
}
echo "<hr>";
echo "<table width=\"400\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">";
echo "<tr>";
echo "<td width=\"400\">";
echo "<center>";
echo "<font face='Verdana, Arial, Helvetica, sans-serif' size='1' color='#000000'>";
echo "<a href=\"adminusers.php?name=$name&password=$password\">".htmlentities($users_link)."</a>";
echo "</font>";
echo "</center>";
echo "</td></tr></table>";
}
else{
echo "<script>";
echo "location.replace('admin.php?name=$name&password=$password')";
echo "</script>";
}

?>
</body>
</html>
