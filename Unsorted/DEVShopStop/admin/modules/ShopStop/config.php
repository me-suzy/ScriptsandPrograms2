<?php
/********************************************************************************/
/*                                                                              */
/* Copyright (c) 2001-2002 by CrossWalkCentral                                  */
/* http://www.crosswalkcentral.net                                              */
/*                                                                              */
/* CrossWalkCentral                                                             */
/* You Web Hosting Community!                                                   */
/*                                                                              */
/* Let us customize this script for you.                                        */
/*                                                                              */
/* Please let us know what you think of the NUKE-Stop Shop Shop Shopping Center */
/* at http://www.crosswalkcentral.net/modules.php?name=Forum                    */
/*                                                                              */
/* This program is not free software.                                           */
/*                                                                              */
/********************************************************************************/

//THIS IS NOT FREE WARE AND SHOULD NOT BE USED AS SO.

if (!eregi("admin.php", $PHP_SELF)) { die ("Access Denied"); }
include("header.php");
include("config.php");
require("VarCWC02.php");

if ($nuke_admin==on) {
GraphicAdmin();
}

IF ($submit):
    $file = fopen("VarCWC02.php", "w");
    $content .="<?php\n\n";
    $content .="/***************************************************************************/\n";
    $content .="/*                                                                         */\n";
    $content .="/* Copyright (c) 2001-2002 by CrossWalkCentral                             */\n";
    $content .="/* http://www.crosswalkcentral.net                                         */\n";
    $content .="/*                                                                         */\n";
    $content .="/* CrossWalkCentral                                                        */\n";
    $content .="/* You Web Hosting Community!                                              */\n";
    $content .="/*                                                                         */\n";
    $content .="/* Let us customize this script for you.                                   */\n";
    $content .="/*                                                                         */\n";
    $content .="/* Please let us know what you think of the NUKE-Shop Stop Shopping Center */\n";
    $content .="/* at http://www.crosswalkcentral.net/modules.php?name=Forum               */\n";
    $content .="/*                                                                         */\n";
    $content .="/* This program is not free software.                                      */\n";
    $content .="/*                                                                         */\n";
    $content .="/**************************************************************************/\n\n";
    $content .="\$ver = \"1.0\";\n\n";
    $content .="\$paypalid = \"$newpaypalid\";\n";
    $content .="\$logourl = \"$newlogourl\";\n";
    $content .="\$payreturn = \"$newpayreturn\";\n";
    $content .="\$paycancel = \"$newpaycancel\";\n";
    $content .="\$viewcart = \"$newviewcart\";\n\n";
    $content .="\$paycart = \"$newpaycart\";\n\n";
    $content .="/************************************************************************/\n\n";
    $content .="\$nuke_admin = \"$adminmenu\";\n";
    $content .="\$right_side = \"$rightside\";\n";
    $content .="/************************************************************************/\n\n";
    $content .="/************************************************************************/\n\n";
    $content .="/************************************************************************/\n\n";

    $content .= "?>";

    fwrite($file, $content);
    fclose($file);

    // END UPDATE AND RETURN BACK TO CONFIG
