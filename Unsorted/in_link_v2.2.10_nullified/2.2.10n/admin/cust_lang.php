<?php
//Read in config file
$thisfile = "conf_lang";
$admin = 1;

include("../includes/config.php");
include("../includes/admin_languages_lib.php");
include("../includes/hierarchy_lib.php");

if ($action == "updatelang") {
changelanguage($languagen);
inl_header("cust_lang.php");

}
if ($action == "updatedatefmt") {
changedatefmt($dateformat);
}

if ($action == "loadlangfile")
{
	loadlangfile($langfile);
}

if ($action == "editlangfile")
{	
	if (get_magic_quotes_gpc())
		$language_txt = stripslashes($language_txt);
	editlangfile($language_txt, $langfile);
}

languagelist();
languagefileslist();
?>
<html>
<head>
<title><?php echo $la_pagetitle; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $la_char_set; ?>">
<META http-equiv="Pragma" content="no-cache">
<link rel="stylesheet" href="admin.css" type="text/css">
</head>

<body bgcolor="#FFFFFF" text="#000000">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td rowspan="2" width="0"><img src="images/icon3-.gif" width="32" height="32"></td>
    <td class="title" width="100%"><?php echo $la_nav5; ?></td>
    <td rowspan="2" width="0"><a href="help/6.htm#regional"><img src="images/but1.gif" width="30" height="32" border="0"></a><A href="confirm.php?<?php
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
	echo display_admin_nav($la_title_regional, $nav_names_admin, $nav_links_admin);
?>
  <tr> 
    <td class="tabletitle" bgcolor="#666666"><?php echo $la_title_regional; ?></td>
  </tr>
  <tr> 
    <td bgcolor="#F6F6F6"> 

        <table width="100%" border="0" cellspacing="0" cellpadding="4">
          <tr bgcolor="#F6F6F6" valign="middle"> 
            <td class="text" colspan="2"><span class="hint"><img src="images/mark.gif" width="16" height="16" align="absmiddle"><span class="hint"><?php echo $la_language_sets_contained_in; ?></span><br>
              <b><img src="images/mark.gif" width="16" height="16" align="absmiddle"><?php echo $la_change_language_caution; ?></b></span></td>
          </tr>
          <tr bgcolor="#F6F6F6" valign="middle"> 
            <td class="text"><?php echo $la_current_language; ?></td>
            <td class="text"><b><?php echo $language; ?></b></td>
          </tr>
          <form name="language" method="post" action="cust_lang.php<?php
		if($sid && $session_get)
			echo "?sid=$sid";
		?>">
          <tr bgcolor="#DEDEDE">
            <td valign="middle"><span class="text"><?php echo $la_select_a_language_for_use_with_inlink; ?></span></td>
            <td valign="middle"> 
<?php echo $languages; ?>
<input type="hidden" name="action" value="updatelang">
              <input type="submit" name="Submit" value="<?php echo $la_button_load; ?>" class="button">
            </td>
          </tr></form>
          <form name="date" method="post" action="">
          <tr bgcolor="#F6F6F6"> 
            <td valign="middle" class="text"><?php echo $la_regional_date_format; ?></td>
            <td valign="middle"> 
           <input type="text" class="text" name="dateformat" value="<?php echo $datefmt; ?>" size="15">

              <input type="hidden" name="action" value="updatedatefmt">
              <input type="submit" name="Submit" value="<?php echo $la_button_edit; ?>" class="button">
            </td>
          </tr></form>
          <form name="loadlang" method="post" action="">
          <tr bgcolor="#DEDEDE" valign="middle"> 
            <td class="text"><?php echo $la_choose_language_file_to_modify; ?><br>
              
              
              <br>
            </td>
            <td class="text"><?php echo $languagefiles ?>
			<input type="hidden" name="action" value="loadlangfile">
              <input type="submit" name="Submit23" value="<?php echo $la_button_edit; ?>" class="button"></td>

          </tr></form>
				<tr bgcolor="#F6F6F6" valign="middle"> 
            <td class="text">
              <?php echo $la_editting.":<b> ".$langfile; ?></b>
              
			  
              <br>
            </td>
            <td class="text">&nbsp; </td>
          </tr>


          <form name="langfile" method="post">
          <tr bgcolor="#DEDEDE" valign="middle"> 
            <td class="text" colspan="2"> 
              <textarea name="language_txt" cols="80" rows="20" class="text" wrap="off"><?php echo $language_txt; ?></textarea>
            </td>
          </tr>
        </table>
                <input type="hidden" name="langfile" value="<?php echo $langfile; ?>">
                <input type="hidden" name="action" value="<?php if(strlen($language_txt)>0)echo "editlangfile";else echo "";?>">
              <input type="submit" name="submit" value="<?php echo $la_button_update; ?>" class="button">
              <input type="submit" name="Submit3" value="<?php echo $la_button_cancel; ?>" class="button">
        <br>
      </form>
    </td>
  </tr>
</table>
<p>&nbsp;</p>
</body>
</html>
