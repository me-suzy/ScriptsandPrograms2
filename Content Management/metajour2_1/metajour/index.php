<?php
/**
 * @author Jan H. Andersen <jha@ipwsystems.dk>
 * @author Martin R. Larsen <mrl@ipwsystems.dk>
 * @copyright {@link http://www.ipwsystems.dk/ IPW Systems a.s}
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package METAjour
 */

session_start();
require_once('config.php');
require_once('adodb.php');
require_once('core/util/func.php');
require_once('ow.php');
require_once('basic_user.php');

$userhandler =& getUserHandler();

if (@$_POST['cmd'] == 'logout' || @$_GET['cmd'] == 'logout') $userhandler->LogOut();
$failure = 0;
if (@$_POST['cmd'] == 'login') {
	if (!$userhandler->LogIn(myaddslashes($_POST['site']),
			myaddslashes($_POST['brugernavn']),
			myaddslashes($_POST['password']))) $failure = 1;
}

$userhandler->setWebUser(false);
if ($userhandler->LoggedIn()) {
	if (isset($_REQUEST['guilanguage'])) $userhandler->setGuiLanguage($_REQUEST['guilanguage']);
	checkDatabase();
?>
<html>
<title><?php echo $userhandler->getPrgName() ?></title>
<frameset rows="25,*" name="tabframe" border="0" noborder>
<frame name="topmenu" SRC="gui.php?view=menu&otype=sys" scrolling="no" noresize="yes" border=0 noborder>
<?php
	if (isset($_POST['load']) && !empty($_POST['load'])) {
		?>
<frame name="content" src="<?php echo urldecode($_POST['load']) ?>" noresize="yes" scrolling="yes" border=0 noborder>
		<?php
	} else {
		?>
<frame name="content" src="gui.php?view=welcome&otype=sys" noresize="yes" scrolling="yes" border=0 noborder>
		<?php
	}
		?>
</frameset>
</HTML>
<?php
} else {
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<link rel="stylesheet" type="text/css" href="css/gui.css" />
<meta http-equiv="Content-Language" content="da">
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
<title>IPW METAZO</title>
<style type="text/css">
<!--
body {
	background-image: url(image/splashbackground.jpg);
	background-repeat: no-repeat;
	background-attachment: fixed;
	background-position: bottom right;
	font-family: Message-box;
    font-size: 11px;
}
.overskrift {  font-family: "Trebuchet MS", Verdana, Arial; font-size: 24px; font-style: normal; color: #006666; text-decoration: none; font-weight: 600; letter-spacing: 0.5em;}
.suboverskrift {  
	font-family: "Trebuchet MS", Verdana, Arial; 
	font-size: 14px; 
	color: #006666; 
	font-weight: 600; 
	letter-spacing: 0.1em;
}
.tekst, td {  
	font-family: Tahoma, Verdana, Arial;
    font-size: 12px;
}
.copyright {  
	font-family: Tahoma, Verdana, Arial; 
	font-size: 12px; 
	color: #006666; 
	font-weight: 600
}
-->
</style>
<script type="text/javascript">
function setFocus() {
<?php 

$db =& getDbConn();
$sitecount = $db->getOne("SELECT count(*) FROM site ORDER BY name");

if (!isset($_showonesite) && $sitecount > 1) {
?>
	try {
	   document.loginform.site.focus();
	} catch (e) {}
<?php
} else {
?>
  try {
	  document.loginform.brugernavn.focus();
  } catch (e) {}
<?php	
}
?>
}
</script>
</head>
<body onload="setFocus();">
<br><br><br><br>
  <div align="center">
    <table border="0" cellspacing="0" cellpadding="0" width="403" bgcolor="#edebeb">
      <tr>
        <td bgcolor="black">
          <table width="100%" border="0" cellspacing="1" cellpadding="10">
            <tr>
              <td bgcolor="#dddddd" align="center">
                <span class="overskrift">IPW METAZO</span>
                <br>
                <span class="suboverskrift">Application Framework</span>
                <br><br>Open Source Software<br>
                GNU General Public License<br><br>
                <?php
                if (@$failure) echo '<BR><strong><font color="red">Wrong username or password</font></strong><BR>';
                if (@$_GET['expired'] == '1') echo '<BR><strong><font color="red">Your session has expired. Please re-login!</font></strong><BR>';
                ?>
                <br>
                  <table width="100%" border="0">
                  <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" target="_top" name="loginform">
                  <input type="hidden" name="cmd" value="login">
	<?php
	if (!isset($_showonesite)) {
	?>
                    <tr><td align="right">Site/Company:&nbsp;</td><td><select name="site" id="site" size="1" style="width: 204px;"><option value="">VÆLG VIRKSOMHED / SITE</option>
					<?php
					$res = &$db->execute("SELECT * FROM site ORDER BY name");
					while($row = $res->fetchrow()) {
						?>
						<option value="<?php echo $row['site']; ?>" <?php if (@$_POST['site'] == $row['site'] || $res->RecordCount() == 1) echo " SELECTED"; ?>><?php echo $row['name']; ?></option><?php
					}
					?>
					</select></td></tr>
	<?php
	} else {
	?>
					<input type="hidden" name="site" value="<?php echo $_showonesite ?>">
	<?php
	}
	?>
                <?php
                if (isset($_GET['load']) && !empty($_GET['load'])) {
                ?>
                  <input type="hidden" name="load" value="<?php echo $_GET['load']; ?>">
                <?php
                }
                ?>
                    <tr><td align="right">Username:&nbsp;</td><td><input type="text" name="brugernavn" style="width: 200px;" value="<?php echo @$_POST['brugernavn'] ?>"></td></tr>
                    <tr><td align="right">Password:&nbsp;</td><td><input type="password" name="password" style="width: 200px;"></td></tr>
                    <tr><td>&nbsp;</td><td>&nbsp;</td></tr>
                    <tr><td align="center" colspan="2"><input type="submit" value="Login" style="width: 100px;"></td></tr>
                  </form>
                </table>
                <br><br>
                 <hr>
                 <span class="copyright">IPW METAZO version <?php echo $userhandler->getVersion() ?></span><br>
                 <span class="copyright">Copyright 2002-2005 <?php echo $userhandler->getVendor() ?></span>
              </td>
            </tr>
          </table>
        </td>
      </tr>
    </table>
  </div>
</body>
</html>
<?php
}
?>
