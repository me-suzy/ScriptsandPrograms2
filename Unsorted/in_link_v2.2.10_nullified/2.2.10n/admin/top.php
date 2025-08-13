<?php
//Read in config file
$thisfile = "top";
$admin = 1;
$configfile = "../includes/config.php";
include($configfile);
?>
<html>
<head>
<title><?php echo $la_pagetitle; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $la_char_set; ?>">
<link rel="stylesheet" href="admin.css" type="text/css">
</head>

<body bgcolor="#FFFFFF" text="#000000" leftmargin="0" topmargin="0">
<table width="100%" border="0" cellspacing="0" cellpadding="0" height="100%">
  <tr>
    <td align="left"><img src="images/logo.gif" width="176" height="64" border="0"></td><!--CyKuH [WTN]-->
    <td align="right"> 
      <div align="right">
        <p><img src="images/top.gif" width="321" height="64" border="0"></p>
      </div>
    </td>
  </tr>
  <tr class="bottomborder">
    <td bgcolor="#FCDC43" valign="middle" class="bottomborder"><img src="images/moto.gif" width="269" height="21" border="0" align="middle"></td>
    <td bgcolor="#FCDC43" valign="middle" class="bottomborder"> 
      <div align="right" class="small"> 
        <p><?php echo $la_inlink_version ?> <b><?php echo $version ?></b>:<?php echo $la_this_language ?></p>
      </div><!--CyKuH [WTN]-->
    </td>
  </tr>
</table>
</body>
</html>
