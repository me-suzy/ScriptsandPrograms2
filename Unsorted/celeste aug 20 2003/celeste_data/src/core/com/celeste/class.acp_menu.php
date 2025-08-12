<?php
/**
 * Celeste Project Source File
 * Celeste 2003 1.1.3 Build 0811
 * Aug 11, 2003
 * Celeste Dev Team - Lvxing / Y10k
 *
 * Copyright (C) 2002 celeste Team. All rights reserved.
 *
 * This software is the proprietary information of celeste Team.
 * Use is subject to license terms.
 */
 
class ACP_MENU {
  var $content;

  function addCat($cat) {
    //if ($this->content) {
      $this->content .= "<tr><td height=22>&nbsp;</td></tr><tr><td height=25 background='images/acp/left_menu_bg.gif'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font color=white><b>".$cat."</b></font></td></tr>";
    //}
  }

  function addItem($item, $handle) {
    $this->content .= "<tr><td height=24>&nbsp;&nbsp;<a href='".$handle."' target=main>&#187; ".$item."</a></td></tr>";
  }

  function plot() {
//----------------------------------------------//
print <<< EOF
<table width=98% height=100% cellpadding=3 cellspacing=0 border=0 background="images/acp/left_bg.gif">
  $this->content
  <tr height=*><td>&nbsp; </td></tr>
</table>
</body>
</html>
EOF;
//----------------------------------------------//
  } // end of 'plot()'

} // end of 'class ACP_MENU {'

