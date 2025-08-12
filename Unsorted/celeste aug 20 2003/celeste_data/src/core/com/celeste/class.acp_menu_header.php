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
 
class ACP_MENU_HEADER {
  var $groups = array();
  var $cur_group;
  var $content;

  function ACP_MENU_HEADER($cur_group = '') {
    $this->cur_group = $cur_group;
  }

  function addGroup($name, $groupid) {
    $this->groups[$name] = $groupid;
  }

  function plot() {
    foreach($this->groups as $group => $groupid) {
      $this->content .= "<option value='".$groupid."' ".($this->cur_group==$groupid ? 'selected' : '').">".$group."</option>";
    }
print <<< EOF
<html>
<head>
<link rel="stylesheet" href="images/acp/acp.css" type="text/css" />
<META content="text/html; charset=ISO-8859-1" http-equiv=Content-Type>
<title>Celeste Admin Control Panel Menu</title>
<body  text="#000000" link="#000000" vlink="#000000" alink="#000000" topmargin=0 leftmargin=0>
<table width="100%" height="100%" border="0" cellspacing="0" cellpadding="0">
<td width=190 height=100% valign=top align=left bgcolor="#FFFFFF" >
  <img src='images/logo.gif' border=0><br>
  <table width=98% cellpadding=0 cellspacing=0 border=0>
    <tr><td height=1 bgcolor=999999><img src="images/acp/left_bg.gi" height=1 width=1 border=0></td></tr>
  </table>

  <table width=98% cellpadding=3 cellspacing=0 border=0 background="images/mod/left_bg.gif">
  <form method=GET action="$_SERVER[PHP_SELF]">
  <tr><td height=30>&nbsp;&nbsp;
    <select name=groupid class=menuselect>
    <option value=''>--> Menu --<
    <option value='logout'>Log Out</option>
    <option value='viewForum'>Goto Forum</option>
    <option value=''>-------------------</option>
    $this->content
    </select>&nbsp; &nbsp;<input type=submit name=submit class=menuinput value=' Go '>&nbsp;
    <input type=hidden name=prog value=menu>
    <input type=hidden name=oldGroupid value='$this->cur_group'>
  </td></tr></form>
  </table>
EOF;

  } // end of 'function plot() {'

} // end of 'class ACP_MENU_HEADER {'

