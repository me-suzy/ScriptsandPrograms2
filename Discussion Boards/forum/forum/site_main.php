<?php


include("header.php");




echo ("<table border=\"0\" cellspacing=\"0\" cellpadding=\"3\" bgcolor=\"$tborder_color2\" width=\"$fwidth\" align=\"center\">
   <tr bgcolor=\"#FFFFFF\" align=\"center\">
      <td width=\"20%\">");

      //left blocks
      
      echo ("<table border=\"0\" cellspacing=\"1\" cellpadding=\"3\" bgcolor=\"$tborder_color2\" width=\"100%\" align=\"center\">
   <tr bgcolor=\"#FFFFFF\" align=\"center\" class=\"background\">
      <td width=\"100%\"><font color=\"$fsubtitle\" face=\"$fface\" size=\"$fmedium\"><b>Nav</b></font></td>
   </tr>
   <tr bgcolor=\"#FFFFFF\" align=\"center\" bgcolor=\"$tbackground1\">
      <td width=\"100%\"><font color=\"$fcolor\" face=\"$fface\" size=\"$fsmall\">Link</font></td>
   </tr>
   <tr bgcolor=\"#FFFFFF\" align=\"center\" bgcolor=\"$tbackground2\">
      <td width=\"100%\"><font color=\"$fcolor\" face=\"$fface\" size=\"$fsmall\">Link</font></td>
   </tr>
   <tr bgcolor=\"#FFFFFF\" align=\"center\" bgcolor=\"$tbackground1\">
      <td width=\"100%\"><font color=\"$fcolor\" face=\"$fface\" size=\"$fsmall\">Link</font></td>
   </tr>
   <tr bgcolor=\"#FFFFFF\" align=\"center\" bgcolor=\"$tbackground2\">
      <td width=\"100%\"><font color=\"$fcolor\" face=\"$fface\" size=\"$fsmall\">Link</font></td>
   </tr>
</table>");

      echo ("</td>
      <td width=\"80%\">");

      //actual content
      echo ("<table border=\"0\" cellspacing=\"1\" cellpadding=\"3\" bgcolor=\"$tborder_color2\" width=\"100%\" align=\"center\">
   <tr bgcolor=\"#FFFFFF\" align=\"center\" class=\"background\">
      <td width=\"100%\"><font color=\"$fsubtitle\" face=\"$fface\" size=\"$fmedium\"><b>Content Loader...</b></font></td>
   </tr>
   <tr bgcolor=\"#FFFFFF\" align=\"center\" bgcolor=\"$tbackground1\">
      <td width=\"100%\"><font color=\"$fcolor\" face=\"$fface\" size=\"$fsmall\">Some content<br /><Br />could go here...<br /><br />:D</font></td>
   </tr>
</table>");

      echo ("</td>
   </tr>
</table>");
include("footer.php");


?>
