<?php
//Read in config file
$thisfile = "license";
$admin = 1;

include("../includes/config.php");
include("../includes/hierarchy_lib.php");
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
    <td rowspan="2" width="0"><img src="images/icon6-.gif" width="32" height="32"></td>
    <td class="title" width="100%"><?php echo $la_nav8 ?></td>
    <td rowspan="2" width="0"><a href="help/6.htm#license"><img src="images/but1.gif" width="30" height="32" border="0"></a><A href="confirm.php?<?php
		if($sid && $session_get)
			echo "sid=$sid&";
	?>action=logout" target="_top"><img src="images/but2.gif" width="30" height="32" border="0"></a></td>
  </tr>
  <tr> 
    <td width="100%"><img src="images/line.gif" width="354" height="2"></td>
  </tr>
</table>
<br><!--CyKuH [WTN]-->
<table width="100%" border="0" cellspacing="0" cellpadding="2" class="tableborder">
<?php
	if($sid && $session_get)
		$att_sid="?sid=$sid";
	$nav_names_admin=array($la_title_license, $la_title_support, $la_title_credits, $cykuh_title_nullification);
	$nav_links_admin[$la_title_license]="license.php$att_sid";
	$nav_links_admin[$la_title_support]="support.php$att_sid";
	$nav_links_admin[$la_title_credits]="credits.php$att_sid";
	$nav_links_admin[$cykuh_title_nullification]="nullification.php$att_sid";
	echo display_admin_nav($la_title_license, $nav_names_admin, $nav_links_admin);
?>
  <tr> 
    <td class="tabletitle" bgcolor="#666666"><?php echo $la_title_license ?></td>
  </tr>
  <tr> 
    <td bgcolor="#F6F6F6"> 
      <form name="form1" method="post" action="">
        <table width="100%" border="0" cellspacing="0" cellpadding="4">
          <tr bgcolor="#999999" valign="middle"> 
            <td colspan="2" class="textTitle"><?php echo $la_title_license ?></td>
          </tr>
          <tr bgcolor="#F6F6F6" valign="middle">
            <td class="text">
              <?php echo $la_this_copy_of_inlink_is_registered_to ?>
            </td>
            <td class="text"><a href="http://<?php echo $server." ".$filepath ?>" target="_blank" class="adminitem"><?php echo $first_name." ".$last_name ?></a></td>
          </tr>
          <tr bgcolor="#DEDEDE" valign="middle"> 
            <td class="text">
              <?php echo $la_inlink_is_licensed_for_use_on_this_server ?>
            </td>
            <td class="text"><b><?php echo $server ?></b></td>
          </tr>
		        <tr bgcolor="#F6F6F6" valign="middle">
            <td class="text">
              <?php echo $cykuh_credits ?>
            </td>
            <td class="text"> <b>CyKuH [WTN]</b></td>
          <tr bgcolor="#999999" valign="middle"> 
            <td colspan="2" class="textTitle">
              <?php echo $la_software_license_and_user_agreement ?>
            </td>
          </tr>
          <tr bgcolor="#F6F6F6" valign="middle"> 
            <td class="text" colspan="2"> 
              <?php 
			  
			  if(!$filehand=@fopen($filedir."languages/".$language."/license.txt", "r"))
			  {
				  echo "<span class=error> $la_error_404 </span>";
			  }
			  else
			  {	
				  fpassthru($filehand);
			  }
			  ?>
            </td>
          </tr>
        </table>
        <br>
      </form>
    </td>
  </tr>
</table>
<p>&nbsp; </p>
</body>
</html>
