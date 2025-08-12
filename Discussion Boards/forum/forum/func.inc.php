<?php

define("SKIN", "$skins");
define("FSMALL", "$fsmall");
define("FFACE", "$fface");
define("FTITLE", "$ftitle");
define("TITLE", "$title");
define("FWIDTH", "$fwidth");


function table_header($content="null"){

  echo ("
<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\" width=\"".FWIDTH."\" align=\"center\">
   <tr>
      <td width=\"1\" colspan=\"1\"><img src=\"".SKIN."/table/tl.gif\" alt=\"".TITLE.": Top Left\" /></td>
      <td width=\"100%\" colspan=\"1\" class=\"backgroundtm\"><div align=\"center\"><font size=\"".FSMALL."\" face=\"".FFACE."\" color=\"".FTITLE."\"><b>$content</b></font></div></td>
      <td width=\"1\" colspan=\"1\"><img src=\"".SKIN."/table/tr.gif\" alt=\"".TITLE.": Top Right\" /></td>
   </tr>
</table>");
}; //end function table_header


function table_footer(){

  echo ("
<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\" width=\"".FWIDTH."\" align=\"center\">
   <tr>
      <td width=\"1\" colspan=\"1\"><img src=\"".SKIN."/table/bl.gif\" alt=\"".TITLE.": Bottom Left\" /></td>
      <td width=\"100%\" colspan=\"1\" class=\"backgroundbm\"></td>
      <td width=\"1\" colspan=\"1\"><a href=\"#top\" title=\"Top of Page\"><img src=\"".SKIN."/table/br.gif\" alt=\"".TITLE.": Bottom Right\" border=\"0\" /></a></td>
   </tr>
</table><font size=\"1\"><br /></font>\r\n\r\n");
}; //end function table_footer


function is_logged_in($user, $pass){

if (isset($user) && $user != "" && isset($pass) && $pass != ""){
  return true;
} else {
  return false;
};

}; //end function is_logged_in


function announce_header($content="null"){

  echo ("
<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\" width=\"".FWIDTH."\" align=\"center\">
   <tr>
      <td width=\"1\" colspan=\"1\"><img src=\"".SKIN."/table/tl2.gif\" alt=\"".TITLE.": Top Left\" /></td>
      <td width=\"100%\" colspan=\"1\" class=\"backgroundtm2\"><div align=\"center\"><font size=\"".FSMALL."\" face=\"".FFACE."\" color=\"".FTITLE."\"><b>$content</b></font></div></td>
      <td width=\"1\" colspan=\"1\"><img src=\"".SKIN."/table/tr2.gif\" alt=\"".TITLE.": Top Right\" /></td>
   </tr>
</table>");
}; //end function table_header


function announce_footer(){

  echo ("
<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\" width=\"".FWIDTH."\" align=\"center\">
   <tr>
      <td width=\"1\" colspan=\"1\"><img src=\"".SKIN."/table/bl2.gif\" alt=\"".TITLE.": Bottom Left\" /></td>
      <td width=\"100%\" colspan=\"1\" class=\"backgroundbm2\"></td>
      <td width=\"1\" colspan=\"1\"><img src=\"".SKIN."/table/br2.gif\" alt=\"".TITLE.": Bottom Right\" /></td>
   </tr>
</table><font size=\"1\"><br /></font>\r\n\r\n");
}; //end function table_footer


?>
