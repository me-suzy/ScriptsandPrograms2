<?
	require "conf/sys.conf";
        require "lib/mysql.lib";
        require "lib/mail.lib";

	$sval = "";

	if($action == "submit"){
		$db = c();
	 	$r = q("select * from members where login='$login' and email='$email'");
		if(e($r)) $es = "User not found!";
		else{
			$ff = f($r);
			$fname = $ff[fname];
			$lname = $ff[lname];
			$pswd = $ff[pswd];
			
			MsgFromTpl($email,"Forgot password info.","tpl/forgot.mtl");		
			$sval = "mail";
		}
		d($db);
 	}

 include "tpl/top.ihtml";

 if($sval == ""){
?>
<br><b><font size=4 face=verdana>Request password via email</font></b><br>
<font size=2 face=verdana>If you cannot access your account, please, <a href=mailto:<? echo $ADMIN_MAIL; ?>><font size=2 face=verdana color=#0000FF>contact our system administrator</a>.<br><br>
<table width="250" border="0" cellspacing="0" cellpadding="5">
  <form action="forgot.php?action=submit" method=post>
  <?
    if($es != "" && $action == "submit")
       echo "<tr><td colspan=2><b><font color=C00000>$es</td></tr>";
  ?>
  <tr>
      <td><font size=2 face=verdana>Username:</td>
    <td>
        <input type="text" name="login" class=cmn>
      </td>
  </tr>
  <tr>
      <td><font size=2 face=verdana>E-mail:</td>
    <td>
        <input type="text" name="email" class=cmn>
      </td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>
        <input type="submit" value="Get password">
      </td>
  </tr>
  </form>
</table>
<?
   } 

  if($sval == "mail"){
 	echo "<center>";
	echo "Dear $ff[fname] $ff[lname]!<br>";
	echo "Check your mail soon, we have sent you your account info.";
	echo "</center>";
  }
?>
<? include "tpl/bottom.ihtml" ?>