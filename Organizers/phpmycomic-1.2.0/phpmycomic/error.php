<?php session_start();

 // Include needed files
 include("./class.TemplatePower.inc.php");
 include("./config/config.php");

 // Create a new template object
 $tpl = new TemplatePower("themes/$themes/tpl/error.tpl");

 // Prepare the template
 $tpl->prepare();

 include("./lang/$language/error.lang.php");

 // Assign needed values
 $tpl->assignGlobal("theme", $themes);
 $tpl->assignGlobal("pmcurl", $siteurl);
 $tpl->assignGlobal("sitetitle", $sitetitle);
 $tpl->assignGlobal("imgfolder", "themes/$themes/img");
 $tpl->assign("version", $version);

 // Getting the error messages
 if (!strcmp($_GET['error'], "01")) {
   $tpl->assign("errormsg", $lang_error_needlogin);
 }

 if (!strcmp($_GET['error'], "02")) {
   $tpl->assign("errormsg", $lang_error_loginfail);
 }

 if (!strcmp($_GET['error'], "03")) {
   $tpl->assign("errormsg", $lang_error_emtyfield);
 }

 if (!strcmp($_GET['error'], "04")) {
   $tpl->assign("errormsg", $lang_error_nomatch);
 }

 if (!strcmp($_GET['error'], "06")) {
   $tpl->assign("errormsg", $lang_error_adminaccess);
 }

 if (!strcmp($_GET['error'], "09")) {
   $tpl->assign("errormsg", $lang_error_fileheight);
 }

 if (!strcmp($_GET['error'], "10")) {
   $tpl->assign("errormsg", $lang_error_filetype);
 }

 if (!strcmp($_GET['error'], "11")) {
   $tpl->assign("errormsg", $lang_error_openfile);
 }

 if (!strcmp($_GET['error'], "12")) {
   $tpl->assign("errormsg", $lang_error_filewrite);
 }

 if (!strcmp($_GET['error'], "13")) {
   $tpl->assign("errormsg", $lang_error_noadmin);
 }

 if (!strcmp($_GET['error'], "14")) {
   $tpl->assign("errormsg", $lang_error_nologin);
 }

 if (!strcmp($_GET['error'], "15")) {
   $tpl->assign("errormsg", $lang_error_nodefault);
 }

 if (!strcmp($_GET['error'], "16")) {
   $tpl->assign("errormsg", $lang_error_choosetype);
 }

 if (!strcmp($_GET['error'], "17")) {
   $tpl->assign("errormsg", $lang_error_noedit);
 }

 if (!strcmp($_GET['error'], "18")) {
   $tpl->assign("errormsg", $lang_error_exists);
 }

 if (!strcmp($_GET['error'], "19")) {
   $tpl->assign("errormsg", $lang_error_install);
 }

 if (!strcmp($_GET['error'], "20")) {
   $tpl->assign("errormsg", $lang_error_artistexist);
 }

 if (!strcmp($_GET['error'], "21")) {
   $tpl->assign("errormsg", $lang_error_filename);
 }

 if (!strcmp($_GET['error'], "22")) {
   $tpl->assign("errormsg", $lang_error_typeexist);
 }

 if (!strcmp($_GET['error'], "23")) {
   $tpl->assign("errormsg", $lang_error_settype);
 }

 if (!strcmp($_GET['error'], "24")) {
   $tpl->assign("errormsg", $lang_error_artisttype);
 }

 if (!strcmp($_GET['error'], "25")) {
   $tpl->assign("errormsg", $lang_error_imageexist);
 }

 if (!strcmp($_GET['error'], "26")) {
   $tpl->assign("errormsg", $lang_error_imagetobig);
 }

 if (!strcmp($_GET['error'], "27")) {
   $tpl->assign("errormsg", $lang_error_userexist);
 }

 if (!strcmp($_GET['error'], "28")) {
   $tpl->assign("errormsg", $lang_error_toshort);
 }

 if (!strcmp($_GET['error'], "29")) {
   $tpl->assign("errormsg", $lang_error_characters);
 }

 if (!strcmp($_GET['error'], "30")) {
   $tpl->assign("errormsg", $lang_error_update);
 }
 
 if (!strcmp($_GET['error'], "31")) {
 	$tpl->assign("errormsg", $lang_error_addmulti);
 }

 // Print the result
 $tpl->printToScreen();

?>