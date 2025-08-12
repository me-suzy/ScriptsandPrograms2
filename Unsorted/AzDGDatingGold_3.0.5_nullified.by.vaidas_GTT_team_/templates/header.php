<?php
##################################################################
# \-\-\-\-\-\-\-\     AzDG  - S C R I P T S    /-/-/-/-/-/-/-/-/ #
##################################################################
# AzDGDatingGold                Version 3.0.5                     #
# Status                        Paid                             #
# Writed by                     AzDG (support@azdg.com)          #
# Created 21/09/02              Last Modified 21/09/02           #
# Scripts Home:                 http://www.azdg.com              #
##################################################################
$mtime1 = explode(" ", microtime());
$starttime = $mtime1[1] + $mtime1[0];
include_once $int_path."/classes/AzDG.template2.inc.php";
//include_once $int_path."/classes/AzDG.template3.inc.php";

$t = new Template;
$t->set_file($int_path."/templates/".$template_name."/header.html");
$t->set_var("W_CHARSET", W_CHARSET);
$t->set_var("C_URL", $url);
$t->set_var("LANGUAGE", $l);
$t->set_var("C_FLAG_PATH", $flag_path);
$t->set_var("W_ADD_USER", W_ADD_USER);
$t->set_var("W_MEMBERS_AREA", W_MEMBERS_AREA);
$t->set_var("W_SEARCH", W_SEARCH);
$t->set_var("W_FEEDBACK", W_FEEDBACK);
$t->set_var("W_STATISTIC", W_STATISTIC);
$t->set_var("W_MAIN_PAGE", W_MAIN_PAGE);
$t->set_var("W_FAQ", W_FAQ);

// Check language selected or no, show language or no
if (!(($l != "") && ($show_lf == "0")))   {
   $handle=opendir("$int_path/$flag_path");
   $filenumber = 0;
   while (false!==($file = readdir($handle))) { 
      if ($file != "." && $file != "..") {
	  $langfile[$filenumber] = substr($file,0,strpos($file,'.'));
      $filenumber++;
      } 
   }
   closedir($handle); 
   if ($filenumber == 0)   {
   $t->set_var("W_LANGUAGES", W_NO_LANG);
   }
   else   {
   $i = 0;
   $t->set_var("W_LANGUAGES", W_LANGUAGES);
   for ($i = 0; $i < $filenumber; $i++)   {
       $t->set_var("LANG", $langfile[$i]);
       $t->parse("languages_cycle", "languages_cycle", true);
       }
   }
   $t->parse("if_no_selected", "if_no_selected", true);
}
$t->parse("header", "header", true);
$t->parse("postheader", "postheader", true);
$t->pparse();
?>
