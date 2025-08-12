<?php
//////////////////////////////////////////////////////////////////////////////
// DJ Status v1.8.2															//
// ©2005 Nathan Bolender www.nathanbolender.com								//
// Free to use on any website												//
//////////////////////////////////////////////////////////////////////////////

include ("config.php");	

if ($mode == "1") {

if (!isset($dj)) {
echo "<form name=\"form1\" method=\"post\" action=\"update2.php\">
  <p><b>Welcome to the DJ select page. Please choose your name and input your password.</b><br> 
    <select name=\"newdj\" id=\"newdj\">
    <option>--Choose Your Name--</option>";
$query="SELECT * FROM currentdj ORDER BY `dj` ASC";
$result=mysql_query($query);
	while($row = mysql_fetch_array($result)) {
		$edj = "$row[dj]";
		$ename = "$row[name]";
    	echo "<option value=\"$edj\">$ename</option>";
    }
echo "</select>
      <b>Password: </b>
    <input name=\"password\" type=\"password\" id=\"password\">
</p>
  <p>
    <input type=\"submit\" name=\"Submit\" value=\"Update DJ\">
</p>
</form>";
 } else {
echo "<form name=\"form1\" method=\"post\" action=\"update2.php\">
<input type=\"hidden\" value=\"$dj\" name=\"olddj\">
  <p><b>$name is still signed on. If you are $name, please input your password to sign off.</b><br> 
<b>Name: </b>$name
      <b>Password: </b>
    <input name=\"password1\" type=\"password\" id=\"password1\"><br>You may also contact $adminname for an override if needed.
</p>
  <p>
    <input type=\"submit\" name=\"Submit\" value=\"Update DJ\">
</p>
</form>";
 } 
 } else {
 echo "<b>DJ Status is currently in Automatic mode. To switch to Manual mode, have $adminname change the setting in the administration panel.</b>";
 }
 echo "<br><br><font size=\"-1\"><strong>Powered by DJ Status v$version - &copy;2005 Nathan Bolender - <a href=\"http://www.nathanbolender.com\" target=\"_blank\">www.nathanbolender.com</a></strong></font>";
 ?>