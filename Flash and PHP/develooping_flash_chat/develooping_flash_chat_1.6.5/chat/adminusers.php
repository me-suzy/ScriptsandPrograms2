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
/*	users management for develooping flash chat         */
/*	version 1.6.5 Created by Juan Carlos Posé	            */
/*	juancarlos@develooping.com	                        */
?>


<?php 
require ('required/config.php');
$users_file = "required/users.txt";

if (($name==$admin_name) and ($password==$admin_password)){

echo "<table width=\"400\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">";
echo "<tr>";
echo "<td width=\"100\">";
echo "<font face='Verdana, Arial, Helvetica, sans-serif' size='1' color='#990000'><b>";
echo htmlentities($intro_admin_name);
echo "</b></font></td><td width=\"100\">";
echo "<font face='Verdana, Arial, Helvetica, sans-serif' size='1' color='#990000'><b>";
if ($password_system=="ip"){
echo "IP";
}else{
echo htmlentities($intro_admin_password);
}
echo "</b></font></td>";
echo "<td width=\"100\">&nbsp</td>";
echo "<td width=\"100\">&nbsp</td></tr></table>";
$lines = file($users_file);
$a = count($lines);

if ($a==0){
echo "<table width=\"400\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">";
echo "<tr><td>";
echo "<center>";
echo "<font face='Verdana, Arial, Helvetica, sans-serif' size='1' color='#000000'>";
echo htmlentities($no_users);
echo "</font>";
echo "</center>";
echo "</td></tr></table>";
}
else{
$presence=0;
for($i = $a; $i >= 0 ;$i=$i-2){
$each_user = strval($lines[$i]);//each connected user
$each_user = str_replace ("\n","", $each_user);
$each_password = strval($lines[$i+1]);
$each_password = str_replace ("\n","", $each_password);
$each_password = trim ($each_password);
$userisgood=1;
if (($each_password=="kicked")or($each_password=="banned")){$userisgood=0;}
if (($each_user!="") and ($userisgood==1)){
$presence=1;
echo "<table width=\"400\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">";
echo "<tr>";
echo "<td width=\"100\">";
echo "<font face='Verdana, Arial, Helvetica, sans-serif' size='1' color='#000000'>";
echo $each_user."&nbsp;";
echo "</font></td><td width=\"100\">";
echo "<font face='Verdana, Arial, Helvetica, sans-serif' size='1' color='#000000'>";
echo $each_password."&nbsp;";
echo "</font></td><td width=\"100\">";
echo "<form name=\"$each_user\" method=\"post\" action=\"kickuser.php\">";
echo "<font face='Verdana, Arial, Helvetica, sans-serif' size='1' color='#000000'>";
echo "<input type=\"hidden\" name=\"name\" value=\"$name\">";
echo "<input type=\"hidden\" name=\"password\" value=\"$password\">";
echo "<input type=\"hidden\" name=\"user_name\" value=\"$each_user\">";
echo "<input type=\"hidden\" name=\"user_password\" value=\"$each_password\">";
echo "<input type=\"submit\" name=\"Submit\" value=\"".htmlentities($text_for_kick_button)."\" class=\"but\" onmouseover=\"style.backgroundColor='#DDDDDD'; style.color='#CC0000';\" onmouseout=\"style.backgroundColor='#CCCCCC'; style.color='#666666'; width:75\">";
echo "</font></form></td>";
echo "<td width=\"100\">";
if ($password_system=="ip"){
echo "<form name=\"ban$each_user\" method=\"post\" action=\"ban_user.php\">";
echo "<font face='Verdana, Arial, Helvetica, sans-serif' size='1' color='#000000'>";
echo "<input type=\"hidden\" name=\"name\" value=\"$name\">";
echo "<input type=\"hidden\" name=\"password\" value=\"$password\">";
echo "<input type=\"hidden\" name=\"user_name\" value=\"$each_user\">";
echo "<input type=\"hidden\" name=\"user_password\" value=\"".htmlentities($each_password)."\">";
echo "<input type=\"submit\" name=\"Submit\" value=\"".htmlentities($text_for_bann_button)."\" class=\"but\" onmouseover=\"style.backgroundColor='#DDDDDD'; style.color='#CC0000';\" onmouseout=\"style.backgroundColor='#CCCCCC'; style.color='#666666'; width:75\">";
echo "</font></form>";
}
echo "</td></tr></table><hr>"."\n";
 
}
}
if ($presence==0){
echo "<table width=\"400\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">";
echo "<tr><td>";
echo "<center>";
echo "<font face='Verdana, Arial, Helvetica, sans-serif' size='1' color='#000000'>";
echo htmlentities($no_users);
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
if ($password_system=="ip"){
echo "<a href=\"adminips.php?name=$name&password=$password\">".htmlentities($ip_link)."</a>";
}else{
echo htmlentities($no_ip_link);
}
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
