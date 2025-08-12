<?
	require "conf/sys.conf";
        require "lib/mysql.lib";

	$sval = "";

	if($action == "submit"){
		$db = c();
	 	$r = q("select id,status from members where login='$login' and pswd='$pswd'");
		if(e($r)) $es = "Invalid password!";
		else{
			$ff = f($r);

			switch($ff[status]){
			 	case 0:
				$sval = "confirm";
				break;
				case 1:
					setcookie("auth",$ff[id]);
					d($db);
					header("Location: clients/");
				break;
				case 2:
				$sval = "contact";
				break;
			}
		}
		d($db);
 	}

 include "tpl/top.ihtml";

 if($sval == ""){
?>
<blockquote> 
  <p><b><font size=4 color=555555 face=verdana>Sign in &gt;</font></b><br>
    <br>
   <font size=2 color=#000000 face=verdana>
    If you are visiting the site in a frame please open it in it's own page. </p>
  <p>If you cannot access your account, please, <a href=mailto:<?php echo $ADMIN_MAIL; ?>><font size=2 color=#0000FF face=verdana>contact 
    our system administrator</a><p>
  <table width="250" border="0" cellspacing="0" cellpadding="0">
  <form action="login.php?action=submit" method=post>
  <?
    if($es != "" && $action == "submit")
       echo "<tr><td colspan=2><b><font color=C00000>$es</td></tr>";
  ?>
  <tr>
      <td><font size=2 color=#000000 face=verdana>Username:</td>
    <td>
        <input type="text" name="login" value="<?php echo $username; ?>" class=cmn>
      </td>
  </tr>
  <tr>
      <td><font size=2 color=#000000 face=verdana>Password:</td>
    <td>
        <input type="password" name="pswd" value="<?php echo $password; ?>" class=cmn>
      </td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>
        <input type="submit" value="Sign in">
      </td>
  </tr>
  </form>
  <tr>
	<td>
	<?php if ($exchange[safelists]) echo "<a href=safelist_login.php>Safelist Login</a><br>";?>
	<a href=forgot.php><font size=1 color=#FF0000 face=verdana>Forgot password?</a> <br><br>
	</td>
  </tr>
</table>
<?
   } 
   if($sval == "confirm"){
?>
  <font size=2 color=#000000 face=verdana>You cannot access your account now!<br>
  Please check your e-mail. You should receive confirmation letter.<br>
  Only after your account confirmation you will be able to access your account!<br>
<?
   }

   if($sval == "contact"){
?>
  <font size=2 color=#000000 face=verdana>Hello User <? echo $login ?>,<br>
  Your account has been disabled by administrator!<br>
  For any questions about this reason, please <a href=mailto:<? echo $ADMIN_MAIL ?>><font size=2 color=#0000FF face=verdana>contact our system administrator</a>.
<?
   }
?>
</blockquote>
<? include "tpl/bottom.ihtml" ?>