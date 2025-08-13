<?php
//Read in config file
$thisfile = "error";
$admin = 1;
$configfile = "../includes/config.php";
include($configfile);
?>
<html>
<head>
<title><?php echo $la_pagetitle ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $la_char_set;?>">
<link rel="stylesheet" href="admin.css" type="text/css">
<META http-equiv="Pragma" content="no-cache">
</head>

<body bgcolor="#FFFFFF" text="#000000">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td rowspan="2" width="0"><img src="images/icon5-.gif" width="32" height="32"></td>
    <td class="title" width="100%"><?php echo $la_nav4 ?></td>
    <td rowspan="2" width="0"><a href="help/manual.pdf"><img src="images/but1.gif" width="30" height="32" border="0"></a><A href="confirm.php?<?php
		if($sid && $session_get)
			echo "sid=$sid&";
	?>action=logout" target="_top"><img src="images/but2.gif" width="30" height="32" border="0"></a></td>
  </tr>
  <tr> 
    <td width="100%"><img src="images/line.gif" width="354" height="2"></td>
  </tr>
</table>
<br>    <form name="form1" method="post" action="">

  <table width="100%" border="0" cellspacing="0">
    <tr> 
      <td align="center"> 
        <table width="300" border="0" cellspacing="0" cellpadding="2" class="tableborder">
          <tr> 
            <td class="tabletitle" bgcolor="#666666"><?php echo $la_title_error ?></td>
  </tr>
  <tr> 
      <td bgcolor="#F6F6F6" align="center" valign="middle"> 
        <p>&nbsp;</p>
              <p align="center"><b class="error">This is a sample error message</b></p>
  
              <p> 
                <input type="submit" name="Submit" value="<?php echo $la_button_ok ?>" class="button">
              </p>
              <p>&nbsp; </p>
    </td>
  </tr>
</table>    </td>
  </tr>
</table></form>
<p>&nbsp; </p>
</body>
</html>
