<?php
    
   	/*=====================================================================
	  // $Id: register.php,v 1.3 2005/05/10 17:52:17 carsten Exp $
    // copyright evandor media Gmbh 2003
	  //=====================================================================*/

	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");    // Datum aus Vergangenheit
	header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
	header("Cache-Control: no-store, no-cache, must-revalidate");  // HTTP/1.1
	header("Cache-Control: post-check=0, pre-check=0", false);
	header("Pragma: no-cache");                          // HTTP/1.0

  isset ($_REQUEST['login_wanted']) ? $login_wanted = $_REQUEST['login_wanted'] : $login_wanted = "";
  isset ($_REQUEST['vorname'])      ? $vorname      = $_REQUEST['vorname']      : $vorname      = "";
  isset ($_REQUEST['nachname'])     ? $nachname     = $_REQUEST['nachname']     : $nachname     = "";
  isset ($_REQUEST['email'])        ? $email        = $_REQUEST['email']        : $email        = "";

?>

<html>
<head>
	<title>Willkommen bei leads4web - Your CRM for the web - Version <?=$version_name?></title>
	<meta http-equiv="content-type" content="text/html;charset=iso-8859-1">
	<meta name="copyright"			content="evandor media GmbH">
	<meta name="author" 			content="Carsten Gräf, Martin Wiedemann, Oliver Gräber, Stefan Jaeckel">
	<meta name="publisher"			content="evandor media GmbH">
	<link REL="SHORTCUT ICON"       HREF="http://www.evandor.com/icon.ico">
	<meta name="description"        content="leads4web is a customer relationship management tool for small to medium companies."> 
	<meta name="keywords"           content="CRM, leads4web, leads4web installation, carsten, gräf, l4w, evandor, media, customer, relationship, management, download, open source, evandor media, Munich"> 

	<style type="text/css">
	<!--
		input.login { 
			color:#000066; 
			background-color:'#999999';
			border-width:1px;
			font-size:13px; 
			text-align:left;
		}
		
		input.loginbutton { 
			color:#000066; 
			background-color='#bfbfff';
			border-width:1px;
			border-style:solid;
			border-color='#000066';
			font-size:13px; 
			font-weight:bold;
			text-align:center;
		}
		table.login { 
			background-color:'#999999';
			border-width:1px;
			border-style:solid;
			border-color='#000066';
		}
	-->
	</style>

</head>

<body bgcolor="#ffffff" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">


<table width="100%" border="0" cellspacing="0" cellpadding="12" height="100%">

  <tr>
  	<td width='33%'>&nbsp;</td>
  	<td align=center>
	<a href='http://www.leads4web.de' target='_blank' 
		style='font-family:verdana; font-weight:bold; font-size:13px; color:#000066;
		text-decoration:none;'>
		leads4web/3 - Register demo account
	</a>
	</td>
	<td bgcolor="#ffffff" valign="top" width='33%' align=right>
	  &nbsp;
	</td>
  </tr>
  
  <tr>
    <td>&nbsp;</td>
  	
    <td valign=top align=center>
	
	  <form action="register2.php" name="formular" method='post'>

		<table width="100%" border=0 cellpadding="10" cellspacing="0">
		  <tr>
			<td>
			  <p><font face="Verdana, Arial, Helvetica, sans-serif" size="1">Geben
				  Please provide the following informations to get a
				  leads4web demo account:
        </font></p>
			</td>
		  </tr>
		</table>
		
		<table class='login' border='1' cellpadding="10" cellspacing="0" width="100%">
		  <tr>
			<td align="left">

			  <font size="2" face="Verdana, Arial, Helvetica, sans-serif"><b>Wanted Login:</b></font><br>
			  
			  <input type="text" name="login_wanted" size="15"
			  		 value='<?php echo $login_wanted?>'
			  		 maxlength="50" class='login'>
			  <br><br>
			  <font size="2" face="Verdana, Arial, Helvetica, sans-serif"><b>Name:</b></font><br>
			  <input type="text" name="vorname"
			  	     value='<?=$vorname?>' size="15" maxlength="50"
			  	     class='login'>
        <br>
			  <font size="2" face="Verdana, Arial, Helvetica, sans-serif"><b>Surname:</b></font><br>
			  <input type="text" name="nachname"
			  	     value='<?=$nachname?>' size="15" maxlength="50" class='login'>
        <br>
			  <font size="2" face="Verdana, Arial, Helvetica, sans-serif"><b>email address:</b></font><br>
			  <input type="text" name="email"
			  	     value='<?=$email?>' size="15" class='login'>
			  <br><br>
			  <font size="2" face="Verdana, Arial, Helvetica, sans-serif"><b>Password:</b></font><br>
			  <input type="password" name="pass1"
			  	     value='' size="15" maxlength="50"
			  	     class='login'>
        <br>
			  <font size="2" face="Verdana, Arial, Helvetica, sans-serif"><b>Retype Password:</b></font><br>
			  <input type="password" name="pass2"
			  	     value='' size="15" maxlength="50"
			  	     class='login'>
			  <br> <br>
			  <input align='bottom' type=submit value="Register" name="Register" class='loginbutton'>
			  <br>        <br>
			  <font size=1 face=verdana>&copy; 
			  	<a href='http://www.evandor.com' 
			  		target='_blank' 
					style='font-family:verdana; font-weight:bold; font-size:11px; color:#000066;
					text-decoration:none;'>evandor media</a> 2000 - 2004</font>
			</td>
		  </tr>
		</table>
        <?php
            if (isset ($_REQUEST['msg'])) {
                echo "<br><font face='verdana' size=1 color=red><b>";
                echo $_REQUEST['msg'];
                echo "</b></font>";
            }    
        ?>
	  </form>
	<td>&nbsp;</td>
  </tr>
 
  <tr>
	<td colspan='3' align=center valign='bottom'>
		<img src='img/php-powered.png' border=0>
		<img src='img/mysql-powered.png' border=0>
		<img src='img/css-power.png' border=0>
		<img src='img/mozilla-power.png' border=0>
		<img src='img/apache-power.png' border=0>
	</td>
  </tr>
</table>

	<script language=javascript>
		document.formular.login.focus();
	</script>

</body>
</html>