OpenTable();
   Echo("<center>
  <h4>Shop Stop System Settings Updated</h4>
</center>");
   $page = "/admin.php?op=shopstopcfg";
   Echo ("<BR><center>Click <a href=$page>here</a> or wait 3 seconds to go back to Support Admin</center>");
CloseTable();
?>
<script language="Javascript" type="text/javascript">
    <!--
    function gotoThread() {
    window.location.href="<?php print $page ?>";
    }
    window.setTimeout("gotoThread()", 3000);
    //-->
</script>
<?



else:

// SYSTEM MENU
Echo ("<B>Stop Shop System Settings</b>");

// MAIN MENU
OpenTable();
Echo ("<h4><center>Shop Stop Shopping Center</center></h4>");
Echo ("<center><a href='admin.php?op=shopstop'>Add Product</A> | <a href='admin.php?op=shoplst'>Product List</a> | <a href='admin.php?op=shopcat'>Categories</a></center>");
CloseTable();

Echo ("<br>");
echo ("<BR>");
OpenTable();

// EMAIL PREFERENCES
?>
<form action="/admin.php?op=shopstopcfg" method=post>
  <p align="center">Shop Stop Shopping Center Version <? Echo("$ver");?></p>
  <p><b>PayPal Preferences</B></p>
  <table width="95%" border="0" cellpadding="0" cellspacing="0" align="center">
    <tr> 
      <td width="30%">&nbsp;</td>
      <td width="65%"><b>Email Preferences to Customer.</b></td>
    </tr>
    <tr> 
      <td width="30%"> <div align="left">Pay Pal Email ID</div></td>
      <td width="65%"> <input name="newpaypalid" type="text" id="newpaypalid"value="<?php echo($paypalid); ?>" size="80" maxlength="100">
        (100)</td>
    </tr>
    <tr> 
      <td width="30%">Path to your LOGO</td>
      <td width="65%"> <input name="newlogourl" type="text" id="newlogourl"value="<?php echo($logourl); ?>" size="80" maxlength="100">
        (100)</td>
    </tr>
    <tr> 
      <td width="30%"> <div align="left">Return URL after purchase</div></td>
      <td width="65%"> <input name="newpayreturn" type="text" id="newpayreturn"value="<?php echo($payreturn); ?>" size="80" maxlength="100">
        (100)</td>
    </tr>
    <tr> 
      <td width="30%"> <div align="left">Return URL after cancel</div></td>
      <td width="65%"> <input name="newpaycancel" type="text" id="newpaycancel"value="<?php echo($paycancel); ?>" size="80" maxlength="100">
        (100)</td>
    </tr>
    <tr> 
      <td width="30%">Full path to add to cart img</td>
      <td width="65%"> <input name="newpaycart" type="text" id="newpaycart"value="<?php echo($paycart); ?>" size="80" maxlength="100">
        (100)</td>
    </tr>
    <tr> 
      <td width="30%">Full path to view cart img</td>
      <td width="65%"><input name="newviewcart" type="text" id="newviewcart"value="<?php echo($viewcart); ?>" size="80" maxlength="100">
        (100)</td>
    </tr>
  </table>
  <?
Closetable();
echo("<br>");
Opentable();

// CODE PREFRENCES
?>
  <table width="95%" border="0" align="center" cellspacing="0" cellpadding="0">
    <tr>
      <td width="20%">&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td width="20%">Nuke Admin</td>
      <td>
<?
if ($nuke_admin==on) {
?>
        <input type="radio" name="adminmenu" value="on" CHECKED>
        on
        <input type="radio" name="adminmenu" value="off">
        off
<?
} else {
?>
        <input type="radio" name="adminmenu" value="on" >
        on
        <input type="radio" name="adminmenu" value="off" checked>
        off
<?
}
?>
      </td>
    </tr>
    <tr>
      <td width="20%">Right Side Bar</td>
      <td>
<?
if ($right_side==on) {
?>
        <input type="radio" name="rightside" value="on" checked>
        on
        <input type="radio" name="rightside" value="off">
        off
        <?
} else {
?>
        <input type="radio" name="rightside" value="on">
        on
        <input type="radio" name="rightside" value="off"checked>
        off
        <?
}
?>
	</td>
    </tr>
  </table>

  <?
Closetable();
echo("<br>");
Opentable();

// FIELD PREFERENCES
?>
  <table width="95%" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr> 
      <td><p>&nbsp;</p></td>
      <td><p>&nbsp;</p></td>
    </tr>
    <tr> 
      <td width="20%">&nbsp;</td>
      <td width="80%"> <input type="submit" name="submit" value="Update"> </td>
    </tr>
  </table>
</form>
<?
closetable();
endif;
include ("footer.php")

?>