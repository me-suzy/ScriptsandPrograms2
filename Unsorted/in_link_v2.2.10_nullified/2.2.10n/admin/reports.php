<?php
//Read in config file
$thisfile = "reports";
$admin = 1;
$configfile = "../includes/config.php";
include($configfile);
include("../includes/hierarchy_lib.php");
?>
<html>
<head>
<title><?php echo $la_pagetitle; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $la_char_set; ?>">
<link rel="stylesheet" href="admin.css" type="text/css">
<META http-equiv="Pragma" content="no-cache">
</head>

<body bgcolor="#FFFFFF" text="#000000">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td rowspan="2" width="0"><img src="images/icon5-.gif" width="32" height="32"></td>
    <td class="title" width="100%"><?php echo $la_nav4 ?></td>
    <td rowspan="2" width="0"><a href="help/6.htm#reports"><img src="images/but1.gif" width="30" height="32" border="0"></a><A href="confirm.php?<?php
		if($sid && $session_get)
			echo "sid=$sid&";
	?>action=logout" target="_top"><img src="images/but2.gif" width="30" height="32" border="0"></a></td>
  </tr>
  <tr> 
    <td width="100%"><img src="images/line.gif" width="354" height="2"></td>
  </tr>
</table>
<br>
<table width="100%" border="0" cellspacing="0" cellpadding="2" class="tableborder">
  <?php
  if($sid && $session_get)
		$att_sid="?sid=$sid";
	$nav_names_admin=array($la_title_statistics, $la_title_search_log, $la_title_reports);
	$nav_links_admin[$la_title_statistics]="log.php$att_sid";
	$nav_links_admin[$la_title_search_log]="search_log.php$att_sid";
	$nav_links_admin[$la_title_reports]="reports.php$att_sid";
	echo display_admin_nav($la_title_reports, $nav_names_admin, $nav_links_admin);
?>
  <tr> 
    <td class="tabletitle" bgcolor="#666666">
      <?php echo $la_title_reports ?>
      </td>
  </tr>
  <tr> 
    <td bgcolor="#F6F6F6"> 
      
        <table width="100%" border="0" cellspacing="0" cellpadding="4">
          <form name="form2" method="post" action="reports_sql.php<?php
			if($sid && $session_get)
				echo "?sid=$sid";
		  ?>"><tr bgcolor="#999999" valign="middle"> 
            <td colspan="3" class="textTitle">
              <?php echo $la_custom_sql_query ?>
            </td>
          </tr>
          <tr bgcolor="#F6F6F6" valign="middle"> 
            <td class="text" colspan="3">
              <textarea name="customquery" cols="50" rows="3"></textarea>
            </td>
          </tr>
          <tr bgcolor="#F6F6F6" valign="middle"> 
            <td class="text" colspan="2"> 
              <input type="submit" name="Submit342" value="<?php echo $la_button_send ?>" class="button">
              <input type="button" onClick="history.back();" value="<?php echo $la_button_cancel ?>" class="button">
            </td>
            <td class="text">&nbsp;</td>
          </tr></form>
        </table>
        <br>
		<span class="error"><?php echo "<ul><li>$la_sql_warning</li></ul>&nbsp;";?></span>
      
    </td>
  </tr>
</table>
<p>&nbsp; </p>
</body>
</html>
