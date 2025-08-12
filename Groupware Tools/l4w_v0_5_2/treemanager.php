<?php

	/*=====================================================================
    // $Id: treemanager.php,v 1.1 2005/07/08 19:45:59 carsten Exp $
    // copyright evandor media Gmbh 2004
    //=====================================================================*/

    include ("inc/startpage_header.inc.php");

?>

<body bgcolor="#ffffff" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">


<table width="100%" border="0" cellspacing="0" cellpadding="12" height="100%">
<colgroup>
    <col>
    <col width="300">
    <col>
</colgroup>
<tr>
    <td>&nbsp;</td>
    <td align=center>
        &nbsp;
    </td>
    <td bgcolor="#ffffff" valign="top" align=right>
      &nbsp;
    </td>
</tr>
<tr>
    <td>&nbsp;</td>
    <td align=center>
        <!--<img src='img/<?=APPLICATION?>.png' border=0>--><br>
    </td>
    <td bgcolor="#ffffff" valign="top" align=right>
      &nbsp;
    </td>
</tr>

  <tr>
    <td>&nbsp;</td>

        <td valign="top" align="center">

          <form action="check_login.php" name="formular" method='post'>

                <table width="300" border="0" cellpadding="10" cellspacing="0">
                  <tr>
                        <td>
                          <font face="Verdana, Arial, Helvetica, sans-serif" size="1">
                                Please provide your login and your password</font>
                        </td>
                  </tr>
                </table>

                <table class='login' cellpadding="6" cellspacing="0">
                  <tr>
                        <td align="left">

                          <font size="2" face="Verdana, Arial, Helvetica, sans-serif"><b>Login:</b></font><br>

                          <input type="text" name="login" size="15"
                                           value='<?php echo $login_given?>'
                                           maxlength="50" class='login'>
                          &nbsp;<font size=1 face="Verdana, Arial, Helvetica, sans-serif">case sensitive</font>
                          <br>
                          <font size="2" face="Verdana, Arial, Helvetica, sans-serif"><b>Kennwort:</b></font><br>
                          <input type="password" name="passwort"
                                       value='' size="15" maxlength="50"
                                       class='login'>
                          &nbsp;<font size=1 face="Verdana, Arial, Helvetica, sans-serif">case sensitive</font>
					<?php
						list ($count,$mandators) = getMandators ();
						if ($count == 1) {
							echo "<input type='hidden' name='mandator' value='".$mandators[0]['key']."'>";
						}
						else {
					?>				          
				          <br>
                          <font size="2" face="Verdana, Arial, Helvetica, sans-serif"><b>Mandator:</b></font><br>
                          
				    <?php } ?>      
				          <br><br>
                          <input align='bottom' type=submit value="Login" name="submit" class='loginbutton'>
                          <br><br>
                          <font size=1 face=verdana>&copy;
                                  <a href='http://www.evandor.com'
                                          target='_blank'
                                        style='font-family:verdana; font-weight:bold; font-size:11px; color:#000066;
                                        text-decoration:none;'>evandor media</a> 2000 - <?=date("Y")?>  (v. <?=$version?>)</font>
                        </td>
                  </tr>
                </table>
                <?php if (SHOW_REGISTER_BOX) { ?> 
                <br>
                <table class='login' cellpadding="10" cellspacing="0" width="100%">
                  <tr>
                        <td align="left">
                            <font size=1 face=verdana>
                                New to leads4web? <a href='register.php'>Register</a> for a demo login
                            </font>
                        </td>
                  </tr>
                </table>
                <?php } ?>
                <?php if (ALLOW_GUEST_USER) { ?> 
                <br>
                <table class='login' cellpadding="10" cellspacing="0" width="100%">
                  <tr>
                        <td align="left">
                            <font size=1 face=verdana>
                                Want to login as guest? Click <a href='guest.php'>here</a>
                            </font>
                        </td>
                  </tr>
                </table>
                <?php } ?>
                </form>
                <br>
                <font color=red face='verdana' size=2>&nbsp;<?=$error_msg?>&nbsp;</font>
        <td>&nbsp;</td>
  </tr>

  <tr>
        <td colspan='3' align=center valign='bottom'>
                <img src='img/php-powered.png'   alt="php-powered"   border="0">
                <img src='img/mysql-powered.png' alt="mysql-power"   border="0">
                <img src='img/css-power.png'     alt="css-power"     border="0">
                <img src='img/mozilla-power.png' alt="mozilla-power" border="0">
                <img src='img/apache-power.png'  alt="apache-power"  border="0">
        </td>
  </tr>
</table>

        <script language=javascript TYPE="text/javascript">
                document.formular.login.focus();
        </script>

</body>
</html>