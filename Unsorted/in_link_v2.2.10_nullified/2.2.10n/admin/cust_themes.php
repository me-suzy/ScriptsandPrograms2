<?php
if($preview && $templ_txt && $templlist)
{	//display template
	$prev_admin=1;
	$admin=0;
	$t_cache["$templlist"]=stripslashes($templ_txt);
	include("../includes/config.php");
	include("../includes/templ_lib.php");
	echo parse($templlist);
	exit;
}

//Read in config file
$thisfile = "cust_themes";
$admin = 1;

include("../includes/config.php");
include("../includes/admin_themes_lib.php");
include("../includes/hierarchy_lib.php");

if ($action == "changetheme") {
changetheme($themeselect);
}
if ($action == "loadtempl")
{
	loadtempl($templlist);
//	if (get_magic_quotes_gpc())
//		$templ_txt = addslashes($templ_txt);
}
if ($action == "edittempl") 
{
	if(eregi("&lt;/textarea&gt;",$templ_txt)>0)
		$templ_txt=eregi_replace("&lt;/textarea&gt;","</textarea>",$templ_txt);
	if (get_magic_quotes_gpc())
		edittemplfile(stripslashes($templ_txt), $templlist);
	else
		edittemplfile($templ_txt, $templlist);
	if (get_magic_quotes_gpc())
		$templ_txt = stripslashes($templ_txt);
}

templateslist();
themelist();
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
    <td class="title" width="100%"><?php echo $la_nav5 ?></td>
    <td rowspan="2" width="0"><a href="help/6.htm#themes"><img src="images/but1.gif" width="30" height="32" border="0"></a><A href="confirm.php?<?php
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
	echo display_admin_nav($la_title_themes, $nav_names_admin, $nav_links_admin);
?>
  <tr> 
    <td class="tabletitle" bgcolor="#666666"> 
      <?php echo $la_title_themes ?>
    </td>
  </tr>
  <tr> 
    <td bgcolor="#F6F6F6"> 
      
        <table width="100%" border="0" cellspacing="0" cellpadding="4">
          <tr bgcolor="#F6F6F6" valign="middle"> 
            <td class="text" colspan="2"><span class="hint"><img src="images/mark.gif" width="16" height="16" align="absmiddle">
              <?php echo $la_what_is_a_theme ?>
              </span></td>
          </tr>
          <tr bgcolor="#F6F6F6" valign="middle"> 
            <td class="text">
              <?php echo $la_current_theme ?>
              </td>
            <td class="text"><b><?php echo $theme ?></b></td>
          </tr>
	<form name="form1" method="post" action="cust_themes.php<?php
		if($sid && $session_get)
			echo "?sid=$sid";
		?>">
          <tr bgcolor="#DEDEDE" valign="middle"> 
            <td class="text">
              <?php echo $la_theme_for_output ?>
              </td>
            <td class="adminitem"> 
              <?php echo $themes ?>
			  <input type="hidden" name="action" value="changetheme" class="button">
              <input type="submit" name="Submit22" value="<?php echo $la_button_load ?>" class="button">
			  
            </td>
          </tr>
		</form>
		<form name="form2" method="post" action="cust_themes.php<?php
		if($sid && $session_get)
			echo "?sid=$sid";
		?>">
          <tr bgcolor="#F6F6F6" valign="middle"> 
            <td class="text">
              <?php echo $la_theme_to_modify ?>
            
              
			  
              <br>
            </td>
            <td class="text"><?php echo $templates ?>
			<input type="hidden" name="action" value="loadtempl" class="button">
              <input type="submit" name="Submit23" value="<?php echo $la_button_edit ?>" class="button"></td>
          </tr>
		  </form>
		           
					<tr bgcolor="#DEDEDE" valign="middle"> 
            <td class="text">
              <?php echo $la_editting.":<b> ".$templlist; ?></b>
              
			  
              <br>
            </td>
            <td class="text">&nbsp; </td>
          </tr>

		  <form name="form3" method="post" action="cust_themes.php<?php
		if($sid && $session_get)
			echo "?sid=$sid";
		?>">
          <tr bgcolor="#F6F6F6" valign="middle"> 
            <td class="text" colspan="2"> 
              <textarea name="templ_txt" cols="80" rows="20" class="text" wrap="off">
			  <?php
				if(eregi("</TEXTAREA>",$templ_txt)>0)
					$templ_txt=eregi_replace("</TEXTAREA>","&lt;/textarea&gt;",$templ_txt);
				echo $templ_txt; ?>
			  </textarea>
            </td>
          </tr>
        </table>
		<input type="hidden" name="action" value="edittempl" class="button">
		<input type="hidden" name="templlist" value="<?php echo $templlist ?>" class="button">
        <input type="<?php if(strlen($templ_txt)>0)echo "submit";else echo "button";?>" name="Submit2" value="<?php echo $la_button_update ?>" class="button">
        <input type="<?php if(strlen($templ_txt)>0)echo "submit";else echo "button";?>" name="preview" value="<?php echo $la_button_preview ?>" class="button">
        <input type="submit" name="Submit322" value="<?php echo $la_button_cancel ?>" class="button">
        <br>
      
    </td>
  </tr></form>
</table>
<p>&nbsp;</p>
</body>
</html>
