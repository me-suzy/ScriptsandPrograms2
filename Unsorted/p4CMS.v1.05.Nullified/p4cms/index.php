<?
 include("include/include.inc.php");
?>
<html>
<head>
<title>LOGIN</title>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
<link rel="stylesheet" href="/p4cms/style/style.css">
<? StyleSheet(); ?>
<style type="text/css">
<!--
.Stil1 {font-size: Kein}
.Stil2 {
	color: #FF9900;
	font-weight: bold;
}
.Stil3 {color: #ABABAB}
-->
</style>
</head>
<body scroll="no" bgcolor="#FFFFFF" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table width="350" height="100%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr> 
    <td>
	<!-- NOSCRIPT -->
	<noscript>
		<font color="#FF0000" size="+1"><strong><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Achtung 
		!</font></strong></font><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><br>
      	<font color="#FF0000">Sie haben in Ihrem Browser Javascript deaktiviert.<br>
      	Um diese Software zu nutzen, m&uuml;ssen Sie dies jedoch aktiviert haben. 
      	</font></font>
		<br>
		<br>
	</noscript>
	<!-- NOSCRIPT -->
	
      <table width="100%" border="0" align="center" cellpadding="8" cellspacing="1" class="boxstandartborder">
        <tr> 
          <td bgcolor="#F6F6F6" class="boxstandart"> <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td><img src="gfx/login/logo_klein.gif" alt=""></td>
              </tr>
          </table>
            <p><span class="Stil1">Version <? echo($version); ?><br>
              <br>
&copy; 2002-2004 <!--CyKuH [WTN]-->dream4. Alle Rechte vorbehalten.<br>
  <br>
  <?
                   if ($_REQUEST[action]=="" or !isset($_REQUEST[action])) {
                  ?>
  Bitte loggen Sie sich mit Ihrem Benutzernamen und Ihrem Passwort ein.<br>
              <br>
              <?
                    ShowLogin();
                   }
                   if ($_REQUEST[action]=="login") {
                   	$korrekt = 0;
                   	$sql =& new MySQLq();
                   	$sql->Query("SELECT * FROM " . $sql_prefix . "redakteure WHERE username='$_REQUEST[benutzername]' AND passwort='$_REQUEST[passwort]'");
                   	while ($row = $sql->FetchRow()) {
                   		$korrekt = 1;
                   		$u_gid = $row->gruppe;
                   		$u_name = $row->name;
                   		$u_email = $row->email;
                   		$u_id = $row->id;
                   	}
                   	$sql->Close();
                   	
                   	if ($korrekt == 1) {
                   		eLog("user", "$benutzername loggt sich ein");
                   		$HTTP_SESSION_VARS['u_loggedin'] = 'yes';
                   		$HTTP_SESSION_VARS['u_user'] = $_REQUEST['benutzername'];
                   		$HTTP_SESSION_VARS['u_gid'] = $u_gid;
                   		$HTTP_SESSION_VARS['u_name'] = $u_name;
                   		$HTTP_SESSION_VARS['u_email'] = $u_email;
                   		$HTTP_SESSION_VARS['u_id'] = $u_id;
                   		?>
  Herzlich Willkommen, <? echo($u_name); ?>! <br>
  <br>
  p4cms wird geladen, bitte warten...
  <script language="JavaScript">
                   		<!--
                   		 PopUpCMS('<? echo($sessid); ?>');
                   		//-->
                   		</script>
  <?
                   	} else {
                   		eLog("user", "$benutzername versucht sich einzuloggen: FALSCHES PASSWORT");
                   		?>
  <span class="Stil2">Die Logindaten sind inkorrekt. Bitte versuchen Sie es erneut. </span></span><br>
                      <br>
                      <?
                   		ShowLogin();
                   	}    
                   }               		
                  ?>
                      <font size="1" face="Verdana, Arial, Helvetica, sans-serif"><br>
            </font></td>
        </tr>
      </table>
    </td>
  </tr>
</table>
</body>
</html>
