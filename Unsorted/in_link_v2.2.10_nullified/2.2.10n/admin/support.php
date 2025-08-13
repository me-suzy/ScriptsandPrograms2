<?php
//Read in config file
$thisfile = "support";
$admin = 1;

include("../includes/config.php");
include("../includes/hierarchy_lib.php");
?>
<html>
<head>
<title><?php echo $la_pagetitle; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $la_char_set; ?>">
<link rel="stylesheet" href="admin.css" type="text/css">
</head>

<body bgcolor="#FFFFFF" text="#000000">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> <!--CyKuH [WTN]-->
    <td rowspan="2" width="0"><img src="images/icon6-.gif" width="32" height="32"></td>
    <td class="title" width="100%"><?php echo $la_nav8 ?></td>
    <td rowspan="2" width="0"><a href="help/6.htm#support"><img src="images/but1.gif" width="30" height="32" border="0"></a><A href="confirm.php?<?php
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
	$nav_names_admin=array($la_title_license, $la_title_support, $la_title_credits, $cykuh_title_nullification);
	$nav_links_admin[$la_title_license]="license.php$att_sid";
	$nav_links_admin[$la_title_support]="support.php$att_sid";
	$nav_links_admin[$la_title_credits]="credits.php$att_sid";
	$nav_links_admin[$cykuh_title_nullification]="nullification.php$att_sid";
	echo display_admin_nav($la_title_support, $nav_names_admin, $nav_links_admin);
?>
  <tr> <!--CyKuH [WTN]-->
    <td class="tabletitle" bgcolor="#666666"> 
      <?php echo $la_title_support ?>
    </td>
  </tr>
  <tr> 
    <td bgcolor="#F6F6F6"> 
      <form name="form1" method="post" action="support.php<?php
		if($sid && $session_get)
			echo "?sid=$sid";
	  ?>">
        <table width="100%" border="0" cellspacing="0" cellpadding="4">
          <tr bgcolor="#999999" valign="middle"> 
            <td colspan="2" class="textTitle"> 
              <?php echo $la_inlink_site ?>
            </td>
          </tr>
          <tr bgcolor="#F6F6F6" valign="middle"> 
            <td class="text"> 
              <?php 
			  
			  if(!$filehand=@fopen($filedir."languages/".$language."/site.txt", "r"))
			  {
				  echo "<span class=error> $la_error_404 </span>";
			  }
			  else
			  {	
				  fpassthru($filehand);
			  }
			  ?>
              <br>
            </td>
            <td class="text" valign="top" bgcolor="#DEDEDE">in-link.net 
              <img src="images/arrow1.gif" width="8" height="9"> </td>
          </tr>
          <tr bgcolor="#999999" valign="middle"> 
            <td colspan="2" class="textTitle"> 
              <?php echo $la_support_forum ?>
            </td>
          </tr>
          <tr bgcolor="#F6F6F6" valign="middle"> 
            <td class="text"> 
              <?php 
			  
			  if(!$filehand=@fopen($filedir."languages/".$language."/support.txt", "r"))
			  {
				  echo "<span class=error> $la_error_404 </span>";
			  }
			  else
			  {	
				  fpassthru($filehand);
			  }
			  ?>
            </td>
            <td class="text" valign="top" bgcolor="#DEDEDE">support.intechnic.com/forum
              <img src="images/arrow1.gif" width="8" height="9"> </td>
          </tr>
          <tr bgcolor="#999999" valign="middle"> 
            <td colspan="2" class="textTitle"> 
              <?php echo $la_support_system ?>
            </td>
          </tr>
          <tr bgcolor="#F6F6F6" valign="middle"> 
            <td class="text"> 
              <?php 
			  
			  if(!$filehand=@fopen($filedir."languages/".$language."/support_system.txt", "r"))
			  {
				  echo "<span class=error> $la_error_404 </span>";
			  }
			  else
			  {	
				  fpassthru($filehand);
			  }
			  ?>
            </td><!--CyKuH [WTN]-->
            <td class="text" valign="top" bgcolor="#DEDEDE">in-link.net/support/
              <img src="images/arrow1.gif" width="8" height="9"> </td>
          </tr>
          <tr bgcolor="#999999" valign="middle"> 
            <td colspan="2" class="textTitle"> 
              <?php echo $la_feedback ?>
            </td>
          </tr>
          <tr bgcolor="#F6F6F6" valign="middle"> 
            <td class="text" valign="top"> 
              <?php 
			  
			  if(!$filehand=@fopen($filedir."languages/".$language."/feedback.txt", "r"))
			  {
				  echo "<span class=error> $la_error_404 </span>";
			  }
			  else
			  {	
				  fpassthru($filehand);
			  }
			  ?>
            </td>
            <td class="text" valign="top" nowrap bgcolor="#DEDEDE">in-link.net/feedback.php
              <img src="images/arrow1.gif" width="8" height="9"> </td>
          </tr>
          <tr bgcolor="#999999" valign="middle"> 
            <td colspan="2" class="textTitle"> 
              <?php echo $la_error_reporting ?>
            </td>
          </tr>
          <tr bgcolor="#F6F6F6" valign="middle"> 
            <td class="text" valign="top"> 
              <?php 
			  
			  if(!$filehand=@fopen($filedir."languages/".$language."/errors.txt", "r"))
			  {
				  echo "<span class=error> $la_error_404 </span>";
			  }
			  else
			  {	
				  fpassthru($filehand);
			  }
			  ?>
            </td>
            <td class="text" valign="top" nowrap>&nbsp;</td>
          </tr>
        </table>
       
      </form>
    </td>
  </tr>
</table>
<p>&nbsp; </p>
</body>
</html>
