<?php
//Read in config file
$thisfile = "cust_mod";
$admin = 1;

include("../includes/config.php");
include("../includes/admin_mods_lib.php");
include("../includes/hierarchy_lib.php");

modlist();
?>
<html>
<head>
<title><?php echo $la_pagetitle; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $la_char_set;?>">
<META http-equiv="Pragma" content="no-cache">
<link rel="stylesheet" href="admin.css" type="text/css">
</head>

<body bgcolor="#FFFFFF" text="#000000">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td rowspan="2" width="0"><img src="images/icon3-.gif" width="32" height="32"></td>
    <td class="title" width="100%">
      <?php echo $la_nav5 ?>
    </td>
    <td rowspan="2" width="0"><a href="help/6.htm#mods"><img src="images/but1.gif" width="30" height="32" border="0"></a><A href="confirm.php?<?php
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
	$nav_names_admin=array($la_title_themes,$la_title_regional,$la_title_modules);
	$nav_links_admin[$la_title_themes]="cust_themes.php$att_sid";
	$nav_links_admin[$la_title_regional]="cust_lang.php$att_sid";
 	$nav_links_admin[$la_title_modules]="cust_mod.php$att_sid";
	echo display_admin_nav($la_title_modules, $nav_names_admin, $nav_links_admin);
?>
  <tr> 
    <td class="tabletitle" bgcolor="#666666">
      <?php echo $la_title_modules ?>
    </td>
  </tr>
  <tr> 
    <td bgcolor="#F6F6F6"> 
      <form name="form1" method="post" action="cust_mod.php<?php
		if($sid && $session_get)
			echo "?sid=$sid";
		?>">
        <table width="100%" border="0" cellspacing="0" cellpadding="4">
          <tr bgcolor="#F6F6F6" valign="middle"> 
            <td class="text" colspan="2"><span class="hint"><img src="images/mark.gif" width="16" height="16" align="absmiddle">
              <?php echo $la_what_is_a_module ?>
              </span></td>
          </tr>
          <tr bgcolor="#DEDEDE"> 
            <td valign="middle"><span class="text">
              <?php echo $la_modules_installed ?>
              </span></td>
            <td valign="middle"> 
              <?php echo $mods ?>
              <input type="submit" name="view" value="<?php echo $la_button_view ?>" class="button">
            </td>
          </tr>
          <tr bgcolor="#F6F6F6"> 
            <td valign="middle" class="text"><span class="text">
              <?php echo $la_module_usage ?>
			  <p>
				<?php
					$mod_file="../mods/$modselected";
					$tag_name=substr($modselected,0,strlen($modselected)-4);
					$ret="";
					if(!function_exists("description_".$modselected))
					{	if(is_readable($mod_file))
						{	include($mod_file);
							if(function_exists("description_".$tag_name))
							{	$fname="description_".$tag_name;
								 $ret=$fname();
							}
						}
					}
					else
					{	$fname="description_".$tag_name;
						$ret=$fname();
					}
					echo $ret;
				?>
			  </p>
              </span></td>
            <td valign="middle">&nbsp; </td>
          </tr>
        </table>
        
        <br>
      </form>
    </td>
  </tr>
</table>
<p>&nbsp;</p>
</body>
</html>
