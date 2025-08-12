<?php
    list ($name, $versionFromIniFile) = getInstalledApplication("../");
?><!DOCTYPE HTML SYSTEM "http://www.evandor.de/HTML4evandor.dtd">
<html>
<head>
        <title>Leads4web CRM - Version <?=$version?></title>
        <link href="favicon.gif"        rel="SHORTCUT ICON">
        <meta http-equiv="content-type" content="text/html;charset=iso-8859-1">
        <meta name="copyright"          content="evandor media GmbH">
        <meta name="author"             content="Carsten Gräf">
        <meta name="publisher"          content="evandor media GmbH">
        <meta name="description"        content="leads4web is a customer relationship management tool for small to medium companies.">
        <meta name="keywords"           content="CRM, leads4web, leads4web installation, carsten, gräf, l4w, media, customer, relationship, management, download, open source, evandor media, Munich">

        <style type="text/css">
        <!--
                input.login {
                        color:#000066;
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
                        background-color='#bfbfbf';
                        border-width:1px;
                        border-style:solid;
                        border-color='#000066';
                }
                td {
                	font-family:Verdana;
                	font-size:12px;
                }
        -->
        </style>

</head>

<body bgcolor="#ffffff" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">

<form action='' action=post>
<table width="100%" border="0" cellspacing="0" cellpadding="4" height="100%">
<colspan>
    <col>
    <col width="300">
    <col>
</colspan>
<tr>
    <td>&nbsp;</td>
    <td align=center>
        <img src='../img/<?=APPLICATION?>.png' border=0>
        <br>
        <b>Upgrade from <?=$version?> to <?=$versionFromIniFile?></b>
        <br><br>
        <br>
        <br/>
        <font face="Verdana, Arial, Helvetica, sans-serif" size="1">
                                Some details from your configuration file</font>
        
    </td>
    <td bgcolor="#ffffff" valign="top" align=right>
      &nbsp;
    </td>
</tr>
<tr>
	<td>&nbsp;</td>
	<td valign=top align=center width="300">

        <table class='login' cellpadding="2" cellspacing="0" width="100%">
          <tr>
			<td align="left"><b>DB Host:</b></td>
            <td align="left"><?=$db_host?></td>
          </tr>
          <tr>
			<td align="left"><b>DB User:</b></td>
            <td align="left"><?=$db_user?></td>
          </tr>
          <tr>
			<td align="left"><b>DB Name:</b></td>
            <td align="left"><?=$db_name?></td>
          </tr>
          <tr>
			<td align="left"><b>Table Prefix:</b></td>
            <td align="left"><?=TABLE_PREFIX?></td>
          </tr>
          <tr>
			<td align="left"><b>Version:</b></td>
            <td align="left"><?=$version_name?></td>
          </tr>
        </table>
       
	<td>&nbsp;</td>
  </tr>
  <?php if (!$info_only) { ?>
  <tr>
  	<td colspan=3 align=center>
  		<input type=submit name=submit value='Upgrade'>
  	</td>
  </tr>
  <?php } ?>
  <tr>
        <td colspan='3' align=center valign='bottom'>
                <img src='../img/php-powered.png'   alt="php-powered"   border="0">
                <img src='../img/mysql-powered.png' alt="mysql-power"   border="0">
                <img src='../img/css-power.png'     alt="css-power"     border="0">
                <img src='../img/mozilla-power.png' alt="mozilla-power" border="0">
                <img src='../img/apache-power.png'  alt="apache-power"  border="0">
        </td>
  </tr>
</table>
</form>
</body>

</html>