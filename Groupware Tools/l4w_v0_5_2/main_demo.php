<?php

	/*=====================================================================
    // $Id: main_demo.php,v 1.7 2005/08/01 14:55:12 carsten Exp $
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
    <td colspan=3 align=center>
        <script type="text/javascript"><!--
            google_ad_client = "pub-1335741973265162";
            google_ad_width = 728;
            google_ad_height = 90;
            google_ad_format = "728x90_as";
            google_ad_channel ="4512061099";
            google_color_border = "CCCCCC";
            google_color_bg = "FFFFFF";
            google_color_link = "000000";
            google_color_url = "666666";
            google_color_text = "333333";
            //--></script>
            <script type="text/javascript"
              src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
            </script>
    </td>

  </tr>
  <tr>
    <td colspan=3 align=center>
            <font face='Verdana' size=2>
				<b>Leads4web - Your CRM Solution for the web</b>
			</font>
    </td>
  </tr>
  <tr>
    <td>&nbsp;</td>

        <td valign=top align=center>

          <form action="check_login.php" name="formular" method='post'>

                <table width="300" border="0" cellpadding="1" cellspacing="0">
                  <tr>
                        <td>
                          <font face="Verdana, Arial, Helvetica, sans-serif" size="1">
                                Please provide your login and your password</font>
                        </td>
                  </tr>
                </table>

                <table class='login' cellpadding="4" cellspacing="0">
                  <tr>
                        <td align="left">

                          <font size="2" face="Verdana, Arial, Helvetica, sans-serif"><b>Login:</b></font><br>

                          <input type="text" name="login" size="15"
                                           value='<?php echo $login_given?>'
                                           maxlength="50" class='login'>
                          &nbsp;<font size=1 face="Verdana, Arial, Helvetica, sans-serif">case sensitive</font>
                          <br><br>
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
		                          <select name="mandator" style="width:120px;">
		                          <?php
		                          foreach ($mandators AS $key => $mandator)
		                          	echo "<option value='".$mandator['key']."'>".$mandator['name']."</option>"
		                          ?>
		                          </select>
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
                <font color=red face='verdana' size=2><?=$error_msg?></font>
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